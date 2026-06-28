<!-- Decline form -->
<div class="modal fade" id="DeclineFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Decline Form') }} </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                @csrf
                <div class="modal-body" id="location_details">
                    <div class="userDetails__wrapper">
                        <form >
                            <span class="text-danger error-car"></span>
                            <input type="hidden" name="sub_order_id" id="sub_order_id" >
                            <input type="hidden" name="order_id" id="order_id">
                            <div class="form-group">
                                <label for="decline_reason">{{ __('Reason') }}</label>
                                <textarea name="decline_reason" id="decline_reason" class="form-control" rows="5" placeholder="{{ __('Write your reason here...') }}"></textarea>
                            </div>  

                            <!-- submit button -->
                            <div class="form-group mt-3">
                                <button type="button" id="send_decline_request" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </form>      
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
        </div>
    </div>
</div>