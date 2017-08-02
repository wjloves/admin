@extends('layouts.admin-app')

@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span></span></h2>
    </div>
    <div class="contentpanel panel-email">
        <div class="row">
            <div class="col-sm-9 col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="pull-right">
                            <div class="btn-group mr10">
                                <a href="{{ route('methodGroup.create',['id'=>$lotteryId]) }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                                <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href=""><i
                                            class="glyphicon glyphicon-trash"></i></a>
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle mb5">玩法组列表</h5>

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
                                    <th>玩法组名称</th>
                                    <th>所属彩票</th>
                                    <th>描述</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($methodGroups as $methodGroup)
                                    <tr>
                                        <td>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" name="id" id="id-{{ $methodGroup->mg_id }}"
                                                       value="{{ $methodGroup->mg_id }}" class="selectall-item"/>
                                                <label for="id-{{ $methodGroup->mg_id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $methodGroup->name }}</td>
                                        <td>{{ $methodGroup->lottery->cname }}</td>
                                        <td>{{ $methodGroup->description }}</td>
                                        <td>{{ $methodGroup->sort }}</td>
                                        <td>
                                            <a href="{{ route('lottery.methodsList',['id'=>$methodGroup->lottery_id,'gid'=>$methodGroup->mg_id]) }}"
                                               class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-th-list"></i> 玩法管理</a>
                                            <a href="{{ route('methodGroup.update',['id'=>$lotteryId,'mgid'=>$methodGroup->mg_id]) }}"
                                               class="btn btn-white btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs lottery-delete"
                                               data-href="{{ route('methodGroup.destory',['id'=>$methodGroup->lottery_id,'mgid'=>$methodGroup->mg_id]) }}" data-title="删除">
                                                <i class="fa fa-trash-o"></i> 删除</a>
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
            Cp.ajax.delete({
                confirmTitle: '确定删除玩法组?此操作将删除玩法组下面所有玩法',
                href: $(this).data('href'),
                successTitle: '删除成功'
            });
        });

        $(".deleteall").click(function () {
            Cp.ajax.deleteAll({
                confirmTitle: '确定删除选中的玩法组?',
                href: $(this).data('href'),
                successTitle: '删除成功'
            });
        });
    </script>

@endsection
