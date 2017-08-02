<?php
namespace App\Exceptions;
use Exception;

class GameException extends Exception{

    protected $codes = [
        '10001' => '余额不足',
        '10002' => '开奖中，请稍后',
        '10003' => '投注有误，请重新选择',
    ];

    public function __construct($code = 0)
    {
        parent::__construct($this->codes[$code],$code);
    }
}