<table class="dataTablesExample table-responsive">
    <thead>
    <th>{{__('ID')}}</th>
    <th>{{__('Order ID')}}</th>
    <th>{{__('Sub Order ID')}}</th>
    <th>{{__('Admin/Provider Info')}}</th>
    <th>{{__('Refunded Amount')}}</th>
    <th>{{__('Action')}}</th>

    </thead>
    <tbody>
    @foreach($refundedOrders as $data)
        <tr>
            <td>{{$data->id}}</td>
            <td>{{$data->order_id}}</td>
            <td>{{$data->sub_order_id}}</td>
            <td>
                <div class="table_customer">
                    <div class="table_customer__flex">
                        <div class="table_customer__contents">
                            @if($data->subOrder?->provider_id)
                                <h6 class="table_customer__title">{{ __('Name:') }} {{ $data->subOrder?->provider?->first_name }} </h6>
                                <h6 class="table_customer__title">{{ __('Email:') }} {{ $data->subOrder?->provider?->email }}</h6>
                            @else
                                <h6 class="table_customer__title">{{ __('Name:') }} {{ $data->subOrder?->admin?->name }} </h6>
                                <h6 class="table_customer__title">{{ __('Email:') }} {{ $data->subOrder?->admin?->email }}</h6>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="table_customer">
                    <div class="table_customer__flex">
                        <div class="table_customer__contents">
                            <h6 class="table_customer__title">{{ __('Refunded Amount:') }} {{float_amount_with_currency_symbol($data->amount) }} </h6>
                        </div>
                    </div>
                </div>
            </td>
 
            <td>
                <div class="d-flex">

                    <a href="{{ route('client.refunded-order.details', $data->id) }}" class="cmnBtn btn_5 btn_bg_info radius-5 me-2">
                        {{ __('View') }}
                    </a>
                    <a href="{{ route('client.order.details', $data->order_id) }}" class="cmnBtn btn_5 btn_bg_info radius-5">
                        {{ __('Order Info') }}
                    </a>
                </div>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    {{ $refundedOrders->links() }}
</div>
