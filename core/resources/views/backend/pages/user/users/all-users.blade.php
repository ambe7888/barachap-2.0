@extends('backend.admin-master')
@section('site-title')
    {{__('All Users')}}
@endsection
@section('style')
    <style>
        td.actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .swal2-container {
            z-index: 9999999;
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
                            <h4 class="dashboard__inner__header__title">{{ __('All Users') }}</h4>
                        </div>
                    </div>
                </div>
                <x-validation.error />
                <div class="tableStyle_three mt-4">
                    @include('backend.pages.user.users.filter-markup')
                    <div class="table_wrapper custom_Table">
                        <div class="search_result">
                            @include('backend.pages.user.users.search-result')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.pages.user.users.user-details-modal')
    @include('backend.pages.user.users.user-password-modal')
    @include('backend.pages.user.users.user-details-edit-modal')
    @include('backend.pages.user.users.identity-verify-details-modal')
@endsection
@section('scripts')
    @include('backend.pages.user.users.user-js')
@endsection
