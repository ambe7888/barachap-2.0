<h4 class="dashboard__inner__item__header__title mt-4">{{ __('Sub Orders') }}</h4>
<!-- Table Design One -->
<div class="tableStyle_one mt-4">
    <div class="table_wrapper table-responsive">
        <!-- Table -->
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('Details') }}</th>
                <th>{{__('Image')}}</th>
                <th>{{__('Service')}}
                <th>{{ __('Category') }}</th>
                <th>{{__('Total Price')}}</th>
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
                                   
                                   <div class="mt-2">
                                   
                                       @if($subOrder->service)
                                       
                                           <a href="{{ route('client.order.service.details', $subOrder->service?->id) }}">
                                               <h6 class="table_customer__title text-primary">{{ __('Service Info') }}</h6>
                                           </a>
                                       @endif
                                      
                                   </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                       
                        {!! render_image_markup_by_attachment_id($subOrder->service?->image) !!}
                            
                    <td>
                       {{ $subOrder->service?->name }}
                                           
                    <td>
                       {{ $subOrder->service?->category?->name }}
                                
                    </td>

                    
                    <td>
                        {{ float_amount_with_currency_symbol($subOrder->total) }}
                    </td>    

                  
                    <td>
                        <span class="my-2"><x-status.order-status :status="$subOrder->status"/></span>
                    </td>
                    <td>
                        
                        <x-icon.view-icon :url="route('client.sub.order.details',$subOrder->id)"/>
                        
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- End-of Table one -->
