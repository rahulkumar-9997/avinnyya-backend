<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} || Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.name') }}"/>
    <meta content="{{ config('app.name') }}" name="author" />
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/fav.png') }}">
    <script src="{{ asset('backend/assets/js/layout.js') }}"></script>
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="auth-page-wrapper pt-5">
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index.html" class="d-inline-block auth-logo">
                                    <img src="{{ asset('backend/assets/images/logo.png') }}" alt="" height="80">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 card-bg-fill">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Forgot Password?</h5>                                   
                                </div>
                                <div class="p-2 mt-4">
                                    @if($errors->any())
                                        <br>
                                        <div class="alert alert-danger">
                                        <p><strong>Opps Something went wrong</strong></p>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        </div>
                                        @endif
                                        @if(session()->has('error'))
                                        <br>
                                        <div class="alert alert-danger">
                                        {{ session()->get('error') }}
                                        </div>
                                        @endif
                                        @if(session()->has('success'))
                                        <br>
                                        <div class="alert alert-danger">
                                        {{ session()->get('success') }}
                                        </div>
                                        @endif
                                    <form action="{{ route('forget.password.submit') }}" class="authentication-form" method="post">
                                    @csrf
                                        @csrf
                                        <div class="mb-3">
                                            <label for="Email" class="form-label">Enter Registered Email'id *</label>
                                            <input type="text" class="form-control" id="Email" placeholder="Enter email" name="email" value="{{ old('email') }}"  autofocus>
                                            
                                        </div>
                                        <div class="mb-3">
                                            <div class="float-end mb-3">
                                                <a href="{{route('login')}}" class="text-muted">Go Back to Login ?</a>
                                            </div>                                            
                                        </div>
                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Submit</button>
                                        </div>                                        
                                    </form>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
        @include('backend.layouts.footer')
    </div>
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('backend/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('backend/assets/js/pages/password-addon.init.js') }}"></script>
</body>
</html>