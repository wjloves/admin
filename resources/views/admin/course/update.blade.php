@extends('layouts.admin-app')
@section('css')
@parent
<link href="{{ asset('css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>修改课程</span></h2>
    </div>

    <div class="contentpanel">

        <div class="row">
            <div class="col-sm-9 col-lg-10">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-btns">
                            <a href="" class="panel-close">×</a>
                            <a href="" class="minimize">−</a>
                        </div>
                        <h4 class="panel-title">修改课程</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('course.update',['id'=>$course->id]) }}" method="POST">

                    <div class="panel-body panel-body-nopadding">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">教师昵称 </label>

                            <div class="col-sm-3">
                                <input type="text" data-toggle="tooltip" name="nick_name"
                                       data-trigger="hover" class="form-control tooltips"
                                       data-original-title="不可重复" value="{{ $course->user->nick_name or ''}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">课程类型</label>

                            <div class="col-sm-3">
                                <select class="form-control mb15" name="course_id">
                                    @foreach($courseType as $k => $v)
                                        <option  value="{{ $v->id }}"
                                        @if($course->course_id == $v->id)
                                        selected
                                        @endif
                                        >{{ $v->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">日期</label>

                            <div class="col-sm-3">
                                <input size="14" type="text" value="{{ $course->course_time }}" name="start_time" placeholder="yyyy-mm-dd hh:ii:ss" data-time="start" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">时间</label>

                            <div class="col-sm-3">
                                <div class="input-group bootstrap-timepicker timepicker">
                                <input id="timepicker1" type="text" class="form-control input-small" name="start_time_exp"
                                value="@php echo date('H:i',strtotime($course->start_time)); @endphp">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                            </div>
                        </div>

                        {{ csrf_field() }}
                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <input class="btn btn-primary" type="button" value="修改" id="updateCourse"/>
                            </div>
                        </div>
                    </div><!-- panel-footer -->

                    </form>
                </div>

            </div><!-- col-sm-9 -->

        </div><!-- row -->

    </div>
@endsection
@section('javascript')
    @parent
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/bootstrap-timepicker.min.js') }}"></script>
    <script type="text/javascript">
        $("#updateCourse").click(function () {
            var form = $(".form-horizontal.form-bordered");
            var data = form.serializeArray();
            Cp.ajax.request({
                href: form.attr('action'),
                successTitle: '操作成功',
                data : data,
            });
        });

        $( ".datepicker" ).datepicker({
            dateFormat:"yy-mm-dd",
            onSelect: function(datetext){
                $(this).val(datetext);
            }
        });
        $('#timepicker1').timepicker({showMeridian: false});

    </script>
@endsection
