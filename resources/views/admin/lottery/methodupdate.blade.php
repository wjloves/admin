@extends('layouts.admin-app')
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>编辑玩法</span></h2>
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
                        <h4 class="panel-title">编辑玩法</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('methods.update',['id'=>$lotteryId,'mgid'=>$mgId,'mid'=>$method->method_id]) }}" method="POST">

                    <div class="panel-body panel-body-nopadding">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">玩法名称 <span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" data-toggle="tooltip" name="name" value="{{ $method->name }}" style="text-transform:uppercase;"
                                       data-trigger="hover" class="form-control tooltips" data-original-title="不可为空">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">中文名称 <span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" data-toggle="tooltip" name="cname" value="{{ $method->cname }}"
                                       data-trigger="hover" class="form-control tooltips" data-original-title="不可为空">
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-sm-3 control-label">玩法描述</label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="description"
                                       value="{{ $method->description }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否启用</label>

                            <div class="col-sm-6">
                                <select class="form-control input" name="is_lock">
                                    <option value="0"
                                    @if($method->is_lock == 0)
                                        selected
                                    @endif>否</option>
                                    <option value="1"
                                    @if($method->is_lock == 1)
                                        selected
                                    @endif>是</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">排序</label>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="sort"
                                       value="{{ $method->sort }}">
                            </div>
                        </div>

                        {{ csrf_field() }}
                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <input class="btn btn-primary" type="button" value="保存" id="createMethodGroup"/>
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
        $("#createMethodGroup").click(function () {
            var form = $(".form-horizontal.form-bordered");
            var data = form.serializeArray();
            Cp.ajax.request({
                href: form.attr('action'),
                successTitle: '操作成功',
                data : data,
            });
        });
    </script>
@endsection
