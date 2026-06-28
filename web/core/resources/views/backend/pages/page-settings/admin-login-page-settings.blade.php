@extends('backend.admin-master')
@section('site-title')
    {{__('Admin Login Page Settings')}}
@endsection
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <x-validation.error/>
        <form action="{{route('admin.login.page.settings')}}" method="POST">
            @csrf

             <div class="col-6">
                <div class="dashboard__card bg__white padding-20 radius-10">
                    <h2 class="dashboard__card__header__title mb-3">{{__('Admin Login Page Settings')}}</h2>

                    <div class="form__input__single">
                        <label for="admin_login_page_title" class="form__input__single__label">{{ __('Title') }}</label>
                        <input type="text" class="form__control radius-5" name="admin_login_page_title"  value="{{get_static_option('admin_login_page_title')}}">
                    </div>
                    <div class="form__input__single">
                        <label for="admin_login_page_subtitle" class="form__input__single__label">{{ __('Sub Title') }}</label>
                        <input type="text" class="form__control radius-5" name="admin_login_page_subtitle" id="admin_login_page_subtitle" value="{{get_static_option('admin_login_page_subtitle')}}">
                    </div>
                </div>
            </div>
            <div class="btn_wrapper mt-4">
                <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Update Changes') }}</button>
            </div>
        </form>
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
    <x-media.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                <x-btn.update/>
            });
        }(jQuery));
    </script>
@endsection
