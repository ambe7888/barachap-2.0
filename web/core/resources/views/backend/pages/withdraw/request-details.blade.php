@extends('backend.admin-master')
@section('title', __('Withdraw Requests Details'))
@section('style')
    <style>
        .edit_gateway_modal {
            display: block;
            margin-top: 10px;
        }
        .seller-img img {
            width: 100px;
            height: auto;
            object-fit: cover;
        }
    </style>
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>{{ __('Withdraw Requests Details') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Provider Info -->
                            <div class="col-xxl-4 col-lg-4">
                                <h5 class="mb-3">{{ __('Provider Info:') }}</h5>
                                <div class="border-top pt-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="seller-img me-3">
                                            {!! render_image_markup_by_attachment_id($withdraw_request->user?->image, '', 'thumb') !!}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.provider.details.page', optional($withdraw_request->user)->id) }}" target="_blank">
                                                <strong>{{ optional($withdraw_request->user)->fullname }}</strong>
                                            </a>
                                            <p>{{ optional($withdraw_request->user)->email }}</p>
                                            <p>{{ optional($withdraw_request->user)->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Withdraw Info -->
                            <div class="col-xxl-4 col-lg-4">
                                <h5 class="mb-3">{{ __('Withdraw Info:') }}</h5>
                                <div class="border-top pt-3">
                                    <p><strong>{{ __('Amount:') }}</strong> {{ float_amount_with_currency_symbol($withdraw_request->amount) }}</p>
                                    <p><strong>{{ __('Fee:') }}</strong> {{ float_amount_with_currency_symbol($withdraw_request->fee) }}</p>
                                    <p><strong>{{ __('Gateway Name:') }}</strong> {{ ucfirst($withdraw_request?->gateway_name?->name) }}</p>
                                    <p><strong>{{ __('Gateway Info:') }}</strong><br>
                                        @foreach (json_decode($withdraw_request->gateway_fields, true) ?? [] as $key => $value)
                                            <span>{{ ucwords(str_replace('_', ' ', $key)) }}: {{ $value }}</span><br>
                                        @endforeach
                                    </p>
                                </div>
                            </div>

                            <!-- Balance Info -->
                            <div class="col-xxl-4 col-lg-4">
                                <h5 class="mb-3">{{ __('Balance Info:') }}</h5>
                                <div class="border-top pt-3">
                                    <p><strong class="text-info">{{ __('Available Balance:') }}</strong> {{ float_amount_with_currency_symbol($withdraw_request?->user?->balance?->available_balance) }}</p>
                                    <p><strong class="text-primary">{{ __('Total Earnings:') }}</strong> {{ float_amount_with_currency_symbol($withdraw_request?->user?->balance?->total_earnings) }}</p>
                                    <p><strong class="text-success">{{ __('Total Withdrawn:') }}</strong> {{ float_amount_with_currency_symbol($withdraw_request?->user?->balance?->total_withdrawn) }}</p>
                                </div>

                                <div class="mt-3">
                                    @if($withdraw_request->image)
                                        <p><strong>{{ __('Image:') }}</strong></p>
                                        <img class="img-fluid" src="{{ asset('assets/uploads/withdraw-request/'.$withdraw_request->image) }}" alt="Withdraw Image">
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <p><strong>{{ __('Status:') }}</strong></p>
                                    <x-status.table.withdraw-request-status :status="$withdraw_request->status" />
                                </div>

                                <div class="mt-3">
                                    <p><strong>{{ __('Note:') }}</strong></p>
                                    <p>{!! $withdraw_request->note !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
