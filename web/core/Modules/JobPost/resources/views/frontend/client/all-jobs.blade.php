@extends('frontend.frontend.client.client-master')
@section('site-title')
    {{__('All Jobs')}}
@endsection
@section('style')
    <style>
        .custom_table tr td:not(:first-child) {
            min-width: 42px;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 w-100 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('All Jobs') }}</h4>
                       </div>
                       <div class="dashboard__inner__header__right">
                        <div class="d-flex text-right w-100 mt-3">
                            <input class="form__control search_result" name="string_search" id="string_search" placeholder="{{ __('Search') }}">
                        </div>
                    </div>
                   </div>
                 </div>
                <x-validation.error/>
                <div class="tableStyle_three mt-4">
                    <div class="table_wrapper custom_Table">
                        <div class="search_result">
                            @include('jobpost::frontend.client.search-result')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('jobpost::frontend.client.js')
@endsection
