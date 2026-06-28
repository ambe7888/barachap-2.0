<div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-4">
    <div class="customer__details__author__item padding-20 radius-10">
        <div class="customer__details__author__item__header">
            <div class="customer__details__author__item__header__flex">
                <div class="customer__details__author__item__header__left">
                    <h4 class="customer__details__author__item__title">{{ __('Staff Details') }}</h4>
                </div>
                <div class="customer__details__author__item__header__right">
                   
                </div>
            </div>
        </div>
        @if($suborder->staff)
            <div class="customer__details__author__item__inner border_top_1 top_15">
                <div class="customer__account__details">
                    <div class="customer__account__details__item">
                        <div class="customer__account__details__item__flex">
                            <strong>{{ __('Name:') }}</strong>
                            <span>{{ $suborder->staff?->first_name }}</span>
                        </div>
                    </div>
                    <div class="customer__account__details__item">
                        <div class="customer__account__details__item__flex">
                            <strong>{{ __('Email:') }}</strong>
                            <span>{{ $suborder->staff?->email }}</span>
                        </div>
                    </div>
                    <div class="customer__account__details__item">
                        <div class="customer__account__details__item__flex">
                            <strong>{{ __('Phone:') }}</strong>
                            <span>{{ $suborder->staff?->phone }}</span>
                           
                        </div>
                        
                    </div> 
                </div>
            </div>
        @endif    
    </div>
</div>
