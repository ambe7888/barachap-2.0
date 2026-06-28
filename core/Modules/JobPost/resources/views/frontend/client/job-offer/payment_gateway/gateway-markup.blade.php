<div class="modal fade w-100" id="paymentGatewayModal" tabindex="-1" aria-labelledby="paymentGatewayModalLabel" aria-hidden="true">
    <div class="modal-dialog ab">
        <form action="{{route('client.job.order.payment.process')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="paymentGatewayModalLabel">{{ $title ?? '' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="confirm-payment payment-border">
                        <div class="single-checkbox">
                            <div class="checkbox-inlines">
                                <label class="checkbox-label" for="check2">
                                    {!! \App\Helpers\PaymentGatewayRenderHelper::renderPaymentGatewayForForm() !!}
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="amount_details">
                        <div class="customer__account__details">
                            <input type="hidden" name="job_post_id" id="job_post_id" value="{{$job_post_offer->job_post_id}}">
                            <input type="hidden" name="job_offer_id" id="job_offer_id" value="{{$job_post_offer->id}}">
                            <div class="customer__account__details__item">
                                <div class="customer__job__details__item__flex">
                                    <strong>{{ __('Offered:') }}</strong>
                                    <span>{{ float_amount_with_currency_symbol($job_post_offer->budget)  }}</span>
                                </div>
                            </div>



                            <div class="customer__account__details__item">
                                <div class="customer__job__details__item__flex">
                                    <strong>{{ __('Tax:') }}</strong>
                                    <span><strong>+</strong>{{ float_amount_with_currency_symbol($tax)  }}</span>
                                </div>
                            </div>

                            @php
                               $total= $job_post_offer->budget + $tax;
                            @endphp
                            <div class="customer__account__details__item">
                                <div class="customer__job__details__item__flex">
                                    <strong>{{ __('Total:') }}</strong>
                                    <span><span id="total_after_discount">{{ float_amount_with_currency_symbol($total)  }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                </div>
                <div class="modal-footer">
                    <div class="btn-wrapper d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{__('Order Now')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

