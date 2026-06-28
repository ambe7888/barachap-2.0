@extends('frontend.frontend.client.client-master')
@section('site-title')
    {{__('Job Details')}}
@endsection
@section('style')
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

        .customer__job__details__item__flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
    <div class="row g-4 w-100 mt-0">
        <div class="col-xl-12 col-lg-12 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="header-wrap d-flex justify-content-between">
                    <div class="left-content">
                        <h4 class="header-title">{{__('Job Details')}}   </h4>
                    </div>
                    <div class="right-content d-flex">
                        <div class="ms-2">
                            <a class="cmnBtn btn_5 btn_bg_info radius-5" href="{{route('client.offer-job.all',$job->id)}}">{{__('All Job Offers')}}</a>
                         </div>
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

                <div class="product__details__single mt-5 p-4">
                    <div class="editProduct">
                        <div class="row g-4">
                            <div class="card" style="width: 100%;background-color: #f9f9f9">
                                <div class="card-body">
                                    <h5 class="card-title d-flex justify-content-between align-items-center">
                                        <span>Job Offer Details</span>
                                        @if($job_post_offer->status == 0)
                                            <div>
                                                <a href="#/"
                                                   class="btn btn-primary"
                                                   data-job_offer_id="{{ $job_post_offer->id }}" data-bs-toggle="modal"
                                                   data-bs-target="#paymentGatewayModal">{{ __('Hire') }}
                                                </a>
                                                <form method="POST" action="{{route('client.offer-job.reject')}}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="job_offer_id" value="{{$job_post_offer->id}}">
                                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                </form>
                                            </div>
                                        @endif
                                    </h5>

                                <div class="d-flex">
                                    <div class="me-3 mt-2">
                                        <h6 class="card-subtitle  mt-2 mb-2 text-body-secondary">{{ float_amount_with_currency_symbol($job_post_offer->budget) }}</h6>
                                    </div>
                                    <div class="mt-2">
                                        @if($job_post_offer->status==1)
                                            <span class="status_btn completed">{{__('Approved')}}</span>
                                        @elseif($job_post_offer->status==0)
                                            <span class="status_btn cancelled">{{__('Pending')}}</span>
                                        @elseif($job_post_offer->status==2)
                                            <span class="status_btn cancelled">{{__('Rejected')}}</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="customer__details__author__item__inner border_top_1 top_15">
                                    <div class="customer__account__details">
                                            <!-- User Info -->
                                            <div class="customer__account__details__item">
                                                <div class="customer__account__details__item__flex">
                                                    <div class="customer__details__author__thumb">
                                                        <div class="seller-img">
                                                           {!! render_image_markup_by_attachment_id($job_post_offer->provider?->image, '', 'thumb') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="customer__details__author__item__header mt-3">
                                                <div class="customer__details__author__item__header__flex mb-2">
                                                    <div class="customer__details__author__item__header__left">
                                                        <h6 class="customer__details__author__item__title">
                                                         {{ __('Provider Info:') }}
                                                        </h6>
                                                    </div>
                                                </div>
                                                <div class="customer__account__details__item">
                                                    <div class="customer__account__details__item__flex">
                                                        <strong>{{ __('Name') }}</strong>
                                                        <span>{{ optional($job_post_offer->provider)->fullname }}</span>
                                                    </div>
                                                </div>
                                                <div class="customer__account__details__item">
                                                    <div class="customer__account__details__item__flex">
                                                        <strong>{{ __('Email') }}</strong>
                                                        <span>{{ optional($job_post_offer->provider)->email }}</span>
                                                    </div>
                                                </div>
                                                <div class="customer__account__details__item">
                                                    <div class="customer__account__details__item__flex">
                                                        <strong>{{ __('Phone') }}</strong>
                                                        <span>{{ optional($job_post_offer->provider)->phone }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>
                                <h6 class="card-title mt-3 mb-2">Cover Letter</h6>
                                <p class="card-text">{{$job_post_offer->cover_letter}}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('jobpost::frontend.client.job-offer.payment_gateway.gateway-markup')
@endsection
@section('scripts')
    @include('jobpost::frontend.client.job-offer.payment_gateway.payment-gateway-js');
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


                {{--$(document).on('click','.job_offer_hire_warning',function(){--}}
                {{--    toastr_warning_js("{{__('You can hire provider only using app.')}}");--}}
                {{--    return false;--}}
                {{--});--}}
            });
        })(jQuery)
    </script>
@endsection
