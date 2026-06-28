<li class="chat-wrapper-contact-list-item chat_item" data-provider-id="{{ $clientChat?->provider?->id }}">
    <div class="chat-wrapper-contact-list-flex">
        <div class="chat-wrapper-contact-list-thumb">
            <a href="javascript:void(0)">
                @if($clientChat?->provider?->image)
                   {{-- {!! render_image_markup_by_attachment_id($clientChat?->provider?->image,'','thumb') !!} --}}
                   <img src="{{ asset('assets/uploads/author.jpg') }}" alt="{{ __('author') }}">
                @else
                    <img src="{{ asset('assets/uploads/author.jpg') }}" alt="{{ __('author') }}">
                @endif
            </a>
            <div class="notification-dots {{ Cache::has('user_is_online_' . $clientChat->provider?->id) ? "active" : "" }}"></div>
        </div>
        <div class="chat-wrapper-contact-list-contents">
            <div class="chat-wrapper-contact-list-contents-flex flex-between">
                <h4 class="chat-wrapper-contact-list-contents-title"><a href="javascript:void(0)">{{ $clientChat->provider?->fullname }}</a></h4>
                <span class="chat-wrapper-contact-list-time">{{ $clientChat?->provider?->check_online_status?->diffForHumans() }}</span>
            </div>
            <div>
                <div class ="unseen_message_count_{{$clientChat?->provider?->id}}">
                    
                    @if($clientChat->client_unseen_msg_count > 0)
                        <span class="badge bg-danger text-right">{{ $clientChat->client_unseen_msg_count }}</span>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>

