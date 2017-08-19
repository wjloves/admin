@extends('layouts.admin-app')
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>修改课程类型</span>
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
                        <h4 class="panel-title">修改课程类型</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('course.type.update',['id'=>$courseType->id]) }}" method="POST">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">全称</label>

                            <div class="col-md-4">
                                <input id="name" type="text" class="form-control" name="full_name" value="{{ $courseType->full_name }}" >
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">别名</label>

                            <div class="col-md-4">
                                <input id="name" type="text" class="form-control" name="alias_name" placeholder="jazz" value="{{ $courseType->alias_name }}" >
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                 <input class="btn btn-primary" type="button" value="保存" id="typeUpdate"/>
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
        $("#typeUpdate").click(function () {
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
