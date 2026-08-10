<script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('backend/assets/js/plugins.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<!-- apexcharts -->
<!-- <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script> -->
<!-- Vector map-->
<!-- <script src="{{ asset('backend/assets/libs/jsvectormap/jsvectormap.min.js') }}"></script> -->
<!-- <script src="{{ asset('backend/assets/libs/jsvectormap/maps/world-merc.js') }}"></script> -->
<!--Swiper slider js-->
<!-- <script src="{{ asset('backend/assets/libs/swiper/swiper-bundle.min.js') }}"></script> -->
<!-- Dashboard init -->
<!-- <script src="{{ asset('backend/assets/js/pages/dashboard-ecommerce.init.js') }}"></script> -->
<script src="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{ asset('backend/assets/js/pages/sweetalerts.init.js')}}"></script>
<!-- App js -->
<script src="{{ asset('backend/assets/js/app.js') }}"></script>

@if(session()->has('success'))
<script>
    Toastify({
        text: '{{ session('success') }}',
        duration: 4000,
        gravity: "top",
        position: "right", 
        className: "bg-success",
        close: true
    }).showToast();
</script>
@endif

@if(session()->has('error'))
<script>
    Toastify({
        text: '{{ session('error') }}',
        duration: 4000,
        gravity: "top",
        position: "right", 
        className: "bg-danger",
        close: true
    }).showToast();
</script>
@endif

@if($errors->any())
<script>
    @foreach ($errors->all() as $error)
        Toastify({
            text: '{{ $error }}',
            duration: 4000,
            gravity: "top",
            position: "right", 
            className: "bg-danger",
            close: true
        }).showToast();
    @endforeach
</script>
@endif