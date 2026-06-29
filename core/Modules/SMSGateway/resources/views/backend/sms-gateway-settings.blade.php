@extends('backend.admin-master')
@section('site-title')
    {{__('SMS Gateway Settings')}}
@endsection
@section('style')
    <style>
        .plugin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }

        .plugin-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
            text-align: center;
            display: flex;
            flex-direction: column;
        }
        
        .plugin-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: rgba(203, 213, 225, 1);
        }

        .plugin-card .thumb-bg-color {
            background: #5433FF;
            padding: 32px 20px;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .plugin-card .thumb-bg-color::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            z-index: -1;
        }

        .plugin-card .thumb-bg-color.nexmo {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .plugin-card .thumb-bg-color.twilio {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .plugin-card .thumb-bg-color.msg91 {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .plugin-card .thumb-bg-color.whatsapp {
            background: linear-gradient(135deg, #25D366, #128C7E);
        }

        .plugin-card .thumb-bg-color strong {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .plugin-meta {
            font-size: 0.875rem;
            color: #475569;
            padding: 24px 20px;
            flex-grow: 1;
        }

        .plugin-meta a {
            color: #2563eb;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .plugin-meta a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .plugin-card .btn-group-wrap {
            padding: 0 20px 24px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .plugin-card .btn-group-wrap a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            background-color: #f1f5f9;
            border-radius: 8px;
            color: #334155;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }

        .plugin-card .btn-group-wrap a.pl_active_deactive {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .plugin-card .btn-group-wrap a.pl_active_deactive.bg-success {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
            border-color: #10b981 !important;
        }

        .plugin-card .btn-group-wrap a.pl_settings {
            background-color: #ffffff;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .plugin-card .btn-group-wrap a:hover {
            opacity: 1;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .plugin-card .btn-group-wrap a.pl_settings:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
        }
        @media (max-width: 500px) {
            .plugin-grid {
                grid-template-columns: 1fr;
            }
        }
        .iti{
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <h2 class="dashboard__card__header__title mb-3">{{__('SMS Gateway Settings')}}</h2>
                <x-validation.error/>

                <div class="row">
                    <div class="col-md-12">
                        <div class="p-4 recent-order-wrapper dashboard-table bg-white padding-30">
                            <div class="wrapper d-flex justify-content-between">
                                <div class="header-wrap">
                                    <h4 class="header-title mb-2">{{__("SMS Gateway Settings")}}</h4>
                                    <p>{{__("Manage all sms gateway from here, you can active/deactivate any sms gateway from here.")}}</p>
                                </div>
                                <div class="settings-options justify-content-end">
                            <span data-bs-toggle="modal" data-bs-target="#settings_option_modal">
                                <a href="#" data-bs-toggle="tooltip"  data-bs-placement="top" title="{{__('Configure when SMS will be send')}}" class="modal-btn btn btn-info btn-small settings-option-modal">
                                    {{__('SMS Settings')}}
                                </a>
                            </span>
                                    <span data-bs-target="#test_sms_modal" data-bs-toggle="modal">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Send a test SMS')}}" class="modal-btn btn btn-success btn-small">
                                    {{__('Test SMS')}}
                                </a>
                            </span>
                                </div>
                            </div>

                            <x-fields.switcher label="Enable or disable OTP" name="otp_login_status" value="{{get_static_option('otp_login_status')}}"/>

                            <div class="my-5 plugin-grid" @style(['display: none' => empty(get_static_option('otp_login_status'))])>
                                @foreach(\Modules\SMSGateway\app\Http\Services\OtpTraitService::gateways() as $key => $item)
                                    @php
                                        $sms_gateway = \Modules\SMSGateway\app\Models\SmsGateway::where('name', $key)->first();
                                        $status = $sms_gateway->status ?? 0;
                                        $otp_time = $sms_gateway->otp_expire_time ?? 0;
                                        $credentials = $sms_gateway->credentials ?? '{}';
                                    @endphp

                                    <div class="plugin-card">
                                        <div class="thumb-bg-color {{$key}} google_analytics">
                                            <strong class="google_analytics text-capitalize">{{$item}}</strong>
                                        </div>
                                        <p class="plugin-meta">
                                            {{__("You can learn more about it from here,")}}
                                            @if($key === 'twilio')
                                                <a href="https://www.twilio.com/" target="_blank">{{__('Link')}}</a>
                                            @else
                                                <a href="https://www.msg91.com/" target="_blank">{{__('Link')}}</a>
                                            @endif
                                        </p>
                                        <div class="btn-group-wrap">
                                            <a href="#"
                                               data-option="{{$key}}"
                                               data-status="{{$status}}"
                                               class="pl-btn pl_active_deactive {{$status ? 'bg-success' : ''}}">{{$status ? __('Activated') : __('Deactivated')}}</a>

                                            <a href="#" data-bs-target="#{{$key}}_modal" data-bs-toggle="modal"
                                               data-option="{{$key}}"
                                               data-otp-time="{{$otp_time}}"
                                               data-credentials="{{$credentials}}"
                                               class="pl-btn pl_delete pl_settings">{{__("Settings") }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('smsgateway::backend.modal.nexmo_modal')
    @include('smsgateway::backend.modal.twilio_modal')
    @include('smsgateway::backend.modal.msg91_modal')
    @include('smsgateway::backend.modal.whatsapp_modal')

    <!-- sms settings -->
    <div class="modal fade" id="settings_option_modal">
        <div class="modal-dialog">
            <div class="popup_contents modal-content">
                <div class="popup_contents__header">
                    <div class="popup_contents__header__flex">
                        <div class="popup_contents__header__contents">
                            <h2 class="popup_contents__header__title">{{ __('SMS Settings') }}</h2>
                        </div>
                        <div class="popup_contents__header__close" data-bs-dismiss="modal">
                            <span class="popup_contents__close popup_close"> <i class="fas fa-times"></i> </span>
                        </div>
                    </div>
                </div>
                <form action="{{route("admin.sms.options")}}" method="post" class="edit_language_form">
                    @csrf
                    <h5 class="title-para mx-4">{{ __('Receive sms when the actions are triggered') }}</h5>
                    <div class="popup_contents__body">

                        <x-fields.switcher label="When new user is registered - for user" name="new_user_user" value="{{get_static_option('new_user_user')}}"/>
                        <x-fields.switcher label="When new user is registered - for admin" name="new_user_admin" value="{{get_static_option('new_user_admin')}}"/>

                        <div class="form__input__single mt-3">
                            <label for="direction" class="form__input__single__label">{{ __('Set a receiving phone number') }}</label>
                            <input type="tel"  class="form-control" name="receiving_phone_number" value="{{get_static_option('receiving_phone_number')}}" placeholder="{{ __('phone number')}}" id="set-telephone">
                        </div>

                    </div>
                    <div class="popup_contents__footer flex_btn justify-content-end profile-border-top">
                        <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal">{{__('Cancel')}}</a>
                        <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Update Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Test sms send-->
    <div class="modal fade" id="test_sms_modal">
        <div class="modal-dialog">
            <div class="popup_contents modal-content">
                <div class="popup_contents__header">
                    <div class="popup_contents__header__flex">
                        <div class="popup_contents__header__contents">
                            <h2 class="popup_contents__header__title">{{ __('Send Test SMS') }}</h2>
                        </div>
                        <div class="popup_contents__header__close" data-bs-dismiss="modal">
                            <span class="popup_contents__close popup_close"> <i class="fas fa-times"></i> </span>
                        </div>
                    </div>
                </div>
                <form action="{{route("admin.sms.test")}}" method="post" class="edit_language_form">
                    @csrf
                    <div class="popup_contents__body">
                        <div class="form__input__single">
                            <label for="TWILIO_AUTH_TOKEN"><strong>{{__('Phone number')}} <span class="text-danger">*</span></strong></label>
                            <input type="tel"  class="form-control" name="test_phone_number" value=""  placeholder="{{ __('Send test sms')}}" id="telephone">
                        </div>
                    </div>
                    <div class="popup_contents__footer flex_btn justify-content-end profile-border-top">
                        <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal">{{__('Cancel')}}</a>
                        <button type="submit" id="test-sms-btn" class="cmnBtn btn_5 btn_bg_blue radius-5" disabled>{{ __('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        (function ($) {
            "use strict";

            $(document).on('change', 'input[name=otp_login_status]', function (e) {
                Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("You will able revert your decision anytime")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes!')}}",
                    cancelButtonText: "{{__('Cancel')}}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get("{{route("admin.sms.login.otp.status")}}")
                            .then((response) => {
                                if (response.data.type === 'success') {
                                    toastr.success(`{{__('Settings updated')}}`);
                                    let plugin_grid = $('.plugin-grid');
                                    plugin_grid.toggle();
                                }
                            });
                    } else {
                        location.reload();
                    }
                });
            });

            $(document).on('click', '.pl_settings', function (e) {
                e.preventDefault();

                let el = $(this);
                let option = el.attr('data-option');
                let otp_expire_time = el.attr('data-otp-time');
                let credentials = el.attr('data-credentials');
                credentials = jQuery.parseJSON(credentials);

                let modal = $(`#${option}_modal`);
                for (let item in credentials)
                {
                    modal.find(`input[name=${item}]`).val(credentials[item]);
                }
                modal.find(`select[name=user_otp_expire_time] option[value=${otp_expire_time}]`).attr('selected', true)
            });

            $(document).on("click", '.pl_active_deactive', function (e) {
                e.preventDefault();
                var el = $(this);
                Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("you will able revert your decision anytime")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes!')}}",
                    cancelButtonText: "{{__('Cancel')}}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        //ajax call
                        let optionName = el.data('option');
                        let status = el.data('status');

                        axios.post("{{route("admin.sms.status")}}", {
                            option_name: optionName,
                            status: status
                        })
                            .then((response) => {
                                if (response.data.type === 'success') {
                                    location.reload();
                                }
                            });
                    }
                });
            })

        })(jQuery);
    </script>

    <x-custom-js.phone-number-config selector="#telephone" submit-button-id="test-sms-btn" key="1"/>
    <x-custom-js.phone-number-config selector="#set-telephone" submit-button-id="test-sms-btn" key="2"/>

    <script>
        $(document).ready(function () {
            setTimeout(() => {
                $('#set-telephone').val(`{{get_static_option('receiving_phone_number')}}`);
            }, 1000);
        });
    </script>

@endsection
