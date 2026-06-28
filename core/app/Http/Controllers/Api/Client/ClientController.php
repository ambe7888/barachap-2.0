<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Modules\Coupon\app\Models\Coupon;
use Modules\Coupon\app\Resources\CouponPublicResources;

class ClientController extends Controller
{
    public function taxInfo($id, $type = 'info'){
        $type = request()->query('type');

       $tax_amount = calculateTaxBasedOnCoordinates($id, $type);
        return response()->json([
            'tax_info' => $tax_amount,
        ]);
    }

    public function couponInfo($coupon_code){

       $coupon = Coupon::where('code',$coupon_code)
           ->where('expire_date', '>=', now())// check date
           ->where('status',1)
           ->first();

        if ($coupon) {
            return response()->json([
                'coupon' => new CouponPublicResources($coupon),
            ]);
        }

        // error response
        return response()->json([
            'message' => __('Coupon not found, expired, or inactive.'),
        ], 404);
    }
}
