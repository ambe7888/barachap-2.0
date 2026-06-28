@extends('frontend.frontend.client.client-master')
@section('site_title', __('Order Details'))
@section('content')
    <div class="d-flex justify-content-center min-vh-100 section-bg-2 w-100">
        <div class="card shadow-lg w-100" style="max-width: 600px;max-height:400px;margin-top: 189px;">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">{{ __('Success!') }}</h4>
            </div>
            <div class="card-body text-center">
                <p class="card-text">{{ __('You have successfully placed an order') }}</p>
                <hr>
                <table class="table table-borderless text-start mb-4">
                    <tbody>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <td>#000{{ $order_details->id }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Price') }}</th>
                            <td>{{ float_amount_with_currency_symbol($order_details->total) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Invoice Number') }}</th>
                            <td>#{{ $order_details->invoice_number }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    <a href="{{ route('client.order.details', $order_details->id) }}" class="btn btn-primary me-2">
                        {{ __('View Details') }}
                    </a>
                    <a href="{{ route('client.frontend.dashboard') }}" class="btn btn-secondary">
                        {{ __('Back to Home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
