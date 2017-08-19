@extends('layouts.admin-app')

@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Dashboard <span></span></h2>
    </div>
    <div class="contentpanel panel-email">
        <div class="row">
            <div class="col-sm-9 col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="pull-right">
                            <div class="btn-group mr10">
                                <a href="{{ route('vip.store') }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                               <!--  <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href=""><i
                                            class="glyphicon glyphicon-trash"></i></a> -->
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle">VIP列表</h5>

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($vips as $vip)
                                    <tr>
                                        <td>{{ $vip->name }}</td>
                                        <td>
                                            <a href="{{ route('vip.update',['id'=>$vip->id]) }}"
                                               class="btn btn-xs btn-white "><i class="fa fa-pencil"></i> 编辑</a>
                                            <a class="btn btn-xs btn-danger vip-del"
                                               data-href="{{ route('vip.del',['id'=>$vip->id]) }}" data-title="删除">
                                                <i class="fa fa-trash-o"></i> 删除</a>
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
        var clipboard = new Clipboard('.btn');
        $(".vip-del").click(function () {
            Cp.ajax.delete({
                confirmTitle: '确定删除?',
                href: $(this).data('href'),
                successTitle: '操作成功'
            });
        });
    </script>

@endsection
