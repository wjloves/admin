@extends('layouts.admin-app')
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>编辑玩法组</span></h2>
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
                        <h4 class="panel-title">编辑玩法组</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('methodGroup.update',['id'=>$lotteryId,'mgid'=>$methodGroup->mg_id]) }}" method="POST">

                    <div class="panel-body panel-body-nopadding">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">玩法组名称 </label>

                            <div class="col-sm-6">
                                <input type="text" data-toggle="tooltip" name="name" value="{{ $methodGroup->name }}" 
                                       data-trigger="hover" class="form-control tooltips" data-original-title="不可为空">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">玩法组描述</label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="description"
                                       value="{{ $methodGroup->description }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">排序</label>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="sort"
                                       value="{{ $methodGroup->sort }}">
                            </div>
                        </div>

                        {{ csrf_field() }}
                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <input class="btn btn-primary" type="button" value="保存" id="updateMethodGroup"/>
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
        $("#updateMethodGroup").click(function () {
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
