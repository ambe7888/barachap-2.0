@extends('backend.admin-master')
@section('site-title')
    {{__('Slider Settings')}}
@endsection
@section('style')
    <x-datatable.css/>
    <x-media.css/>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-9 col-lg-9">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <h2 class="dashboard__card__header__title mb-3">{{__('Slider Settings')}}</h2>
                <x-validation.error/>
                <div class="tableStyle_three mt-4">
                    @include('backend.pages.slider.all-slider')
                </div>
            </div>
        </div>
        @include('backend.pages.slider.edit_slider_new')
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
    <x-media.js />
    <x-datatable.js/>
    <script>
        $(document).ready(function() {
            // Function to show/hide sections based on the selected type
            function toggleSections() {
                let selectedType = $('#type').val();

                if (selectedType === 'service') {
                    $('#service-section').show();
                    $('#category-section').hide();
                    $('#offer-section').hide();
                } else if (selectedType === 'category') {
                    $('#category-section').show();
                    $('#service-section').hide();
                    $('#offer-section').hide();
                }else if (selectedType === 'offer') {
                    $('#offer-section').show();
                    $('#category-section').hide();
                    $('#service-section').hide();
                   
                }  
                else {
                    $('#service-section').hide();
                    $('#category-section').hide();
                    $('#offer-section').hide();
                }
            }

            // Initial setup
            toggleSections();

            // Show/hide sections when type changes
            $(document).on('change', '#type', function (e) {
                toggleSections();
            });
        });
    </script>
@endsection
