@extends('backend.admin-master')
@section('site-title')
    {{__('All Schedules')}}
@endsection
@section('style')
    <style>
        td.actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('All Schedules') }}</h4>
                        </div>
                        <div class="dashboard__inner__header__right">
                            <div class="btn-wrapper">
                                <a href="{{ route('admin.schedule.add') }}" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Add Schedule') }}</a>
                            </div>
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
                            @include('backend.pages.schedules.search-result')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.pages.schedules.schedules-details-modal')
    @include('backend.pages.schedules.schedules-details-edit-modal')
@endsection
@section('scripts')
    @include('backend.pages.schedules.schedules-js')
@endsection
