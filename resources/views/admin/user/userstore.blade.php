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
                        <h4 class="panel-title">创建用户</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('user.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">用户名</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                                {{ $errors->first() }}
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">微信ID</label>

                            <div class="col-md-6">
                                <input id="wechatID" type="text" class="form-control" name="wechatID" value="{{ old('wechatID') }}" required>

                                @if ($errors->has('wechatID'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('wechatID') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">会员类型</label>

                            <div class="col-sm-3">
                                <select class="form-control input" name="card_type">
                                    @foreach($cardType as $k => $v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">会员到期时间</label>

                            <div class="col-sm-3">
                                <input size="14" type="text" value="" name="ex_time" placeholder="yyyy-mm-dd" data-time="start" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">剩余次数<span class="asterisk">*</span></label>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="times"
                                       value="{{ old('times') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                 <input class="btn btn-primary" type="button" value="保存" id="userStore"/>
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
        $("#userStore").click(function () {
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
    </script>
@endsection
