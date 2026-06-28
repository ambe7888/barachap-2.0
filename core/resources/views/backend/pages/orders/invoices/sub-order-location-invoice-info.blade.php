<div class="item-description" style="margin-top: 50px!important;">
    <div class="table-responsive">
        <h5 class="table-title">{{ __('Sub Orders Locations') }}</h5>
        <table class="custom--table">
            <thead class="head-bg">
            <tr>
                <th>{{ __('SubOrder Location ID') }}</th>
                <th>{{ __('Contact Info') }}</th>
                <th>{{ __('Address Info') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order_details->subOrders as $subOrder)
                <tr>
                    <td width="100px">
                        <span class="data-span"> {{ $subOrder->subOrderLocations?->id ?? __('N/A') }} </span>
                    </td>
                    <td>
                        <span class="data-span"> {{ __('Phone:') }} </span>
                        {{ $subOrder->subOrderLocation?->phone }}
                        <br>
                        <span class="data-span"> {{ __('Emerg. Phone:') }} </span>
                        {{ $subOrder->subOrderLocations?->emergency_phone }}
                        <br>
                    </td>
                        <td>
                            <span class="data-span"> {{ __('Address Type:') }} </span>
                            @if($subOrder->subOrderLocations?->type == 0)
                                {{ __('Home') }}
                            @else
                                {{ __('Office') }}
                            @endif
                            <br>
                            <span class="data-span"> {{ __('City:') }} </span>
                            {{ $subOrder->subOrderLocations?->city?->city }}
                            <br>
                            <span class="data-span"> {{ __('Area:') }} </span>
                            {{$subOrder->subOrderLocations?->area?->area }}
                            <br>
                            <span class="data-span"> {{ __('Post Code:') }} </span>
                            {{ $subOrder->subOrderLocations?->post_code }}
                            <br>
                            <span class="data-span"> {{ __('Address:') }} </span>
                            {{ $subOrder->subOrderLocations?->address }}
                        </td>

                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
</div>



