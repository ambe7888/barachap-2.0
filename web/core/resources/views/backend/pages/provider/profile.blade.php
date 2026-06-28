@extends('backend.admin-master')
@section('site-title', __('Profile Details'))
@section('style')
    <style>
        img.no-image {
            height: 100px;
        }
        .seller-img.me-3 img {
            max-width: 100px!important;
        }
    </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white p-4 radius-10 shadow-sm">
                <h2 class="dashboard__card__header__title mb-4">{{ __('Profile Details') }}</h2>
                <div class="row">
                    <!-- Seller Details -->
                    <div class="col-xl-8 col-md-7">
                        <div class="seller-profile">
                            <div class="seller-profile__inner box-shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <div class="seller-img me-3">
                                        @if(!empty($user->image))
                                            {!! render_image_markup_by_attachment_id($user->image, 'thumb') !!}
                                        @else
                                            <img src="{{ asset('assets/frontend/img/static/user-no-image.webp') }}" alt="No Image"
                                                 class="no-image" width="100" height="100">
                                        @endif
                                    </div>

                                    <div class="seller-info">
                                        <h4 class="seller-name mb-1">{{ $user->fullname }}</h4>
                                        @if($user->type == 0 && $user->verified_status == 1)
                                            <span class="badge bg-success text-white">
                                                <!-- SVG for verified icon -->
                                                <svg width="10" height="10" fill="white" xmlns="http://www.w3.org/2000/svg" class="me-1">
                                                    <path d="M9.7775 4.37L9.0975 3.58C8.9675 3.43 8.8625 3.15 8.8625 2.95V2.1C8.8625 1.57 8.4275 1.135 7.8975 1.135H7.0475C6.8525 1.135 6.5675 1.03 6.4175 0.899999L5.6275 0.219999C5.2825 -0.0750012 4.7175 -0.0750012 4.3675 0.219999L3.5825 0.904999C3.4325 1.03 3.1475 1.135 2.9525 1.135H2.0875C1.5575 1.135 1.1225 1.57 1.1225 2.1V2.955C1.1225 3.15 1.0175 3.43 0.8925 3.58L0.2175 4.375C-0.0725 4.72 -0.0725 5.28 0.2175 5.625L0.8925 6.42C1.0175 6.57 1.1225 6.85 1.1225 7.045V7.9C1.1225 8.43 1.5575 8.865 2.0875 8.865H2.9525C3.1475 8.865 3.4325 8.97 3.5825 9.1L4.3725 9.78C4.7175 10.075 5.2825 10.075 5.6325 9.78L6.4225 9.1C6.5725 8.97 6.8525 8.865 7.0525 8.865H7.9025C8.4325 8.865 8.8675 8.43 8.8675 7.9V7.05C8.8675 6.855 8.9725 6.57 9.1025 6.42L9.7825 5.63C10.0725 5.285 10.0725 4.715 9.7775 4.37ZM7.0775 4.055L4.6625 6.47C4.5925 6.54 4.4975 6.58 4.3975 6.58C4.2975 6.58 4.2025 6.54 4.1325 6.47L2.9225 5.26C2.7775 5.115 2.7775 4.875 2.9225 4.73C3.0675 4.585 3.3075 4.585 3.4525 4.73L4.3975 5.675L6.5475 3.525C6.6925 3.38 6.9325 3.38 7.0775 3.525C7.2225 3.67 7.2225 3.91 7.0775 4.055Z"/>
                                                </svg>
                                                {{ __('VERIFIED') }}
                                            </span>
                                        @endif

                                        <span class="form__input__single__label"> <strong> {{ __('Email:') }} </strong> {{ $user->email }} </span> <br>
                                        @if($user->phone)
                                        <span class="form__input__single__label"> <strong> {{ __('Phone:') }} </strong> {{ $user->phone }} </span>
                                        @endif

                                        <p class="mt-2 mb-1">
                                         @if(!empty($user->services) && $user->type == 0)
                                             {{ __('Total Services') }}    <strong>{{ $user->services->count() ?? 0 }}</strong>  <br>
                                            @endif

                                             @if($user->type == 1 && !empty($user->jobs))
                                            {{ __('Total Jobs') }}  <strong>{{ $user->jobs->count() ?? 0 }}</strong> <br>
                                            @endif

                                            @if(!empty($user->subOrders) && $user->type == 0)
                                                {{ __('Total Orders') }}    <strong>{{ $user->subOrders->count() ?? 0 }}</strong>  <br>
                                            @endif

                                            <span>{{ __('Member since') }} {{ optional($user->created_at)->format('Y') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End Seller Details Column -->

                    <!-- Balance Info -->
                    @if($user->type == 0 && !empty($user->balance))
                    <div class="col-xl-4 col-lg-5">
                        <div class="balance-info box-shadow-sm p-4">
                            <h5 class="mb-3">{{ __('Balance Info') }}</h5>
                            <div class="border-top pt-3">
                                <p><strong class="text-info">{{ __('Available Balance:') }}</strong>
                                    {{ float_amount_with_currency_symbol($user?->balance?->available_balance ?? 0) }}</p>
                                <p><strong class="text-primary">{{ __('Total Earnings:') }}</strong>
                                    {{ float_amount_with_currency_symbol($user?->balance?->total_earnings ?? 0) }}</p>
                                <p><strong class="text-success">{{ __('Total Withdrawn:') }}</strong>
                                    {{ float_amount_with_currency_symbol($user?->balance?->total_withdrawn ?? 0) }}</p>
                            </div>
                        </div>
                    </div> <!-- End Balance Info Column -->
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
