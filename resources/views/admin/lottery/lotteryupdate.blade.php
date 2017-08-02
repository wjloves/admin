@extends('layouts.admin-app')
@section('css')
@parent
<link href="{{ asset('css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span>修改彩种</span></h2>
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
                        <h4 class="panel-title">修改彩种</h4>
                    </div>

                    <form class="form-horizontal form-bordered" action="{{ route('lottery.update',['id'=>$lottery->lottery_id]) }}" method="POST">

                    <div class="panel-body panel-body-nopadding">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">彩种名称 <span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" data-toggle="tooltip" name="name"
                                       data-trigger="hover" class="form-control tooltips"
                                       data-original-title="不可重复" value="{{ $lottery->name }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">中文名称<span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="cname"
                                       value="{{ $lottery->cname }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">官网地址<span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="web_site"
                                       value="{{ $lottery->web_site }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">彩种类型</label>

                            <div class="col-sm-6">
                                <select class="form-control input" name="lottery_type">
                                    @foreach($lotteryType as $k => $v)
                                        <option value="{{ $k }}"
                                        @if($lottery->lottery_type == $k)
                                        selected
                                        @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">彩种性质</label>

                            <div class="col-sm-6">
                                <select class="form-control input" name="property_id">
                                @foreach($propertyType as $k => $v)
                                    <option value="{{ $k }}"
                                    @if($lottery->property_id == $k)
                                    selected
                                    @endif>{{ $v }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否手工录号</label>

                            <div class="col-sm-6">
                                <select class="form-control input" name="can_input">
                                    <option value="0"
                                    @if($lottery->can_input == 0)
                                        selected
                                    @endif>否</option>
                                    <option value="1"
                                    @if($lottery->can_input == 1)
                                        selected
                                    @endif>是</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">年度休市开始时间</label>

                            <div class="col-sm-6">
                                <input size="16" type="text" value="{{ $lottery->yearly_start_closed }}" name="yearly_start_closed" class="form_datetime">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">年度休市结束时间</label>

                            <div class="col-sm-6">
                                <input size="16" type="text" value="{{ $lottery->yearly_end_closed }}" name="yearly_end_closed" class="form_datetime">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">彩种描述<span class="asterisk">*</span></label>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="description"
                                       value="{{ $lottery->description }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">排序</label>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="sort"
                                       value="{{ $lottery->sort }}">
                            </div>
                        </div>

                        {{ csrf_field() }}
                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <input class="btn btn-primary" type="button" value="修改" id="createLottey"/>
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
        $("#createLottey").click(function () {
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
