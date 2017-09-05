<?php
if (! function_exists('admin_group_tag')) {
    /**
     * SORT_REGULAR - 默认。将每一项按常规顺序排列。
     * SORT_NUMERIC - 将每一项按数字顺序排列。
     * SORT_STRING - 将每一项按字母顺序排列。
     *
     */
    function admin_group_tag($key){
        $groups = ['admin'=>'首页','role'=>'权限管理','menu'=>'菜单管理','user'=>'用户管理','course'=>'课程管理','push'=>'推送管理','vip'=>'会员管理','version'=>'更新管理',];
        if(in_array($key,array_keys($groups))){
            return $groups[$key];
        }
        return ;
    }
}



if (! function_exists('multisort')) {
    /**
     * SORT_REGULAR - 默认。将每一项按常规顺序排列。
     * SORT_NUMERIC - 将每一项按数字顺序排列。
     * SORT_STRING - 将每一项按字母顺序排列。
     *
     */
    function multisort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
        if(is_array($arrays)){
            foreach ($arrays as $array){
                if(is_array($array)){
                    $key_arrays[] = $array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
        return $arrays;
    }
}



if (! function_exists('isDatetime')) {
    /**
     * 判断是否是时间格式
     * @param  string  $param  [description]
     * @param  string  $format [description]
     * @return boolean         [description]
     */
    function isDatetime($param = '', $format = 'Y-m-d H:i:s'){
        $weeks =['周一'=>1,'周二'=>2,'周三'=>3,'周四'=>4,'周五'=>5,'周六'=>6,'周日'=>7];

        $todayWeek = getWeek();
        $today = $weeks[$todayWeek];
        $teachDay = $weeks[$param[1]];

        if($today > $teachDay && !in_array($today, [6,7])){
            return false;
        }

        //只允许提前两日录入课程
        $days = abs($today - $teachDay);

        //周六周日录入周一周二课程时 区别判断
        if(in_array($todayWeek, ['周六','周日']) && $days > 2 ){
            $days = 2;
        }elseif($days>2){
            return false;
        }

        if($today == 7 && $teachDay == 1){
            $days = 1;
        }

        $amPm = substr($param[0],-2,2);
        $secoud = substr($param[0], 2,2);
        $hour = substr($param[0], 0,2);

        if($hour && $secoud && $amPm){
            $time = $hour.':'.$secoud.' '.$amPm;
            $times  = date("G:i", strtotime($time));
            return date('Y-m-d',strtotime("+".$days." day")).' '.$times.':00';
        }
        return false;
    }
}


if(! function_exists('getWeek'))
{
    function getWeek($times = '')
    {
        $toweeks = ['周日','周一','周二','周三','周四','周五','周六',];
        if(!$times){
            $times = date('Y-m-d');
        }

        return $toweeks[date('w',strtotime($times))];
    }

}


if(! function_exists('getWeekToEn'))
{
    function getWeekToEn($week = '')
    {
        $toweeks = ['周日'=>'sun','周一'=>'mon','周二'=>'tue','周三'=>'wed','周四'=>'thur','周五'=>'fir','周六'=>'sat'];
        return isset($toweeks[$week]) ? $toweeks[$week] : false;
    }

}