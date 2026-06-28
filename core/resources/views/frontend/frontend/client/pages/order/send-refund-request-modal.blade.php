<!-- Cancel form -->
<div class="modal fade" id="RefundRequestFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Refund Form') }} </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                @csrf
                <div class="modal-body" id="location_details">
                    <div class="userDetails__wrapper">
                        <form >
                            <span class="text-danger error-refund"></span>
                            <input type="hidden" name="sub_order_id_for_refund" id="sub_order_id_for_refund" >
                            <input type="hidden" name="order_id_for_refund" id="order_id_for_refund">
                            <div class="form-group">
                                <label for="refund_reason">{{ __('Reason') }}</label>
                                <textarea name="refund_reason" id="refund_reason" class="form-control" rows="5" placeholder="{{ __('Write your reason here...') }}"></textarea>
                            </div>  

                            <div class="single-input my-5">
                                <label class="label-title">{{ __('Gateway Method') }}</label>
                                <select name="gateway_method" id="gateway_method_for_refund" class="form-control">
                                    <option value="">{{__("Select Method")}}</option>
                                    @foreach($gateway_methods as $method)
                                        
                                        <option value="{{ $method->id }}"  data-fields='@json(unserialize( $method->field))'>
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                    
                            </div>
                                <div id="method-fields-for-refund" name="method-fields" class="mt-3"></div>


                            <!-- submit button -->
                            <div class="form-group mt-3">
                                <button type="button" id="send_refund_request" class="btn btn-primary">{{ __('Submit') }}</button>
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