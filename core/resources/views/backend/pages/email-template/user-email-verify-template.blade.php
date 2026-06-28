@extends('backend.admin-master')
@section('site-title')
    {{__('User Email Verify Template')}}
@endsection
@section('style')
    <x-media.css/>
    <x-summernote.css/>
@endsection
@section('content')
<div class="row g-4 mt-0">
    <div class="col-xl-6 col-lg-6 mt-0">
        <div class="dashboard__card bg__white padding-20 radius-10">
            <div class="dashboard_orderDetails__header__flex">
                <div class="dashboard_orderDetails__header__left">
                    <h2 class="dashboard__card__header__title mb-3">{{__('User Email Verify Template')}}</h2>
                </div>
                <div class="dashboard_orderDetails__header__right">
                    <a href="{{route('admin.email.template.all')}}" class="cmnBtn btn_5 btn_bg_info radius-5">{{__('All Email Templates')}}</a>
                </div>
            </div>
            <x-validation.error/>
            <form action="{{route('admin.email.user.verify.template')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="tab-content margin-top-30">
                    <div class="form-group">
                        <label for="user_email_verify_subject">{{__('Email Subject')}}</label>
                        <input type="text" name="user_email_verify_subject"  class="form-control" value="{{ get_static_option('user_email_verify_subject') ?? __('Verify your email address') }}">
                    </div>
                    <div class="form-group">
                        <label for="user_register_message">{{ __('Email Message') }}</label>
                        <textarea class="form-control summernote" name="user_email_verify_message" style="height: 100px;">{!! get_static_option('user_email_verify_message') ?? '<p>Hello @name,</p></p>Here is your verification code Verification Code: @email_verify_tokn</p>'  !!}</textarea>
                    </div>
                    <div class="d-grid">
                        <small class="form-text"><strong class="text-danger"> @name </strong>{{__('will be replaced by dynamically with name.')}}</small>
                        <small class="form-text"><strong class="text-danger"> @username </strong>{{__('will be replaced by dynamically with username.')}}</small>
                        <small class="form-text"><strong class="text-danger"> @email </strong>{{__('will be replaced by dynamically with email.')}}</small>
                        <small class="form-text"><strong class="text-danger"> @type </strong>{{__('will be replaced by dynamically with user type.')}}</small>
                    </div>
                    </div>
                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update')}}</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
    <x-media.js />
    <x-summernote.js/>
@endsection
