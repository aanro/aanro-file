@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('console/articles') }}" class="btn btn-sm btn-default pull-right">返回</a>
        <h1 class="m-n font-thin h3">查看文章</h1>
    </div>
    <div class="wrapper-md">
        <sl-component-file-detail item-id="{{ $article_id }}"></sl-component-file-detail>
    </div>

@endsection