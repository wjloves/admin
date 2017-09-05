@extends('layouts.admin-app')
@section('css')
@parent
<link href="{{ asset('css/bootstrap-wysihtml5.css') }}" rel="stylesheet">
<link href="{{ asset('css/jquery.tagsinput.css') }}" rel="stylesheet" />
<link href="{{ asset('js/uploadify/uploadify.css') }}" rel="stylesheet" type="text/css" >

@endsection
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>创建文章</span>
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

                    <form class="form-horizontal form-bordered" action="{{ route('article.store') }}" method="POST" enctype="multiple/form-data">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">标题</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="title" value="{{ old('title') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">文章banner</label>
                            <div class="col-md-6">
                                  <input  class="form-control" id="file_upload" name="file_upload" type="file" multiple="true"/>
                                  <input class="hide" name="thumb_img_url" id="image_path" value="" type="hidden">
                                  <image id="uploadimg" class="hide" width="350" height="150" src=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">文章内容</label>

                            <div class="col-md-6">
                               <textarea id="wysiwyg" class="form-control" rows="10"  name="content" placeholder="Enter text here..." ></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">标签</label>
                            <div class="col-sm-7">
                                <input name="tags" id="tags" class="form-control" value="HipPop,Jazz Funk" style="display: none;">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                 <input class="btn btn-primary" type="button" value="保存" id="articleStore"/>
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
    <script src="{{ asset('js/jquery.tagsinput.min.js') }}"></script>
    <script src="{{ asset('js/wysihtml5-0.3.0.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-wysihtml5.js') }}"></script>
    <script src="{{ asset('js/uploadify/jquery.uploadify.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/upload.js') }}"></script>
    <script type="text/javascript">
        $("#articleStore").click(function () {
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
       });

       $(function(){
            $('#tags').tagsInput({width:'auto'});
            setTimeout(function(){
                $('#file_upload').uploadify({
                        formData : {
                            'multi'     : true,
                            '_token'    : $("input[name='_token']").val()
                        },
                        height          : 24,
                        width           : 100,
                        swf             : "{{ asset('js/uploadify/uploadify.swf') }}",
                        uploader        : '{{ route("article.upload") }}',
                        fileTypeExts    : '*.jpeg; *.jpg; *.png;*.PNG',
                        buttonText      : '上传图片',
                        fileSizeLimit   : '1024KB',
                        queueSizeLimit  : 10,
                        'onInit'   : function(instance) {

                        },
                        onUploadError   : function(file, errorCode, errorMsg, errorString) {
                        },
                        'onUploadSuccess' : function(file, data, response) {
                            var d = eval("("+data+")");
                            console.log(d);
                            if(d.errorCode == 00000){
                                $('#uploadimg').removeClass('hide');
                                $('#uploadimg').attr('src',d.message);
                                $('#image_path').val(d.message);
                                swal({
                                    title: '上传成功',
                                    type: 'success',
                                    confirmButtonColor: '#8CD4F5',
                                    closeOnConfirm: false
                                });
                            }else{
                                swal('上传失败', 'error','error');
                            }
                        }
                    });
            },1000);
        });
    </script>
@endsection
