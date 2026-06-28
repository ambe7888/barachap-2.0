<table class="table_activation">
    <thead>
    <tr>
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
        <th>{{__('ID')}}</th>
        <th>{{__('Staff Name')}}</th>
        <th>{{__('Email')}}</th>
        <th>{{__('Phone')}}</th>
        <th>{{__('Image')}}</th>
        <th>{{__('Create By')}}</th>
        <th>{{__('Created at')}}</th>
        <th>{{__('Staff Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($all_staffs->total() >=1)
        @foreach($all_staffs as $staff)
            <tr>
                <td>
                    <x-bulk-action.bulk-delete-checkbox :id="$staff->id"/>
                </td>
                <td>{{ $staff->id }}</td>
                <td>{{ $staff->first_name.' '.$staff->last_name }}</td>
                <td>  {{ $staff->email }}  </td>
                <td>{{ $staff->phone }} </td>
                <td>{!! render_image_markup_by_attachment_id($staff->image) !!} </td>
                <td>{{ $staff?->admin?->name }} </td>
                <td>{{ $staff?->created_at->format('d-m-Y') }}</td>
                <td>
                    <x-status.table.active-inactive :status="$staff->status"/>
                    <x-status.table.status-change :url="route('provider.staff.status',$staff->id)"/>
                </td>
                <td class="actions">
                    <a class="cmnBtn btn_5 btn_bg_info radius-5 user_details"
                       data-bs-toggle="modal"
                       data-bs-target="#userDetailsModal"
                       data-user_id="{{ $staff->id }}"
                       data-first_name="{{ $staff->first_name }}"
                       data-last_name="{{ $staff->last_name }}"
                       data-email="{{ $staff->email }}"
                       data-phone="{{ $staff->phone }}"
                       data-about="{{ $staff->about }}"
                     > {{ __('Staff Details') }}
                    </a>
                    <x-icon.edit-icon :url="route('provider.staff.edit', $staff->id)"/>
                   <x-popup.delete-popup :url="route('provider.staff.delete',$staff->id)"/>
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'7'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_staffs"/>
