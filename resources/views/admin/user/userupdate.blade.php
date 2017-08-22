@extends('layouts.admin-app')
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>编辑用户</span>
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
                        <h4 class="panel-title">编辑用户</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('user.update',['id'=>$user->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">用户名</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="username" value="{{ $user->username }}" required autofocus>
                                {{ $errors->first() }}
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">昵称</label>

                            <div class="col-md-6">
                                <input id="nick_name" type="text" class="form-control" name="nick_name" value="{{ $user->nick_name }}" required autofocus>
                                {{ $errors->first() }}
                                @if ($errors->has('nick_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nick_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">会员类型</label>

                            <div class="col-sm-3">
                                <select class="form-control input" name="vip_id">
                                    @foreach($vipType as $k => $v)
                                        <option value="{{ $v->id }}"
                                        @if($user->vip_id == $v->id)
                                        selected
                                        @endif
                                        >{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">用户组</label>

                            <div class="col-sm-3">
                                <select class="form-control input" name="group_id">
                                    @foreach($userGroups as $k => $v)
                                        <option value="{{ $v->id }}"
                                        @if($user->group_id == $v->id)
                                        selected
                                        @endif
                                        >{{ $v->group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">会员到期时间</label>

                            <div class="col-sm-3">
                                <input size="14" type="text" value="{{ $user->ex_time }}" name="ex_time" placeholder="yyyy-mm-dd" data-time="start" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">剩余次数<span class="asterisk">*</span></label>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="times"
                                       value="{{ $user->times }}">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                 <input class="btn btn-primary" type="button" value="保存" id="userUpdate"/>
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
        $("#userUpdate").click(function () {
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
