@extends('frontend.frontend.provider.provider-master')
@section('title', __('Withdraw Requests'))
@section('style')
    <x-datatable.css/>
    <style>
        .w-90 {width: 90%;}

        .w-20 {width: 20%;}
    </style>
@endsection
@section('content')
    <div class="dashboard__body w-100">
        <div class="row w-100">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Withdraw Requests') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <div class="custom_table style-04 search_result">
                                @include('frontend.frontend.provider.pages.withdraw.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
       $(document).on('click','.update-request',function(){
           let request_id = $(this).data('id');
           let request_status = $(this).data('status');

           $("input[name='request_id']").val(request_id);
           $("input[name='request_status']").val(request_status);
       })

       $(document).on('click','.update_request_status',function(){
           let status = $('#status').val();
           if(status == ''){
               toastr_warning_js("{{ __('Please select status') }}")
               return false;
           }
       })

       //pagination
       $(document).on('click', '.pagination a', function(e){
           e.preventDefault();
           let page = $(this).attr('href').split('page=')[1];
           histories(page);
       });
       function histories(page){
           $.ajax({
               url:"{{ route('admin.withdraw.paginate.data').'?page='}}" + page,
               success:function(res){
                   $('.search_result').html(res);
               }
           });
       }

       // toastr warning
       function toastr_warning_js(msg){
           Command: toastr["warning"](msg, "Warning !")
           toastr.options = {
               "closeButton": true,
               "debug": false,
               "newestOnTop": false,
               "progressBar": true,
               "positionClass": "toast-top-right",
               "preventDuplicates": false,
               "onclick": null,
               "showDuration": "300",
               "hideDuration": "1000",
               "timeOut": "5000",
               "extendedTimeOut": "1000",
               "showEasing": "swing",
               "hideEasing": "linear",
               "showMethod": "fadeIn",
               "hideMethod": "fadeOut"
           }
       }
    </script>
@endsection
