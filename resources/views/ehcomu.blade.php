<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>


  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('css/ehcomo-chat.css') }}" rel="stylesheet">
  <link href="{{ asset('css/reset.min.css') }}" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon.png') }}">
</head>

<body>
    <div class="w-100 h-100" id="app">
      <app/>
    </div>
    <script src="{{asset('/js/jquery.min.js')}}"></script>
    <script src="{{asset('/js/popper.min.js')}}"></script>
    <script src="{{asset('/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('/js/app.js')}}"></script>
</body>
</html>