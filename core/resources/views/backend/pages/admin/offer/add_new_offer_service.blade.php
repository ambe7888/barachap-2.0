
<div class="dashboard__card">
    <div class="dashboard__card__header p-2">
        <h4 class="dashboard__card__title">{{ __('Offer Service') }}</h4>

    </div>
    <div class="dashboard__card__body custom__form p-2">
        <div class="single-settings">

            <div class="append-additional-includes">
                <div class="single-dashboard-input what-include-element">
                    <div class="single-info-input margin-top-20">
                        <label class="form__input__single__label">{{ __('Service') }}  <span class="text-danger">*</span> </label>
                        <select name="offer_service_id[]" id="service" class="form-select" onchange="document.getElementById('original_price').value = this.options[this.selectedIndex].getAttribute('data-price') || ''">
                            <option value="">{{__('Select Service')}}</option>
                            @foreach($services as $service)
                                @if($service->status== 1 && $service->is_published == 1)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="single-info-input margin-top-20">
                        <label>{{ __('Original Price') }}</label>
                        <input type="number" class="form-control" id="original_price" readonly>
                    </div>
                    <div class="single-info-input margin-top-20">
                        <label>{{ __('Discount Price') }}</label>
                        <input type="number" class="form-control" name="offer_service_discount_price[]" placeholder="{{__('Discount_price')}}"></input>
                    </div>
                </div>
            </div>
            <div class="btn-wrapper margin-top-20">
                <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_blue radius-5 add-what-includes"> {{__('Add More')}} </a>
            </div>
        </div>

    </div>
</div>
