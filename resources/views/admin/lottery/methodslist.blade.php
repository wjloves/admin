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
                                <a href="{{ route('methods.create',['id'=>$lotteryId,'mgid'=>$mgId]) }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                                <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href=""><i
                                            class="glyphicon glyphicon-trash"></i></a>
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle mb5">玩法列表</h5>

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
                                    <th>玩法简称</th>
                                    <th>中文名</th>
                                    <th>是否启用</th>
                                    <th>所属彩种</th>
                                    <th>选号区描述</th>
                                    <th>玩法描述</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($methods as $method)
                                    <tr>
                                        <td>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" name="id" id="id-{{ $method->method_id }}"
                                                       value="{{ $method->method_id }}" class="selectall-item"/>
                                                <label for="id-{{ $method->method_id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $method->name }}</td>
                                        <td>{{ $method->cname }}</td>
                                        <td>{!! $method->is_lock ? '<span class="label label-success">是</span>':'<span class="label label-default">否</span>' !!}</td>
                                        <td>{{ $method->lottery->cname }}</td>
                                        <td>{{ $method->exp }}</td>
                                        <td>{{ $method->description }}</td>
                                        <td>{{ $method->sort }}</td>
                                        <td>
                                            @if ($method->is_lock)
                                                <a class="btn btn-danger btn-xs method-lock"
                                               data-href="{{ route('methods.lock',['id'=>$method->method_id,'status'=>0]) }}" data-title="禁用">
                                                <i class="fa fa-trash-o"></i> 禁用</a>
                                            @else
                                               <a class="btn btn-danger btn-xs method-lock"
                                               data-href="{{ route('methods.lock',['id'=>$method->method_id,'status'=>1]) }}" data-title="启用">
                                                <i class="fa fa-trash-o"></i> 启用</a>
                                            @endif
                                            <a href="{{ route('methods.update',['id'=>$lotteryId,'mgid'=>$mgId,'mid'=>$method->method_id]) }}"
                                               class="btn btn-white btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
                                            <a class="btn btn-danger btn-xs method-delete"
                                               data-href="{{ route('methods.destory',['id'=>$method->method_id]) }}">
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
        $(".method-delete").click(function () {
            Cp.ajax.delete({
                confirmTitle: '确定删除玩法?',
                href: $(this).data('href'),
                successTitle: '删除成功'
            });
        });

        $(".method-lock").click(function () {
            var titleTyle = $(this).data('title');
            Cp.ajax.delete({
                confirmTitle: '确定'+titleTyle+'玩法?',
                href: $(this).data('href'),
                successTitle: titleTyle+'成功'
            });
        });
    </script>

@endsection
