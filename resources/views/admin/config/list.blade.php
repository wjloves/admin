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
                                <a href="{{ route('config.store') }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                               <!--  <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href=""><i
                                            class="glyphicon glyphicon-trash"></i></a> -->
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle">课程列表</h5>

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
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>英文别名</th>
                                    <th>配置描述</th>
                                    <th>内容</th>
                                    <th>状态</th>
                                    <th>添加人</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($configs as $config)
                                    <tr>
                                        <td>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" name="id" id="id-{{ $config->id }}"
                                                       value="{{ $config->id }}" class="selectall-item"/>
                                                <label for="id-{{ $config->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $config->id }}</td>
                                        <td>{{ $config->title }}</td>
                                        <td>{{ $config->alias_name }}</td>
                                        <td>{{ $config->description }}</td>
                                        <td>
                                        @php $a = str_limit($config->content, 7); @endphp{{ $a }}
                                        @if ($config->content)
                                        <a class="btn btn-xs btn-success config-show"
                                                data-title="预览" data-content="{{$config->content}}">显示全部</a>
                                        @endif
                                        </td>
                                        <td>{!! $config->status== 7 ? '<span class="label label-default">禁用</span>':'<span class="label label-success">正常</span>' !!}</td>
                                        <td>{{ $config->admin->username or ''}}</td>
                                        <td>
                                            @if ($config->status == 7)
                                                <a class="btn btn-success config-lock"
                                               data-href="{{ route('config.lock',['id'=>$config->id,'status'=>8]) }}" data-title="启用">
                                                <i class="fa fa-trash-o"></i> 启用</a>
                                            @else
                                               <a class="btn btn-warning config-lock"
                                               data-href="{{ route('config.lock',['id'=>$config->id,'status'=>7]) }}" data-title="禁用">
                                                <i class="fa fa-trash-o"></i> 禁用</a>
                                            @endif
                                            <a href="{{ route('config.update',['id'=>$config->id]) }}"
                                               class="btn btn-white "><i class="fa fa-pencil"></i> 编辑</a>
                                            <a class="btn btn-danger config-lock"
                                               data-href="{{ route('config.lock',['id'=>$config->id,'status'=>0]) }}" data-title="删除">
                                                <i class="fa fa-trash-o"></i> 删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $configs->links() }}
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
        $(".config-lock").click(function () {
            var titleTyle = $(this).data('title');
            Cp.ajax.delete({
                confirmTitle: '确定'+titleTyle+'课程?',
                href: $(this).data('href'),
                successTitle: '操作成功'
            });
        });
        $(".config-show").click(function(){
            $content = $(this).data('content');
            swal({
              title: "预览效果",
              text: $content,
              html: true
            });

        });
    </script>

@endsection
