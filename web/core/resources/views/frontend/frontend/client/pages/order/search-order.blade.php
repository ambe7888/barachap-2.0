<table class="dataTablesExample">
    <thead>
    <th>{{__('ID')}}</th>
    <th>{{__('Invoice Number')}}</th>
    <th>{{__('Amount Details')}}</th>
    <th>{{__('Staff')}}</th>
    <th>{{__('Payment Gateway')}}</th>
    <th>{{__('Payment Status')}}</th>
    <th>{{__('Order Status')}}</th>
    <th>{{__('Create Date')}}</th>
    <th>{{__('Action')}}</th>
    </thead>
    <tbody>
    @foreach($all_orders as $data)
        <tr>
            <td>{{$data->id}}</td>
           
            <td>{{ $data->invoice_number }}</td>
            <td>
                <div class="table_customer">
                    <div class="table_customer__flex">
                        <div class="table_customer__contents">
                            <h6 class="table_customer__title">{{ __('Sub Total:') }} {{float_amount_with_currency_symbol($data->sub_total) }} </h6>
                            @if($data->coupon_amount > 0)
                                <h6 class="table_customer__title"> {{ __('Coupon Amount:') }}  <strong>-</strong> {{ float_amount_with_currency_symbol($data->coupon_amount) }} </h6>
                            @endif
                            <h6 class="table_customer__title">{{ __('Tax:') }}  <strong>+</strong> {{ float_amount_with_currency_symbol($data->tax) }}</h6>
                            <h6 class="table_customer__title">{{ __('Total:') }} {{ float_amount_with_currency_symbol($data->total) }}</h6>
                        </div>
                    </div>
                </div>
            </td>

            @php
                $subOrder = $data->subOrders->first();
            @endphp
            @if($subOrder && $subOrder->staff)
                <td>
                    <div class="table_customer">
                        <div class="table_customer__flex">
                            <div class="table_customer__thumb">
                                {!! render_image_markup_by_attachment_id($subOrder->staff->image) !!}
                            </div>
                            <div class="table_customer__contents">
                                <h6 class="table_customer__title">{{ $subOrder->staff->fullname}}</h6>
                            </div>
                        </div>
                    </div>
                </td>
            @else
                <td>
                    <div class="table_customer">
                        <div class="table_customer__flex">
                            <div class="table_customer__contents">
                                <h6 class="table_customer__title">{{ __('N/A') }}</h6>
                            </div>
                        </div>
                    </div>
                </td>
                
            @endif

            <td><span class="my-2">{{ $data->payment_gateway }}</span></td>
            <td>
                <span class="my-2"><x-status.payment-status :status="$data->payment_status"/></span>
            </td>
            <td>
                <span class="my-2">
                     <x-status.main-order-status :status="$data->status"/>
                </span>
            </td>
            <td> <strong class="subCap">{{ $data->created_at->diffForHumans() }}</strong></td>
            <td>
                 <x-icon.view-icon :url="route('client.order.details',$data->id)"/>

                <x-icon.file-icon :url="route('client.order.invoice.generate',$data->id)"/>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    {{ $all_orders->links() }}
</div>
