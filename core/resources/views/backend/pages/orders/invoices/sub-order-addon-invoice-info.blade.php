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
   <div class="item-description" style="margin-top: 50px!important;">
    <div class="table-responsive">
        <h5 class="table-title">{{ __('Sub Order Addons Details') }}</h5>
        <table class="custom--table">
            <thead class="head-bg">
            <tr>
                <th>{{ __('SubOrder Addon ID') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Qty') }}</th>
                <th>{{ __('Total') }}</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($order_details->subOrders))
                @foreach($order_details->subOrders as $subOrder)
                    @if(!empty($subOrder->subOrderAddons))
                       @foreach($subOrder->subOrderAddons as $sub_order_addon_data)
                           <tr>
                               <td width="200px">
                                   <span class="data-span"> {{ $sub_order_addon_data->id }}</span>
                               </td>
                               <td width="200px">
                                   <span class="data-span"> {{ $sub_order_addon_data->title }}</span>
                               </td>
                               <td>
                                   <span class="data-span"> {{ float_amount_with_currency_symbol($sub_order_addon_data->price) }}</span>
                               </td>
                               <td>
                                   <span class="data-span"> {{ $sub_order_addon_data->quantity }}</span>
                               </td>
                               <td>
                                   {{ float_amount_with_currency_symbol($sub_order_addon_data->total) }}<br>
                               </td>
                           </tr>
                       @endforeach
                    @endif
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
@endif
