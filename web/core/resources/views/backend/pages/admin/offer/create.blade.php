@extends('backend.admin-master')
@section('site-title')
    {{__('Add New Offer')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
    <x-media.css/>
    <style>
        span {
            display: inline;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-12 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="header-wrap d-flex justify-content-between mb-4">
                    <div class="left-content">
                        <h4 class="header-title">{{__('Add New Offer')}}   </h4>
                    </div>
                    <div class="right-content">
                        <a class="cmnBtn btn_5 btn_bg_info radius-5" href="{{route('admin.offer.all')}}">{{__('All Offers')}}</a>
                    </div>
                </div>
                <x-validation.error/>
                <form action="{{route('admin.offer.add')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('backend.pages.admin.offer.offer-general-info')
                </form>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
    <x-media.js />
    <x-frontend.js.new-tag-add-js/>
    @if(!empty(get_static_option('google_map_settings_on_off')))
        <x-map.google-map-api-key-set/>
        <x-map.google-map-listing-js/>
    @endif
    <script src="{{asset('assets/frontend/js/multi-step.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    @include('backend.pages.admin.offer.offer-add-more-option-js')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for the select box
            $('#service').select2({
                placeholder: '{{ __('Select Service') }}',  // Placeholder text
                allowClear: true, // Allow clearing the selection
            });
        });
    </script>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {

                // is featured
                $(document).on('click', '.is_featured', function () {
                    $('#is_featured').val($('#is_featured').is(':checked') ? '1' : '');
                });

            });
        })(jQuery)
    </script>
    @if(session('success'))
        <script>
            toastr.success('{{ session('success') }}', 'Success');
        </script>
    @endif
@endsection
