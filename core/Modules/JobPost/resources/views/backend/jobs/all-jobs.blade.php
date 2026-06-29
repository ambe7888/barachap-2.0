@extends('backend.admin-master')
@section('site-title')
    {{__('All Jobs')}}
@endsection
@section('style')
    <style>
        td.actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .DataTable_activation {
            width: 100% !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            margin-top: 15px !important;
            border: 1px solid #f0f0f0 !important;
            background: #ffffff !important;
            border-radius: 8px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02) !important;
        }
        .DataTable_activation thead {
            background-color: #198754 !important; /* Theme Clean Green */
            color: #ffffff !important;
        }
        .DataTable_activation thead th {
            padding: 14px 16px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 11px !important;
            letter-spacing: 0.5px !important;
            border: none !important;
        }
        .DataTable_activation tbody tr {
            border-bottom: 1px solid #f5f5f5 !important;
            transition: all 0.2s ease-in-out !important;
        }
        .DataTable_activation tbody tr:hover {
            background-color: #f8fdf9 !important;
        }
        .DataTable_activation tbody td {
            padding: 14px 16px !important;
            vertical-align: middle !important;
            font-size: 13px !important;
            color: #444444 !important;
            border: none !important;
        }
        .table_customer__title {
            font-size: 12px !important;
            margin-bottom: 4px !important;
            color: #666666 !important;
            font-weight: 500 !important;
        }
        .status_btn {
            padding: 4px 10px !important;
            border-radius: 12px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            display: inline-block !important;
            letter-spacing: 0.3px !important;
        }
        .status_btn.in_progress {
            background-color: #e8f5e9 !important;
            color: #2e7d32 !important;
        }
        .status_btn.completed {
            background-color: #e3f2fd !important;
            color: #1565c0 !important;
        }
        .alert {
            padding: 4px 8px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            text-transform: uppercase !important;
            display: inline-block !important;
            margin-bottom: 0 !important;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header mb-3">
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
                            @include('jobpost::backend.jobs.search-result')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <x-sweet-alert.sweet-alert2-js/>
    @include('jobpost::backend.jobs.js')
@endsection
