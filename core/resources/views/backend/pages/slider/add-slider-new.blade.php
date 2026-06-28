<div class="col-xl-3 col-lg-3">
    <div class="dashboard__card bg__white padding-20 radius-10">
        <h2 class="dashboard__card__header__title mb-3">{{__('Add New Slider')}}</h2>
        <form action="{{route('admin.slider.add')}}" method="POST" class="new_language_form">
            @csrf
            <div class="form__input__flex">
                <div class="form-group">
                    <label for="image">{{__('Upload Background Image')}}</label>
                    <div class="media-upload-btn-wrapper">
                        <div class="img-wrap"></div>
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
                        <option value="service">{{__('Service')}}</option>
                        <option value="category">{{__("Category")}}</option>
                        <option value="offer">{{__("Offer")}}</option>
                    </select>
                </div>

                <!-- Service Section -->
                <div class="form__input__single" id="service-section">
                    <label for="identity_service_id" class="form__input__single__label">{{ __('Service') }}</label>
                    <select name="identity_service_id" id="identity_service_id" class="form__control radius-5">
                        <option value="">{{ __('Select Service') }}</option>
                        @foreach($services as $service)
                            <option value="{{$service->id}}">{{$service->title}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Section -->
                <div class="form__input__single" id="category-section">
                    <label for="identity_category_id" class="form__input__single__label">{{ __('Category') }}</label>
                    <select name="identity_category_id" id="identity_category_id" class="form__control radius-5">
                        <option value="">{{ __('Select Category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}"> {{ $category->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form__input__single" id="offer-section">
                    <label for="identity_offer_id" class="form__input__single__label">{{ __('Offer') }}</label>
                    <select name="identity_offer_id" id="identity_offer_id" class="form__control radius-5">
                        <option value="">{{ __('Select Offer') }}</option>
                        @foreach($offers as $offer)
                            <option value="{{$offer->id}}"> {{ $offer->title }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="form__input__single">
                    <label for="status" class="form__input__single__label">{{__('Status')}}</label>
                    <select name="status" id="status" class="form__control radius-5">
                        <option value="1">{{__('Publish')}}</option>
                        <option value="0">{{__("Unpublished")}}</option>
                    </select>
                </div>
            </div>
            <div class="btn_wrapper mt-4">
                <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Submit') }}</button>
            </div>
        </form>
    </div>
</div>
