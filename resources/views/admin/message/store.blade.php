@extends('layouts.admin-app')
@section('css')
@parent
<link href="{{ asset('css/bootstrap-wysihtml5.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>创建消息</span>
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
                        <h4 class="panel-title">创建消息</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('message.store') }}" method="POST">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">关键字</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="keywords" value="{{ old('keywords') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">回复内容</label>

                            <div class="col-md-6">
                               <textarea id="wysiwyg" class="form-control" rows="10"  name="reply" placeholder="Enter text here..." ></textarea>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                 <input class="btn btn-primary" type="button" value="保存" id="messageStore"/>
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
    <script src="{{ asset('js/wysihtml5-0.3.0.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-wysihtml5.js') }}"></script>

    <script type="text/javascript">
        $("#messageStore").click(function () {
            var form = $(".form-horizontal.form-bordered");
            var data = form.serializeArray();
            Cp.ajax.request({
                href: form.attr('action'),
                successTitle: '操作成功',
                data : data,
            });
        });
        $(document).ready(function(){
           $('#wysiwyg').wysihtml5();
           $('.wysihtml5-sandbox').css("background-color","yellow");
           console.log($('.wysihtml5-sandbox'));
       });
    </script>
@endsection
