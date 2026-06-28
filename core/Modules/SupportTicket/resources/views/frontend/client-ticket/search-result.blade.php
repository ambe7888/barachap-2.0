<table class="DataTable_activation">
    <thead>
    <tr>
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
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->title }}</td>

            <td>
                @if(!empty($ticket->user))
                    <span class="text-primary">
                        <p> {{ __('Name: ') }} {{ $ticket->user->fullname }}</p>
                        {{__('Provider')}}
                    </span>
                   
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
                <a class="cmnBtn btn_5 btn_bg_info btnIcon radius-5" href="{{ route('client.ticket.details',$ticket->id) }}">
                    <i class="las la-eye"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$tickets"/>

