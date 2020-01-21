@extends('layouts.app')

@section('title', isset($topic->id) ? '编辑话题': '新建话题')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/simditor.css') }}">
@stop



@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          <i class="far fa-edit"></i>
          @if($topic->id)
            编辑话题
          @else
            新建话题 
          @endif
        </h1>
      </div>

      <div class="card-body">
        @if($topic->id)
          <form action="{{ route('topics.update', $topic->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('topics.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('common.error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                  <input class="form-control" 
                  placeholder="请填写标题"
                  type="text" name="title" id="title-field" 
                  value="{{ old('title', $topic->title ) }}" />
                </div> 
                <div class="form-group">
                  <select name="category_id" class="form-control" id="category_id" required>
                    <option value="" hidden disabled selected>请选择分类</option>
                    @foreach ($categories as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach 
                  </select>
                </div> 
                <div class="form-group">
                  <textarea name="body" id="body-field" 
                  placeholder="请填入至少三个字符的内容。"
                  class="form-control" rows="3">{{ old('body', $topic->body ) }}</textarea>
                </div> 
          <div class="well well-sm">
            <button type="submit" class="btn btn-primary"><i class="far fa-save mr-2" aria-hidden="true"></i>保存</button>
            <a class="btn btn-link float-xs-right" href="{{ route('topics.index') }}">返回</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection


@section('scripts')
<script src="{{ asset('js/module.js') }}"></script>
<script src="{{ asset('js/hotkeys.js') }}"></script>
<script src="{{ asset('js/uploader.js') }}"></script>
<script src="{{ asset('js/simditor.js') }}"></script>

<script>
$(document).ready(function(){
  let editor = new Simditor({
    textarea:$('textarea'),
    upload:{
      url: '{{ route('topics.upload_image') }}',
      params: {
        _token: '{{ csrf_token() }}',
      },
      fileKey: 'upload_file',
      connectionCount: 3,
      leaveConfirm: '文件上传中，关闭此页面将取消上传'
    },
    pasteImage:true, 
  })
})
</script>
@stop