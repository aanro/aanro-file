@extends('console.layout.frame')

@section('content')

    <div class="bg-light lter b-b wrapper-md">
        <a href="{{ url('console/files/new') }}" class="btn btn-sm btn-info pull-right"><i class="fa fa-plus"></i> 添加文章</a>
        <h1 class="m-n font-thin h3">文章列表</h1>
    </div>
    <div class="wrapper-md">
        <sl-component-file-list></sl-component-file-list>
    </div>

@endsection