@extends('backend.admin-master')
@section('site-title')
    {{__('All Tickets')}}
@endsection
@section('style')
    <x-summernote.css/>
    <style>
        .select2-container {
            z-index: 99999;
        }
        .new_st + .select2-container{
            z-index: 9999;
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
                            <h4 class="dashboard__inner__header__title">{{ __('All Tickets') }}</h4>
                            @can('support-ticket-bulk-delete')
                                <x-bulk-action.bulk-action/>
                            @endcan
                        </div>
                        <div class="dashboard__inner__header__right">
                            <x-btn.add-modal :title="__('Add Ticket')" />
                            <div class="d-flex text-right w-100 mt-3 ">
                                <select name="priority" id="priority" class="select2_activation radius-5 new_st">
                                    <option value="">{{ __('Select priority') }}</option>
                                    <option value="high">{{ __('High') }}</option>
                                    <option value="low">{{ __('Low') }}</option>
                                    <option value="urgent">{{ __('Urgent') }}</option>
                                    <option value="medium">{{ __('Medium') }}</option>
                                </select>

                                <select name="status" id="status" class="select2_activation radius-5 mx-4 new_st">
                                    <option value="">{{ __('Select Status') }}</option>
                                    <option value="open">{{ __('Open') }}</option>
                                    <option value="close">{{ __('Close') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <x-validation.error/>
                <div class="tableStyle_three mt-4">
                    <div class="table_wrapper custom_Table">
                        <div class="mt-4">
                            <x-notice.general-notice
                                :description="__('Notice: The admin has the ability to create tickets for both the client and the freelancer if desired.')"
                                :description1="__('Notice: Admin can search by ticket id, ticket status, ticket priority.')"
                            />
                        </div>
                        <div class="search_result">
                            @include('supportticket::backend.ticket.search-result')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('supportticket::backend.ticket.add-modal')
@endsection
@section('scripts')
    @can('support-ticket-bulk-delete')
        <x-bulk-action.bulk-delete-js :url="route('admin.ticket.delete.bulk.action')"/>
    @endcan
    @include('supportticket::backend.ticket.ticket-js')
    <x-summernote.js />
@endsection
