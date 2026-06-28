<?php

namespace App\Rules;

use App\Models\Offer;
use App\Models\OfferService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsExistOfferServiceEditRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $offerId;
    public function __construct($offerId)
    {
        $this->offerId = $offerId; // Store the provided id
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $servicePresent=OfferService::where('service_id', $value)->where('offer_id','!=',$this->offerId)->first();
      
            if($servicePresent)
            {
              
                $fail('One or more services are already included in offers.');      
                
            }
    }
}
