<!-- State Edit Modal -->
<div class="modal fade" id="reviewDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Review Details') }} </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                @csrf
                <div class="modal-body" id="review_details">
                    <div class="userDetails__wrapper">
                        <p class="userDetails__wrapper__item"><strong>{{ __('Reviewer Full Name: ') }}</strong><span class="full_name"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Title: ') }}</strong><span class="title"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Type: ') }}</strong><span class="type"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Rating: ') }}</strong><span class="rating"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Message: ') }}</strong><span class="message"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
        </div>
    </div>
</div>
