@extends('layouts.app')

@section('title','我的通知')

@section('content')
  <div class="container">
    <div class="col-md-10 offset-md-1">
      <div class="card text-left">
        <div class="card-body">
          <h4 class="card-title">我的通知</h4>
          <hr>
          @if($notifications->count())
            <ul class="list-unstyled">
              @foreach ($notifications as $notification)
                @include('notifications.types._'.Str::snake(class_basename($notification->type)))                  
              @endforeach
              {!! $notifications->render() !!}
            </ul>
          @else
            <div class="empty-block">没有消息通知！</div>
          @endif
        </div>
      </div>
    </div>


  </div>

@stop