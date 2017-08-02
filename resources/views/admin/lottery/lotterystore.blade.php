@extends('layouts.admin-app')
@section('css')
@parent
<link href="{{ asset('css/jquery-ui-1.10.3.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>创建彩种</span></h2>
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
                        <h4 class="panel-title">创建彩种</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('lottery.store') }}" method="POST">

                    <div class="panel-body panel-body-nopadding">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">彩种名称 <span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" data-toggle="tooltip" name="name"
                                       data-trigger="hover" class="form-control tooltips"
                                       data-original-title="不可重复" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">中文名称<span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="cname"
                                       value="{{ old('cname') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">官网地址<span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="web_site"
                                       value="{{ old('web_site') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">彩种类型</label>

                            <div class="col-sm-6">
                                <select class="form-control input" name="lottery_type">
                                    @foreach($lotteryType as $k => $v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">彩种性质</label>

                            <div class="col-sm-6">
                                <select class="form-control input" name="property_id">
                                @foreach($propertyType as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-sm-3 control-label">是否手工录号</label>

                            <div class="col-sm-6">
                                <select class="form-control input" name="can_input">
                                    <option value="0">否</option>
                                    <option value="1">是</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">年度休市开始时间</label>

                            <div class="col-sm-2">
                                <input size="16" type="text" value="" name="yearly_start_closed" placeholder="yyyy-mm-dd" data-time="start" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">年度休市结束时间</label>

                            <div class="col-sm-2">
                                <input size="16" type="text" value="" name="yearly_end_closed" placeholder="yyyy-mm-dd" data-time="end" class="form-control datepicker">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">奖期规则<span class="asterisk">*</span></label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="issue_rule"
                                       value="{{ old('issue_rule') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">奖期设置<span class="asterisk">*</span></label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="settings"
                                       value="{{ old('settings') }}">
                            </div>
                            <div class="col-sm-4">
                            <p class="text-left">奖期设置(序列化存储)开始销售时间，最后结束销售时间，销售周期，停售时间，号码录入时间，撤单时间</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">彩种描述<span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="description"
                                       value="{{ old('description') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">排序</label>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="sort"
                                       value="{{ old('sort') }}">
                            </div>
                        </div>

                        {{ csrf_field() }}
                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <input class="btn btn-primary storeLottey" type="button" value="保存" />
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
        $(".storeLottey").click(function () {
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
