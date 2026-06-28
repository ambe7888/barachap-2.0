@extends('frontend.frontend.client.client-master')
@section('site_title', __('Payment Cancel'))
@section('content')
    <div class="d-flex justify-content-center min-vh-100 section-bg-2 w-100">
        <div class="card shadow-lg w-100" style="max-width: 600px; max-height: 400px; margin-top: 189px;">
            <div class="card-header bg-danger text-white text-center">
                <h4 class="mb-0">{{ __('Oops!') }}</h4>
            </div>
            <div class="card-body text-center">
                <p class="card-text">{{ __('Your payment has been') }} <strong>{{ __('cancelled') }}</strong>.</p>
                <hr>
                <div class="d-flex justify-content-center">
                    <a href="{{ route('client.frontend.dashboard') }}" class="btn btn-secondary">
                        {{ __('Back to Dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
