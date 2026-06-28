@include('frontend/frontend/provider/partials/header')
<body>
<!-- preloader area start -->
{{-- @if(!empty(get_static_option('admin_loader_animation')))
    <div class="preloader" id="preloader">
        <div class="preloader-inner">
            <div class="loader_bars">
                <span></span>
            </div>
        </div>
    </div>
@endif --}}
<!-- preloader area end -->
@include('frontend/frontend/provider/partials/navbar');
<div class="user-dashboard-wraper">
    <div class="custom-container-one">
        <div class="user-dashboard-content-wraper">
            @include('frontend/frontend/provider/partials/sidebar');
            @yield('content');
        </div>
    </div>
</div>        
@include('frontend/frontend/provider/partials/footer')
</body>
</html>

