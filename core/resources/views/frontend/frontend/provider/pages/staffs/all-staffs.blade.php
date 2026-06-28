@extends('frontend.frontend.provider.provider-master')
@section('site-title')
    {{__('All Staffs')}}
@endsection
@section('style')
    <x-media.css/>
    <style>
        td.actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
    </style>
@endsection
@section('content')
    <div class="row w-100 g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('All Staffs') }}</h4>
                            <x-bulk-action.bulk-action/>
                        </div>
                        <div class="dashboard__inner__header__right">
                            <div class="d-flex text-right w-100 mt-3">
                                <input class="form__control blog_string_search" name="string_search" id="string_search" placeholder="{{ __('Search') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <x-validation.error />
                <div class="tableStyle_three mt-4">
                    <div class="table_wrapper custom_Table">
                        <div class="search_result">
                            @include('frontend.frontend.provider.pages.staffs.search-result')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.frontend.provider.pages.staffs.staff-details-modal')
    <x-media.markup/>
@endsection
@section('scripts')
    <x-bulk-action.bulk-action-js :url="route('provider.bulk.action.staff')"/>
    <x-media.js />
    @include('frontend.frontend.provider.pages.staffs.staff-js')
@endsection
