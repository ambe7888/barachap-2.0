<div class="dashboard__header__right__item">
    <div class="dashboard__header__notification">
        <a href="javascript:void(0)" class="dashboard__header__notification__icon">
            <i class="las la-bell"></i>
        </a>
        <span class="dashboard__header__notification__number">
            {{ \App\Models\UserNotification::unread_notification_count() }}
        </span>

        <div class="dashboard__header__notification__wrap">
            <h6 class="dashboard__header__notification__wrap__title"> {{ __('Notifications') }} </h6>
            <ul class="dashboard__header__notification__wrap__list">
                <!-- Display notification details -->
                @foreach(\App\Models\UserNotification::unread_notification() as $notification)
                    <x-frontend.client_notification_in_top :notification="$notification"/>
                @endforeach
            </ul>
            <a href="{{ route('client.notification.all') }}" class="dashboard__header__notification__wrap__btn"> {{ __('See All Notification') }} </a>
        </div>
    </div>
</div>
