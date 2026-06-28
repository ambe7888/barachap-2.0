@extends('frontend.frontend.client.client-master')
@section('site-title')
    {{__('Favourite Service Details')}}
@endsection
@section('style')
    <x-media.css/>
    <x-summernote.css/>
    <style>
        span {
            display: inline;
        }
        .dashboard__rates__card__thumb {
            gap: 6px;
            margin: 5px;
            padding: 7px;
            display: flex;
            flex-wrap: wrap;
        }

        .effectBorder {
            pointer-events: none; /* Disable interactions */
            cursor: not-allowed; /* Indicate non-interactivity */
        }
        .customer__account__details__item__flex {
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: flex-start;
        }

        .seller-img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            overflow: hidden;
            border: 1px solid #ddd;
            position: relative;
        }

        .seller-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

    </style>
@endsection
@section('content')
    <div class="ms-5">
        <div class="row g-4 mt-0">
            <div class="col-xl-12 col-lg-12 mt-0">
                <div class="dashboard__card bg__white padding-20 radius-10">
                    <div class="header-wrap d-flex justify-content-between">
                        <div class="left-content">
                            <h4 class="header-title">{{__('Favourite Service Details')}}   </h4>
                        </div>
                        <div class="right-content">
                            <a class="cmnBtn btn_5 btn_bg_info radius-5" href="{{route('client.favourite.services.all')}}">{{__('All Favourite Services')}}</a>
                        </div>
                    </div>
                    <x-validation.error/>
                @include('frontend.frontend.client.pages.favourite-services.favourite-service-details-basic-info')

                </div>
            </div>
        </div>
        @if($service->disable_staff==1)
            <div class="row g-4 mt-1">
                <div class="col-lg-12">
                    <div class="dashboard__card bg__white padding-20 radius-10">
                        <div class="dashboard__card__header">
                            <div class="dashboard__card__header__flex">
                                <div class="dashboard__card__header__left">
                                    <h5 class="dashboard__card__header__title">{{ __('Service Staffs') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="task_assignments__table custom_table mt-4">
                            <table>
                                <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('First Name') }}</th>
                                    <th>{{ __('Second Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($staffs as $staff)
                                @if($staff)
                                        <tr class="table_row">
                                            <td>{{ $staff->id }}</td>
                                            <td>
                                                <div class="table_customer">
                                                    <div class="table_customer__flex">
                                                        <div class="table_customer__contents">
                                                            <h6 class="table_customer__title">{{$staff->first_name}}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="table_customer">
                                                    <div class="table_customer__flex">
                                                        <div class="table_customer__contents">
                                                            <p class="table_customer__para mt-1">{{ $staff->last_name }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="table_customer">
                                                    <div class="table_customer__flex">
                                                        <div class="table_customer__contents">
                                                            <p class="table_customer__para mt-1">{{ $staff->email }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif    


        <div class="row g-4 mt-1">
            <div class="col-lg-12">
                <div class="dashboard__card bg__white padding-20 radius-10">
                    <div class="dashboard__card__header">
                        <div class="dashboard__card__header__flex">
                            <div class="dashboard__card__header__left">
                                <h5 class="dashboard__card__header__title">{{ __('Includes Service') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="task_assignments__table custom_table mt-4">
                        <table>
                            <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('description') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($service->includes as $include)
                                <tr class="table_row">
                                    <td>{{ $include->id }}</td>
                                    <td>
                                        <div class="table_customer">
                                            <div class="table_customer__flex">
                                                <div class="table_customer__contents">
                                                    <h6 class="table_customer__title">{{ $include->title }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table_customer">
                                            <div class="table_customer__flex">
                                                <div class="table_customer__contents">
                                                    <p class="table_customer__para mt-1">{{ $include->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-12">
                <div class="dashboard__card bg__white padding-20 radius-10">
                    <div class="dashboard__card__header">
                        <div class="dashboard__card__header__flex">
                            <div class="dashboard__card__header__left">
                                <h5 class="dashboard__card__header__title">{{ __('Excludes Service') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="task_assignments__table custom_table mt-4">
                        <table>
                            <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('description') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($service->excludes as $exclude)
                                <tr class="table_row">
                                    <td>{{ $exclude->id }}</td>
                                    <td>
                                        <div class="table_customer">
                                            <div class="table_customer__flex">
                                                <div class="table_customer__contents">
                                                    <h6 class="table_customer__title">{{ $exclude->title }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table_customer">
                                            <div class="table_customer__flex">
                                                <div class="table_customer__contents">
                                                    <p class="table_customer__para mt-1">{{ $exclude->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-12">
                <div class="dashboard__card bg__white padding-20 radius-10">
                    <div class="dashboard__card__header">
                        <div class="dashboard__card__header__flex">
                            <div class="dashboard__card__header__left">
                                <h5 class="dashboard__card__header__title">{{ __('Addons Service') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="task_assignments__table custom_table mt-4">
                        <table>
                            <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Description') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($service->addons as $addon)
                                <tr class="table_row">
                                    <td>{{ $addon->id }}</td>
                                    <td>
                                        <div class="table_customer">
                                            <div class="table_customer__flex">
                                                <div class="table_customer__contents">
                                                    <h6 class="table_customer__title">{{ $addon->title }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table_customer">
                                            <div class="table_customer__flex">
                                                <div class="table_customer__contents">
                                                    <p class="table_customer__para mt-1">{{ float_amount_with_currency_symbol($addon->price) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table_customer">
                                            <div class="table_customer__flex">
                                                <div class="table_customer__contents">
                                                    <p class="table_customer__para mt-1">{{ $addon->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-12">
                <div class="dashboard__card bg__white padding-20 radius-10">
                    <div class="dashboard__card__header">
                        <div class="dashboard__card__header__flex">
                            <div class="dashboard__card__header__left">
                                <h5 class="dashboard__card__header__title">{{ __('Faqs Service') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="task_assignments__table custom_table mt-4">
                        <table>
                            <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('description') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($service->faqs as $faq)
                                    <tr class="table_row">
                                        <td>{{ $faq->id }}</td>
                                        <td>
                                            <div class="table_customer">
                                                <div class="table_customer__flex">
                                                    <div class="table_customer__contents">
                                                        <h6 class="table_customer__title">{{ $faq->title }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="table_customer">
                                                <div class="table_customer__flex">
                                                    <div class="table_customer__contents">
                                                        <p class="table_customer__para mt-1">{{ $faq->description }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    <x-media.markup/>
@endsection
@section('scripts')
    <x-media.js />
    <script src="{{asset('assets/backend/js/fontawesome-iconpicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/backend/css/fontawesome-iconpicker.min.css')}}">
    <x-summernote.js/>
    <script>
        <x-icon.icon-picker/>
    </script>
    <script>
        (function ($) {
            "use strict";

            $(document).ready(function () {
                //zone
                $(document).ready(function () {
                    $('.zone_settings').select2();
                });

                // Optionally, prevent keyboard events (spacebar) to toggle checkbox
                $(document).on('keydown', '#checkbox', function (e) {
                    if (e.which === 32) {
                        e.preventDefault();
                    }
                });

                //Permalink Code
                var sl =  $('.category_slug').val();
                var url = `{{url('/service-list/category/')}}/` + sl;
                var data = $('#slug_show').text(url).css('color', 'blue');

                function converToSlug(slug){
                    let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                    finalSlug = slug.replace(/  +/g, ' ');
                    finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                    return finalSlug;
                }
                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.category_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    var update_input = $('.category_slug').val();
                    var slug = converToSlug(update_input);
                    var url = `{{url('/service-list/category/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.category_slug').val(slug)
                    $('.category_slug').hide();
                });

                // for summernote
                $('.summernote').summernote({
                    height: 400,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function (contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
                if ($('.summernote').length > 0) {
                    $('.summernote').each(function (index, value) {
                        $(this).summernote('code', $(this).data('content'));
                    });
                }
            });
        })(jQuery)
    </script>
@endsection
