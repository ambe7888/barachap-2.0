@php
    $suborders = \App\Models\SubOrder::where('order_id', $order_details->id)->get();
    $suborderIds = $suborders->pluck('id')->toArray();
    $suborder_addons = \App\Models\SubOrderAddon::whereIn('sub_order_id', $suborderIds)->get();

    $order_locations =  \App\Models\SubOrderLocation::with('state', 'city', 'area')->where('sub_order_id', $suborderIds)->get();
@endphp

<div class="inner-wrap-contents">
    <p class="wrap-para">{{ __('Hello, Order Created By:') }} {{ optional($order_details->client)->fullname }} <br>
        {{ __('Order has been created successfully at:') .' ' .optional($order_details->created_at)->toFormattedDateString().',  '. ucwords(str_replace("_", " ", $order_details->payment_gateway)) }}
    </p>
    <h4 class="earning-order-title">{{ __('Your Order ID') }} #{{ $order_details->id }}<br>
        {{ __('Sub Total Amount:') }} {{ float_amount_with_currency_symbol($order_details->sub_total) }}<br>
        {{ __('Tax Amount:') }} {{ float_amount_with_currency_symbol($order_details->tax) }} <br>
        {{ __('Total Amount:') }} {{ float_amount_with_currency_symbol($order_details->total) }}<br>
        {{ __('Invoice Number:') }} {{ $order_details->invoice_number }} <br>
       @if(!empty($order_details->transaction_id))
            {{ __('Your Transaction Id:') }} {{ $order_details->transaction_id }} <br>
        @endif
       <strong>{{ __('Order Create Date:') }} {{ optional($order_details->created_at)->toFormattedDateString() }} </strong>
    </h4>
</div>


@if($order_details->coupon_code != '')
    <h3 style="text-align: center; color: #111d5c; margin: 20px 0;">{{ __('Coupon Details') }}</h3>
    <div class="table-container" style="overflow-x: auto;">
        <table style="border-collapse: collapse; width: 100%; margin-bottom: 30px; border: 1px solid #ddd;">
            <thead>
            <tr style="background-color: #111d5c; color: #fff;">
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Coupon Code') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Coupon Type') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Coupon Amount') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $order_details->coupon_code }}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $order_details->coupon_type }}</td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">
                    @if($order_details->coupon_amount > 0)
                        {{ float_amount_with_currency_symbol($order_details->coupon_amount) }}
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endif

@if($suborders->count() >= 1)
    <h3 style="text-align: center; color: #111d5c; margin: 20px 0;"> @if($user_type == 0) {{ __('Order Details') }} @else {{ __('Sub Order Details') }} @endif </h3>
    <div style="overflow-x: auto;">
        <table style="border-collapse: collapse; width: 100%; margin-bottom: 30px; border: 1px solid #ddd;">
            <thead>
            <tr style="background-color: #111d5c; color: #fff;">
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Basic Price') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Sub Total') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Tax') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Total') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Commission Type') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Commission Charge') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Commission Amount') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Date') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Schedule') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($suborders as $suborder)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ float_amount_with_currency_symbol($suborder->basic_price) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ float_amount_with_currency_symbol($suborder->sub_total) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ float_amount_with_currency_symbol($suborder->tax) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ float_amount_with_currency_symbol($suborder->total) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $suborder->commission_type }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ float_amount_with_currency_symbol($suborder->commission_charge) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ float_amount_with_currency_symbol($suborder->commission_amount) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $suborder->date }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $suborder->schedule }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

@if($suborder_addons->count() >= 1)
    <h3 style="text-align: center; color: #111d5c; margin: 20px 0;">@if($user_type == 0) {{ __('Order Addons Details') }} @else {{ __('Sub Order Addons Details') }} @endif</h3>
    <div style="overflow-x: auto;">
        <table style="border-collapse: collapse; width: 100%; margin-bottom: 30px; border: 1px solid #ddd;">
            <thead>
            <tr style="background-color: #111d5c; color: #fff;">
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Sub Order Id') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Title') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Price') }}</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">{{ __('Quantity') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($suborder_addons as $suborder_addon)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $suborder_addon->sub_order_id }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $suborder_addon->title }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ float_amount_with_currency_symbol($suborder_addon->price) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;">{{ $suborder_addon->quantity }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

@if(!empty($order_locations))
    @if($order_locations->count() >= 1)
        @foreach($order_locations as $order_location)
        <div class="earning-wrapper">
            <h3 style="text-align: center; color: #111d5c; margin: 20px 0;">
                @if($user_type == 0) {{ __('Order Location Details') }} @else {{ __('Sub Order Location Details') }} @endif
            </h3>
            <hr>
            <p class="wrap-para"><strong>{{ __('State:') }}</strong> {{ optional($order_location->state)->state }}</p>
            <p class="wrap-para"><strong>{{ __('City:') }}</strong> {{ optional($order_location->city)->city }}</p>
            <p class="wrap-para"><strong>{{ __('Area:') }}</strong> {{ optional($order_location->area)->area }}</p>
            <p class="wrap-para"><strong>{{ __('Title:') }}</strong> {{ $order_location->title }}</p>
            <p class="wrap-para"><strong>{{ __('Post Code:') }}</strong> {{ $order_location->post_code }}</p>
            <p class="wrap-para"><strong>{{ __('Address:') }}</strong> {{ $order_location->address }}</p>
            <p class="wrap-para"><strong>{{ __('Phone:') }}</strong> {{ $order_location->phone }}</p>
            <p class="wrap-para"><strong>{{ __('Emergency Phone:') }}</strong> {{ $order_location->emergency_phone }}</p>
            <p class="wrap-para"><strong>{{ __('Location Type:') }}</strong>
                @if($order_location->type == 0)
                    {{ __('Home') }}
                @else
                   {{ __('Office') }}
                @endif
            </p>
        </div>
      @endforeach
   @endif
@endif
