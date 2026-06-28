@extends('backend.admin-master')
@section('title', __('Order Cancellation Policy'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Order Cancellation Policy') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.order.cancellation-policy')}}" method="POST">
                                @csrf
                                <div class="form__input__single">
                                    <label class="form__input__single__label">{{ __('Fine Type') }} <span class="text-danger"></label>
                                    <select id="fine_type" name="fine_type" class="form-control">
                                        <option {{ $cancellationPolicy?->fine_type == 'flat' ? 'selected' : '' }} value="flat"> {{ __('Flat') }} </option>
                                        <option {{ $cancellationPolicy?->fine_type == 'percentage' ? 'selected' : '' }} value="percentage"> {{ __('Percentage') }} </option>
                                    </select>
                                </div>
                                <div class="form__input__single">
                                    <label class="form__input__single__label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form__control radius-5" name="amount" id="amount" value="{{ $cancellationPolicy?->amount}}" placeholder="{{__('Add Amount')}}" step="0.01">
                                </div>

                                <div class="form__input__single">
                                    <label class="form__input__single__label">{{ __('Available Type') }} <span class="text-danger"></label>
                                    <select id="available_type" name="available_type" class="form-control">
                                        <option {{ $cancellationPolicy?->available_type == 'always' ? 'selected' : '' }} value="always"> {{ __('Always') }} </option>
                                        <option {{ $cancellationPolicy?->available_type == 'certain_time' ? 'selected' : '' }} value="certain_time"> {{ __('Certain Time') }} </option>
                                    </select>
                                </div>

                                <div class="form__input__single d-none" id="div_time_in_min">
                                    <label class="form__input__single__label">{{ __('Time') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form__control radius-5 " name="time_in_min" id="time_in_min" value="{{ $cancellationPolicy?->time_in_min}}" placeholder="{{__('Add Time in minute')}}" step="0.01">
                                </div>
                                 <!-- Description -->
                                <div class="form__input__single mt-3">
                                    <label class="form__input__single__label">{{ __('Description') }} <span class="text-danger"></label>
                                    <div class="input-form input-form2">
                                        <textarea class="textarea--form" name="description" placeholder="{{__('Type Description')}}" rows="8" cols="8">{{ $cancellationPolicy?->description }}</textarea>
                                    </div>
                                </div>
                                
                               
                                <div class="btn_wrapper mt-4">
                                    <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5 update_info">{{ __('Update Setting') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('assets/backend/js/select2.min.js')}}"></script>    
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){

                     let available_types = $("#available_type").val();
                     if(available_types == 'certain_time'){
                         $('#div_time_in_min').removeClass("d-none");
                     }
                     else
                     {
                            $('#div_time_in_min').addClass("d-none");
                     }    
                    //sub order status change
                    $(document).on('click', '#available_type', function () {
                        let value = $(this).val();
                        if(value == 'certain_time'){
                            $('#div_time_in_min').removeClass("d-none");

                        }else
                        {
                            $('#div_time_in_min').addClass("d-none");
                        }
                    });  
            });
         })(jQuery);
    </script>
@endsection
