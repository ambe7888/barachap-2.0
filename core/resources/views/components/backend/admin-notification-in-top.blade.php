
@if($notification->type =='service')
    @php
        $service = \App\Models\Service::find($notification->identity);
    @endphp
    
    @if($service)
    <a href="{{ route('admin.service.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
        <li class="dashboard__header__notification__wrap__list__item">
            <div class="dashboard__header__notification__wrap__list__flex">
                <div class="dashboard__header__notification__wrap__list__icon">
                    <i class="las la-bell"></i>
                </div>
                <div class="dashboard__header__notification__wrap__list__contents">
                    {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                    <span class="dashboard__header__notification__wrap__list__contents__sub">
                        {{ $notification->created_at->toFormattedDateString() }}
                    </span>
                </div>
            </div>
        </li>
    </a>
    @else
        <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
                           
            <li class="dashboard__header__notification__wrap__list__item">
                <div class="dashboard__header__notification__wrap__list__flex">
                    <div class="dashboard__header__notification__wrap__list__icon">
                        <i class="las la-bell"></i>
                    </div>
                    <div class="dashboard__header__notification__wrap__list__contents">
                        {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                        <span class="dashboard__header__notification__wrap__list__contents__sub">
                                 {{ $notification->created_at->toFormattedDateString() }}
                        </span>
                    </div>
                                    
                </div>
            </li>
                           
        </a>
    @endif
    
@endif

@if($notification->type =='job-created')
    @php
        $job = \Modules\JobPost\app\Models\JobPost::find($notification->identity);
    @endphp
    
    @if($job)
            <a href="{{ route('admin.jobs.details', ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
            <li class="dashboard__header__notification__wrap__list__item">
                <div class="dashboard__header__notification__wrap__list__flex">
                    <div class="dashboard__header__notification__wrap__list__icon">
                        <i class="las la-bell"></i>
                    </div>
                    <div class="dashboard__header__notification__wrap__list__contents">
                        {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                        <span class="dashboard__header__notification__wrap__list__contents__sub">
                            {{ $notification->created_at->toFormattedDateString() }}
                        </span>
                    </div>
                </div>
            </li>
            </a>
    @else
        <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
            
                <li class="dashboard__header__notification__wrap__list__item">
                    <div class="dashboard__header__notification__wrap__list__flex">
                        <div class="dashboard__header__notification__wrap__list__icon">
                            <i class="las la-bell"></i>
                        </div>
                        <div class="dashboard__header__notification__wrap__list__contents">
                            {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                            <span class="dashboard__header__notification__wrap__list__contents__sub">
                                {{ $notification->created_at->toFormattedDateString() }}
                            </span>
                        </div>
                    </div>
                </li>
            
            </a>
    @endif
    
@endif

@if($notification->type =='order')
    @php
        $order = \App\Models\Order::find($notification->identity);
    @endphp

    @if($order)
        <a href="{{ route('admin.order.details', ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
            <li class="dashboard__header__notification__wrap__list__item">
                <div class="dashboard__header__notification__wrap__list__flex">
                    <div class="dashboard__header__notification__wrap__list__icon">
                        <i class="las la-bell"></i>
                    </div>
                    <div class="dashboard__header__notification__wrap__list__contents">
                        {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                        <span class="dashboard__header__notification__wrap__list__contents__sub">
                            {{ $notification->created_at->toFormattedDateString() }}
                        </span>
                    </div>
                </div>
            </li>
        </a>
    @else

        <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
            
                <li class="dashboard__header__notification__wrap__list__item">
                    <div class="dashboard__header__notification__wrap__list__flex">
                        <div class="dashboard__header__notification__wrap__list__icon">
                            <i class="las la-bell"></i>
                        </div>
                        <div class="dashboard__header__notification__wrap__list__contents">
                            {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                            <span class="dashboard__header__notification__wrap__list__contents__sub">
                                {{ $notification->created_at->toFormattedDateString() }}
                            </span>
                        </div>
                    </div>
                </li>
            
        </a>
    @endif
    
@endif

@if($notification->type =='admin-order')
        @php
        $admin_order = \App\Models\Order::whereNotNull('admin_id')->find($notification->identity);

        @endphp

        @if($admin_order)
            <a href="{{ route('admin.main.order.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                <li class="dashboard__header__notification__wrap__list__item">
                    <div class="dashboard__header__notification__wrap__list__flex">
                        <div class="dashboard__header__notification__wrap__list__icon">
                            <i class="las la-bell"></i>
                        </div>
                        <div class="dashboard__header__notification__wrap__list__contents">
                            {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                            <span class="dashboard__header__notification__wrap__list__contents__sub">
                                {{ $notification->created_at->toFormattedDateString() }}
                            </span>
                        </div>
                    </div>
                </li>
            </a>
        @else
            <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">
                    <li class="dashboard__header__notification__wrap__list__item">
                        <div class="dashboard__header__notification__wrap__list__flex">
                            <div class="dashboard__header__notification__wrap__list__icon">
                                <i class="las la-bell"></i>
                            </div>
                            <div class="dashboard__header__notification__wrap__list__contents">
                                {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                                <span class="dashboard__header__notification__wrap__list__contents__sub">
                                    {{ $notification->created_at->toFormattedDateString() }}
                                </span>
                            </div>
                        </div>
                    </li>
            </a>
        @endif
    
@endif

@if($notification->type =='ticket' || $notification->type =='ticket-update')
    @php
        $ticket = \Modules\SupportTicket\app\Models\Ticket::find($notification->identity);
    @endphp

        @if($ticket)
            <a href="{{ route('admin.ticket.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
                <li class="dashboard__header__notification__wrap__list__item">
                    <div class="dashboard__header__notification__wrap__list__flex">
                        <div class="dashboard__header__notification__wrap__list__icon">
                            <i class="las la-bell"></i>
                        </div>
                        <div class="dashboard__header__notification__wrap__list__contents">
                            {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                            <span class="dashboard__header__notification__wrap__list__contents__sub">
                                {{ $notification->created_at->toFormattedDateString() }}
                            </span>
                        </div>
                    </div>
                </li>
            </a>
        @else
            <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">

                
                    <li class="dashboard__header__notification__wrap__list__item">
                        <div class="dashboard__header__notification__wrap__list__flex">
                            <div class="dashboard__header__notification__wrap__list__icon">
                                <i class="las la-bell"></i>
                            </div>
                            <div class="dashboard__header__notification__wrap__list__contents">
                                {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                                <span class="dashboard__header__notification__wrap__list__contents__sub">
                                    {{ $notification->created_at->toFormattedDateString() }}
                                </span>
                            </div>
                        </div>
                    </li>
                

            </a>
        @endif
    
@endif

@if($notification->type =='withdraw')
    @php
        $ticket = \Modules\SupportTicket\app\Models\Ticket::find($notification->identity);
    @endphp

    @if($ticket)
        <a href="{{ route('admin.ticket.details',  ['id' => $notification->identity, 'notificationId' => $notification->id]) }}" class="dashboard__notification__list__item click-notification">
            <li class="dashboard__header__notification__wrap__list__item">
                <div class="dashboard__header__notification__wrap__list__flex">
                    <div class="dashboard__header__notification__wrap__list__icon">
                        <i class="las la-bell"></i>
                    </div>
                    <div class="dashboard__header__notification__wrap__list__contents">
                        {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                        <span class="dashboard__header__notification__wrap__list__contents__sub">
                            {{ $notification->created_at->toFormattedDateString() }}
                        </span>
                    </div>
                </div>
            </li>
        </a>
    @else
        <a href="javascript:void(0)" class="dashboard__notification__list__item click-notification">

                <li class="dashboard__header__notification__wrap__list__item">
                    <div class="dashboard__header__notification__wrap__list__flex">
                        <div class="dashboard__header__notification__wrap__list__icon">
                            <i class="las la-bell"></i>
                        </div>
                        <div class="dashboard__header__notification__wrap__list__contents">
                            {{ $notification->message ?? '' }}  <strong>#{{ $notification->identity }}</strong>
                            <span class="dashboard__header__notification__wrap__list__contents__sub">
                                {{ $notification->created_at->toFormattedDateString() }}
                            </span>
                        </div>
                    </div>
                </li> 

        </a>
    @endif

@endif
