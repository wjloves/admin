@extends('layouts.admin-app')

@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>创建菜单</span>
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
                        <h4 class="panel-title">创建菜单</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('menu.store') }}" method="POST">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">菜单名称</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('username') }}">
                            </div>
                        </div>

                        <div class="form-group" id="group">
                            <label for="name" class="col-md-4 control-label">分组标志</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="group" value="{{ old('group') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">排序</label>

                            <div class="col-md-1">
                                <input type="number" maxlength="3" name='sort' onkeyup="maxLengthCheck(this)" class="form-control"  placeholder="权重" value="255">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">父菜单</label>

                            <div class="col-sm-3">
                                <select class="form-control input" name="pid" id="pid">
                                    <option value="0" @if(empty($data) || empty($data->pid)) selected="selected" @endif>顶级菜单</option>
                                      @foreach($top_menus as $menu)
                                      <option value="{{$menu->id}}" @if(isset($data->pid) && $data->pid == $menu->id) selected="selected" @endif>{{$menu->name}}</option>
                                      @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group hide" id="perm_id">
                            <label class="col-sm-4 control-label">uri</label>
                            <div class="col-md-6">
                                <input  type="text" class="form-control" name="uri" value="{{ old('uri') }}" required>
                            </div>
                            <!-- <div class="col-sm-3">
                                <select class="form-control" id="perm_id">
                                  @foreach($perms as $perm)
                                  <option value="{{$perm->id}}" @if(isset($data->perm_id) && $data->perm_id == $perm->id) selected="selected" @endif>{{$perm->desc}}</option>
                                  @endforeach
                                </select>
                            </div> -->
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                 <input class="btn btn-primary" type="button" value="保存" id="menuStore"/>
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
    <script type="text/javascript">
        $("#menuStore").click(function () {
            var form = $(".form-horizontal.form-bordered");
            var data = form.serializeArray();
            Cp.ajax.request({
                href: form.attr('action'),
                successTitle: '操作成功',
                data : data,
            });
        });

        $("#pid").change(function(){
          var val = $(this).val();
          if(0 == val){
            //$('#group').parent().parent().fadeIn();
            //$('#perm_id').parent().parent().fadeOut();
            $('#group').removeClass('hide');
            $("#group input[name='group']").attr('disabled',false);
            $('#perm_id').addClass('hide');
            $("#perm_id input[name='uri']").attr('disabled',true);
          }else{
            $('#group').addClass('hide');
            $("#group input[name='group']").attr('disabled',true);
            $('#perm_id').removeClass('hide');
            $("#perm_id input[name='uri']").attr('disabled',false);
          }
        });
    </script>
@endsection
