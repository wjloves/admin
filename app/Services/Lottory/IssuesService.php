<?php

namespace App\Services\Lottory;

use Exception;
use App\Exceptions\Handler;
use App\Exceptions\IssuesException;
use App\Models\Lottory as LotteryModel;
use App\Models\Issues as IssueModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 *   彩票判奖过滤服务
 *   大小单双  【1：小 2：大 3：单 4：双】
 *   龙虎和    【1：龙 2：虎 0：和】
 *   @auther logen
 */
class IssuesService
{

    function __construct()
    {

    }

    /**
     * 奖期批量生成
     * @param type $lottery
     * @param type $data
     * @return int
     * @throws IssuesException
     */
    static public function genIssue($lottery, $data)
    {
        $firstIssue = $data['firstIssue'];
        $startTS = strtotime($data['startDate']);
        $endTS = strtotime($data['endDate']);

        //常规判断
        if( $startTS > $endTS || $endTS - $startTS > 86400 * 365 )
        {
            throw new IssuesException(50002);
        }
        if( !$lottery )
        {
            throw new IssuesException(50001);
        }

        // 判断是否需要起始期号
        if( strpos($lottery->issue_rule, 'd') === false )
        {
            if( !$firstIssue )
            {
                throw new IssuesException(50003);
            }
            if( !issues::checkIssueRule($firstIssue, $lottery->issue_rule) )
            {
                throw new IssuesException(50004);
            }
        }

        //删除可能有的重复奖期先
        self::deleteItemByDate($lottery->lottery_id, $startTS);
        /**
         *
         * CQSSC:   100121-054       ymd-[n3]
         * JX-SSC:  20100121-036    Ymd-[n3]
         * HLJSSC:  0016571         [n7]
         * SSL:     20100121-11     Ymd-[n2]
         * SD11Y:   10012131        ymd-[n2]
         * 格式符： y,m,d的值分别为0,1,0，0表示清零，1表示不清零
         */
        $rules = self::analyze($lottery->issue_rule);

        $totalCounter = 0;
        $curIssueNumber = intval(substr($firstIssue, 0 - $rules['n'])); // 获取期号，一般在最后几位
        $cacheName = $lottery->name.'_issues';

        for( $i = $startTS; $i <= $endTS; $i += 86400 ) {
            //休市则忽略
            if( $i >= strtotime($lottery->yearly_start_closed) && $i <= strtotime($lottery->yearly_end_closed) )
            {
                continue;
            }

            // 属于哪天的奖期
            $belong_date = date('Y-m-d', $i);
            $sample = $rules['sample'];
            // 先替换日期大部
            if( $rules['ymd'] )
            {
                $sample = preg_replace_callback('`([ymd]+)`i',function($m) use($i){ return date($m[1],$i);},$sample);
            }

            // 得到当前期号
            if( $rules['n'] )
            {
                // 如果按天清零，则每天数字部分从头开始
                if( !$rules['d'] )
                {
                    $curIssueNumber = 1;
                }
                //按年清零的时候跨年了
                if( !$rules['y'] && isset($lastDay) && date('Y', $i) > date('Y', $lastDay) )
                {
                    $curIssueNumber = 1;
                }
            }
            // 开始生成
            /*
            *  [start_time] => 05:00:00 开始时间 [first_end_time] => 10:00:00 第一次结束时间 [end_time] => 22:00:00 正常销售结束时间 [cycle] => 600 销售时间 600s = 10分钟 [end_sale] => 60 停止销售时间 60s = 1分钟 [code_time] => 120 [drop_time] => 60 预备字段[官方玩法] 撤单时间
             */

            $datas = array();
            $settings = json_decode($lottery['settings'],true);

            foreach( $settings as $v ) {
                if( !$v['is_use'] )
                {
                    continue;
                }
                $startTime = date_parse($v['start_time']);
                $startTime = $startTime['hour']*3600+$startTime['minute']*60+$startTime['second'];
                $endTime = date_parse($v['end_time']);
                $endTime = $endTime['hour']*3600+$endTime['minute']*60+$endTime['second'];

                $isFirst = 0;
                if( $endTime < $startTime )
                {
                    $endTime += 86400;
                }

                for( $j = $startTime; $j <= $endTime - $v['cycle']; ) {
                    $curIssueStartTime = date('Y-m-d H:i:s', $i + $j - $v['end_sale']);

                    if( !$isFirst )
                    {
                        $curIssueEndTimeStamp = $i + self::timeTosecond($v['first_end_time']);
                    }
                    else
                    {
                        $curIssueEndTimeStamp = $i + $j + $v['cycle'];
                    }
                    $curIssueEndTime = date('Y-m-d H:i:s', $curIssueEndTimeStamp - $v['end_sale']);
                    $curCodeTime = date('Y-m-d H:i:s', $curIssueEndTimeStamp + $v['code_time']);
                    $finalIssue = str_replace("[n{$rules['n']}]", str_pad($curIssueNumber, $rules['n'], '0', STR_PAD_LEFT), $sample);

                    // 写入
                    $data = array(
                        'lottery_id' => $lottery->lottery_id,
                        'belong_date' => $belong_date,
                        'issue' => $finalIssue,
                        'start_sale_time' => $curIssueStartTime,
                        'end_sale_time' => $curIssueEndTime,
                        'earliest_input_time' => $curCodeTime,
                    );

                    $datas[] = $data;
                    if( !$isFirst )
                    {
                        $j = self::timeTosecond($v['first_end_time']);
                    }
                    else
                    {
                        $j += $v['cycle'];
                    }
                    $isFirst++;
                    $curIssueNumber++;
                    $totalCounter++;
                }
            }
            //批量添加
            $sql = 'INSERT INTO cp_issues (' . implode(',', array_keys(reset($datas))) . ') VALUES ';
            foreach( $datas as $v ) {
                $sql .= "('" . implode("','", $v) . "'),";
            }
            $sql = rtrim($sql, ',');

            if( !DB::insert($sql) )
            {
                throw new IssuesException(50005);
            }
            //缓存奖期
            Redis::hset($cacheName,$datas[0]['belong_date'],json_encode($datas));
            //记录前一天 为年清零之用
            $lastDay = $i;
        }
        //设置这批奖期过期时间   最后一日 加一天为过期时间
        $cacheTime = strtotime($datas[count($datas)-1]['belong_date'])-time()+84600;
        Redis::EXPIRE($cacheName,$cacheTime);

        return $totalCounter;
    }

    /**
     * 删除日期之后的奖期 批量生成奖期时使用
     * @param  [type]  $lotteryId       [description]
     * @param  integer $belong_date     [description]
     * @param  integer $start_sale_time [description]
     * @return [type]                   [description]
     */
    static private function deleteItemByDate($lotteryId, $belong_date = 0, $start_sale_time = 0)
    {
        if( $lotteryId <= 0 )
        {
            throw new IssuesException(50006);
        }

        if( !$belong_date && !$start_sale_time )
        {
            throw new IssuesException(50007);
        }

        $options = [$lotteryId];
        $sql = "DELETE FROM cp_issues WHERE lottery_id=?";

        //附加条件判断
        if($belong_date){
            $sql .= " AND belong_date >=?";
            $options[] = date('Y-m-d',$belong_date);
        }
        if($start_sale_time){
            $sql .= "AND start_sale_time >=?";
            $options[] = date('Y-m-d',$start_sale_time);
        }

        DB::delete($sql,$options);
    }

    /**
     * 私有方法 分析奖期规则
     * @param type $issuerule
     * @return type
     */
    static private function analyze($issuerule)
    {
        $tmp = explode('|', $issuerule);
        $result['sample'] = $tmp[0];
        $result['ymd'] = '';
        $result['n'] = 0;
        preg_match_all('`\[(n)(\d+)\]`', $tmp[0], $matches);

        if( $matches[1] )
        {
            $result['n'] = $matches[2][0];
        }

        if( preg_match('`^[yY][md]*`i', $tmp[0], $match) )
        {
            $result['ymd'] = $match[0];
        }

        $result['ymd_length'] = strlen(date($result['ymd']));
        $result['length'] = $result['n'];

        if( $result['ymd'] )
        {
            $result['length'] += $result['ymd_length'];
        }
        $result['length'] += strlen(preg_replace(array('`^[yY][md]*`i', '`\[(n)(\d+)\]`i'), '', $result['sample']));

        $tmp3 = explode(',', $tmp[1]);
        $result['y'] = $tmp3[0] ? true : false;
        $result['m'] = $tmp3[1] ? true : false;
        $result['d'] = $tmp3[2] ? true : false;

        return $result;
    }

    /**
     * 录入号码或者是验证号码
     * @param  [type] $issueId [description]
     * @param  [type] $code    [description]
     * @param  [type] $adminId [description]
     * @return [type]          [description]
     */
    static public  function drawNumber($issueId, $code, $adminId)
    {

        if (!$issue = IssueModel::where('issue_id',$issueId)->first()) {
            throw new IssuesException(50008);
        }

        //彩种不存在
        if (!$lottery = LotteryModel::where('lottery_id',$issue->lottery_id)->first()) {
            throw new IssuesException(50009);
        }

        if ($issue->status_code == 2) {
            throw new IssuesException(50010);
        }
        elseif ($issue->status_code == 3) {
            throw new IssuesException(50011);
        }

        if (date('Y-m-d H:i:s') < $issue['earliest_input_time']) {
            throw new IssuesException(50012);
        }

        // 这里写死判断规则
        switch ($lottery->property_id) {
            case 1:// SSC数字型 5个连续数字
                $matches = array();
                if (!preg_match('`^\d{5}$`', $code)) {
                    throw new IssuesException(50013, $code);
                }
                break;
            case 2:// 低频3D型 3个连续数字
                $matches = array();
                if (!preg_match('`^\d{3}$`', $code)) {
                    throw new IssuesException(50013, $code);
                }
                break;
            case 3:// 快三型 3位数字 范围1~6
                $matches = array();
                if (!preg_match('`^[1-6]{3}$`', $code)) {
                    throw new IssuesException(50013, $code);
                }
                break;
            case 4://pk10 ,1-10十个数字不能重复
                if (!preg_match('`^(1?\d ){9}1?\d$`', $code)) {
                    throw new IssuesException(50013, $code);
                }
                break;
            default:
                throw new IssuesException(50014);
        }

        if ($issue->status_code == 1) {
                //验证成功，需要检测两个身份是否重合
                if ($issue->input_admin_id == $adminId) {
                    throw new IssuesException(50015);
                }
                if ($code == $issue["code"]) {
                    $data = array(
                        'status_code' => 2,
                        'verify_time' => date("Y-m-d H:i:s"),
                        'verify_admin_id' => $adminId,
                    );
                    if (!IssueModel::where('issue_id',$issue->issue_id)->update($data)) {
                        throw new IssuesException(50016);
                    }
                    else {
                        if ($data["status_code"] == 2) {
                            //将开奖结果记录到redis缓存
                            $cachePrefix = $lottery->name.'_openCode';
                            $cacheKey = $issue->issue;
                            $cacheValue = ['issue' => $issue->issue, 'code' => $code];
                            Redis::hset($cachePrefix, $cacheKey, $cacheValue, 60 * 60 * 24 * 2);
                        }
                        return true;
                    }
                }
                else {
                    // 号码不一致属严重错误，重置0
                    $data = array(
                        'code' => '',
                        'input_time' => '0000-00-00 00:00:00',
                        'input_admin_id' => 0,
                        'status_code' => 0,
                    );
                    if (!IssueModel::where('issue_id',$issue->issue_id)->update($data)) {
                        throw new IssuesException(50016);
                    }
                    throw new IssuesException(50017);
                }
        }
        else {
                //首次录入号码
                $data = array(
                    'code' => $code,
                    'input_admin_id' => $admin_id,
                    'input_time' => date("Y-m-d H:i:s"),
                    'status_code' => 2,
                );

                if (!issues::updateItem($issueId, $data)) {
                    throw new IssuesException(50018);
                }
                else {
                    if ($data["status_code"] == 2) {
                        //将开奖结果记录到mc缓存
                        $cachePrefix = $lottery->name.'_openCode';
                        $cacheKey = $issue->issue;
                        $cacheValue = ['issue' => $issue->issue, 'code' => $code];
                        Redis::hset($cachePrefix, $cacheKey, $cacheValue, 60 * 60 * 24 * 2);
                        return true;
                    }
                    return false;
                }
        }
    }


    /**
     * 转换秒数
     * @param  [type] $time [description]
     * @return [type]       [description]
     */
    static private function timeTosecond($time){
        $times = explode(':', $time);
        $seconds = 0;
        if(isset($times[0])){
            $seconds += $times[0]*3600;
        }
        if(isset($times[1])){
            $seconds += $times[1]*60;
        }
        if(isset($times[2])){
            $seconds += $times[2];
        }
        return $seconds;
    }

}
