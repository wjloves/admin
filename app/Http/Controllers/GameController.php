<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as User;
use App\Models\Lottery as Lottery;
use Auth;
use App\Services\Lottory\MethodsService as MethodsService;
use Illuminate\Support\Facades\DB;

/**
 * 投注等游戏相关action
 * @auther logen
 */
class GameController extends Controller
{

    protected $user = [];
    protected $lotteries = ['1' => 'ssc', '2' => 'syxw', '3' => 'ks'];

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * 游戏首页
     * @return type
     */
    public function index()
    {
        return view('game/index', ['user' => $this->user]);
    }

    /**
     *  投注@ajax
     */
    public function play(Request $request)
    {
        $data = $request->toArray();

        $result = [];
        //常规检测
        if( $this->user->money < $data['amount'] )
        {
            // return response()->json(array('error'=>'10001','message'=>'user.faildMoney'));
        }

        //获取彩种数据 主要获取信用和官方玩法区分
        $lottory = Lottery::where('lottery_id', $data['lottery'])->first();
        if( $lottory->lottery_type == 1 )
        {
            //信用玩法
            try {
                $result = MethodsService::creditExplanCodes($data['code']);
            } catch( \Exception $e ) {
                return response()->json(array('error' => $e->getCode(), 'message' => $e->getMessage()));
            }
        }
        else
        {  //官方玩法
            //MethodsService::officExplanCodes($data['code']);  //TODO
        }

        //用户IP
        $data['user_ip'] = $request->getClientIp();
        $data['code'] = json_encode($result);

        //事物操作帐变和注单
        try {
            DB::transaction(function($data)use($data) {
                //事物中查询最新user数据 防止金额出错
                $userId = Auth::id();
                $user = DB::table('users')->where('id', $userId)->lockForUpdate()->first();
                //计算投注后剩余金额
                $beMoney = ($user->money - $data['amount']);

                //执行帐变和输入插入
                DB::table('users')->where('id', $userId)->update(['money' => $beMoney]);
                DB::table('order')->insert([
                    'user_id' => $userId,
                    'lottery_id' => $data['lottery'],
                    'issue' => $data['issue'],
                    'codes' => $data['code'],
                    'amount' => $data['amount'],
                    'site_no' => $data['sitno'],
                    'user_ip' => $data['user_ip'],
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                DB::table('account_changes')->insert([
                    'lottery_id' => $data['lottery'],
                    'issue' => $data['issue'],
                    'user_id' => $userId,
                    'username' => $user->username,
                    'type' => 401, //投注
                    'amount' => $data['amount'],
                    'pre_balance' => $user->money,
                    'balance' => $beMoney,
                    'site_no' => $data['sitno'],
                ]);
            });
        } catch( \Exception $ex ) {
            return response()->json(array('error' => 00001, 'message' => '注单失败'));
        }

        return response()->json(array('error' => 00000, 'message' => '投注成功'));
    }

}
