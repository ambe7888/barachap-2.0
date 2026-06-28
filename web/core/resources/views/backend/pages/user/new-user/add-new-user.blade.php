@extends('backend.admin-master')
@section('site-title')
 {{ __('Add New User') }}
@endsection
@section('style')
    <x-media.css />
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('Add New User') }}</h4>
                        </div>
                    </div>
                </div>
                <x-validation.error/>
                <div class="customMarkup__single__inner mt-4">
                    <form action="{{route('admin.user.add')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.text :title="__('First Name')" :type="__('text')" :name="'first_name'" :value="old('first_name', '')" :required="'yes'" :placeholder="__('Enter first name')"/>
                            </div>
                            <div class="col-lg-6">
                                <x-form.text :title="__('Last Name')" :type="__('text')" :name="'last_name'" :value="old('last_name', '')"  :required="'yes'" :placeholder="__('Enter last name')"/>
                            </div>
                            <div class="col-lg-6">
                                <x-form.text :title="__('Username')" :type="__('text')" :name="'username'" :value="old('username', '')" :required="'yes'"  :placeholder="__('Enter username')"/>
                            </div>
                            <div class="col-lg-6">
                                <x-form.text :title="__('Email Address')" :type="__('email')" :name="'email'" :value="old('email', '')"  :required="'yes'" :placeholder="__('Enter email')"/>
                            </div>
                            <div class="col-lg-6 mt-3">
                                <div class="form__input__single">
                                    <label for="TWILIO_AUTH_TOKEN"><strong>{{__('Phone number')}} <span class="text-danger">*</span></strong></label>
                                    <input type="tel"  id="telephone" class="form-control" name="phone" value=""  placeholder="{{ __('phone number')}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <x-form.text :title="__('Password')" :type="__('text')" :name="'password'" :value="old('password', '')"  :required="'yes'" :placeholder="__('Enter password')"/>
                            </div>
                            <div class="col-lg-6">
                                <x-form.text :title="__('Confirm Password')" :type="__('text')" :name="'password_confirmation'" :value="old('password_confirmation', '')" :required="'yes'"  :placeholder="__('Confirm password')"/>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <label for="type" class="notice-type-label"><strong>{{__('Select User Type')}}</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="type" id="type" class="form-control">
                                    <option value="">{{ __('Select User Type') }}</option>
                                    <option value="1">{{ __('Client') }}</option>
                                    <option value="0">{{ __('Provider') }}</option>
                                </select>
                            </div>

                        </div>
                        <br>
                        <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 validate_subscription_type">{{__('Submit')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <x-custom-js.phone-number-config selector="#telephone" submit-button-id="test-sms-btn" key="1"/>
    <x-custom-js.phone-number-config selector="#set-telephone" submit-button-id="test-sms-btn" key="2"/>
@endsection
