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
                                <a href="{{ route('article.store') }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                               <!--  <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href=""><i
                                            class="glyphicon glyphicon-trash"></i></a> -->
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle">文章列表</h5>

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>图片</th>
                                    <th>文章内容</th>
                                    <th>标签</th>
                                    <th>状态</th>
                                    <th>创建者</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($articles as $article)
                                    <tr>
                                        <td>{{ $article->id }}</td>
                                        <td>{{ $article->title }}</td>
                                        <td><img height="50px" src="{{ $article->thumb_img_url }}"></td>
                                        <td>
                                        @php $a = str_limit($article->content, 6); @endphp{{ $a }}
                                        @if ($article->content)
                                        <a class="btn btn-xs btn-success article-show"
                                                data-title="预览" data-content="{{$article->content}}">预览</a>
                                        @endif
                                        </td>
                                        <td>{{ $article->tags }}</td>
                                        <td>{{ $article->cadmin->username }}</td>
                                        <td>{!! $article->status== 7 ? '<span class="label label-default">禁用</span>':'<span class="label label-success">正常</span>' !!}</td>
                                        <td>
                                            @if ($article->status == 7)
                                                <a class="btn btn-success article-lock"
                                               data-href="{{ route('article.lock',['id'=>$article->id,'status'=>8]) }}" data-title="启用">
                                                <i class="fa fa-trash-o"></i> 启用</a>
                                            @else
                                               <a class="btn btn-warning article-lock"
                                               data-href="{{ route('article.lock',['id'=>$article->id,'status'=>7]) }}" data-title="禁用">
                                                <i class="fa fa-trash-o"></i> 禁用</a>
                                            @endif
                                            <a href="{{ route('article.update',['id'=>$article->id]) }}"
                                               class="btn btn-white "><i class="fa fa-pencil"></i> 编辑</a>
                                            <a class="btn btn-danger article-lock"
                                               data-href="{{ route('article.lock',['id'=>$article->id,'status'=>0]) }}" data-title="删除">
                                                <i class="fa fa-trash-o"></i> 删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $articles->links() }}
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
        $(".article-lock").click(function () {
            var titleTyle = $(this).data('title');
            Cp.ajax.delete({
                confirmTitle: '确定'+titleTyle+'消息?',
                href: $(this).data('href'),
                successTitle: '操作成功'
            });
        });

        $(".article-show").click(function(){
            $content = $(this).data('content');
            swal({
              title: "预览效果",
              text: $content,
              html: true
            });

        });
    </script>

@endsection
