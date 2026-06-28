<table class="dataTablesExample table-responsive">
    <thead>
    <th>{{__('ID')}}</th>
    <th>{{__('Order ID')}}</th>
    <th>{{__('Sub Order ID')}}</th>
    <th>{{__('Admin/Provider Info')}}</th>
    <th>{{__('Client Info')}}</th>
    <th>{{__('Refunded Amount')}}</th>
    <th>{{__('Refunded Order Status')}}</th>
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
                            <h6 class="table_customer__title">{{ __('Name:') }} {{ $data->user?->first_name }} </h6>
                            <h6 class="table_customer__title">{{ __('Email:') }} {{ $data->user?->email }}</h6>
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
                 <div class="d-flex" id="refunded-order-status">
                    <span class="me-2"><x-status.refunded-order-status :status="$data->status"/></span>
                    <span >
                       @if($data->status == 0)
                            <button type="button" class="cmnBtn btn_5 btn_bg_warning
                            btnIcon radius-5 refunded_order_status_change_modal"
                                    data-order_id="{{$data->id}}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#RefundedOrderStatusChangeModal">
                                <i class="las la-pen"></i>
                            </button>
                       @endif     
                    </span>
               </div>
            </td>
            <td>
                <div class="d-flex">

                    <a href="{{ route('provider.refunded-order.details', $data->id) }}" class="cmnBtn btn_5 btn_bg_info radius-5 me-2">
                        {{ __('View') }}
                    </a>
                    <a href="{{ route('provider.order.details', $data->order_id) }}" class="cmnBtn btn_5 btn_bg_info radius-5">
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
