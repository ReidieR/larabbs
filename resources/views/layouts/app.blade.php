<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  {{-- csrf_token --}}
  <meta name="csrf_token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('description','LaraBBS 爱好者社区')">
  <title>@yield('title')-laravel进阶教程</title>
  {{-- styles --}}
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  @yield('styles')
</head>
<body>
<div id="app" class="{{ route_class() }}-page">
  @include('layouts._header')
  <div class="container">

    @include('shared._messages')

    @yield('content')

  </div>

  @include('layouts._footer')
</div>
@if(app()->islocal())
  @include('sudosu::user-selector')
@endif
{{-- scripts --}}
<script src="{{ mix('js/app.js') }}"></script>
@yield('scripts')
</body>

</html>
