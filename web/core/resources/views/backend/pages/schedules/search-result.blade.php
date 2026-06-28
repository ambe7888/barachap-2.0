<table class="table_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Day')}}</th>
        <th>{{__('Schedule')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($schedules->total() >=1)
        @foreach($schedules as $schedule)
            <tr>
                <td>{{ $loop->index + 1 + ($schedules->currentPage() - 1) * $schedules->perPage() }}</td>
                <td>{{ $schedule->day }} </td>
                <td>{{ $schedule->schedule }} </td>
                <td class="actions">
                    <a class="cmnBtn btn_5 btn_bg_info radius-5 schedule_details"
                       data-bs-toggle="modal"
                       data-bs-target="#userDetailsModal"
                       data-id="{{ $schedule->id }}"
                       data-day="{{ $schedule->day }}"
                       data-schedule="{{ $schedule->schedule }}"
                     > {{ __('Edit') }}
                    </a>
                   <x-popup.delete-popup :url="route('admin.schedule.delete', $schedule->id)"/>
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'7'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$schedules"/>
