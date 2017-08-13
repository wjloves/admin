<?php

namespace App\Services\Help;

use Exception;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 *   公共基础服务
 *   @auther morgan
 */
class HelpService
{

    public function __construct(){}

    /**
     * 时间格式判断
     * @param  string  $param  [description]
     * @param  string  $format [description]
     * @return boolean         [description]
     */
    static  public function isDatetime($param = '', $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($param)) === $param;
    }

}
