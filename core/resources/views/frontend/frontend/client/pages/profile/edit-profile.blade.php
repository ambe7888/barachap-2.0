@extends('frontend.frontend.client.client-master')
@section('site-title')
    {{__('Edit Profile')}}
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
                    <div class="general-information mt-5">
                        <form action="{{route('client.profile.update')}}" method="POST" class="validateForm" enctype="multipart/form-data">
                            @csrf
                            <div class="img-upload-part d-flex align-items-center gap-4">
                                <div class="upload-img">
                                    <div class="media-upload-btn-wrapper">
                                        <div class="img-wrap">
                                            {!! render_attachment_preview_for_admin(auth()->user()->image ?? '') !!}
                                        </div>
                                        <input type="hidden" name="profile_image" value="{{auth()->user()->image ?? ''}}">
                                        <button type="button" class="btn btn-info media_upload_form_btn"
                                                data-btntitle="{{__('Select Image')}}"
                                                data-modaltitle="{{__('Upload Image')}}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#media_upload_modal">
                                            {{__('Upload Main Image')}}
                                        </button>
                                        <small>{{ __('image format: jpg,jpeg,png,gif,webp')}}</small> <br>
                                        <small>{{ __('recommended size 810x450') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="name-wraper d-flex flex-wrap gap-3">
                                <div class="fname flex-1">
                                    <label for="first_name" class="custom-label">{{__('First Name')}}</label>
                                    <input type="text" name="first_name" id="first_name" value="{{auth()->user()->first_name}}" class="custom-input w-100" placeholder="{{__('Enter First Name')}}">
                                </div>
                                <div class="lname flex-1">
                                    <label for="last_name" class="custom-label">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" value="{{auth()->user()->last_name}}" class="custom-input w-100" placeholder="{{__('Enter Last Name')}}">
                                </div>
                            </div>
                            <div class="email-wraper d-flex flex-wrap gap-3 mt-3">
                               <div class="flex-1">
                                    <label for="email" class="custom-label">Email</label>
                                    <input type="email" name="email" id="email" class="custom-input w-100" value="{{auth()->user()->email}}" placeholder="{{__('Enter Your Email')}}">
                               </div>
                               <div class="flex-1">
                                    <label for="date_of_birth" class="custom-label">Date Of Birth</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="custom-input w-100" value="{{auth()->user()->date_of_birth}}" placeholder="{{__('Enter Your Date of Birth')}}">
                               </div>
                            </div>
                            <div class="video-url-wraper mt-3">
                                <label class="form__input__single__label">{{ __('Video Url') }} </label>
                                <div class="input-form input-form2">
                                    <input type="text" class="form__control radius-5" name="video_url" id="video_url" value="{{__("https://www.youtube.com/watch?v=")}}{{auth()->user()->video_url}}" placeholder="{{__('youtube url')}}">
                                </div>
                                <small class="text-danger video_url_design">{{ __('Example:') }} https://www.youtube.com/watch?v=IcM8_Llgxf4&t=1s </small>
                            </div>
                            <div class="about-wraper mt-3">
                                <label for="about" class="form__input__single__label">{{ __('About') }}</label>
                                <textarea id="about" name="about" class="form__control radius-5" style="width:100%; height:100px;">{{ auth()->user()->about }} </textarea>
                            </div>
                            <div class="state-city-wraper d-flex gap-3 flex-wrap mt-3">
                                <div class="upload-img">
                                    <div class="media-upload-btn-wrapper">
                                        <div class="img-wrap">
                                            {!! render_gallery_image_attachment_preview(auth()->user()->store_images ?? '') !!}
                                        </div>
                                        <input type="hidden" name="gallery_images" value="{{ auth()->user()->store_images }}">
                                        <button type="button" class="btn btn-info media_upload_form_btn"
                                                data-btntitle="{{__('Select Image')}}"
                                                data-modaltitle="{{__('Upload Image')}}"
                                                data-mulitple="true"
                                                data-bs-toggle="modal"
                                                data-bs-target="#media_upload_modal">
                                            {{__('Upload Gallery Images')}}
                                        </button>
                                        <small>{{ __('image format: jpg,jpeg,png,gif,webp')}}</small> <br>
                                        <small>{{ __('recommended size 810x450') }}</small>
                                    </div>
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
