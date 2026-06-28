@extends('frontend.frontend.client.client-master')
@section('site-title')
    {{__('All Locations')}}
@endsection
@section('style')
    <style>
        td.actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
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
                            <h4 class="dashboard__inner__header__title">{{ __('All Locations') }}</h4>
                        </div>
                        <div class="dashboard__inner__header__right">
                            <div class="d-flex text-right w-100 mt-3">
                                <input class="form__control blog_string_search" name="string_search" id="string_search" placeholder="{{ __('Search') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <x-validation.error />
                <div class="tableStyle_three mt-4">
                    <div class="table_wrapper custom_Table">
                        <div class="search_result">
                            @include('frontend.frontend.client.pages.location.search-result')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.frontend.client.pages.location.location-details-modal')
   
    <x-media.markup/>
@endsection
@section('scripts')
    <x-media.js />

    <script>
        (function(){
            "use strict";
            $(document).ready(function(){
                // show review details in modal
            $(document).on('click','.location_details',function(){
                let location_id = $(this).data('location_id');
                let title = $(this).data('title');
                let state = $(this).data('state');
                let city = $(this).data('city');
                let area = $(this).data('area');
                let type = $(this).data('type');
                let address = $(this).data('address');
                let post_code = $(this).data('post_code');
                let latitude = $(this).data('latitude');
                let longitude = $(this).data('longitude');
                let phone = $(this).data('phone');
                let emergency_phone = $(this).data('emergency_phone');


                
                $('#location_details .title').text(title);
                $('#location_details .state').text(state);
                $('#location_details .city').text(city);
                $('#location_details .area').text(area);
                $('#location_details .type').text(type);
                $('#location_details .address').text(address);
                $('#location_details .post_code').text(post_code);
                $('#location_details .latitude').text(latitude);
                $('#location_details .longitude').text(longitude);
                $('#location_details .phone').text(phone);
                $('#location_details .emergency_phone').text(emergency_phone);
                
            });
            });
        })(jQuery);      
    </script>    

   
@endsection
