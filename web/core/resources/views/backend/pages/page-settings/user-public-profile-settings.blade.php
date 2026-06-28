@extends('backend.admin-master')
@section('site-title')
    {{__('Listing User Public Profile Page Settings')}}
@endsection
@section('style')
    <x-summernote.css/>
    <style>
        .dashboard__card {
            height: auto;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <x-validation.error/>
        <form action="{{route('admin.user.public.profile.settings')}}" method="POST">
            @csrf

             <div class="col-6">
                <div class="dashboard__card bg__white padding-20 radius-10">
                    <h2 class="dashboard__card__header__title mb-3">{{__('User Public Profile')}}</h2>
                        <div class="form__input__single">

                        </div>
                </div>
            </div>
            <div class="btn_wrapper mt-4">
                <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Update Changes') }}</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <x-summernote.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                <x-btn.update/>
            });
        }(jQuery));
    </script>
@endsection
