<table class="table_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Address')}}</th>
        <th>{{__('State')}}</th>
        <th>{{__('City')}}</th>
        <th>{{__('Area')}}</th>
        <th>{{__('Type')}}</th>
        <th>{{__('Phone')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($all_locations->total() >=1)
        @foreach($all_locations as $location)
            <tr>
                <td>{{ $location->id }}</td>
                <td>{{ $location->title }}</td>
                <td>  {{ $location->address}}  </td>
                <td>{{ $location->state?->state }} </td>
                <td>{{ $location->city?->city }} </td>
                <td>{{ $location->area?->area }} </td>
                @php
                    if($location->type == 0){
                        $type = __('Home');
                    }
                    else {
                        $type = __('Office');
                    }    
                @endphp

                <td>{{ $type }} </td>
                <td>{{ $location->phone }} </td>
               
                <td class="actions">
                    
                    <x-icon.edit-icon :url="route('client.location.edit',$location->id)"/>
                
                    <a class="cmnBtn btn_5 btn_bg_info radius-5 location_details"
                    data-bs-toggle="modal"
                    data-bs-target="#locationDetailsModal"
                    data-location_id="{{ $location->id }}"
                    data-title="{{ $location->title }}"
                    data-state="{{ $location->state?->state }}"
                    data-city="{{ $location->city?->city }}"
                    data-area="{{ $location->area?->area }}"
                    data-address="{{ $location->address }}"
                    data-type="{{ $type }}"
                    data-latitude="{{ $location->latitude }}"
                    data-longitude="{{ $location->longitude }}"
                    data-post_code="{{ $location->post_code }}"
                    data-phone="{{ $location->phone }}"
                    data-emergency_phone="{{ $location->emergency_phone }}"
                    
                > {{ __('Location Details') }}
                </a>
                    <x-popup.delete-popup :url="route('client.location.delete',$location->id)"/>
                
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'7'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_locations"/>
