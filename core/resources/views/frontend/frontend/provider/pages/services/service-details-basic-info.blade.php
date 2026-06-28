<div class="product__details__single">
    <div class="editProduct">
        <div class="row g-4">
            <div class="col-xxl-4 col-lg-4">
                <div class="editProduct__contents__category mb-2">
                    <strong class="editProduct__contents__sku__para">{{ __('Thumb Image:') }}</strong>
                </div>
                <div class="editProduct__thumb">
                    <div class="editProduct__thumb__main">
                        {!! render_image_markup_by_attachment_id($service->image, '', 'thumb') !!}
                    </div>
                </div>
                <div class="editProduct__contents__category mt-3">
                    <strong class="editProduct__contents__sku__para">{{ __('Gallery Images:') }}</strong>
                </div>
                <div class="dashboard__rates__card__thumb">
                    {!! render_gallery_image_attachment_preview($service->gallery_images ?? '') !!}
                </div>


                <div class="customer__details__author__item__header mt-3">
                    <div class="customer__details__author__item__header__flex">
                        <div class="customer__details__author__item__header__left">
                            <h4 class="customer__details__author__item__title">
                                @if($service->provider_id != null && $service->provider_id != 0)
                                    {{ __('Provider Info:') }}
                                @else
                                    {{ __('Admin Info:') }}
                                @endif
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="customer__details__author__item__inner border_top_1 top_15">
                    <div class="customer__account__details">
                        @if($service->provider_id != null && $service->provider_id != 0)
                            <!-- User Info -->
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <div class="customer__details__author__thumb">
                                        <div class="seller-img">
                                            {!! render_image_markup_by_attachment_id($service->provider?->image, '', 'thumb') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Name') }}</strong>
                                    <a href="{{route('admin.provider.details.page', optional($service->provider)->username)}}" target="_blank">
                                        <span>{{ optional($service->provider)->fullname }}</span>
                                    </a>
                                </div>
                            </div>
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Email') }}</strong>
                                    <span>{{ optional($service->provider)->email }}</span>
                                </div>
                            </div>
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Phone') }}</strong>
                                    <span>{{ optional($service->provider)->phone }}</span>
                                </div>
                            </div>
                        @else
                            <!-- Admin Info -->
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong></strong>
                                    <a href="{{route('admin.provider.details.page', optional($service->admin)->username)}}" target="_blank">
                                        <div class="customer__details__author__thumb">
                                            {!! render_image_markup_by_attachment_id($service->admin->image, '', 'thumb') !!}
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Name') }}</strong>
                                    <a href="{{route('admin.provider.details.page', optional($service->admin)->username)}}" target="_blank">
                                        <span>{{ optional($service->admin)->name }}</span>
                                    </a>
                                </div>
                            </div>
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Email') }}</strong>
                                    <span>{{ optional($service->admin)->email }}</span>
                                </div>
                            </div>
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Phone') }}</strong>
                                    <span>{{ optional($service->admin)->phone }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!--step two -->
            <div class="col-xxl-8 col-lg-8">
                <div class="editProduct__contents">
                    <div class="editProduct__contents__category mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('Service Title:') }}</strong> {{ $service->title }}</span>
                    </div>
                    <div class="editProduct__contents__category mt-3">
                        <span class="editProduct__contents__sku__para">
                            <strong>{{ __('Price:') }}</strong>
                            @if($service->discount_price > 0)
                                <span class="discount-price">
                                    <del> {{ float_amount_with_currency_symbol($service->price) }} </del>
                               </span>
                            @else
                                {{ float_amount_with_currency_symbol($service->price) }}
                            @endif
                        </span>
                    </div>
                    @if($service->discount_price > 0)
                    <div class="editProduct__contents__category mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('Discount Price:') }}</strong>
                            {{ float_amount_with_currency_symbol($service->discount_price) }}
                        </span>
                    </div>
                    @endif
                    <div class="editProduct__contents__category mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('Category:') }}</strong> {{ optional($service->category)->name }}</span>
                    </div>
                    <div class="editProduct__contents__category mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('Sub Category:') }}</strong> {{ optional($service->sub_category)->name }}</span>
                    </div>
                    <div class="editProduct__contents__category mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('Child Category:') }}</strong> {{ optional($service->child_category)->name }}</span>
                    </div>
                    <div class="editProduct__contents__brand mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('View Count:') }}</strong> {{ $service->view }}</span>
                    </div>
                    <div class="editProduct__contents__brand mt-3">
                            <span class="editProduct__contents__sku__para"><strong>{{ __('Status:') }}</strong>
                                @if($service->status==1)
                                    <span class="status_btn completed">{{__('Approved')}}</span>
                                @else
                                    <span class="status_btn cancelled">{{__('Pending')}}</span>
                                @endif
                            </span>
                    </div>
                    <div class="editProduct__contents__category mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('Is Featured:') }}</strong></span>
                        <input class="effectBorder" type="checkbox" @if(!empty($service->is_featured)) checked @endif>
                        <span class="checkmark"></span>
                    </div>
                    <div class="editProduct__contents__brand mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('State:') }}</strong> {{ optional($service->state)->state }}</span>
                    </div>
                    <div class="editProduct__contents__brand mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('City:') }}</strong> {{ optional($service->city)->city }}</span>
                    </div>
                    <div class="editProduct__contents__brand mt-3">
                        <span class="editProduct__contents__sku__para">
                            <strong>{{ __('Video URL:') }}</strong>
                            @if(optional($service)->video_url)
                                <a href="https://www.youtube.com/watch?v={{ $service->video_url}}" target="_blank">
                                    https://www.youtube.com/watch?v={{ $service->video_url}}
                                </a>
                            @else
                                {{ __('No video available') }}
                            @endif
                        </span>
                    </div>
                    
                    <div class="product__details__description mt-3">
                        <span class="editProduct__contents__sku__para"><strong>{{ __('Description:') }}</strong></span>
                        <p class="product__details__para">{!! $service->description !!}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
