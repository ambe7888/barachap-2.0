@extends('backend.admin-master')
@section('site-title')
    {{__('Service Details')}}
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
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="header-wrap d-flex justify-content-between">
                    <div class="left-content">
                        <h4 class="header-title">{{__('Offer Details')}}   </h4>
                    </div>
                    <div class="right-content">
                        <a class="cmnBtn btn_5 btn_bg_info radius-5" href="{{route('admin.offer.all')}}">{{__('All Offers')}}</a>
                    </div>
                </div>
                <x-validation.error/>
               @include('backend.pages.admin.offer.offer-details-basic-info')

            </div>
        </div>
    </div>


    <div class="row g-4 mt-1">
        <div class="col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__card__header">
                    <div class="dashboard__card__header__flex">
                        <div class="dashboard__card__header__left">
                            <h5 class="dashboard__card__header__title">{{ __('Offer Service Details') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="task_assignments__table custom_table mt-4">
                    <table>
                        <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Service Name') }}</th>
                            <th>{{ __('Original Price') }}</th>
                            <th>{{ __('Discount Price') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            
                        @if($offer->offerService && $offer->offerService->isNotEmpty())
                        @foreach($offer->offerService as $data)
                            <tr class="table_row">
                                <td>{{ $data->id }}</td>
                                <td>
                                    <div class="table_customer">
                                        <div class="table_customer__flex">
                                            <div class="table_customer__contents">
                                                <h6 class="table_customer__title">{{ $data->service?->title}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table_customer">
                                        <div class="table_customer__flex">
                                            <div class="table_customer__contents">
                                                <p class="table_customer__para mt-1">{{ $data->service?->price}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table_customer">
                                        <div class="table_customer__flex">
                                            <div class="table_customer__contents">
                                                <p class="table_customer__para mt-1">{{ $data->discount_price}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @else
                        <tr><td colspan="2">No services available for this offer.</td></tr>
                        @endif
                        </tbody>
                    </table>
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
