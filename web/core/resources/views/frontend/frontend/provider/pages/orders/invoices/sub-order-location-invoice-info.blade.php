<div class="item-description" style="margin-top: 120px!important;">
    <div class="table-responsive">
        <h5 class="table-title">{{ __('Sub Orders Locations') }}</h5>
        <table class="custom--table">
            <thead class="head-bg">
            <tr>
                <th>{{ __('SubOrder ID') }}</th>
                <th>{{ __('Contact Info') }}</th>
                <th>{{ __('Address Info') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order_details->subOrders as $subOrder)
                @foreach($subOrder->subOrderLocations as $location)
                    <tr>
                        <td width="100px">
                            <span class="data-span"> {{ $location->id ?? __('N/A') }} </span>
                        </td>
                        <td>
                            <span class="data-span"> {{ __('Phone:') }} </span>
                            {{ $location->phone }}
                            <br>
                            <span class="data-span"> {{ __('Emerg. Phone:') }} </span>
                            {{ $location->emergency_phone }}
                            <br>
                        </td>
                         <td>
                             <span class="data-span"> {{ __('Address Type:') }} </span>
                             @if($location->type == 0)
                                 {{ __('Home') }}
                             @else
                                 {{ __('Office') }}
                             @endif
                             <br>
                             <span class="data-span"> {{ __('City:') }} </span>
                             {{ $location->city?->city }}
                             <br>
                             <span class="data-span"> {{ __('Area:') }} </span>
                             {{ $location->area?->area }}
                             <br>
                             <span class="data-span"> {{ __('Post Code:') }} </span>
                             {{ $location->post_code }}
                             <br>
                             <span class="data-span"> {{ __('Address:') }} </span>
                             {{ $location->address }}
                         </td>

                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
</div>



