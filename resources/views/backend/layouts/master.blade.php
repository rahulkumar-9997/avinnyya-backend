<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
    <head>
        @include('backend.layouts.head')
        @stack('styles')
    </head>
    <body>
        <div id="layout-wrapper">
            @include('backend.layouts.header')
            @include('backend.layouts.sidebar')
            <div class="main-content">
                @yield('main-content')
                @include('backend.layouts.footer')               
            </div>
        </div>
        @include('backend.layouts.footer-js')        
        @stack('scripts')
    </body>
</html>