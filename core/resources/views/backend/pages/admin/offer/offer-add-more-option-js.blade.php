<script>

    (function ($) {
        "use strict";
        $(document).ready(function() {

            // add what new include
            $(".add-what-includes").on('click',function(){
                let  total_element = $(".what-include-element").length;
                let max = 15;
                if(total_element < max ){
                    $(".append-additional-includes").append(
                        '<div class="single-dashboard-input what-include-element">\
                        <div class="single-info-input margin-top-20">\
                        <label class="form__input__single__label">{{ __('Service') }}  <span class="text-danger">*</span> </label>\
                    <select name="offer_service_id[]" id="service" class="form-select service-select">\
                        <option value="">{{__('Select Service')}}</option>\
                        @foreach($services as $service)\
                        @if($service->status== 1 && $service->is_published == 1)\
                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->title }}</option>\
                        @endif\
                        @endforeach\
                    </select>\
                    </div>\
                    <div class="single-info-input margin-top-20">\
                        <label>{{ __('Original Price') }}</label>\
                        <input type="number" class="form-control" id="original_price_more"  readonly>\
                    </div>\
                    <div class="single-info-input margin-top-20">\
                        <label>{{ __('Discount Price') }}</label>\
                        <input type="number" class="form-control" name="offer_service_discount_price[]"\
                         placeholder="{{__('Discount Price')}}"></input>\
                    </div>\
                    <span class="btn btn-danger remove-include mt-2"><i class="las la-times"></i></span>\
                </div>'
                    );
                    $('.service-select').last().select2({
                        placeholder: '{{ __("Select Service") }}',  // Placeholder text
                        allowClear: true, // Allow clearing the selection
                    });
                }
            });

            $(document).on('change', ".service-select", function() 
             {
                
                let priceId = document.getElementById('original_price'); 
                let selectedOption = $(this).find('option:selected');
                let price = selectedOption.data('price') || '';
                console.log(price);
               $("#original_price_more").val(price);
             });
            // remove include service
            $(document).on('click', ".remove-include", function() {
                $(this).closest('.what-include-element').remove();
            });







        });
    })(jQuery)
</script>

