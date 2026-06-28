@extends('frontend.frontend.provider.provider-master')
@section('site-title')
    {{__('All Jobs')}}
@endsection
@section('style')
    <style>
        .custom_table tr td:not(:first-child) {
            min-width: 42px;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 w-100 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('All Jobs') }}</h4>
                       </div>
                   </div>
                 </div>
                <x-validation.error/>
                <div class="tableStyle_three mt-4">
                    @include('jobpost::frontend.jobs.job-filter')
                    <div class="table_wrapper custom_Table">
                        <div class="search_result">
                            @include('jobpost::frontend.jobs.search-result')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){

                // change status
                $(document).on('click','.swal_status_change',function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: '{{__("Are you sure to change status complete? Once you done you can not revert this !!")}}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "{{ __('Yes, change it!') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).next().find('.swal_form_submit_btn').trigger('click');
                        }
                    });
                });

                // live search
                $(document).on('keyup','.string_search',function(){
                    let string_search = $(this).val();
                    $.ajax({
                        url:"{{ route('provider.job.search') }}",
                        method:'GET',
                        data:{string_search:string_search},
                        success:function(res){
                            if(res.status=='nothing'){
                                $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                            }else{
                                $('.search_result').html(res);
                            }
                        }
                    });
                });

                // pagination
                $(document).on('click', '.pagination li a', function(e){
                    e.preventDefault();
                    let page = $(this).attr('href').split('page=')[1];

                    // Get the currently active tab status from the URL
                    let activeTab = $('.tabs li.active a').attr('href');
                    let status = new URL(activeTab).searchParams.get('status');

                    admin_orders(page, status);
                });
                function admin_orders(page, status){
                    $.ajax({
                        url:"{{ route('provider.job.paginate').'?page='}}" + page + "&status=" + status,
                        success:function(res){
                            $('.search_result').html(res);
                        }
                    });
                }
            });
        })(jQuery);
    </script>
@endsection
