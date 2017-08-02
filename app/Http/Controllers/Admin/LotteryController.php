<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Services\Lottory\MethodsService;
use App\Services\Lottory\IssuesService as IssuesService;
use App\Models\Lottery as LotteryModel;
use App\Models\MethodGroups as MethodGroupsModel;
use App\Models\Methods as MethodsModel;
use App\Models\Issues as IssuesModel;
use Illuminate\Support\Facades\DB;
use Toastr,
    Breadcrumbs;

/**
 *  彩种管理方法 执行手工开奖动作
 *  @auther logen
 */
class LotteryController extends BaseController
{
    /**
     * 彩种玩法类型
     */
    const lotteryType  = [0 => '信用玩法', 1 => '官方玩法'];

    /**
     * 彩种性质类型
     */
    const propertyType = [1 => '时时彩', 2 => '十一选五', 3 => '快乐十分', 4 => 'PK拾', 5 => '福彩3D', 6 => '排列三'];

    /**
     * 奖期状态
     */
    const issueStatus  = [0 => '未抓取', 1 => '进行中', 2 => '已完成'];

    /**
     * init
     */
    public function __construct(Request $request)
    {
        parent::__construct();
        //后台面包屑功能  TODO
        Breadcrumbs::setView('admin._partials.breadcrumbs');
        Breadcrumbs::register('adminLottery', function ($breadcrumbs) {
            $breadcrumbs->push('彩种管理', route('lottery.list'));
        });
    }

    /**
     * 彩种管理
     * @param Request $request
     */
    public function lotteryList(Request $request)
    {
        $data = $request->toArray();

        $lotteries = LotteryModel::orderBy('sort')->get();
        //面包屑导航
        Breadcrumbs::register('adminLotteryList', function ($breadcrumbs) {
            $breadcrumbs->parent('adminLottery');
            $breadcrumbs->push('彩种列表', route('lottery.list'));
        });

        return view('admin.lottery.lotterylist', ['lotteries' => $lotteries, 'lotteryType' => self::lotteryType, 'propertyType' => self::propertyType]);
    }

    /**
     *  创建彩种
     * @param Request $request
     * @return type html/json
     */
    public function lotteryCreate(Request $request)
    {
        $data = $request->toArray();

        //执行操作
        if( $request->isMethod('post') )
        {
            //常规判断
            if( !$data['name'] || !$data['cname'] || !$data['web_site'] )
            {
                return response()->json(array('errorCode' => 70003, 'message' => '参数不能为空'));
            }

            if( LotteryModel::where('name', $data['name'])->first() )
            {
                return response()->json(array('errorCode' => 70001, 'message' => '彩种名称重复'));
            }

            unset($data['_token']);
            $status = LotteryModel::create($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('lottery.list')));
            }

            return response()->json(array('errorCode' => 70002, 'message' => '创建失败'));
        }

        return view('admin.lottery.lotterycreate', ['lotteryType' => self::lotteryType, 'propertyType' => self::propertyType]);
    }

    /**
     * 修改彩种
     * @param Request $request
     * @param type $lottery
     * @return type html/json
     */
    public function lotteryUpdate(Request $request, $lotteryId)
    {
        if( !$lotteryId )
        {
            return response()->json(['errorCode' => 70003, 'message' => '参数不能为空']);
        }

        $data = $request->toArray();

        //执行操作
        if( $request->isMethod('post') )
        {
            //过滤数组
            unset($data['_token']);
            $status = LotteryModel::where('lottery_id', $lotteryId)->update($data);

            if( $status )
            {
                return response()->json(['errorCode' => 00000, 'message' => '操作成功', 'route' => route('lottery.list')]);
            }

            return response()->json(['errorCode' => 70002, 'message' => '操作失败']);
        }
        $lottery = LotteryModel::where('lottery_id', $lotteryId)->first();

        return view('admin.lottery.lotteryupdate', ['lottery' => $lottery, 'lotteryType' => self::lotteryType, 'propertyType' => self::propertyType]);
    }

    /**
     * 禁用/启用 彩种
     * @param Request $request
     * @param type $lottery
     * @return type json
     */
    public function lotteryDestory(Request $request, $lotteryId, $status = 1)
    {

        if( !$lotteryId )
        {
            return response()->json(['errorCode' => 70003, 'message' => '参数不能为空']);
        }

        $status = LotteryModel::where('lottery_id', $lotteryId)->update(['status' => $status]);

        if( $status )
        {
            return response()->json(['errorCode' => 00000, 'message' => '操作成功']);
        }

        return response()->json(['errorCode' => 70002, 'message' => '操作失败']);
    }

    /**
     * 玩法组管理
     * @param Request $request
     * @return type html
     */
    public function methodGroups(Request $request, $lotteryId)
    {
        $data = $request->toArray();

        $methodGroups = MethodGroupsModel::where('lottery_id', $lotteryId)->where('status', 1)->orderBy('sort')->get();

        return view('admin.lottery.methodgroups', ['methodGroups' => $methodGroups, 'lotteryType' => self::lotteryType, 'propertyType' => self::propertyType, 'lotteryId' => $lotteryId]);
    }

    /**
     *  创建玩法组
     * @param Request $request
     * @return type html/json
     */
    public function methodGroupCreate(Request $request, $lotteryId)
    {
        $data = $request->toArray();

        //执行操作
        if( $request->isMethod('post') )
        {
            if( !$data['name'] )
            {
                return response()->json(array('errorCode' => 70003, 'message' => '名称不能为空'));
            }
            //过滤数组
            unset($data['_token']);
            $data['lottery_id'] = $lotteryId;
            $status = MethodGroupsModel::create($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('lottery.methodGroup', ['id' => $data['lottery_id']])));
            }

            return response()->json(array('errorCode' => 70002, 'message' => '创建失败'));
        }

        return view('admin.lottery.methodgroupcreate', compact('lotteryId'));
    }

    /**
     * 修改玩法组
     * @param Request $request
     * @param type $mgId
     * @return type html/json
     */
    public function methodGroupUpdate(Request $request, $lotteryId, $mgId)
    {
        if( !$mgId || !$lotteryId )
        {
            return response()->json(array('errorCode' => 70003, 'message' => '参数不能为空'));
        }

        $data = $request->toArray();

        if( $request->isMethod('post') )
        {
            //过滤数组
            unset($data['_token']);
            $status = MethodGroupsModel::where('mg_id', $mgId)->update($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('lottery.methodGroup', ['id' => $lotteryId])));
            }

            return response()->json(array('errorCode' => 70002, 'message' => '操作失败'));
        }

        $methodGroup = MethodGroupsModel::where('mg_id', $mgId)->first();

        return view('admin.lottery.methodgroupupdate', compact('methodGroup', 'lotteryId'));
    }

    /**
     * 删除 玩法组 并删除禁用玩法组下面所有玩法
     * @param Request $request
     * @param type $lottery
     * @return type json
     */
    public function methodGroupDestory(Request $request, $lottery, $mgId)
    {

        if( !$mgId || !$lottery )
        {
            return response()->json(['errorCode' => 70003, 'message' => '参数不能为空']);
        }

        //事物操作玩法组和玩法
        try {
            DB::transaction(function($mgId)use($mgId) {
                DB::table('method_groups')->where('mg_id', $mgId)->update(['status' => 0]);
                DB::table('methods')->where('mg_id', $mgId)->update(['status' => 0]);
            });
        } catch( \Exception $ex ) {
            return response()->json(array('error' => 70002, 'message' => '操作失败'));
        }

        return response()->json(['errorCode' => 00000, 'message' => '操作成功', 'route' => route('lottery.methodGroup', ['id' => $lottery])]);
    }

    /**
     * 玩法管理
     * @param Request $request
     * @return type html
     */
    public function methodsList(Request $request, $lotteryId, $mgId)
    {
        $data = $request->toArray();

        $methods = MethodsModel::where('lottery_id', $lotteryId)
                ->where('mg_id', $mgId)
                ->where('status', 1)
                ->orderBy('sort')
                ->get();

        return view('admin.lottery.methodslist', compact('methods', 'lotteryId', 'mgId'));
    }

    /**
     *  创建玩法组
     * @param Request $request
     * @return type html/json
     */
    public function methodsCreate(Request $request, $lotteryId, $mgId)
    {
        $data = $request->toArray();

        //执行操作
        if( $request->isMethod('post') )
        {
            //常规判断
            if( !$data['name'] || !$data['cname'] )
            {
                return response()->json(array('errorCode' => 70003, 'message' => '玩法名称,中文名称不能为空'));
            }

            if( MethodsModel::where('name', $data['name'])->first() )
            {
                return response()->json(array('errorCode' => 70001, 'message' => '玩法名称重复'));
            }

            //过滤数组
            unset($data['_token']);
            $data['mg_id'] = $mgId;
            $data['lottery_id'] = $lotteryId;
            $status = MethodsModel::create($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('lottery.methodsList', ['id' => $lotteryId, 'mgid' => $mgId])));
            }

            return response()->json(array('errorCode' => 70002, 'message' => '创建失败'));
        }

        return view('admin.lottery.methodcreate', compact('lotteryId', 'mgId'));
    }

    /**
     * 修改玩法
     * @param Request $request
     * @param type $mgId
     * @return type html/json
     */
    public function methodsUpdate(Request $request, $lotteryId, $mgId, $mId)
    {
        if( !$mId || !$lotteryId || !$mgId )
        {
            return response()->json(array('errorCode' => 70003, 'message' => '参数不能为空'));
        }

        $data = $request->toArray();

        if( $request->isMethod('post') )
        {
            //过滤数组
            unset($data['_token']);
            $status = MethodsModel::where('method_id', $mId)->update($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('lottery.methodsList', ['id' => $lotteryId, 'mgid' => $mgId])));
            }

            return response()->json(array('errorCode' => 70002, 'message' => '操作失败'));
        }

        $method = MethodsModel::where('method_id', $mId)->first();

        return view('admin.lottery.methodupdate', compact('method', 'lotteryId', 'mgId'));
    }

    /**
     * 禁用/启用 玩法
     * @param Request $request
     * @param type $lottery
     * @return type json
     */
    public function methodsLock(Request $request, $mid, $is_lock = 1)
    {

        if( !$mid )
        {
            return response()->json(['errorCode' => 70003, 'message' => '参数不能为空']);
        }

        $status = MethodsModel::where('method_id', $mid)->update(['is_lock' => $is_lock]);

        if( $status )
        {
            return response()->json(['errorCode' => 00000, 'message' => '操作成功']);
        }

        return response()->json(['errorCode' => 70002, 'message' => '操作失败']);
    }

    /**
     * 奖期管理
     * @param Request $request
     * @param type $lotteryId
     * @return type html
     */
    public function issuesList(Request $request, $lotteryId = 1)
    {
        var_dump(json_encode(unserialize('a:1:{i:0;a:9:{s:6:"is_use";s:1:"1";s:10:"start_time";s:8:"05:00:00";s:14:"first_end_time";s:8:"10:10:00";s:8:"end_time";s:8:"02:00:00";s:5:"cycle";s:3:"600";s:8:"end_sale";s:2:"90";s:9:"drop_time";s:2:"90";s:9:"code_time";s:2:"30";s:9:"frag_sort";s:1:"0";}}')));
        $issues = IssuesModel::where('lottery_id', $lotteryId)->get();

        return view('admin.lottery.issueslist', ['issues' => $issues, 'issueStatus' => self::issueStatus,'lotteryId'=>$lotteryId]);
    }

    /**
     * 手工开奖
     * @param Request $request
     * @return type json
     */
    public function lotteryUseHand(Request $request, $lotteryId, $issueId)
    {
        $data = $request->toArray();

        if( !$data['issue'] || !$lotteryId || !$issueId )
        {
            return response()->json(['errorCode' => '40001', 'message' => '参数异常']);
        }

        return response()->json(['errorCode' => '00000', 'message' => '操作成功']);
    }

    /**
     * 批量生成奖期
     * @param Request $request
     * @param type $lotteryId
     * @return type html/json
     */
    public function genIssue(Request $request, $lotteryId)
    {
        $lottery = LotteryModel::where('lottery_id', $lotteryId)->first();

        if( $request->isMethod('post') )
        {
            $data = $request->toArray();
            //生成奖期操作
            try {
                IssuesService::genIssue($lottery, $data);
            } catch( \Exception $e ) {
                return response()->json(['errorCode' => $e->getCode(), 'message' => $e->getMessage()]);
            }

            return response()->json(['errorCode' => 00000, 'message' => '操作成功']);
        }

        return view('admin.lottery.genissues',compact('lottery','lotteryId'));
    }

}
