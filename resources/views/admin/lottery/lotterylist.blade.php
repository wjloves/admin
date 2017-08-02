@extends('layouts.admin-app')

@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>{!! Breadcrumbs::render('adminLotteryList') !!}</span></h2>
    </div>
    <div class="contentpanel panel-email">
        <div class="row">
            <div class="col-sm-9 col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="pull-right">
                            <div class="btn-group mr10">
                                <a href="{{ route('lottery.create') }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                                <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href=""><i
                                            class="glyphicon glyphicon-trash"></i></a>
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle">彩种列表</h5>

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <thead>
                                <tr>
                                    <th>
                                        <span class="ckbox ckbox-primary">
                                            <input type="checkbox" id="selectall"/>
                                            <label for="selectall"></label>
                                        </span>
                                    </th>
                                    <th>彩种简称</th>
                                    <th>中文名</th>
                                    <th>是否启用</th>
                                    <th>彩种性质</th>
                                    <th>业务类型</th>
                                    <th>描述</th>
                                    <th>官网地址</th>
                                    <th>是否手工录号</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lotteries as $lottery)
                                    <tr>
                                        <td>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" name="id" id="id-{{ $lottery->lottery_id }}"
                                                       value="{{ $lottery->lottery_id }}" class="selectall-item"/>
                                                <label for="id-{{ $lottery->lottery_id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $lottery->name }}</td>
                                        <td>{{ $lottery->cname }}</td>
                                        <td>{!! $lottery->status ? '<span class="label label-success">是</span>':'<span class="label label-default">否</span>' !!}</td>
                                        <td>
                                        {{ $lotteryType[$lottery->lottery_type] }}
                                        </td>
                                        <td>{{ $propertyType[$lottery->property_id] }}</td>
                                        <td>{{ $lottery->description }}</td>
                                        <td id="{{ $lottery->name }}">http://{{ $lottery->web_site }} <button class="btn btn-white btn-xs" data-clipboard-target="#{{ $lottery->name }}">复制</button></td>
                                        <td>{!! $lottery->can_input ? '<span class="label label-success">是</span>':'<span class="label label-default">否</span>' !!} </td>
                                        <td>{{ $lottery->created_at }}</td>
                                        <td>
                                            <a href="{{ route('lottery.methodGroup',['id'=>$lottery->lottery_id]) }}"
                                               class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-th-list"></i> 下级管理</a>
                                            <a href="{{ route('lottery.issues',['id'=>$lottery->lottery_id]) }}"
                                               class="btn btn-info btn-xs"><i class="glyphicon glyphicon-th-list"></i> 奖期管理</a>
                                            <a href="{{ route('lottery.update',['id'=>$lottery->lottery_id]) }}"
                                               class="btn btn-white btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
                                            @if ($lottery->status)
                                            <a class="btn btn-danger btn-xs lottery-delete"
                                               data-href="{{ route('lottery.destory',['id'=>$lottery->lottery_id,'status'=>0]) }}" data-title="禁用">
                                                <i class="fa fa-trash-o"></i> 禁用</a>
                                            @else
                                               <a class="btn btn-danger btn-xs lottery-delete"
                                               data-href="{{ route('lottery.destory',['id'=>$lottery->lottery_id,'status'=>1]) }}" data-title="启用">
                                                <i class="fa fa-trash-o"></i> 启用</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- panel-body -->
                </div><!-- panel -->

            </div><!-- col-sm-9 -->

        </div><!-- row -->

    </div>
@endsection

@section('javascript')
    @parent
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script type="text/javascript">
        var clipboard = new Clipboard('.btn');
        $(".lottery-delete").click(function () {
            var titleTyle = $(this).data('title');
            Cp.ajax.delete({
                confirmTitle: '确定'+titleTyle+'彩种?',
                href: $(this).data('href'),
                successTitle: '操作成功'
            });
        });

        $(".deleteall").click(function () {
            Cp.ajax.deleteAll({
                confirmTitle: '确定删除选中的彩种?',
                href: $(this).data('href'),
                successTitle: '用户删除成功'
            });
        });
    </script>

@endsection
