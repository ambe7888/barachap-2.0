<!-- State Edit Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Schedule Details') }} </h1>
                <a class="user_info_edit cmnBtn btn_5 btn_bg_warning radius-5 btnIcon mx-2" href="">
                    <i class="fas fa-pencil"></i></a>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
                @csrf
                <div class="modal-body" id="schedule_details">
                    <div class="userDetails__wrapper">
                        <p class="userDetails__wrapper__item"><strong>{{ __('Day: ') }}</strong><span class="day"></span></p>
                        <p class="userDetails__wrapper__item"><strong>{{ __('Schedule: ') }}</strong><span class="schedule"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
        </div>
    </div>
</div>
