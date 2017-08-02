@extends('layouts.admin-app')

@section('content')

    <div class="contentpanel panel-email">
        <div class="row">
            <div class="col-sm-9 col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="pull-right">
                            <div class="btn-group mr10">
                                <a href="{{ route('issues.genIssues',['id'=>$lotteryId]) }}" class="btn btn-success tooltips"
                                   data-toggle="tooltip" data-original-title="批量生成奖期"><i
                                            ></i>批量生成奖期</a>
                            </div>
                        </div><!-- pull-right -->
                        <h4>奖期列表</h4>

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <thead>
                                <tr>
                                    <th>
                                        <span class="ckbox ckbox-primary">
                                            <input type="checkbox" id="selectall"/>
                                            <label for="selectall"></label>
                                        </span>
                                    </th>
                                    <th>彩种简称</th>
                                    <th>奖期期号</th>
                                    <th>开奖号码</th>
                                    <th>销售开始时间</th>
                                    <th>销售结束时间</th>
                                    <th>录号状态</th>
                                    <th>开奖状态</th>
                                    <th>派奖状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($issues as $issue)
                                    <tr>
                                        <td>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" name="id" id="id-{{ $issue->issue_id }}"
                                                       value="{{ $issue->issue_id }}" class="selectall-item"/>
                                                <label for="id-{{ $issue->issue_id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $issue->lottery->cname }}</td>
                                        <td>{{ $issue->issue }}</td>
                                        <td>{{ $issue->code }}</td>
                                        <td>{{ $issue->start_sale_time }}</td>
                                        <td>{{ $issue->end_sale_time }}</td>
                                        <td>{{ $issueStatus[$issue->status_fetch] }}</td>
                                        <td>{{ $issueStatus[$issue->status_check_prize] }}</td>
                                        <td>{{ $issueStatus[$issue->status_send_prize] }}</td>
                                        <td>
                                            <a class="btn btn-white btn-xs draw" data-href="{{ route('lottery.use.hand',['id'=>$issue->lottery_id,'isid'=>$issue->issue_id]) }}"><i class="fa fa-pencil"></i> 手工开奖</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- panel-body -->
                </div><!-- panel -->

            </div><!-- col-sm-9 -->

        </div><!-- row -->

    </div>
@endsection

@section('javascript')
    @parent
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script type="text/javascript">
        $(".draw").click(function(){
            Cp.ajax.draw({
                    confirmTitle: '输入开奖号码',
                    confirmText: '注意只能输入数字',
                    href: $(this).data('href'),
                    successTitle: '操作成功'
            });
        });

        var clipboard = new Clipboard('.btn');
        $(".lottery-delete").click(function () {
            Cp.ajax.delete({
                confirmTitle: '确定删除奖期?',
                href: $(this).data('href'),
                successTitle: '用户删除成功'
            });
        });

        $(".deleteall").click(function () {
            Cp.ajax.deleteAll({
                confirmTitle: '确定删除选中的奖期?',
                href: $(this).data('href'),
                successTitle: '用户删除成功'
            });
        });
    </script>

@endsection
