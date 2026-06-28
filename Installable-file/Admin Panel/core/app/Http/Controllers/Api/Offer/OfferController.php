<?php

namespace App\Http\Controllers\Api\Offer;

use App\Actions\Services\GalleryImageModifier;
use App\Http\Controllers\Controller;
use App\Http\Resources\Services\OfferResource;
use App\Http\Resources\Services\ServiceSummaryResource;
use App\Models\Offer;
use App\Models\OfferService;
use App\Models\Service;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function primaryOffer()
    {
        $offers = Offer::where('is_primary','1')->where("status",'1')->where("expires_at",">",now())->get();
        if($offers->isEmpty())
        {
            return response()->json([
                'offers'=>[]
            ]);
        }
        return response()->json([
            'offers'=>OfferResource::collection($offers),
        ]);
    }

    public function allActiveOffers()
    {
        $offers = Offer::where("status",'1')->where("expires_at",">",now())->get();

        return response()->json([
           'offers'=>OfferResource::collection($offers),
        ]);
    }
    public function offerServices($offer_id)
    {
        $offer= Offer::where("id",$offer_id)->where("status",'1')->where("expires_at",">",now())->first();
        if($offer){
            $services = OfferService::where('offer_id', $offer_id)
            ->with([
                'service.reviews',
                'service.includes',
                'service.excludes',
                'service.faqs',
                'service.addons',
                'service.provider',
                'service.admin',
                'service.offer_service',
            ])
            ->get()
            ->pluck('service'); 

        return response()->json([
            'offerServices' => ServiceSummaryResource::collection($services),
        ]);
        }else{
            return response()->json([
                'offerServices'=>[]
            ]);
        }
    
    }
}
