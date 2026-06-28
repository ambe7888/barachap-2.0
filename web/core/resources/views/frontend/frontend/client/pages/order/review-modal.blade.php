<!-- Decline form -->
<div class="modal fade" id="ReviewFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Review Form') }} </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                
                <div class="modal-body" id="review_details">
                    <div class="userDetails__wrapper">
                        <form >
                            <span class="text-danger error-review"></span>
                            <input type="hidden" name="sub_order_id" id="sub_order_id_for_review" >
                            <input type="hidden" name="order_id" id="order_id_for_review">
                            <input type="hidden" name="service_id" id="service_id">
                            <div class="star-rating">
                                <div>
                                    <h6 class="mb-2">{{ __('Give Rating') }}</h6>
                                </div>    
                               
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                       <span class="star mt-2" data-value="{{ $i }}">&#9733;</span>
                                    @endfor
                                </div>    
                            </div>

                            <input type="hidden" name="rating" id="ratingInput" value="{{ $review_details?->rating}}">
                            <div class="form-group mt-3">
                                <label for="review">{{ __('Review') }}</label>
                                <textarea name="review" id="review" class="form-control" rows="5" placeholder="{{ __('Write your reason here...') }}">{{ $review_details?->message}}</textarea>
                            </div>  

                            <!-- submit button -->
                            <div class="form-group mt-3">
                                <button type="button" id="send_review" class="btn btn-primary">{{ __('Submit') }}</button>
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