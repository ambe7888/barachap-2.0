@extends('backend.admin-master')
@section('site-title')
    {{__('Edit Service')}}
@endsection
@section('style')
    <x-media.css/>
    <style>
        input#pac-input {
            background-color: ghostwhite;
        }
        .select2-container .select2-selection--single {
            background-color: var(--white-bg);
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            position: relative;
            padding: 10px 5px;
        }

        span.select2.select2-container.select2-container--default.select2-container--focus {
            width: 100% !important;
        }
        .select-itms span.select2{
            width: 100% !important;
        }


        .close{ border: none;  }
        .dashboard-switch-single{
            font-size: 20px;
        }
        .swal_delete_button{
            color: #da0000 !important;
        }
        /* Default styles for the input box */
        #pac-input {
            height: 3em;
            width:75%;
            margin-left: 140px;
            border: 1px solid;
            top: 4px;
            font-size: 16px;
        }

        /* Media query for screens smaller than 768px */
        @media (max-width: 1499px) {
            #pac-input {
                width: 100%;
                margin-left: 0;
            }
        }

        /*select tags start css*/
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e3e3e3;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #e3e3e3;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            font-size: 23px;
        }
        .select2-selection__choice__display {
            font-size: 15px;
            color: #000;
            font-weight: 400;
        }
        /*select tags end css*/

        /* price and number css start   */
        label.infoTitle.position-absolute {
            top: 0;
            background-color: whitesmoke;
            left: 0;
            padding: 10px 15px;
        }
        .checkBox {
            margin-top: 10px;
            border: 1px solid whitesmoke;
            border-radius: 8px;
            padding: 10px 15px;
            display: inline-block;
        }
        input#price, input#phone {
            padding: 5px 0 5px 76px;
        }
        input.effectBorder.checkBox__input {
            border: 2px solid #a3a3a3;
        }
        /* price and number css end   */

        .condition {
            padding: 13px;
            border: 2px solid #e9e9e9;
            border-radius: 6px;
        }

        .radio input {
            height: 20px;
            width: 20px;
        }
        .form__input__single {
            flex: 1;
        }

        .flex_0 {
            flex-shrink: 0;
        }


    /*  new css start  */
        .single-dashboard-input {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
            align-items: center;
        }
        .single-info-input {
            flex: 1;
        }
        .btn-wrapper.margin-top-20 {
            text-align: end;
        }
    /*  new css end  */

    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="header-wrap d-flex justify-content-between mb-4">
                    <div class="left-content">
                        <h4 class="header-title">{{__('Edit Service')}}   </h4>
                    </div>
                    <div class="right-content">
                        <a class="cmnBtn btn_5 btn_bg_info radius-5" href="{{route('admin.all.services')}}">{{__('All Services')}}</a>
                    </div>
                </div>
                <x-validation.error/>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="add-listing-wrapper mt-4">
                                <!--Nav Bar Tabs markup start -->
                                <div class="nav nav-pills" id="add-listing-tab"
                                     role="tablist" aria-orientation="vertical">
                                    <a class="nav-link  stepIndicator active stepForm_btn__previous new_stepForm_list__item"
                                       id="listing-info-tab"
                                       data-bs-toggle="pill"
                                       href="#listing-info"
                                       role="tab"
                                       aria-controls="listing-info"
                                       aria-selected="true">
                                        <span class="new_stepForm_list__item__numb"><b class="numb">1</b></span>
                                        {{__('Service Details')}}
                                    </a>
                                    <a class="nav-link  stepIndicator new_stepForm_list__item" id="location-tab"
                                       data-bs-toggle="pill"
                                       href="#location" role="tab"
                                       aria-controls="location"
                                       aria-selected="false">
                                        <span class="new_stepForm_list__item__numb"><b class="numb">2</b></span>
                                        {{__('Service Attributes')}}
                                    </a>
                                </div>
                                <form action="{{route('admin.edit.service', $service->id)}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div  class="add-listing-content-wrapper mt-4">
                                        <div class="tab-content add-listing-content" id="add-listing-tabContent">
                                            <!-- service Info start-->
                                           @include('backend.pages.services.admin.edit-service-details')
                                            <!-- service Info end-->
                                            <!-- Location start-->
                                            @include('backend.pages.services.admin.edit-service-include')
                                            <!-- Location end-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
    <x-media.js />
    @if(!empty(get_static_option('google_map_settings_on_off')))
        <x-map.google-map-api-key-set/>
        <x-map.google-map-listing-js/>
    @endif
    <script src="{{asset('assets/frontend/js/multi-step.js')}}"></script>
    @include('backend.pages.services.admin.service-add-more-option-js')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                const chooseStaffCheckbox = document.getElementById('choose_staff');
                const chooseStaffOption = document.getElementById('choose_staff_option');
                const chooseStaffSelected = document.getElementById('any_staff1');
                const chooseStaffAll = document.getElementById('any_staff2');
                const select_staff = document.getElementById('select_staff');
                if (chooseStaffCheckbox.checked)
                {
                    chooseStaffOption.classList.remove('d-none');
                }
                else{
                    chooseStaffOption.classList.add('d-none');
                    select_staff.classList.add('d-none');
                }

                chooseStaffCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        chooseStaffOption.classList.remove('d-none');
                        if(chooseStaffSelected.checked)
                        {
                           select_staff.classList.remove('d-none');
                        }
                        else{
                            select_staff.classList.add('d-none');
                        }
                    } else {
                        chooseStaffOption.classList.add('d-none');
                        select_staff.classList.add('d-none');
                    }
                });

                
                
                if(chooseStaffSelected.checked) {
                    select_staff.classList.remove('d-none');
                }  
                else
                {
                    select_staff.classList.add('d-none');
                    
                }
                
                chooseStaffSelected.addEventListener('change', function () {
                    if (this.checked) {
                        select_staff.classList.remove('d-none');
                    } else {
                        select_staff.classList.add('d-none');
                       
                    }
                });
                chooseStaffAll.addEventListener('change', function () {
                    if (this.checked) {
                        select_staff.classList.add('d-none');
                        
                    } 
                    else
                    {
                        select_staff.classList.remove('d-none');
                    }
                });
                    

                $('#mySelect').select2({
                    allowClear: true 
                });

                // is featured
                $(document).on('click', '.is_featured', function () {
                    $('#is_featured').val($('#is_featured').is(':checked') ? '1' : '');
                });


                //Permalink Code
                let slug = $('.listing_slug').val();
                let url = "{{url('/service/')}}/" + slug;
                let data = $('#slug_show').text(url).css('color', '#3c3cf7');


                function converToSlug(slug){
                    let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                    //remove multiple space to single
                    finalSlug = slug.replace(/  +/g, ' ');
                    // remove all white spaces single or multiple spaces
                    finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                    return finalSlug;
                }

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.listing_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    var update_input = $('.listing_slug').val();
                    var slug = converToSlug(update_input);
                    var url = `{{url('/service/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.listing_slug').hide();
                });

                $(document).on('change','#category', function() {
                    let category_id = $(this).val();
                    $.ajax({
                        method:'post',
                        url:"{{route('get.subcategory')}}",
                        data:{category_id:category_id},
                        success:function(res){
                            if(res.status=='success'){
                                let alloptions = "<option value=''>{{__('Select Sub Category')}}</option>";
                                let allSubCategory = res.sub_categories;
                                $.each(allSubCategory,function(index,value){
                                    alloptions +="<option value='" + value.id + "'>" + value.name + "</option>";
                                });
                                $(".subcategory").html(alloptions);
                                $('#subcategory').niceSelect('update');
                            }
                        }
                    })
                });

                //  sub category and child category
                $(document).on('change','#subcategory', function() {
                    var sub_cat_id = $(this).val();
                    $.ajax({
                        method: 'post',
                        url: "{{ route('get.subcategory.with.child.category') }}",
                        data: {
                            sub_cat_id: sub_cat_id
                        },
                        success: function(res) {

                            if (res.status == 'success') {
                                var alloptions = "<option value=''>{{__('Select Child Category')}}</option>";
                                var allList = "<li data-value='' class='option'>{{__('Select Child Category')}}</li>";
                                var allChildCategory = res.child_category;

                                $.each(allChildCategory, function(index, value) {
                                    alloptions += "<option value='" + value.id +
                                        "'>" + value.name + "</option>";
                                    allList += "<li class='option' data-value='" + value.id +
                                        "'>" + value.name + "</li>";
                                });

                                $("#child_category").html(alloptions);
                                $(".child_category_wrapper ul.list").html(allList);
                                $(".child_category_wrapper").find(".current").html("Select Child Category");
                            }
                        }
                    });
                });

                // change country and get state
                $(document).on('change','#country_id', function() {
                    let country = $(this).val();
                    $.ajax({
                        method: 'post',
                        url: "{{ route('au.state.all') }}",
                        data: {
                            country: country
                        },
                        success: function(res) {
                            if (res.status == 'success') {
                                let all_options = "<option value=''>{{__('Select State')}}</option>";
                                let all_state = res.states;
                                $.each(all_state, function(index, value) {
                                    all_options += "<option value='" + value.id +
                                        "'>" + value.state + "</option>";
                                });
                                $(".get_country_state").html(all_options);
                                $(".state_info").html('');
                                if(all_state.length <= 0){
                                    $(".state_info").html('<span class="text-danger"> {{ __('No state found for selected country!') }} <span>');
                                }
                            }
                        }
                    })
                })

                // change state and get city
                $(document).on('change','#state_id', function() {
                    let state = $(this).val();
                    $.ajax({
                        method: 'post',
                        url: "{{ route('au.city.all') }}",
                        data: {
                            state: state
                        },
                        success: function(res) {
                            if (res.status == 'success') {
                                let all_options = "<option value=''>{{__('Select City')}}</option>";
                                let all_city = res.cities;
                                $.each(all_city, function(index, value) {
                                    all_options += "<option value='" + value.id +
                                        "'>" + value.city + "</option>";
                                });
                                $(".get_state_city").html(all_options);

                                $(".city_info").html('');
                                if(all_city.length <= 0){
                                    $(".city_info").html('<span class="text-danger"> {{ __('No city found for selected state!') }} <span>');
                                }
                            }
                        }
                    })
                });

            });
        })(jQuery)
    </script>
@endsection
