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
                                <a href="{{ route('course.store') }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                               <!--  <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href=""><i
                                            class="glyphicon glyphicon-trash"></i></a> -->
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle">课程列表</h5>

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
                                    <th>课程ID</th>
                                    <th>教师</th>
                                    <th>课程类型</th>
                                    <th>报名人数</th>
                                    <th>开始时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        <td>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" name="id" id="id-{{ $course->id }}"
                                                       value="{{ $course->id }}" class="selectall-item"/>
                                                <label for="id-{{ $course->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $course->id }}</td>
                                        <td>{{ $course->user->nick_name or ''}}</td>
                                        <td>{{ $course->courseType->full_name or '未知'}}</td>
                                        <td>{{ $course->userCourse->count() }}</td>
                                        <td>{{ $course->start_time }}</td>
                                        <td>{!! $course->status== 7 ? '<span class="label label-default">取消</span>':'<span class="label label-success">正常</span>' !!}</td>
                                        <td>
                                            @if ($course->status == 7)
                                                <a class="btn btn-success user-lock"
                                               data-href="{{ route('course.lock',['id'=>$course->id,'status'=>8]) }}" data-title="启用">
                                                <i class="fa fa-trash-o"></i> 启用</a>
                                            @else
                                               <a class="btn btn-warning user-lock"
                                               data-href="{{ route('course.lock',['id'=>$course->id,'status'=>7]) }}" data-title="取消">
                                                <i class="fa fa-trash-o"></i> 取消</a>
                                            @endif
                                            <a href="{{ route('course.update',['id'=>$course->id]) }}"
                                               class="btn btn-white "><i class="fa fa-pencil"></i> 编辑</a>
                                            <a class="btn btn-danger user-lock"
                                               data-href="{{ route('course.lock',['id'=>$course->id,'status'=>0]) }}" data-title="删除">
                                                <i class="fa fa-trash-o"></i> 删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $courses->links() }}
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
        $(".user-lock").click(function () {
            var titleTyle = $(this).data('title');
            Cp.ajax.delete({
                confirmTitle: '确定'+titleTyle+'课程?',
                href: $(this).data('href'),
                successTitle: '操作成功'
            });
        });
    </script>

@endsection
