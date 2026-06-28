<li class="chat-wrapper-contact-list-item chat_item" data-client-id="{{ $providerChat?->client?->id }}">
    <div class="chat-wrapper-contact-list-flex">
        <div class="chat-wrapper-contact-list-thumb">
            <a href="javascript:void(0)">
                @if($providerChat?->client?->image)
                    <img src="{{ asset('assets/uploads/profile/'.$providerChat?->client?->image) }}" alt="{{ $providerChat->client?->fullname }}">
                @else
                    <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                @endif
            </a>
            <div class="notification-dots {{ Cache::has('user_is_online_' . $providerChat?->client?->id) ? "active" : "" }}"></div>
        </div>
        <div class="chat-wrapper-contact-list-contents">
            <div class="chat-wrapper-contact-list-contents-flex flex-between">
                <h4 class="chat-wrapper-contact-list-contents-title"><a href="javascript:void(0)">{{ $providerChat?->client?->fullname }}</a></h4>
                <span class="chat-wrapper-contact-list-time">{{ $providerChat->client?->check_online_status?->diffForHumans() }}</span>
            </div>
            <div>
                
                <div class="unseen_message_count_{{$providerChat?->client->id}}">
                    @if($providerChat->provider_unseen_msg_count > 0)
                        <span class="badge bg-danger text-right">{{ $providerChat->provider_unseen_msg_count }}</span>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>

