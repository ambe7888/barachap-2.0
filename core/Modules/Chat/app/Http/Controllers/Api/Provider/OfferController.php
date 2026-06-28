<?php

namespace Modules\Chat\Http\Controllers\Api\Freelancer;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class OfferController extends Controller
{
    public function offer_send(Request $request)
    {
        $pay_by_milestone = $request->pay_by_milestone;
        $pay_at_once = $request->pay_at_once;
        return response()->json([
            'msg' => __('Offer Successfully Send')
        ]);

    }
}
