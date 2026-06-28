@extends('frontend.frontend.client.client-master')
@section('site-title')
    {{__('Account Delete')}}
@endsection
@section('style')
   <link rel="stylesheet" href="{{asset('assets/backend/css/select2.min.css')}}">
   <x-media.css/>
@endsection
@section('content')
<div class="user-right-part-content-wraper">
    <div class="user-right-part-content">
        <div class="main-content-wraper">
            <div class="mb-4 d-md-none">
                <span class="dashbord-toggle-icon">
                    <i class="fa-solid fa-bars"></i>
                </span>
            </div>
            <div class="main-content">
                <div class="user-profile-wraper">
                    <x-validation.error/>
                    <div class="general-information mt-5">
                        <form action="{{route('client.account.delete')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="name-wraper d-flex flex-wrap gap-3">
                                <div class="fname flex-1">
                                    <label for="reason_id" class="form__input__single__label">{{ __('Select Reason') }}</label>
                                    <select name="reason_id" id="reason_id">
                                        <option value="">{{__('Select Reason')}}</option>
                                        @foreach($reasons as $reason)        
                                            <option value="{{ $reason->id }}">{{ $reason->title}}</option>       
                                        @endforeach
                                    </select>  
                                </div>

                            </div>
                            <div class="email-wraper d-flex flex-wrap gap-3 mt-3">
                               <div class="flex-1">
                                    <label for="password" class="custom-label">Password</label>
                                    <input type="password" name="password" id="password" class="custom-input w-100" value="{{old('password')}}" placeholder="{{__('Enter Your Password')}}">
                               </div>
                              
                            </div>

                            <div class="about-wraper mt-3">
                                <label class="form__input__single__label">{{ __('Description') }} <span class="text-danger">*</span>  </label>
                                <div class="input-form input-form2">
                                    <textarea class="textarea--form" name="description" placeholder="{{__('Type Description')}}" rows="8" cols="8">{{ old('description') }}</textarea>
                                </div>
                            </div>
                           
                            <div class="information-save-btn mt-4">
                                <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Save changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="user-right-part-footer">
        <span class="version">Version 2.0.1</span>
        <span class="all-right">&copy;2000-2024, <a href="#/">Xproperties</a> All Rights Reserved</span>
    </div>
</div>
    <x-media.markup/>
@endsection
@section('scripts')
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <x-media.js/>
@endsection
