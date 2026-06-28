@extends('backend.admin-master')
@section('site-title')
 {{ __('Edit Staff') }}
@endsection
@section('style')
    <x-media.css />
    <style>
        img.no-image {
            max-width: 100px;
        }
        .img-wrap img {
            max-width: 119px;
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
                            <h4 class="dashboard__inner__header__title">{{ __('Edit Staff') }}</h4>
                        </div>
                    </div>
                </div>
                <x-validation.error/>
                <div class="customMarkup__single__inner mt-4">
                    <form action="{{route('admin.staff.info.edit', $staff->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <x-form.text :title="__('First Name')" :type="'text'" :name="'first_name'"
                                             :value="old('first_name', $staff->first_name)"
                                             :placeholder="__('Enter first name')"/>
                                @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="col-lg-6">
                                <x-form.text :title="__('Last Name')" :type="'text'" :name="'last_name'"
                                             :value="old('last_name', $staff->last_name)"
                                             :placeholder="__('Enter last name')"/>
                                @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="col-lg-6">
                                <x-form.text :title="__('Email Address')" :type="'email'" :name="'email'"
                                             :value="old('email', $staff->email)"
                                             :placeholder="__('Enter email')"/>
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="col-lg-6">
                                <x-form.text :title="__('Phone Number')" :type="'tel'" :name="'phone'"
                                             :value="old('phone', $staff->phone)"
                                             :placeholder="__('Enter phone')"/>
                                @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- About -->
                            <div class="col-lg-6">
                                <x-form.text :title="__('About')" :type="'text'" :name="'about'"
                                             :value="old('about', $staff->about)"
                                             :placeholder="__('Enter about')"/>
                                @error('about')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Staff Image -->
                            <div class="col-lg-6">
                                <div class="form__input__single">
                                    <label for="image" class="form__input__single__label">{{ __('Staff Image') }}</label>
                                    <div class="media-upload-btn-wrapper">
                                        <div class="img-wrap">
                                            {!! render_image_markup_by_attachment_id($staff->image, '', 'thumb') !!}
                                        </div>
                                        <input type="hidden" name="image" value="{{ $staff->image }}">
                                        <button type="button" class="cmnBtn btn_5 btn_bg_blue radius-5 media_upload_form_btn"
                                                data-btntitle="{{ __('Select Image') }}"
                                                data-modaltitle="{{ __('Upload Image') }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#media_upload_modal">
                                            {{ __('Upload Image') }}
                                        </button>
                                    </div>
                                    @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="btn_wrapper mt-4 text-end">
                            <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_warning radius-5">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
    <x-media.js />
@endsection
