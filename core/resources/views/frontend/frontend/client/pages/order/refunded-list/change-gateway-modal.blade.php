<!-- Cancel form -->
<div class="modal fade" id="ChangeGatewayModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Change Refund Gateway') }} </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body" id="change_gateway">
                    <div class="userDetails__wrapper">
                        <form >
                            <span class="text-danger error-gateway"></span>
                            <input type="hidden" name="refund_id" id="refund_id" >

                            <div class="single-input">
                                <label class="label-title">{{ __('Gateway Method') }}</label>
                                <select name="gateway_method" id="gateway_method_for_change" class="form-control">
                                    <option value="">{{__("Select Method")}}</option>
                                    @foreach($gateway_methods as $method)
                                        
                                        <option value="{{ $method->id }}" @if($refundedOrder->gateway_id == $method->id) selected @endif data-fields='@json(unserialize( $method->field))'>
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                    
                            </div>
                            <div id="method-fields" name="method-fields" class="mt-3">
                                @if($refundedOrder->gateway_field)
                                    @foreach($refundedOrder->gateway_field as $key=>$value)
                                        <div class="mb-2">
                                            <label class="form-label">{{$key}}</label>
                                            <input type="text" name="{{$key}}" value="" class="form-control" placeholder="{{$value}}" value="{{$value}}" required>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach($gateway_details as $key=>$value)
                                        <div class="mb-2">
                                            <label class="form-label">{{$value}}</label>
                                            <input type="text" name="{{$value}}" value="" class="form-control" placeholder="{{$value}}" value="{{$value}}" required>
                                        </div>
                                    @endforeach
                                     
                                @endif         
                            </div>
                             <div id="method-fields-for-change" name="method-fields-for-change" class="mt-3">
                                
                            </div>


                            <!-- submit button -->
                            <div class="form-group mt-3">
                                <button type="button" id="change_gateway_details" class="btn btn-primary">{{ __('Edit') }}</button>
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