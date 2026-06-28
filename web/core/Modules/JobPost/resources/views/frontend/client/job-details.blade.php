@extends('frontend.frontend.client.client-master')
@section('site-title')
    {{__('Job Details')}}
@endsection
@section('style')
    <x-media.css/>
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
    <div class="row w-100 g-4 mt-0">
        <div class="col-xl-12 col-lg-12 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="header-wrap d-flex justify-content-between">
                    <div class="left-content">
                        <h4 class="header-title">{{__('Job Details')}}   </h4>
                    </div>
                    <div class="right-content">
                        <a class="cmnBtn btn_5 btn_bg_info radius-5" href="{{route('client.all.jobs')}}">{{__('All Jobs')}}</a>
                    </div>
                </div>
                <x-validation.error/>
                <div class="product__details__single">
                    <div class="editProduct">
                        <div class="row g-4">
                            <div class="col-xxl-3 col-lg-4">
                                <div class="editProduct__contents__category mt-3">
                                    <strong class="editProduct__contents__sku__para">{{ __('Gallery Images:') }}</strong>
                                </div>
                                <div class="dashboard__rates__card__thumb">
                                    {!! render_gallery_image_attachment_preview($job->gallery_images ?? '') !!}
                                </div>

                            </div>

                            <!--step two -->
                            <div class="col-xxl-4 col-lg-4">
                                <div class="editProduct__contents">
                                    <div class="editProduct__contents__category mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Job Title:') }}</strong> {{ $job->title }}</span>
                                    </div>
                                    <div class="editProduct__contents__category mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Budget:') }}</strong> {{ float_amount_with_currency_symbol($job->budget) }}</span>
                                    </div>
                                    <div class="editProduct__contents__category mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Category:') }}</strong> {{ optional($job->category)->name }}</span>
                                    </div>
                                    <div class="editProduct__contents__category mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Sub Category:') }}</strong> {{ optional($job->subcategory)->name }}</span>
                                    </div>
                                    <div class="editProduct__contents__category mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Child Category:') }}</strong> {{ optional($job->childcategory)->name }}</span>
                                    </div>
                                    <div class="editProduct__contents__brand mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('View Count:') }}</strong> {{ $job->view }}</span>
                                    </div>
                                    <div class="editProduct__contents__brand mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Status:') }}</strong>
                                            @if($job->status==1)
                                                <span class="status_btn completed">{{__('Approved')}}</span>
                                            @else
                                                <span class="status_btn cancelled">{{__('Pending')}}</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="editProduct__contents__brand mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('State:') }}</strong> {{ $job->job_location?->state?->state }}</span>
                                    </div>
                                    <div class="editProduct__contents__brand mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('City:') }}</strong> {{ $job->job_location?->city?->city }}</span>
                                    </div>
                                    <div class="editProduct__contents__brand mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Area:') }}</strong> {{ $job->job_location?->area?->area }}</span>
                                    </div>
                                    <div class="editProduct__contents__brand mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Address:') }}</strong> {{ $job->job_location?->address }}</span>
                                    </div>
                                    <div class="editProduct__contents__brand mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Phone:') }}</strong> {{ $job->job_location?->phone }}</span>
                                    </div>
                                    <div class="editProduct__contents__brand mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Post Code:') }}</strong> {{ $job->job_location?->post_code }}</span>
                                    </div>
                                </div>
                            </div>
                            <!--step two -->
                            <div class="col-xxl-4 col-lg-4">
                                <div class="editProduct__contents">
                                    <div class="product__details__description mt-3">
                                        <span class="editProduct__contents__sku__para"><strong>{{ __('Description:') }}</strong></span>
                                        <p class="product__details__para">{!! $job->description !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function ($) {
            "use strict";

            $(document).ready(function () {
                //zone
                $(document).ready(function () {
                    $('.zone_settings').select2();
                });

                $(document).on('click','#checkbox',function(e){
                    e.preventDefault();
                });

                $(document).on('keydown','#checkbox',function(e){
                    if (e.which === 32) {
                        e.preventDefault();
                    }
                });
            });
        })(jQuery)
    </script>
@endsection
