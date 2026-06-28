<table class="DataTable_activation">
    <thead>
    <tr>
        @can('city-bulk-delete')
            <th class="no-sort">
                <div class="mark-all-checkbox">
                    <input type="checkbox" class="all-checkbox">
                </div>
            </th>
        @endcan
        <th>{{__('ID')}}</th>
        <th>{{__('Area')}}</th>
        <th>{{__('City')}}</th>
        <th>{{__('State')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_areas as $area)
        <tr>
            @can('area-bulk-delete')
                <td>
                    <x-bulk-action.bulk-delete-checkbox :id="$area->id"/>
                </td>
            @endcan
            <td>{{ $area->id }}</td>
            <td>{{ $area->area }}</td>
            <td>{{ optional($area->city)->city }}</td>
            <td>{{ optional($area->state)->state }}</td>
            <td>
                <x-status.table.active-inactive :status="$area->status"/>
            </td>
            <td>
                    @can('area-edit')
                        <a class="cmnBtn btn_5 btn_bg_warning radius-5 edit_area_modal"
                           data-bs-toggle="modal"
                           data-bs-target="#editAreaModal"
                           data-area="{{ $area->area }}"
                           data-area_id="{{ $area->id }}"
                           data-city_id="{{ $area->city_id }}"
                           data-state_id="{{ $area->state_id }}">
                            {{ __('Edit Area') }}
                        </a>
                    @endcan
                    @can('area-delete')
                        <x-popup.delete-popup :title="__('Delete Area')" :url="route('admin.area.delete',$area->id)"/>
                    @endcan
                    @can('area-status-change')
                       <x-status.table.status-change :title="__('Change Status')" :url="route('admin.area.status',$area->id)"/>
                    @endcan
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_areas"/>
