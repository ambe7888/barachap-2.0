<script src="{{asset('assets/common/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/common/js/jquery-migrate-3.4.1.min.js')}}"></script>
<script src="{{asset('assets/common/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/backend/js/slick.js')}}"></script>
<script src="{{asset('assets/backend/js/plugin.js')}}"></script>
<script src="{{asset('assets/backend/js/fancybox.umd.js')}}"></script>
<script src="{{asset('assets/backend/js/main.js')}}"></script>
<script src="{{asset('assets/common/js/toastr.min.js')}}"></script>
<x-backend.password-show-hide-js/>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<x-btn.custom :id="'update'" :title="__('Submitting')" />

@yield('scripts')

<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            // Sync initial state
            if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
                $('body').addClass('iocn_view');
                $('html').addClass('iocn_view');
            }

            // Desktop toggle click handler
            $(document).on('click', '#sidebar_toggle_desktop, .sidebar-toggle-desktop', function(e) {
                e.preventDefault();
                $('body').toggleClass('iocn_view');
                $('html').toggleClass('iocn_view');
                var isCollapsed = $('body').hasClass('iocn_view');
                localStorage.setItem('admin_sidebar_collapsed', isCollapsed);
            });
        });
    })(jQuery);
</script>
<x-popup.default-js-popup/>
