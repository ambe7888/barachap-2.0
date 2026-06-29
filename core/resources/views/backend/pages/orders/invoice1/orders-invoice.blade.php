<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>AutoCare Invoice</title>
    <style>
        * {

            outline: none !important;
            box-shadow: none !important;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            margin: 0;
            padding: 0;
            background: white; /* no gray background */
            color: #333;
        }

        .invoice {
            width: 100%;
            padding-right: 2rem;
            padding-left: 1rem;
            border-radius: 0; /* remove rounded card look */
            margin: 0;
        }


        .header {

            padding-bottom: 1rem;
        }
        .logo_wrapper{
            display: inline-block;
            width: 65%;
            vertical-align: top;
            height: 50px;
            /*max-width: 300px;*/
        }
        .logo_wrapper img {
            max-height: 50px;
            width: auto;
        }
        .invoice-info{
            display: inline-block;
            width: 28%;
            vertical-align: top;
        }


        .section {
            margin-top: 1.25rem;
        }

        .section h3 {
            margin-bottom: 0.5rem;
            font-size: 1rem;
            padding-bottom: 0.25rem;
        }

        .details p {
            margin: 0.2rem 0;
            font-size: 0.95rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.75rem;
            font-size: 0.95rem;

        }
        thead tr {
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
            text-align: left;
            background-color: #f2f2f2;
        }
        thead tr th {
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
            text-align: left;
            font-size: 16px;
            padding-left: 10px;
        }

        th, td {
            text-align: left;
            padding: 0.6rem 0.75rem;
            width:25% !important;

        }



        .addons {
            font-size: 0.9rem;
            color: #555;
        }

        .location {
            font-size: 0.85rem;
            color: #888;
        }

        .bill-ship-container {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
        }

        .bill-ship-container > div {
            flex: 1;
        }
        .w_70{
            display: inline-block;
            width: 65%;
            vertical-align: top;
        }
        .w_30{
            display: inline-block;
            width: 25%;
            vertical-align: top;
        }

    </style>
</head>
<body>
<div class="invoice">
    <div class="header">
        <div class="logo_wrapper">
            <img src="{{$site_logo}}" alt="Logo">
        </div>
        <div class="invoice-info">
            <div><h3>{{ get_static_option('invoice_title') ?? __('INVOICE') }}</h3></div>
            <div><strong>{{ __('Invoice #') }}</strong> {{ $order_details->invoice_number }}</div>
        </div>
    </div>

    <div class="section details bill-ship-container">
        <div class="w_70">
            <h3>{{__('Bill To')}}</h3>
            <p><strong>{{ __('Name:') }} </strong> {{ get_static_option('site_title') }}</p>
            <p><strong>{{ __('Phone:') }}</strong>  {{  get_static_option('site_phone') }}</p>
            <p><strong>{{ __('Email:') }} </strong> {{ get_static_option('site_email') }}</p>
            <p><strong>{{ __('Address:') }}</strong>  {{ get_static_option('site_address') }}</p>
        </div>
        <div class="w_30">
            @php
                $subOrder = $order_details?->subOrders?->first();
                $location = $subOrder?->subOrderLocations;
            @endphp
            <h3>{{__('Ship To')}}</h3>
            <p><strong>{{__('Name:')}}</strong> {{ $order_details?->client?->fullname ?? __('N/A') }}</p>
            <p><strong>{{__('Phone:')}}</strong> {{ $location?->phone ?? __('N/A') }}</p>
            <p><strong>{{ __('Emergency Phone') }}:</strong> {{ $location?->emergency_phone ?? __('N/A') }}</p>
            <p><strong>{{ __('Address Type') }}: </strong>
                @if($location?->type == 0)
                    {{ __('Home') }}
                @else
                    {{ __('Office') }}
                @endif
            </p>
            <p><strong>{{ __('City') }}: </strong>{{ $location?->city?->city ?? __('N/A') }}</p>
            <p><strong>{{ __('Area') }}: </strong>{{ $location?->area?->area ?? __('N/A') }}</p>
            <p><strong>{{ __('Postcode') }}: </strong>{{ $location?->postcode?->postcode ?? __('N/A') }}</p>
            <p><strong>{{ __('Address') }}: </strong>{{ $location?->address ?? __('N/A') }}</p>
        </div>
    </div>

    <div class="section">
        <h3>{{ __('Order Details') }}</h3>
        <table>
            <thead>
            <tr>
                <th>{{ __('Order ID') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Payment Info') }}</th>
                <th>{{ __('Amount Details') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $order_details->id }}</td>
                <td>
                    @if ($order_details->status == 0) {{ __('Pending') }} @endif
                    @if ($order_details->status == 1) {{ __('Active') }} @endif
                    @if ($order_details->status == 2) {{ __('Completed') }} @endif
                    @if ($order_details->status == 3) {{ __('Delivered') }} @endif
                    @if ($order_details->status == 4) {{ __('Cancelled') }} @endif
                </td>
                <td>
                    {{ __('Gateway:') }} {{ ucwords(str_replace("_", " ", $order_details->payment_gateway)) }} <br>
                    {{ __('Status:') }} {{ ucfirst($order_details->payment_status) }}
                </td>
                <td>
                    {{ __('Sub Total:') }} {{ float_amount_with_currency_symbol($order_details->sub_total) }} <br>
                    @if(!empty($order_details->coupon_amount))
                        {{ __('Coupon Amount:') }} -{{ float_amount_with_currency_symbol($order_details->coupon_amount) }} <br>
                    @endif
                    {{ __('Tax:') }} +{{ float_amount_with_currency_symbol($order_details->tax) }} <br>
                    {{ __('Total:') }} {{ float_amount_with_currency_symbol($order_details->total) }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>{{ __('Order Items Summary') }}</h3>
        <table>
            <thead>
            <tr>
                <th>{{__('Service')}}</th>
                <th>{{__('Location')}}</th>
                <th>{{__('Date & Schedule')}}</th>
                <th>{{__('Amount')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order_details->subOrders as $subOrder)
                <tr>
                    <td>{{ $subOrder->service?->title ?? __('N/A') }}</td>
                    <td>
                        @if($subOrder->subOrderLocations)
                            {{ $subOrder->subOrderLocations?->address ?? __('N/A') }}
                        @else
                            {{ __('N/A') }}
                        @endif
                    </td>
                    <td>
                        {{ __('Date:') }} {{__($subOrder->date)}} <br>
                        {{ __('Schedule:') }} {{__($subOrder->schedule) }}
                    </td>
                    <td>
                        {{ __('Sub Total:') }} {{ float_amount_with_currency_symbol($subOrder->sub_total) }} <br>
                        @if(!empty($subOrder->coupon_amount))
                            {{ __('Coupon Amount:') }} -{{ float_amount_with_currency_symbol($subOrder->coupon_amount) }} <br>
                        @endif
                        {{ __('Tax:') }} +{{ float_amount_with_currency_symbol($subOrder->tax) }} <br>
                        {{ __('Total:') }} {{ float_amount_with_currency_symbol($subOrder->total) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @php
        $hasAddons = false;
        foreach($order_details->subOrders as $subOrder) {
            if (!empty($subOrder->subOrderAddons) && count($subOrder->subOrderAddons) > 0) {
                $hasAddons = true;
                break;
            }
        }
    @endphp

    @if($hasAddons)
        <div class="section">
            <h3>{{ __('Sub Order Addons Details') }}</h3>
            <table>
                <thead>
                <tr>

                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Qty') }}</th>
                    <th>{{ __('Total') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order_details->subOrders as $subOrder)
                    @foreach($subOrder->subOrderAddons as $sub_order_addon_data)
                        <tr>

                            <td>{{ $sub_order_addon_data->title }}</td>
                            <td>{{ float_amount_with_currency_symbol($sub_order_addon_data->price) }}</td>
                            <td>{{ $sub_order_addon_data->quantity }}</td>
                            <td>{{ float_amount_with_currency_symbol($sub_order_addon_data->total) }}</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="section">
        <h3>{{ __('Note') }}</h3>
        <p>{{ get_static_option('order_invoice_notes') ?? __('Thank you for choosing AutoCare Services. Please contact us for any questions regarding this invoice.') }}</p>
    </div>
</div>
</body>
</html>
