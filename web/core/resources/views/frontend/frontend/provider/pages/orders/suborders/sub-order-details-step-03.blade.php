<div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-4">
    @if($suborder->provider)
        <div class="customer__details__author__item padding-20 radius-10">
            <div class="customer__details__author__item__header">
                <div class="customer__details__author__item__header__flex">
                    <div class="customer__details__author__item__header__left">
                        <h4 class="customer__details__author__item__title">{{ __('Provider Details') }}</h4>
                    </div>
                </div>
            </div>
            <div class="customer__details__author__item__inner border_top_1 top_15">
                <div class="customer__account__details">
                    <div class="customer__account__details__item">
                        <div class="customer__account__details__item__flex">
                            <strong>{{ __('Name:') }}</strong>
                            <span>{{ $suborder->provider?->first_name }}</span>
                        </div>
                    </div>
                    <div class="customer__account__details__item">
                        <div class="customer__account__details__item__flex">
                            <strong>{{ __('Email:') }}</strong>
                            <span>{{ $suborder->provider?->email }}</span>
                        </div>
                    </div>
                    <div class="customer__account__details__item">
                        <div class="customer__account__details__item__flex">
                            <strong>{{ __('Phone:') }}</strong>
                            <span>{{ $suborder->provider?->phone }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($suborder->admin)
        <div class="customer__details__author__item padding-20 radius-10">
            <div class="customer__details__author__item__header">
                <div class="customer__details__author__item__header__flex">
                    <div class="customer__details__author__item__header__left">
                        <h4 class="customer__details__author__item__title">{{ __('Admin Details') }}</h4>
                    </div>
                </div>
            </div>
            <div class="customer__details__author__item__inner border_top_1 top_15">
                <div class="customer__account__details">
                    <div class="customer__account__details__item">
                        <div class="customer__account__details__item__flex">
                            <strong>{{ __('Name:') }}</strong>
                            <span>{{ $suborder->admin?->name }}</span>
                        </div>
                    </div>
                    <div class="customer__account__details__item">
                        <div class="customer__account__details__item__flex">
                            <strong>{{ __('Email:') }}</strong>
                            <span>{{ $suborder->admin?->email }}</span>
                        </div>
                    </div>
                    <div class="customer__account__details__item">
                        <div class="customer__account__details__item__flex">
                            <strong>{{ __('Phone:') }}</strong>
                            <span>{{ $suborder->admin?->phone }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
