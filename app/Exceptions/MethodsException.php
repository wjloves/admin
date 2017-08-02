<?php
namespace App\Exceptions;

use Exception;

class MethodsException extends Exception{

     protected $codes = [
        '20001' => '参数异常,不能为空',
        '20002' => '投注数据异常',
        '20003' => '',
    ];

    public function __construct($code = 0)
    {
        parent::__construct($this->codes[$code],$code);
    }
}