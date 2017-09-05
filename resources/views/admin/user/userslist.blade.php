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
                                <a href="{{ route('user.store') }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                               <!--  <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href=""><i
                                            class="glyphicon glyphicon-trash"></i></a> -->
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle">用户列表</h5>

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
                                    <th>用户ID</th>
                                    <th>昵称</th>
                                    <th>唯一标识</th>
                                    <th>会员类别</th>
                                    <th>剩余次数</th>
                                    <th>Mobile</th>
                                    <th>会员过期时间</th>
                                    <th>用户组</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" name="id" id="id-{{ $user->id }}"
                                                       value="{{ $user->id }}" class="selectall-item"/>
                                                <label for="id-{{ $user->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->nick_name }}</td>
                                        <td>{{ $user->from_user }}</td>
                                        <td>{{ $user->vip->name or '普通会员' }}</td>
                                        <td>{{ $user->times or '0' }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->ex_time }}</td>
                                        <td>{{ $user->userGroup->group_name or '学员' }}</td>
                                        <td>{!! $user->status== 7 ? '<span class="label label-default">禁用</span>':'<span class="label label-success">正常</span>' !!}</td>
                                        <td>
                                            @if ($user->status == 7)
                                                <a class="btn btn-success user-lock"
                                               data-href="{{ route('user.lock',['id'=>$user->id,'status'=>8]) }}" data-title="启用">
                                                <i class="fa fa-trash-o"></i> 启用</a>
                                            @else
                                               <a class="btn btn-warning user-lock"
                                               data-href="{{ route('user.lock',['id'=>$user->id,'status'=>7]) }}" data-title="禁用">
                                                <i class="fa fa-trash-o"></i> 禁用</a>
                                            @endif
                                            <a href="{{ route('user.update',['id'=>$user->id]) }}"
                                               class="btn btn-white "><i class="fa fa-pencil"></i> 编辑</a>
                                            <a class="btn btn-danger user-lock"
                                               data-href="{{ route('user.lock',['id'=>$user->id,'status'=>0]) }}" data-title="删除">
                                                <i class="fa fa-trash-o"></i> 删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $users->links() }}
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
        $(".user-lock").click(function () {
            var titleTyle = $(this).data('title');
            Cp.ajax.delete({
                confirmTitle: '确定'+titleTyle+'用户?',
                href: $(this).data('href'),
                successTitle: '操作成功'
            });
        });
    </script>

@endsection
