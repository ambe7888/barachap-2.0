<div  class="tab-pane fade step active show" id="listing-info" role="tabpanel" aria-labelledby="listing-info-tab">
    <div class="row">
        <div class="col-lg-8">
            <!-- Title -->
            <div class="form__input__single">
                <label class="form__input__single__label">{{ __('Title') }} <span class="text-danger">*</span></label>
                <input type="text" class="form__control radius-5"  name="title" id="title" value="{{$service->title}}" placeholder="{{__('Add title')}}">
            </div>

            <div class="form__input__single mt-3">
                <div class="input-form input-form2 permalink_label">
                    <label for="title" class="form__input__single__label text-dark"> {{__('Permalink')}}  <span class="text-danger">*</span>  </label>
                    <span id="slug_show" class="display-inline"></span>
                    <span id="slug_edit" class="display-inline d-inline">
                    <button class="btn btn-warning btn-sm slug_edit_button">  <i class="las la-edit"></i> </button>
                    <input class="listing_slug form__control radius-5" name="slug" value="{{$service->slug}}" id="slug" style="display: none" type="text">
                    <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none">{{__('Update')}}</button>
                </span>
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-between gap-3 mt-4">
                <div class="form__input__single">
                    <label class="form__input__single__label">{{ __('Category') }}  <span class="text-danger">*</span> </label>
                    <select name="category_id" id="category" class="select-itms select2_activation">
                        <option value="">{{__('Select Category')}}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @if($cat->id == $service->category_id) selected @endif>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__input__single" id="sub_category">
                    <label for="subcategory" class="form__input__single__label"> {{__('Sub Category')}} </label>
                    <select  name="sub_category_id" id="subcategory" class="subcategory select2_activation">
                        <option value="">{{__('Select Sub Category')}}</option>
                        @foreach($sub_categories as $sub_cat)
                            <option value="{{$sub_cat->id}}" @if($sub_cat->id == $service->sub_category_id) selected @endif> {{ $sub_cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form__input__single child_category_wrapper w-100">
                    <label for="child_category" class="form__input__single__label"> {{__('Child Category')}} </label>
                    <select  name="child_category_id" id="child_category" class="select2_activation">
                        <option value="">{{__('Select Child Category')}}</option>
                        @if(!empty($service->child_category_id))
                            @foreach($child_categories as $child_cat)
                                <option value="{{ $child_cat->id }}"  @if($child_cat->id == $service->child_category_id) selected @endif>{{ $child_cat->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="form__input__single mt-4">
                <label class="form__input__single__label">{{ __('Description') }} <span class="text-danger">*</span> <span class="text-danger">{{ __('minimum 150 characters.') }}</span> </label>
                <div class="input-form input-form2">
                    <textarea class="textarea--form" name="description" placeholder="{{__('Type Description')}}" rows="8" cols="8">{{ $service->description }}</textarea>
                </div>
            </div>

            <div class="d-flex">
                <!-- video url -->
                <div class="form__input__single">
                    <label class="form__input__single__label">{{ __('Video Url') }} </label>
                    <div class="input-form input-form2">
                        <input type="text" class="form__control radius-5" name="video_url" id="video_url" value="{{ $service->video_url }}" placeholder="{{__('youtube url')}}">
                    </div>
                    <small class="text-danger video_url_design">{{ __('Example:') }} https://www.youtube.com/watch?v=IcM8_Llgxf4&t=1s </small>
                </div>

                <!-- featured services -->
                <div class="form__input__single mt-4 mx-3">
                    <div class="checkBox">
                        <label class="is_featured form__input__single__label d-flex gap-2">
                            <input class="checkBox__input effectBorder" type="checkbox" name="is_featured" id="is_featured" value="{{$service->is_featured}}" @if($service->is_featured == 1) checked @endif>
                            {{ __('Is Featured') }}
                        </label>
                    </div>
                </div>
            </div>
           
            <div class="form__input__single mt-3">
                <input type="checkbox" class="form-check-input" id="choose_staff" name="disable_staff" @if($service->disable_staff==1) checked @endif >
                <label class="form-check-label" for="choose_staff">Client can choose staff</label>
            </div>
                
            
            <div class="form__input__single mt-3 d-none" id="choose_staff_option">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="any_staff" id="any_staff1" value="option1" @if($service->allocate_staff==1) checked @endif>
                    <label class="form-check-label" for="any_staff">
                        Client can choose allocated staff only
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="any_staff" id="any_staff2" value="option2" @if($service->allocate_staff==0) checked @endif>
                    <label class="form-check-label" for="any_staff">
                        Client can choose all staff
                    </label>
                </div>
            </div>
            <div class="form__input__single d-none" id="select_staff">
                <label for="mySelect" class="form__input__single__label">{{ __('Select Staff') }}</label>
                <select name="staffs[]" id="mySelect" multiple="multiple" style="width: 300px;">
                    @php
                        $service_staffs=$service->staffs_id;
                        $service_staffs=explode(',',$service_staffs);
                    @endphp
                    <option value="">{{__('Select Service')}}</option>
                    @foreach($staffs as $staff)   
                        <option value="{{ $staff->id }}" @if(in_array($staff->id,$service_staffs)) selected @endif>{{ $staff->first_name }} {{ $staff->last_name }}</option> 
                    @endforeach
                </select>     
            </div>
        </div>


        <div class="col-lg-4">
            <!-- Price -->
            <div class="col-lg-12 col-md-12 mt-5">
                <div class="form__input__single">
                    <label class="form__input__single__label">{{ __('Price') }} <span class="text-danger">*</span></label>
                    <input type="number" class="form__control radius-5" name="price" id="price" value="{{ $service->price }}" placeholder="{{__('0.00')}}">
                </div>
            </div>

            <!-- Discount Price -->
            <div class="col-lg-12 col-md-12 mt-4">
                <div class="form__input__single">
                    <label class="form__input__single__label">{{ __('Discount Price') }} <span class="text-danger">*</span></label>
                    <input type="number" class="form__control radius-5" name="discount_price" id="discount_price" value="{{ $service->discount_price }}" placeholder="{{__('0.00')}}">
                </div>
            </div>

            <!-- Service Unit -->
            <div class="col-lg-12 col-md-12 mt-4">
                <div class="form__input__single">
                    <label class="form__input__single__label">{{ __('Unit') }}</label>
                    <input type="text" class="form__control radius-5" name="unit" id="unit" value="{{ $service->unit }}" placeholder="{{ __('e.g., kg, gm, m, L,hr, km/h, m²') }}">
                </div>
            </div>

            <!--single image -->
            <div class="col-lg-12 mt-3">
                <div class="upload-img">
                    <div class="media-upload-btn-wrapper">
                        <div class="img-wrap">
                            {!! render_attachment_preview_for_admin($service->image ?? '') !!}
                        </div>
                        <input type="hidden" name="image" value="{{$service->image ?? ''}}">
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

            <div class="col-lg-12 mt-3">
                <div class="upload-img">
                    <div class="media-upload-btn-wrapper">
                        <div class="img-wrap">
                            {!! render_gallery_image_attachment_preview($service->gallery_images ?? '') !!}
                        </div>
                        <input type="hidden" name="gallery_images" value="{{ $service->gallery_images }}">
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

            <!-- start previous / next buttons -->
            <div  class="col-lg-12 mt-5">
                <div class="btn_wrapper d-flex justify-content-end gap-3">
                    <button class="cmnBtn btn_5 btn_bg_blue radius-5" id="nextBtn" type="button">{{__('Next')}}</button>
                </div>
            </div>

        </div>
    </div>
</div>
