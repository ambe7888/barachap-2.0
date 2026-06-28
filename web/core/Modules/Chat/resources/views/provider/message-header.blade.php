<div class="chat-wrapper-details-header profile-border-bottom flex-between" id="livechat-message-header"
    data-client-id="{{ $data->client->id }}">
    <div class="chat-wrapper-details-header-left d-flex gap-2 align-items-center">
        <div class="chat-wrapper-details-header-left-author d-flex gap-2 align-items-center">
            @if ($data->client?->image)
                <div class="chat-wrapper-contact-list-thumb-main chat-wrapper-contact-list-thumb">
                   {!! render_image_markup_by_attachment_id($data->client?->image,'','thumb') !!}
                </div>
            @else
                <div class="chat-wrapper-contact-list-thumb-main chat-wrapper-contact-list-thumb">
                    <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                </div>
            @endif
            <div class="chat-wrapper-contact-list-thumb-contents">
                <h5 class="chat-wrapper-details-header-title">{{ $data->client?->fullname }}</h5>
            </div>
        </div>
    </div>
   
</div>
