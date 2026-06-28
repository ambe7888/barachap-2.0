<table class="DataTable_activation">
    <thead>
    <tr>
        @can('support-ticket-bulk-delete')
            <th class="no-sort">
                <div class="mark-all-checkbox">
                    <input type="checkbox" class="all-checkbox">
                </div>
            </th>
        @endcan
        <th>{{__('ID')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('User Details')}}</th>
        <th>{{__('Priority')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tickets as $ticket)
        <tr>
            @can('support-ticket-bulk-delete')
                <td> <x-bulk-action.bulk-delete-checkbox :id="$ticket->id"/> </td>
            @endcan
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->title }}</td>

            <td>
                @if(!empty($ticket->user))
                    @if($ticket->user->type == 0)
                        <span class="text-primary">
                            <p> {{ __('Name: ') }} {{ $ticket->user->fullname }}</p>
                            {{__('Provider')}}
                        </span>
                    @else
                        <span class="text-success" >
                               <p> {{ __('Name: ') }} {{ $ticket->user->fullname }}</p>
                            {{__('Client')}}
                        </span>
                    @endif
                @endif
            </td>

            <td>{{ $ticket->priority }}</td>
            <td>
                @if($ticket->status === 'open')
                    <span class="alert alert-success" >{{__('Open')}}</span>
                @else
                    <span class="alert alert-danger" >{{__('Close')}}</span>
                @endif
            </td>
            <td>
                @can('support-ticket-details')
                    <a class="cmnBtn btn_5 btn_bg_info btnIcon radius-5" href="{{ route('admin.ticket.details',$ticket->id) }}">
                        <i class="las la-eye"></i>
                    </a>
                @endcan
                @can('support-ticket-delete')
                 <x-popup.delete-popup :url="route('admin.ticket.delete',$ticket->id)"/>
                @endcan
                @can('support-ticket-status-change')
                    @if($ticket->status === 'open')
                      <x-status.table.status-change :url="route('admin.ticket.status',$ticket->id)"/>
                    @endif
                @endcan
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$tickets"/>

