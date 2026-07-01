@extends('backend.admin-master')
@section('site-title')
    {{__('WhatsApp Settings')}}
@endsection
@section('style')
    <style>
        .fs-26{
            font-size: 26px;
            line-height: 1.5;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="row align-items-center mb-3">
                <div class="col-12 col-md-6">
                    <h2 class="dashboard__card__header__title fs-26 mb-2 mb-md-0">{{ __('WhatsApp Settings') }}</h2>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-flex flex-wrap justify-content-md-end gap-2">
                        <a href="{{ route('admin.whatsapp.message.setting') }}" class="btn btn-primary px-4">{{ __('Set Default Messages') }}</a>
                        <a href="{{ route('admin.whatsapp.button-text.setting') }}" class="btn btn-secondary px-4">{{ __('Set Button Text') }}</a>
                        <a href="{{ route('admin.whatsapp.message.template.guide') }}" class="btn btn-success px-4">{{ __('Rules of Template Create') }}</a>
                    </div>
                </div>
            </div>
                <x-validation.error/>
                <form action="{{ route('admin.whatsapp.setting.update') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4 mt-5">
                        <h5 class="mb-2 ">{{ __('WhatsApp Verify Token') }}</h5>
                        <input type="text"
                               id="whatsapp_verify_token"
                               name="whatsapp_verify_token"
                               class="form-control mt-3"
                               value="{{ isset($isDemoMiddlewareIsEnabled) ? __('Your whatsapp verify token...') : get_whatsapp_option('whatsapp_verify_token') }}"
                               readonly>
                        <p class="text-muted mt-3"><small>{{__("-This token is required during Webhook Verification when setting up your WhatsApp Business API on Meta Developer Portal. Meta will compare this with what your app returns.")}}</small></p>
                    </div>

                    <div class="form-group mb-4 mt-5">
                        <h5 class="mb-2">{{ __('WhatsApp Phone Number ID') }}</h5>


                        <input type="text"
                               id="whatsapp_phone_number_id"
                               name="whatsapp_phone_number_id"
                               class="form-control mt-3"
                               value="{{ isset($isDemoMiddlewareIsEnabled) ? 'Your whatsapp phone number id...' : get_whatsapp_option('whatsapp_phone_number_id') }}"
                            {{ isset($isDemoMiddlewareIsEnabled) ? 'readonly' : '' }}>
                        <p class="text-muted mt-3"><small>{{__("-This is the unique identifier for the phone number connected to your WhatsApp Business Account. You can find it in your Meta Business Manager under your WhatsApp assets.")}}</small></p>
                    </div>

                    <div class="form-group mb-4 mt-5">
                        <h5 class="mb-2 ">{{ __('WhatsApp Permanent Token') }}</h5>


                        <input type="text"
                               id="whatsapp_permanent_token"
                               name="whatsapp_permanent_token"
                               class="form-control mt-3"
                               value="{{ isset($isDemoMiddlewareIsEnabled) ? 'Your whatsapp permanent token...' : get_whatsapp_option('whatsapp_permanent_token') }}"
                            {{ isset($isDemoMiddlewareIsEnabled) ? 'readonly' : '' }}>
                        <p class="text-muted mt-3 "><small>{{__("-This is a long-lived access token used to authenticate API requests from your server to WhatsApp. It replaces the short-lived tokens and is tied to your system user in Meta.")}}</small></p>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            {{ __('Update Settings') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){

            });
        }(jQuery));
    </script>
@endsection
