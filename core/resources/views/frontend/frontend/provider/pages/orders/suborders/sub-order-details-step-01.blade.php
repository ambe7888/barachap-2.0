<div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-4">
    <div class="customer__details__author__item padding-20 radius-10">
        <div class="customer__details__author__item__header">
            <div class="customer__details__author__item__header__flex">
                <div class="customer__details__author__item__header__left">

                    <h4 class="customer__details__author__item__title">{{ __('Details') }}</h4>
                </div>
            </div>
        </div>
        <div class="customer__details__author__item__inner border_top_1 top_15">
            <div class="customer__account__details">
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Title:') }}</strong>
                        <span>{{ $suborder->service?->title }}</span>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Status:') }}</strong>
                        <x-status.order-status :status="$suborder->status"/>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Payment Status:') }}</strong>
                        <span class="text-bold">{{ ucwords(str_replace("_", " ", $suborder->payment_status)) }}</span>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Date') }}</strong>
                        <span class="text-bold">{{  \Carbon\Carbon::parse($suborder->date)->format('d-m-Y') }}</span>
                    </div>
                </div>

                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Time') }}</strong>
                        <span class="text-bold">{{  $suborder->schedule }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

