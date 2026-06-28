@extends('backend.admin-master')
@section('site-title')
    {{__('Job Settings')}}
@endsection
@section('content')
    <div class="col-lg-6 col-ml-12 padding-bottom-30">
        <div class="row m-0 p-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Job Create Settings')}} </h4>
                                <p class="mb-3 text-info">{{ __('You can set the buyer create job status auto active/Inactive from here.') }}</p>
                            </div>
                        </div>
                        <div class="mb-2 mt-2">
                            <x-msg.success/>
                            <x-msg.error/>
                        </div>
                        <form action="{{route('admin.job.create.settings.update')}}" method="post">
                            @csrf
                            <div class="tab-content margin-top-10">
                                <div class="form-group">
                                    <label for="job_create_settings">{{__('Select')}}</label>
                                    <select type="text" class="form-control" name="job_create_settings" id="job_create_settings">
                                        <option value="">{{ __('Select Status') }}</option>
                                        <option value="active" {{ get_static_option('job_create_settings')=='active' ? 'selected' : '' }} >{{ __('Active') }}</option>
                                        <option value="inactive" {{ get_static_option('job_create_settings')=='inactive' ? 'selected' : '' }} >{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3 submit_btn">{{__('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
