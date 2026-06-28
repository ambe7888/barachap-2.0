<table class="dataTablesExample">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Message')}}</th>
        <th>{{__('Notification Type')}}</th>
        <th>{{__('Read/Unread')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_notifications as $notification)
        <tr>
            <td>{{ $notification->id }}</td>
            <td>



                @if($notification->type =='job-created')
                @php
                       $job = \Modules\JobPost\app\Models\JobPost::find($notification->identity);
                    @endphp

                    @if($job)
                    <a href="{{ route('client.jobs.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                        {{ $notification->message }}
                     </a>
                    @else
                        <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
                            {{ $notification->message }}
                         </a>
                    @endif

                @endif
                @if($notification->type =='order')
                    @php
                       $order = \App\Models\Order::find($notification->identity);
                    @endphp

                    @if($order)
                        <a href="{{ route('client.order.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                            {{ $notification->message }}
                        </a>
                    @else
                        <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
                            {{ $notification->message }}
                        </a>
                    @endif
                @endif


                @if($notification->type =='ticket' || $notification->type =='ticket-update')

                        @php
                            $ticket = \Modules\SupportTicket\app\Models\Ticket::find($notification->identity);
                        @endphp

                         @if($ticket)
                            <a href="{{ route('client.ticket.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                                {{ $notification->message }}
                            </a>
                         @else
                            <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">

                                {{ $notification->message }}

                            </a>
                          @endif
                @endif

                @if($notification->type =='withdraw')
                    @php
                       $ticket = \Modules\SupportTicket\app\Models\Ticket::find($notification->identity);
                    @endphp

                    @if($ticket)
                        <a href="{{ route('client.ticket.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                           {{ $notification->message }}
                        </a>
                    @else
                        <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">

                           {{ $notification->message }}

                        </a>
                    @endif

                @endif
            </td>
            <td>{{ $notification->type }}</td>
            <td>
                @if($notification->is_read == 'unread')
                    <span class="badge bg-danger">{{ __('Unread') }}</span>
                @else
                    <span class="badge bg-success">{{ __('Read') }}</span>
                @endif
            </td>
            <td>


                @if($notification->type =='job-created')
                    @php
                       $job = \Modules\JobPost\app\Models\JobPost::find($notification->identity);
                    @endphp

                    @if($job)
                    <a href="{{ route('client.jobs.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                        <button type="button" class="btn btn-primary">
                            View
                          </button>
                     </a>
                    @else
                        <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
                            <button type="button" class="btn btn-primary">
                                View
                              </button>
                         </a>
                    @endif

                @endif

                @if($notification->type =='order')
                    @php
                       $order = \App\Models\Order::find($notification->identity);
                    @endphp

                    @if($order)
                    <a href="{{ route('client.order.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                        <button type="button" class="btn btn-primary">
                        View
                      </button>
                  </a>
                    @else
                        <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
                            <button type="button" class="btn btn-primary">
                                View
                              </button>
                        </a>
                    @endif

                @endif

                @if($notification->type =='ticket' || $notification->type =='ticket-update')
                @php
                    $ticket = \Modules\SupportTicket\app\Models\Ticket::find($notification->identity);
                @endphp

                @if($ticket)
                <a href="{{ route('client.ticket.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                    <button type="button" class="btn btn-primary">
                        View
                    </button>
                </a>
                @else
                    <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
                        <button type="button" class="btn btn-primary">
                            View
                        </button>
                    </a>
                @endif

                @endif

                @if($notification->type =='withdraw')
                @php
                $ticket = \Modules\SupportTicket\app\Models\Ticket::find($notification->identity);
            @endphp

            @if($ticket)
            <a href="{{ route('client.ticket.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                <button type="button" class="btn btn-primary">
                    View
                </button>
            </a>
            @else
                <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
                    <button type="button" class="btn btn-primary">
                        View
                    </button>
                </a>
            @endif
                @endif

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_notifications"/>
