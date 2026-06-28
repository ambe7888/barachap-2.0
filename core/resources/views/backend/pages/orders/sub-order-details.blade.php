<h4 class="dashboard__inner__item__header__title mt-4">{{ __('Sub Orders') }}</h4>
<!-- Table Design One -->
<div class="tableStyle_one mt-4">
    <div class="table_wrapper table-responsive">
        <!-- Table -->
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('Details') }}</th>
                <th>{{ __('Provider & Staff') }}</th>
                <th>{{ __('Date & Time') }}</th>
                <th>{{ __('Pricing Summary') }}</th>
                <th>{{ __('Commission Details') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->subOrders as $subOrder)
                <tr>
                    <td>
                        <div class="table_customer">
                            <div class="table_customer__flex">
                                <div class="table_customer__contents">
                                    <h6 class="table_customer__title">{{ __('Id:') }} {{ $subOrder->id }} </h6>
                                    <h6 class="table_customer__title">{{ __('Order Id:') }} {{ $subOrder->order_id }}</h6>
                                   <div class="mt-2">
                                       @if($subOrder->service)
                                       
                                           <a href="{{ route('admin.subservice.details', $subOrder->service?->id) }}">
                                               <h6 class="table_customer__title text-primary">{{ __('Service Info') }}</h6>
                                           </a>
                                       @endif
                                       @if($subOrder->job)
                                           <a href="{{ route('admin.jobs.details', $subOrder->job?->id) }}" class="ext-primary">
                                               <h6 class="table_customer__title text-primary"> {{ __('Job Info') }}</h6>
                                           </a>
                                       @endif
                                   </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <div class="table_customer provider_wrapper">
                                <strong>{{ __('Provider') }}</strong>
                                <div class="table_customer__flex">
                                    @if($subOrder->provider)
                                        <div class="table_customer__thumb">
                                           {!! render_image_markup_by_attachment_id($subOrder?->provider?->image) !!}
                                        </div>
                                        <div class="table_customer__contents">
                                           <h6 class="table_customer__title">{{ $subOrder?->provider?->fullname }}</h6>
                                        </div>
                                    @else
                                        <div class="table_customer__thumb">
                                           {!! render_image_markup_by_attachment_id($subOrder?->admin?->image) !!}
                                        </div>
                                        <div class="table_customer__contents">
                                           <h6 class="table_customer__title">{{ $subOrder?->admin?->name }}</h6>
                                        </div>
                                    @endif

                                </div>
                            </div>
                            <div class="table_customer">
                                <strong>{{ __('Staff') }}</strong>
                                <div class="table_customer__flex">
                                    <div class="table_customer__thumb">
                                        {!! render_image_markup_by_attachment_id($subOrder?->staff?->image) !!}
                                    </div>
                                    <div class="table_customer__contents">
                                        <h6 class="table_customer__title">{{ $subOrder?->staff?->fullname }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="table_customer">
                            <div class="table_customer__flex">
                                <div class="table_customer__contents">
                                    <h6 class="table_customer__title">{{ __('Date:') }} {{ \Carbon\Carbon::parse($subOrder->date)->format('d-m-Y') }} </h6>
                                    <h6 class="table_customer__title">{{ __('Schedule:') }} {{ $subOrder->schedule }}</h6>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="table_customer">
                            <div class="table_customer__flex">
                                <div class="table_customer__contents">
                                    <h6 class="table_customer__title">{{ __('Basic Price:') }} {{ float_amount_with_currency_symbol($subOrder->basic_price) }} </h6>

                                    @if($subOrder?->subOrderAddons->isNotEmpty())
                                        <h6 class="table_customer__title">
                                            {{ __('Addon Price:') }}
                                            <strong>+</strong> {{ float_amount_with_currency_symbol($subOrder?->subOrderAddons->sum('total')) }}
                                        </h6>
                                    @endif

                                    <h6 class="table_customer__title">
                                        {{ __('Sub Total:') }}
                                        {{ float_amount_with_currency_symbol($subOrder->sub_total) }}
                                    </h6>

                                    @if(!empty($subOrder->coupon_amount))
                                        <h6 class="table_customer__title">{{ __('Coupon Amount:') }} <strong>-</strong> {{ float_amount_with_currency_symbol($subOrder->coupon_amount) }}</h6>
                                    @endif
                                    <h6 class="table_customer__title">{{ __('Tax:') }} <strong>+</strong> {{ float_amount_with_currency_symbol($subOrder->tax) }}</h6>
                                    <h6 class="table_customer__title">{{ __('Total:') }} {{ float_amount_with_currency_symbol($subOrder->total) }}</h6>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="table_customer">
                            <div class="table_customer__flex">
                                <div class="table_customer__contents">
                                    <h6 class="table_customer__title">{{ __('Type:') }} {{ $subOrder->commission_type }}</h6>
                                    <h6 class="table_customer__title">{{ __('Charge:') }} {{ float_amount_with_currency_symbol($subOrder->commission_charge) }}</h6>
                                    <h6 class="table_customer__title">{{ __('Amount:') }} {{ float_amount_with_currency_symbol($subOrder->commission_amount) }}</h6>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="my-2"><x-status.order-status :status="$subOrder->status"/></span>
                        <span class="my-2">
                            <button type="button" class="cmnBtn btn_5 btn_bg_warning
                            btnIcon radius-5 sub_order_status_change_modal"
                                    data-sub_order_id="{{$subOrder->id}}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#SubOrderStatusChangeModal">
                                <i class="las la-pen"></i>
                            </button>
                        </span>
                    </td>
                    <td>
                        @if(request()->is('admin/orders/details/*'))
                            <x-icon.view-icon :url="route('admin.main.sub.order.addon.details',$subOrder->id)"/>
                        @else
                            <x-icon.view-icon :url="route('admin.sub.order.addon.details',$subOrder->id)"/>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- End-of Table one -->
