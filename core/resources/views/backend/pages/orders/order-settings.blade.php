@extends('backend.admin-master')
@section('site-title')
    {{__('Basic Settings')}}
@endsection
@section('style')
    <x-media.css/>
    <style>
        #order_invoice_notes{
            min-height: 100px !important;
            line-height:1.5 !important;
        }
    </style>
@endsection
@section('content')
<div class="row g-4 mt-0">
    <div class="col-xl-6 col-lg-6 mt-0">
        <div class="dashboard__card bg__white padding-20 radius-10">
            <h2 class="dashboard__card__header__title mb-3">{{__('Order Settings')}}</h2>
            <x-validation.error/>
            <form action="{{route('admin.order.settings')}}" method="POST" class="validateForm" enctype="multipart/form-data">
                @csrf
                <div class="col-6 mt-5">
                     <h6 class="text-info mb-2">{{ __('You can change order invoice page text from here.') }}</h6>
                    <div class="form-group mt-2">
                        <label for="bill_to_title">{{__('Bill To Title')}}</label>
                        <input type="text" name="bill_to_title"  class="form-control" value="{{get_static_option('bill_to_title')}}" id="bill_to_title">
                    </div>
                    <div class="form-group mt-2">
                        <label for="ship_to_title">{{__('Ship To Title')}}</label>
                        <input type="text" name="ship_to_title"  class="form-control" value="{{get_static_option('ship_to_title')}}" id="ship_to_title">
                    </div>
                    <div class="form-group mt-2">
                        <label for="invoice_title">{{__('INVOICE Title')}}</label>
                        <input type="text" name="invoice_title"  class="form-control" value="{{get_static_option('invoice_title')}}" id="invoice_title">
                    </div>
                    <div class="form-group mt-2">
                        <label for="invoice_no_title">{{__('Invoice No Title')}}</label>
                        <input type="text" name="invoice_no_title"  class="form-control" value="{{get_static_option('invoice_no_title')}}" id="invoice_no_title">
                    </div>
                    <div class="form-group mt-2">
                        <label for="order_invoice_notes">{{__('Invoice Notes for Admin')}}</label>
                        <textarea name="order_invoice_notes" id="order_invoice_notes" class="form-control">{{ get_static_option('order_invoice_notes') }}</textarea>
                    </div>
                </div>
                <div class="btn_wrapper mt-4">
                    <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Update Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<x-media.markup/>
@endsection
@section('scripts')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                <x-btn.update/>
                $(document).on("change","#user_email_verify_enable_disable",function (){
                    let current_value = $("#user_email_verify_enable_disable").is(':checked');
                    if(current_value == true){
                        $("#user_otp_verify_enable_disable").prop("checked", false)
                    }
                    if (!$(this).is(':checked')) {
                        $(".otp_time_settings_show_hide").hide();
                    }
                    if ($(this).is(':checked')) {
                        $(".otp_time_settings_show_hide").hide();
                    }
                });
                $(document).on("change","#user_otp_verify_enable_disable",function (){
                    let current_value = $("#user_otp_verify_enable_disable").is(':checked');
                    if(current_value == true){
                        $("#user_email_verify_enable_disable").prop("checked", false)
                    }
                    if ($(this).is(':checked')) {
                        $(".otp_time_settings_show_hide").show();
                    } else {
                        $(".otp_time_settings_show_hide").hide();
                    }
                });
            });
        }(jQuery));
    </script>
@endsection
