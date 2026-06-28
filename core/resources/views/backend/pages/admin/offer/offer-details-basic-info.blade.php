<div class="product__details__single">
    <div class="editProduct">
        <div class="row g-4">
            <div class="col-8">
                <div class="editProduct__contents__category mb-1">
                    <strong class="editProduct__contents__sku__para">{{ __('Thumb Image:') }}</strong>
                </div>
                <div class="editProduct__thumb">
                    <div class="editProduct__thumb__main">
                        {!! render_image_markup_by_attachment_id($offer->image, '', 'thumb') !!}
                    </div>
                </div>
                

                <div class="customer__details__author__item__header mt-3">
                    <div class="customer__details__author__item__header__flex">
                        <div class="customer__details__author__item__header__left">
                            <h4 class="customer__details__author__item__title">
                               
                                    {{ __('Offer Info:') }}
                                
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="customer__details__author__item__inner border_top_1 top_15">
                    <div class="customer__account__details">
                        
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <div class="customer__details__author__thumb">
                                    </div>
                                </div>
                            </div>
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Title') }}</strong>
                                   
                                    <span>{{ $offer->title}}</span>
                                   
                                </div>
                            </div>
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Subtitle') }}</strong>
                                    <span>{{ $offer->subTitle}}</span>
                                </div>
                            </div>
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Expires At') }}</strong>
                                    <span>{{ $offer->expires_at}}</span>
                                </div>
                            </div>
                            <div class="editProduct__contents__brand mt-3">
                                <span class="editProduct__contents__sku__para"><strong>{{ __('Status:') }}</strong>
                                    @if($offer->status==1)
                                        <span class="status_btn completed">{{__('Active')}}</span>
                                    @else
                                        <span class="status_btn cancelled">{{__('Inactive')}}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="editProduct__contents__brand mt-3">
                                <span class="editProduct__contents__sku__para"><strong>{{ __('Primary Offer:') }}</strong>
                                    @if($offer->is_primary=='1')
                                        <span class="status_btn completed">{{__('Yes')}}</span>
                                    @else
                                        <span class="status_btn cancelled">{{__('No')}}</span>
                                    @endif
                                </span>
                            </div>
                    </div>
                </div>
            </div>

           

        </div>
    </div>
</div>
