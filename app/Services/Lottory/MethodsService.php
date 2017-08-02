<?php

namespace App\Services\Lottory;

use Exception;
use App\Exceptions\Handler;
use App\Exceptions\MethodsException;

/**
 *   彩票判奖过滤服务
 *   大小单双  【1：小 2：大 3：单 4：双】
 *   龙虎和    【1：龙 2：虎 0：和】
 *   @auther logen
 */
class MethodsService
{

    protected $lottories = [];
    protected $methods = [];

    function __construct()
    {
        # code...
    }

    /**
     * 判断开奖号码转换信用玩法 针对 时时彩 和 快乐10分 等
     * @param type $lottory  彩种
     * @param type $code     开奖号码
     * @return array|boolean
     * @testdox - 注释：此方法针对数字型开奖号码进行准备数据判断
     *      #大小单双  【1：小 2：大 3：单 4：双】
     *      #龙虎和    【1：龙 2：虎 0：和】
     *      $result['sum']   --- 和值大小单双
     *      $result['single'] --- 单号大小单双
     *      $result['lhh']  --- 龙虎和
     *      $result['QS']  --- 前三
     *      $result['ZS']  --- 中三
     *      $result['HS']  --- 后三
     *      $result['WS']  --- 尾数
     */
    static public function transferCode($lottory, $code = '')
    {
        if( !$lottory || !$code )
        {
            return false;
        }

        $result = [];
        $codes = str_split($code);


        //和值大小单双
        $sum = array_sum($codes);
        $result['sum'] = ($lottory == 'ssc') ? ($sum % 2 == 1 ? '3' : '4') . ($sum < 5 ? '1' : '2') : ($sum % 2 == 1 ? '3' : '4') . ($sum < 85 ? '1' : '2');
        //单号大小单双
        for( $i = 0; $i < count($codes); $i++ ) {
            $result['single'][] = ($codes[$i] % 2 == 1 ? '3' : '4') . ($codes[$i] < 5 ? '1' : '2');
        }

        //龙虎和
        if( $codes[0] > $codes[count($codes) - 1] )
        {
            $result['lhh'] = 1;
        }
        else if( $codes[0] < $codes[count($codes) - 1] )
        {
            $result['lhh'] = 2;
        }
        else
        {
            $result['lhh'] = 0;
        }

        //趣味玩法
        if( $lottory == 'ssc' )
        {
            //前三
            $result['QS'] = self::funnyPlay(str_split(substr($code, 0, 3)));
            //中三
            $result['ZS'] = self::funnyPlay(str_split(substr($code, 1, 3)));
            //后三
            $result['HS'] = self::funnyPlay(str_split(substr($code, 2, 3)));
        }
        else
        {   // 尾数大小
            $result['WS'] = ($sum % 10 <= 5 ) ? 1 : 2;
        }

        return $result;
    }

    /**
     * 趣味玩法判断方法 豹子 顺子 对子 杂六 半顺
     * @param type $codes 必须为三位数
     * @return  豹子：BZ   对子：DZ  顺子：SZ  半顺：BS  杂六：ZL
     */
    static private function funnyPlay($codes)
    {
        $result = '';
        $tmp = count(array_unique($codes));
        if( $tmp == 1 )
        {
            return 'BZ';
        }
        else if( $tmp == 2 )
        {
            return 'DZ';
        }
        else
        {
            if( self::sortCodes($codes, 3) )
            {
                return 'SZ';
            }
            else if( self::sortCodes($codes, 2) )
            {
                return 'BS';
            }
            else
            {
                return 'ZL';
            }
        }
    }

    /**
     * 判断顺子和半顺
     * @param type $codes  [0,9,8]
     * @param type $salt   3:判断是否为顺子  2：是否为半顺
     * @return boolean
     */
    static private function sortCodes($codes, $salt)
    {
        $i = 0;
        foreach( $codes as $k => $v ) {
            if( in_array(($v + 10 - 1) % 10, $codes) || in_array(($v + 1) % 10, $codes) )
            {
                $i++;
            }
        }

        if( $i >= $salt )
        {
            $codes = true;
        }
        else
        {
            $codes = false;
        }

        return $codes;
    }

    /**
     * PK10分析
     * @param type $code
     * @return boolean
     */
    static public function analyzePk($code)
    {
        if( !preg_match('`^(1?\d ){9}1?\d$`', $code) )
        {
            return false;
        }
        $result = array();
        $codes = explode(' ', $code);
        //单号大小单双和 前五龙虎
        foreach( $codes as $k => $v ) {
            $result['single'][$k]['DXDS'] = ($codes[$k] % 2 == 1 ? '3' : '4') . ($codes[$k] < 5 ? '1' : '2');
            if( $k < 5 )
            {
                $result['single'][$k]['LHH'] = ($codes[$k] < $codes[count($codes[$k])]) ? '2' : '1';
            }
        }

        //冠亚大小单双
        $result['prizeParts'] = $codes;
        $result['gyhz'] = $codes[0] + $codes[1];
        $result['gyhzlh'] = $result['gyhz'] % 2 ? '3' : '4';
        $result['gyhzDx'] = $result['gyhz'] < 11 ? '1' : '2';
        return $result;
    }

    /**
     * 拆分信用玩法投注数据
     * @param type $codes
     * @return array   【method】：玩法id；【amount】：单注玩法投注金额
     */
    static public function creditExplanCodes($codes = '')
    {
        $tmp = explode(';', $codes);

        $result = [];
        foreach( $tmp as $k => $v ) {
            $methods = explode('|', $v);
            if( count($methods) < 2 )
            {
                throw new MethodsException('20002');
            }
            $result[$k]['method'] = $methods[0];
            $result[$k]['amount'] = $methods[1];
        }
        return $result;
    }

}
