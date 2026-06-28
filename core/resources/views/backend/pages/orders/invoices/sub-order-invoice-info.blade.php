<div class="item-description" style="margin-top: 90px!important;">
    <div class="table-responsive">
        <h5 class="table-title">{{ __('Sub Orders Details') }}</h5>
        <table class="custom--table">
            <thead class="head-bg">
            <tr>
                <th>{{ __('ID & Status') }}</th>
                <th>{{ __('Date & Schedule') }}</th>
                <th>{{ __('Payment') }}</th>
                <th>{{ __('Amount Details') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order_details->subOrders as $subOrder)
                <tr>
                    <td width="100px">
                        <span class="data-span"> {{ $subOrder->id }}</span>
                        <br>
                       <span style="margin-top: 5px">
                            @if ($subOrder->status == 0)  <span>{{ __('Pending') }}</span>@endif
                           @if ($subOrder->status == 1) <span> {{ __('Active') }}</span>@endif
                           @if ($subOrder->status == 2) <span> {{ __('Completed') }}</span>@endif
                           @if ($subOrder->status == 3) <span> {{ __('Delivered') }}</span>@endif
                           @if ($subOrder->status == 4) <span> {{ __('Cancelled') }}</span>@endif
                       </span>
                    </td>
                    <td width="120px">
                        <span>
                            {{ $subOrder->date }}
                            <br>
                            {{ $subOrder->schedule }}
                        </span>
                    </td>
                    <td width="180px">
                        <span class="data-span"> {{ __('Gateway:') }} </span>
                        {{ __(ucwords(str_replace("_", " ", $order_details->payment_gateway))) }} <br>
                        <span class="data-span"> {{ __('Status:') }} </span>
                        {{ __(ucfirst($subOrder->payment_status)) }}
                    </td>
                    <td>
                        <span class="data-span"> {{ __('Basic Price:') }} </span>
                        {{ float_amount_with_currency_symbol($subOrder->basic_price) }}
                        @if($subOrder?->subOrderAddons->isNotEmpty())
                            <br>
                            <span class="data-span"> {{ __('Addon Price:') }} </span>
                            <strong>+</strong>
                            {{ float_amount_with_currency_symbol($subOrder?->subOrderAddons->sum('total')) }}
                        @endif
                        <br>
                        <span class="data-span"> {{ __('Sub Total:') }} </span>
                        {{ float_amount_with_currency_symbol($subOrder->sub_total) }}
                        <br>

                        @if(!empty($subOrder->coupon_amount))
                            <span class="data-span"> {{ __('Coupon Amount:') }} </span>
                            <strong>-</strong> {{ float_amount_with_currency_symbol($subOrder->coupon_amount) }} <br>
                        @endif

                        <span class="data-span"> {{ __('Tax:') }} </span>  <strong>+</strong> {{ float_amount_with_currency_symbol($subOrder->tax) }} <br>
                        <span class="data-span"> {{ __('Total:') }} </span>{{ float_amount_with_currency_symbol($subOrder->total) }} <br>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
