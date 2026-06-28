@extends('backend.admin-master')
@section('site-title')
    {{__('New Order Template')}}
@endsection
@section('style')
    <x-summernote.css/>
    <style>
        .custom-textarea {
            width: 100%;
            box-sizing: border-box;
            height: 150px; /* Adjust height as needed */
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapp d-flex justify-content-between">
                            <h4 class="header-title">{{__('New Order Template')}}</h4>
                            <a class="btn btn-info" href="{{route('admin.email.template.all')}}">{{__('All Email Templates')}}</a>
                        </div>
                        <form action="{{route('admin.email.new.order.template')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content margin-top-30">
                                <div class="form-group">
                                    <label for="new_order_email_subject">{{__('Email Subject')}}</label>
                                    <input type="text" name="new_order_email_subject"  class="form-control" value="{{ get_static_option('new_order_email_subject') ?? __('New Order') }}">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="new_order_client_message">{{ __('Email Message For Client') }}</label>
                                    <textarea class="form-control summernote custom-textarea" name="new_order_client_message">{!! get_static_option('new_order_client_message') ?? '' !!} </textarea>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="new_order_admin_provider_message">{{ __('Email Message For Provider And Admin') }}</label>
                                    <textarea class="form-control summernote custom-textarea" name="new_order_admin_provider_message">{!! get_static_option('new_order_admin_provider_message') ?? '' !!} </textarea>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="new_order_admin_order_message">{{ __('Email Message For Admin Order') }}</label>
                                    <textarea class="form-control summernote custom-textarea" name="new_order_admin_order_message" rows="10" cols="12">{!! get_static_option('new_order_admin_order_message') ?? '' !!} </textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <x-summernote.js/>
@endsection
