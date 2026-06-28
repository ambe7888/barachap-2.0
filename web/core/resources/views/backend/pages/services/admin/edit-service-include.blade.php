<div class="tab-pane fade step" id="location" role="tabpanel" aria-labelledby="location-tab">
    <div class="row">
        <div class="col-12">

            <div class="single-settings">
                <h5 class="input-title"> {{__('Whats Included This Package')}} </h5>
                <div class="append-additional-includes">
                    @foreach($service->includes as $include)
                        <div class="single-dashboard-input what-include-element">
                            <div class="single-info-input margin-top-20">
                                <label>{{ __('Title') }} <span class="text-danger">*</span> </label>
                                <input class="form-control" type="text" name="include_service_title[]" value="{{ $include->title }}" placeholder="{{__('Service title')}}">
                            </div>
                            <div class="single-info-input margin-top-20">
                                <label>{{ __('Description') }}</label>
                                <textarea class="form-control" name="include_service_description[]" cols="20" rows="5" placeholder="{{__('Description')}}">{{ $include->description }}</textarea>
                            </div>
                            <span class="btn btn-danger remove-include"><i class="las la-times"></i></span>
                        </div>
                    @endforeach
                </div>
                <div class="btn-wrapper margin-top-20">
                    <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_blue radius-5 add-what-includes"> {{__('Add More')}} </a>
                </div>
            </div>


            <div class="single-settings margin-top-40">
                <h5 class="input-title"> {{__('Whats Service Excludes')}} </h5>
                <div class="append-excludes">
                    @foreach($service->excludes as $exclude)
                    <div class="single-dashboard-input excludes">
                        <div class="single-info-input margin-top-20">
                            <input class="form-control" type="text" name="excludes_title[]" value="{{ $exclude->title }}" placeholder="{{__('Type Here')}}">
                        </div>
                        <div class="single-info-input margin-top-20">
                            <textarea class="form-control" name="excludes_description[]" cols="20" rows="5" placeholder="{{__('Description')}}">{{ $exclude->description }}</textarea>
                        </div>
                        <span class="btn btn-danger remove-excludes"><i class="las la-times"></i></span>
                    </div>
                    @endforeach
                </div>
                <div class="btn-wrapper margin-top-20">
                    <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_blue radius-5 add-excludes"> {{__('Add More')}} </a>
                </div>
            </div>



            <div class="single-settings margin-top-40">
                <h5 class="input-title"> {{__('Add Addons Services')}} </h5>
                <div class="append-addons-services">
                    @foreach($service->addons as $addon)
                        <div class="single-dashboard-input addons-services">
                            <div class="single-info-input margin-top-20">
                                <label>{{ __('Title') }}</label>
                                <input class="form-control" type="text" name="addons_service_title[]" value="{{ $addon->title }}" placeholder="{{__('Addon Service title')}}">
                            </div>
                            <div class="single-info-input margin-top-20">
                                <label>{{ __('Price') }}</label>
                                <input class="form-control numeric-value" type="number" step="0.01" name="addons_service_price[]" value="{{ $addon->price }}" placeholder="{{__('Add Price')}}">
                            </div>
                            <div class="single-info-input margin-top-20">
                                <textarea class="form-control" name="addons_service_description[]" cols="20" rows="5" placeholder="{{__('Description')}}">{{ $addon->description }}</textarea>
                            </div>
                            <span class="btn btn-danger remove-service"><i class="las la-times"></i></span>
                        </div>
                    @endforeach
                </div>
                <div class="btn-wrapper margin-top-20">
                    <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_blue radius-5 add-addons-services"> {{__('Add More')}} </a>
                </div>
            </div>


            <div class="single-settings margin-top-40 faq_show_hide">
                <h5 class="input-title"> {{__('Faqs')}} </h5>
                <div class="append-faqs">
                    @foreach($service->faqs as $faq)
                        <div class="single-dashboard-input faqs">
                            <div class="single-info-input margin-top-20">
                                <input class="form-control" type="text" name="faqs_title[]" value="{{ $faq->title }}" placeholder="{{__('Faq Title')}}">
                            </div>
                            <div class="single-info-input margin-top-20">
                                <textarea class="form-control" name="faqs_description[]" cols="20" rows="5" placeholder="{{__('Faq Description')}}">{{ $faq->description }}</textarea>
                            </div>
                            <span class="btn btn-danger remove-faqs"><i class="las la-times"></i></span>
                        </div>
                    @endforeach
                </div>
                <div class="btn-wrapper margin-top-20">
                    <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_blue radius-5 add-faqs"> {{__('Add More')}} </a>
                </div>
            </div>

            <!-- start previous / next buttons -->
            <div  class="col-lg-12 mt-5">
                <div class="btn_wrapper d-flex justify-content-end gap-3">
                    <button class="cmnBtn btn_5 btn_bg_info radius-5" id="prevBtn" type="button">{{__('Previous')}}</button>
                    <button class="cmnBtn btn_5 btn_bg_success radius-5" id="submitBtn" type="submit">{{__('Submit')}}</button>
                </div>
            </div>

        </div>
    </div>
</div>
