<!-- State Edit Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Staff Details') }} </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                @csrf
                <div class="modal-body" id="user_details">
                    <div class="userDetails__wrapper">
                        <p class="userDetails__wrapper__item"><strong>{{ __('Full Name: ') }}</strong><span class="full_name"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Email: ') }}</strong><span class="email"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Phone: ') }}</strong><span class="phone"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('About: ') }}</strong><span class="about"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
        </div>
    </div>
</div>
