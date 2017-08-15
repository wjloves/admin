@extends('layouts.admin-app')
@section('css')
@parent
<link href="{{ asset('css/jquery-ui-1.10.3.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>创建用户</span>
        @if($errors->any())
        <h4>{{$errors->first()}}</h4>
        @endif
        </h2>
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
                        <h4 class="panel-title">创建课程</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('course.store') }}" method="POST">
                    {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">教师昵称</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="nick_name" value="{{ old('nick_name') }}" required autofocus>
                                {{ $errors->first() }}
                                @if ($errors->has('nick_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nick_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-4 control-label">课程类型</label>

                            <div class="col-sm-3 select2-container select2">
                                <select class="select2 select2-offscreen" name="course_id">
                                    @foreach($courseType as $k => $v)
                                        <option value="{{ $v->id }}">{{ $v->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">日期</label>

                            <div class="col-sm-3">
                                <input size="14" type="text" value="" name="start_time" placeholder="yyyy-mm-dd hh:ii:ss" data-time="start" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">时间</label>

                            <div class="col-sm-3">
                                <div class="input-group bootstrap-timepicker timepicker">
                                <input id="timepicker1" type="text" class="form-control input-small" name="start_time_exp">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                 <input class="btn btn-primary" type="button" value="保存" id="courseStore"/>
                            </div>
                        </div>
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
        $("#courseStore").click(function () {
            var form = $(".form-horizontal.form-bordered");
            var data = form.serializeArray();
            console.log(data);return;
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
        $('#timepicker1').timepicker();
    </script>
@endsection
