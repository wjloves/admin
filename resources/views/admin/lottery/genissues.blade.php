@extends('layouts.admin-app')
@section('css')
@parent
<link href="{{ asset('css/jquery-ui-1.10.3.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/jquery.tagsinput.css') }}" rel="stylesheet">
<link href="{{ asset('css/dropzone.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>批量生成奖期</span></h2>
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
                        <h4 class="panel-title">批量生成奖期</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('issues.genIssues',['id'=>$lotteryId]) }}" method="POST">

                    <div class="panel-body panel-body-nopadding">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">奖期规则 <span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" data-toggle="tooltip" name="name"
                                       data-trigger="hover" class="form-control tooltips"
                                       data-original-title="不可重复" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">开始期号</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="firstIssue"
                                       value="{{ old('firstIssue') }}">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label">开始时间</label>

                            <div class="col-sm-2">
                                <input size="16" type="text" value="" name="startDate" placeholder="yyyy-mm-dd" data-time="start" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">结束时间</label>

                            <div class="col-sm-2">
                                <input size="16" type="text" value="" name="endDate" placeholder="yyyy-mm-dd" data-time="end" class="form-control datepicker">
                            </div>
                        </div>

                        {{ csrf_field() }}
                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <input class="btn btn-primary" type="button" value="生成" id="genIssues"/>
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
        $( function() {
            $("#genIssues").click(function(){
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

                    // var d = new Date(); // for now

                    // var h = d.getHours();
                    // h = (h < 10) ? ("0" + h) : h ;

                    // var m = d.getMinutes();
                    // m = (m < 10) ? ("0" + m) : m ;

                    // var s = d.getSeconds();
                    // s = (s < 10) ? ("0" + s) : s ;
                    if($(this).data('time') == 'start'){
                        datetext = datetext + " " + "00" + ":" + "00" + ":" + "00";
                    }else{
                        datetext = datetext + " " + 23 + ":" + 59 + ":" + 59;
                    }
                    $(this).val(datetext);
                }
            });
        } );
    </script>
@endsection
