<div class="invoice-details-flex">
    <div class="invoice-single-details" style="float:left;">
        <h4 class="invoice-details-title">{{ get_static_option('bill_to_title') ?? __('Bill To Title:') }}</h4>
        <ul class="details-list">
            <li class="list"> {{ __('Name:') }} {{ get_static_option('site_title') }} </li>
            <li class="list"> <a href="#"> {{ __('Email:') }} {{ get_static_option('site_email') }} </a> </li>
            <li class="list"> <a href="#"> {{ __('Phone:') }} {{  get_static_option('site_phone') }}</a> </li>
            <li class="list" style="word-break: break-word">
                <a href="#"> {{ __('Address:') }} {{ get_static_option('site_address') }}</a>
            </li>
        </ul>
    </div>
    <div class="invoice-single-details" style="float:right">
        <h4 class="invoice-details-title">{{ get_static_option('ship_to_title') ?? __('Ship To Title:') }}</h4>
        <ul class="details-list">
        @php
            $location = $order_details?->subOrders->first()?->subOrderLocations?->first();
        @endphp
            <li class="list"> <strong>{{ __('Name') }}: </strong> {{ $order_details?->client?->fullname ?? __('N/A') }} </li>
            <li class="list"> <strong>{{ __('Phone') }}: </strong> {{ $location->phone ?? __('N/A') }} </li>
            <li class="list"> <strong>{{ __('Emergency Phone') }}: </strong> {{ $location->emergency_phone ?? __('N/A') }} </li>
            <li class="list"> <strong>{{ __('Address Type') }}: </strong>
                @if($location->type == 0)
                    {{ __('Home') }}
                @else
                    {{ __('Office') }}
                @endif
            </li>
            <li class="list"> <strong>{{ __('City') }}: </strong> {{ $location->city?->city ?? __('N/A') }} </li>
            <li class="list"> <strong>{{ __('Area') }}: </strong> {{ $location->area?->area ?? __('N/A') }} </li>
            <li class="list"> <strong>{{ __('Post Code') }}: </strong> {{ $location->post_code ?? __('N/A') }} </li>
            <li class="list" style="word-break: break-word">
                <strong>{{ __('Address') }}: </strong> {{ $location->address ?? __('N/A') }}
            </li>
        </ul>
    </div>
</div>

<div class="item-description" style="margin-top: 220px!important;">
    <div class="table-responsive">
        <h5 class="table-title">{{ __('Orders Details') }}</h5>
        <table class="custom--table">
            <thead class="head-bg">
            <tr>
                <th>{{ __('Order ID') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Payment Info') }}</th>
                <th>{{ __('Amount Details') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="100px">
                    <span class="data-span"> {{ $order_details->id }}</span>
                </td>
                <td width="100px">
                    @if ($order_details->status == 0)  <span>{{ __('Pending') }}</span>@endif
                    @if ($order_details->status == 1) <span> {{ __('Active') }}</span>@endif
                    @if ($order_details->status == 2) <span> {{ __('Completed') }}</span>@endif
                    @if ($order_details->status == 3) <span> {{ __('Delivered') }}</span>@endif
                    @if ($order_details->status == 4) <span> {{ __('Cancelled') }}</span>@endif
                </td>
                <td width="200px">
                    <span class="data-span"> {{ __('Gateway:') }} </span>
                    {{ __(ucwords(str_replace("_", " ", $order_details->payment_gateway))) }} <br>
                    <span class="data-span"> {{ __('Status:') }} </span>
                    {{ __(ucfirst($order_details->payment_status)) }}
                </td>
                <td>
                    <span class="data-span"> {{ __('Basic Price:') }} </span>
                    {{ float_amount_with_currency_symbol(optional($order_details->subOrders)->sum('basic_price')) }}

                    @if($order_details->subOrders->isNotEmpty() && $order_details->subOrders->some(fn($subOrder) => $subOrder->subOrderAddons->isNotEmpty()))
                        <br>
                        <span class="data-span"> {{ __('Addon Price:') }} </span>
                        <strong>+</strong>
                        {{ float_amount_with_currency_symbol($order_details->subOrders->sum(function($subOrder) {
                            return optional($subOrder->subOrderAddons)->sum('total');
                        })) }}
                    @endif

                    <br>
                    <span class="data-span"> {{ __('Sub Total:') }} </span>
                    {{ float_amount_with_currency_symbol($order_details->sub_total) }}
                    <br>

                    @if(!empty($order_details->coupon_amount))
                        <span class="data-span"> {{ __('Coupon Amount:') }} </span>
                        <strong>-</strong> {{ float_amount_with_currency_symbol($order_details->coupon_amount) }} <br>
                    @endif

                    <span class="data-span"> {{ __('Tax:') }} </span>  <strong>+</strong> {{ float_amount_with_currency_symbol($order_details->tax) }} <br>
                    <span class="data-span"> {{ __('Total:') }} </span>{{ float_amount_with_currency_symbol($order_details->total) }} <br>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
