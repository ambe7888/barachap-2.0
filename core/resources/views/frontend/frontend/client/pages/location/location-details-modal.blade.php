<!-- State Edit Modal -->
<div class="modal fade" id="locationDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Location Details') }} </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                @csrf
                <div class="modal-body" id="location_details">
                    <div class="userDetails__wrapper">
                        <p class="userDetails__wrapper__item"><strong>{{ __('Title: ') }}</strong><span class="title"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('State: ') }}</strong><span class="state"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('City: ') }}</strong><span class="city"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Area: ') }}</strong><span class="area"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Address: ') }}</strong><span class="address"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Post Code: ') }}</strong><span class="post_code"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Longitude: ') }}</strong><span class="longitude"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Latitude: ') }}</strong><span class="latitude"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Type: ') }}</strong><span class="type"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Phone: ') }}</strong><span class="phone"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Emergency Phone: ') }}</strong><span class="emergency_phone"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
        </div>
    </div>
</div>
