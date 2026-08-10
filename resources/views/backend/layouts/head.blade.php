<meta charset="utf-8" />
<title>{{ config('app.name') }} || @yield('title')</title>
@yield('meta')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script> window.Laravel = { csrfToken: 'csrf_token() ' } </script>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="base-url" content="{{URL::to('/')}}">

<link href="{{asset('backend/assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="{{asset('backend/assets/images/fav.png')}}">
<link href="{{asset('backend/assets/libs/jsvectormap/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/assets/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('backend/assets/js/layout.js')}}"></script>
<link href="{{asset('backend/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />