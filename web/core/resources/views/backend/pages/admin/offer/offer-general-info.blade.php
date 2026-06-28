<div class="row g-4">
    <div class="col-md-6">
        <div class="dashboard__card">
            <div class="dashboard__card__header p-2">
                <h4 class="dashboard__card__title">{{ __('Create Info') }}</h4>
            </div>
            <div class="dashboard__card__body custom__form mt-4 p-2">
                <div class="form-group">
                    <label for="title">{{ __('Title') }}</label>
                    <input type="text" class="form-control" id="title"
                           name="title" placeholder="Offer Title">
                </div>
                <div class="form-group mt-2">
                    <label for="campaign_subtitle">{{ __('Subtitle') }}</label>
                    <textarea type="text" style="width: 100%; height: 90px;line-height: 1.4;padding-top:10px;" class="form-control" id="subtitle" name="subtitle"
                              placeholder="Order Subtitle" ></textarea>
                </div>
                <div class="form-group mt-2">
                    <label for="expaired_at">{{ __('Expaired At') }}</label>
                    <input type="date" class="form-control" id="expaired_at"
                           name="expaired_at" placeholder="Expaired At">
                </div>
                <div class="upload-img mt-3">
                    <div class="media-upload-btn-wrapper">
                        <div class="img-wrap">
                            <img src="{{ asset('assets/frontend/img/gallery/single-image-upload.png') }}" alt="images" class="w-100">
                        </div>
                        <input type="hidden" name="image">
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

                <div class="form-group mt-2">
                    <label for="offer_status">{{ __('Offer Status') }}</label>
                    <select name="status" id="status" class="form-control">
                        <option value="0">{{ __('Draft') }}</option>
                        <option value="1">{{ __('Publish') }}</option>
                    </select>
                </div>
            <div class="form-group mt-2">
                <label for="is_primary">{{ __('Primary Offer') }}</label>
                <select name="is_primary" id="is_primary" class="form-control">
                    <option value="0">{{ __('No') }}</option>
                    <option value="1">{{ __('Yes') }}</option>
                </select>
            </div>
        </div>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="row">
            <div class="col-md-12">
                <div id="product_repeater_container">
                     @include('backend.pages.admin.offer.add_new_offer_service')
                </div>

            </div>
        </div>
    </div>
    <div class="col-sm-12 text-end">
        @can('campaign-create')
            <hr>
            <button type="submit"
                    class="cmn_btn btn_bg_profile">{{ __('Create Offer') }}</button>
        @endcan
    </div>
</div>






