<div class="col-xl-3 col-lg-3">
    <div class="dashboard__card bg__white padding-20 radius-10">
        <h2 class="dashboard__card__header__title mb-3">{{__('Add New Slider')}}</h2>

            <form action="{{route('admin.slider.edit', $slider->id)}}" method="POST" class="new_language_form">
                @csrf

            <div class="form__input__flex">

                <div class="form-group">
                    <label for="image">{{__('Upload Background Image')}}</label>
                    <div class="media-upload-btn-wrapper">
                        <div class="img-wrap">
                            {!! render_image_markup_by_attachment_id($slider->image,'','thumb') !!}
                        </div>
                        <input type="hidden" name="image">
                        <button type="button" class="btn btn-info media_upload_form_btn"
                                data-btntitle="{{__('Select Image')}}"
                                data-modaltitle="{{__('Upload Image')}}"
                                data-bs-toggle="modal"
                                data-bs-target="#media_upload_modal">
                            {{__('Upload Slider Image')}}
                        </button>
                    </div>
                </div>

                <div class="form__input__single">
                    <label for="direction" class="form__input__single__label">{{__('Type')}}</label>
                    <select name="type" id="type" class="form__control radius-5">
                        <option value="">{{ __('Select Type') }}</option>
                        <option value="service" @if($slider->type == 'service') selected @endif>{{__('Service')}}</option>
                        <option value="category" @if($slider->type == 'category') selected @endif >{{__("Category")}}</option>
                        <option value="offer" @if($slider->type == 'offer') selected @endif >{{__("Offer")}}</option>
                    </select>
                </div>

                <div id="service-section" class="form__input__single" style="display: none;">
                    <label for="identity_service_id" class="form__input__single__label">{{ __('Service') }}</label>
                    <select name="identity_service_id" id="identity_service_id" class="form__control radius-5">
                        <option value="">{{ __('Select Service') }}</option>
                        @foreach($services as $service)
                            <option @if($service->id == $slider->identity) selected @endif value="{{ $service->id }}">{{ $service->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="category-section" class="form__input__single" style="display: none;">
                    <label for="identity_category_id" class="form__input__single__label">{{ __('Category') }}</label>
                    <select name="identity_category_id" id="identity_category_id" class="form__control radius-5">
                        <option value="">{{ __('Select Category') }}</option>
                        @foreach($categories as $category)
                            <option @if($category->id == $slider->identity) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="offer-section" class="form__input__single" style="display: none;">
                    <label for="identity_offer_id" class="form__input__single__label">{{ __('Offer') }}</label>
                    <select name="identity_offer_id" id="identity_offer_id" class="form__control radius-5">
                        <option value="">{{ __('Select Offer') }}</option>
                        @foreach($offers as $offer)
                            <option @if($offer->id == $slider->identity) selected @endif value="{{ $offer->id }}">{{ $offer->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form__input__single">
                    <label for="status" class="form__input__single__label">{{__('Status')}}</label>
                    <select name="status" id="status" class="form__control radius-5">
                        <option value="1" @if($slider->status == 1) selected @endif>{{__('Publish')}}</option>
                        <option value="0" @if($slider->status == 0) selected @endif >{{__("Unpublished")}}</option>
                    </select>
                </div>
            </div>
            <div class="btn_wrapper mt-4">
                <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_warning radius-5">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</div>
