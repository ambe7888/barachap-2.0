<div class="modal fade" tabindex="-1" id="whatsapp_modal">
    <div class="modal-dialog">
        <div class="popup_contents modal-content">
            <div class="popup_contents__header">
                <div class="popup_contents__header__flex">
                    <div class="popup_contents__header__contents">
                        <h2 class="popup_contents__header__title">{{ __('WhatsApp Meta API') }}</h2>
                    </div>
                    <div class="popup_contents__header__close" data-bs-dismiss="modal">
                        <span class="popup_contents__close popup_close"> <i class="fas fa-times"></i> </span>
                    </div>
                </div>
            </div>
            <form action="{{route('admin.sms.gateway.update')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="sms_gateway_name" value="whatsapp">
                <div class="popup_contents__body">
                    <!--otp env settings -->
                    <h5 class="mb-4">{{ __('Configure WhatsApp Meta credentials') }}</h5>
                    <div class="form-group mt-3">
                        <label for="WHATSAPP_CLOUD_TOKEN"><strong>{{__('WhatsApp Cloud Token')}} <span class="text-danger">*</span> </strong></label>
                        <input type="text"  class="form-control" name="whatsapp_cloud_token" value=""
                               placeholder="{{ __('WhatsApp Cloud Token')}}">
                    </div>

                    <div class="form-group">
                        <label for="WHATSAPP_PHONE_NUMBER_ID"><strong>{{__('WhatsApp Phone Number ID')}} <span class="text-danger">*</span></strong></label>
                        <input type="text"  class="form-control" name="whatsapp_phone_number_id" value=""
                               placeholder="{{ __('WhatsApp Phone Number ID')}}">
                    </div>

                    <div class="form-group">
                        <label for="WHATSAPP_OTP_TEMPLATE_NAME"><strong>{{__('OTP Template Name')}} <span class="text-danger">*</span> </strong></label>
                        <input type="text" class="form-control" name="whatsapp_otp_template_name" value=""
                               placeholder="{{ __('Template Name (e.g., otp_verification)')}}">
                    </div>

                    <div class="form-group">
                        <label for="disable_user_otp_verify"><strong>{{__('OTP Expire Time Add')}}</strong></label>
                        <select name="user_otp_expire_time" class="form-control">
                            <option  value="30">{{__('30 Second')}}</option>
                            @for($i=1; $i<=5; $i=$i+0.5)
                                <option value="{{$i}}">{{__($i . ($i > 1 ? ' Minutes' : ' Minute'))}}</option>
                            @endfor
                        </select>
                        <p class="form-text text-muted mt-2">{{__('User OTP verify Expire Time Add.')}}</p>
                    </div>

                    <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
