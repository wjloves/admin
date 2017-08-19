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
                                <a href="{{ route('course.type.store') }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                               <!--  <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href=""><i
                                            class="glyphicon glyphicon-trash"></i></a> -->
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle">课程类型列表</h5>

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
                                    <th>ID</th>
                                    <th>全称</th>
                                    <th>别名</th>
                                    <th>管理员</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courseTypes as $type)
                                    <tr>
                                        <td>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" name="id" id="id-{{ $type->id }}"
                                                       value="{{ $type->id }}" class="selectall-item"/>
                                                <label for="id-{{ $type->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $type->id }}</td>
                                        <td>{{ $type->full_name }}</td>
                                        <td>{{ $type->alias_name }}</td>
                                        <td>{{ $type->admin->username }}</td>
                                        <td>
                                            <a href="{{ route('course.type.update',['id'=>$type->id]) }}"
                                               class="btn btn-white "><i class="fa fa-pencil"></i> 编辑</a>
                                            <a class="btn btn-danger type-lock"
                                               data-href="{{ route('course.type.del',['id'=>$type->id]) }}" data-title="删除">
                                                <i class="fa fa-trash-o"></i> 删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $courseTypes->links() }}
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
        $(".type-lock").click(function () {
            var titleTyle = $(this).data('title');
            Cp.ajax.delete({
                confirmTitle: '确定'+titleTyle+'课程?',
                href: $(this).data('href'),
                successTitle: '操作成功'
            });
        });
    </script>

@endsection
