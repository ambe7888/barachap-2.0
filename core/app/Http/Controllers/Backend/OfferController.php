<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Models\Backend\AdminNotification;
use App\Models\Offer;
use App\Models\OfferService;
use App\Models\Service;
use App\Rules\IsExistOfferServiceEditRule;
use App\Rules\IsExistOfferServiceRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function allOffers()
    {
        $offers = Offer::with('offerService.service')->latest()->paginate(10);

        return view('backend.pages.admin.offer.all_offers', compact('offers'));
    }

    public function offerDelete($id)
    {
        try {
            $offer = Offer::with('offerService')->findOrFail($id);

            if (!empty($offer->metaData())) {

                $offer->metaData()->delete();
            }

            $offer->offerService()->delete();
            $offer->delete();

            return redirect()->back()->with(FlashMsg::item_delete(__('offer Deleted Success')));
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(FlashMsg::item_delete(__('offer not found.')));
        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::item_delete(__('An error occurred while deleting the offer')));
        }
    }
   /* public function offerServiceDelete($id)
    {
        try {
           
            $offer = OfferService::where('service_id', $id)->first();
           
            if($offer)
            {
                $offer->delete();
                return redirect()->back()->with(FlashMsg::item_delete(__('offeService Deleted Success')));
            }
            

            
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(FlashMsg::item_delete(__('offerService not found.')));
        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::item_delete(__('An error occurred while deleting the offerService')));
        }
    }*/

    // search category
    public function offerSearch(Request $request)
    {
        $offers = Offer::with("offerService")->where('title', 'LIKE', "%" . strip_tags($request->string_search) . "%")->latest()->paginate(10);
        return $offers->total() >= 1 ? view(
            'backend.pages.admin.offer.search-offer',
            compact('offers')
        )->render() : response()->json(['status' => __('nothing')]);
    }

    //  offer pagination
    public function offerPaginate(Request $request)
    {
        if ($request->ajax()) {
            $offers = Offer::with("offerService")->latest()->paginate(10);
            return view('backend.pages.admin.offer.search-offer', compact('offers'))->render();
        }
    }

    public function bulkAction(Request $request)
    {
        try {
            // Fetch offers with the requested IDs and eager load relationships
            $offers = Offer::whereIn('id', $request->ids)->get();

            // Loop through each offer to delete related records
            foreach ($offers as $offer) {
                // Delete related models
                $offer->metaData()->delete(); // Deletes the related meta data

                // Finally, delete the offer itself
                $offer->delete();
            }
        } catch (\Exception $e) {
        }

        return response()->json(['status' => 'ok']);
    }

    public function addOffer(Request $request)
    {

        if ($request->isMethod('post')) {

            
            // Validate the request data
            $request->validate([
                'title' => 'required|max:191',
                'subtitle' => 'required|min:100',
                'image' => 'nullable|integer',
               'offer_service_id.*' => ['required','distinct',new IsExistOfferServiceRule], // Ensure it's an array and requiredoffer
                'offer_service_discount_price.*' => 'required',
                'expaired_at' => 'required|date',

            ], [
                'title.required' => __('The title field is required.'),
                'title.max' => __('The title must not exceed 191 characters.'),
                'subTitle.required' => __('The subtitle field is required.'),
                'subTitle.max' => __('The subtitle must be exceed 100 characters.'),
                'image.required' => __('The offer image is required.'),
                'expaired_at.required' => __('The expaired date is required.'),
                'offer_service_id.*.required' => __('The service id is required.'),
                'offer_service_discount_price.*.required' => __('The discount price is required.'),
                'offer_service_id.distinct' => __('select different services.'),

            ]);


            $offer_image = $request->image;
            // Create a new offer instance
            $offer = Offer::create([
                'title' => $request->title,
                'subTitle' => $request->subtitle,
                'expires_at' => $request->expaired_at,
                'image' => $offer_image,
                'status' => $request->status,
                'is_primary' => $request->is_primary,

            ]);


            // Generate meta tags
            $words = explode(' ', $request->input('title'));
            $tags = collect($words)->map(fn($word) => strtolower(trim($word)));
            $tags_name = $tags->implode(', ');

            $Metas = [
                'meta_title' => purify_html($request->title),
                'meta_tags' => purify_html($tags_name),
                'meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_tags' => purify_html($tags_name),
                'facebook_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_image' => $request->image,
                'twitter_meta_tags' => purify_html($tags_name),
                'twitter_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'twitter_meta_image' => $request->image,
            ];

            $last_offer_id = $offer->id;
           

            DB::beginTransaction();
            try {
                $offer->metaData()->create($Metas);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
            $offer_update = false;

            $this->insertRelatedRecords($request, $last_offer_id, $offer_update);


            return redirect()->back()->with(FlashMsg::item_new(__('offer Added Success')));
        }


        $services = Service::all();



        return view('backend.pages.admin.offer.create', compact('services'));
    }

    public function adminEditOffer(Request $request, $id,$serviceId=null)
    {

       
        if ($request->isMethod('post')) {

           
            $request->validate([
                'title' => 'required|max:191',
                'subtitle' => 'required|min:100',
                'image' => 'nullable|integer',
                'offer_service_id.*' => ['required','distinct',new IsExistOfferServiceEditRule($id)],
                'offer_service_discount_price.*' => 'required',
                'expaired_at' => 'required|date',

            ], [
                'title.required' => __('The title field is required.'),
                'title.max' => __('The title must not exceed 191 characters.'),
                'subTitle.required' => __('The subtitle field is required.'),
                'subTitle.max' => __('The subtitle must be exceed 100 characters.'),
                'expaired_at.required' => __('The expaired date is required.'),
                'image.required' => __('The offer image is required.'),
                'offer_service_id.*.required' => __('The service id is required.'),
                'offer_service_id.distinct' => __('select different services.'),
                'offer_service_discount_price.*.required' => __('The discount price is required.'),

            ]);
          
            $services_ids = array_map('intval',(explode(',', $request->deleted_id)));


            if (!empty($services_ids)) {
                
               $aa= OfferService::where('offer_id', $id)
                    ->whereIn('service_id', $services_ids)
                    ->delete();

 
            }

       
 
            $offer = Offer::findOrFail($id);


            
            $offer_image = $request->image;
            if($offer_image!==null)
            {
                $offer->update([
                    'title' => $request->title,
                    'subTitle' => $request->subtitle,
                    'expires_at' => $request->expaired_at,
                    'image' => $offer_image,
                    'status' => $request->status,
                    'is_primary' => $request->is_primary,
                    
                ]);
            }
            else
            {
                // Create a new Service instance
                $offer->update([
                    'title' => $request->title,
                    'subTitle' => $request->subtitle,
                    'expires_at' => $request->expaired_at,
                    'status' => $request->status,
                    'is_primary' => $request->is_primary,
                ]);
            }
           

            

            // Generate meta tags
            $words = explode(' ', $request->input('title'));
            $tags = collect($words)->map(fn($word) => strtolower(trim($word)));
            $tags_name = $tags->implode(', ');

            $Metas = [
                'meta_title' => purify_html($request->title),
                'meta_tags' => purify_html($tags_name),
                'meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_tags' => purify_html($tags_name),
                'facebook_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'facebook_meta_image' => $request->image,
                'twitter_meta_tags' => purify_html($tags_name),
                'twitter_meta_description' => substr(strip_tags(purify_html($request->description)), 0, 100),
                'twitter_meta_image' => $request->image,
            ];

            // Retrieve the last inserted ID
            $last_offer_id = $offer->id;
           
            DB::beginTransaction();
            try {
                $offer->metaData()->update($Metas);
                DB::commit();

            }catch (\Throwable $th){
                DB::rollBack();
            }

            $offer_update = true;

            // Insert related records
            $this->insertRelatedRecords($request, $last_offer_id, $offer_update);

            return redirect()->back()->with(FlashMsg::item_new(__('Offer Updated Success')));
        }
        if($serviceId)
        {
            
            $offer = Offer::with(['offerService' => function($query) use ($serviceId) {
                $query->where('service_id', '!=', $serviceId); 
            }])->findOrFail($id);
           
        }
        else
        {
            $offer = Offer::with('offerService')->findOrFail($id);
        }
        $services = Service::all();
       
        return view('backend.pages.admin.offer.edit-offer', [
            'offer' => $offer,
            'services' => $services
          
        ]);

    }

    private function insertRelatedRecords(Request $request, $offerId,$offer_update)
    {
      
        
        if ($offer_update){
            // Clear existing related records
           
            OfferService::where('offer_id', $offerId)->delete();  
            
           
        }
        $includedOfferServices = [];
        if($request->offer_service_id)
        {
        foreach ($request->offer_service_id as $key => $value) {
           
            $includedOfferServices[] = [
                'offer_id' => $offerId,
                'service_id' => $value,
                'discount_price' => $request->offer_service_discount_price[$key],
            ];

         
            
        }
        OfferService::insert($includedOfferServices);
    }
        
    }



    public function offerDetails($id)
    {
        $offer = Offer::with('offerService.service')->find($id);

        if (!$offer) {
            try {
                AdminNotification::where('identity', $id)->update(['is_read' => 'read']);
            } catch (\Exception $exception) {
            }

            abort(404);
        }

        AdminNotification::where('identity', $id)->update(['is_read' => 'read']);

        return view('backend.pages.admin.offer.offer-details', compact('offer'));
    }

    public function adminChangeStatus($id)
    {
        $offer = Offer::select('id', 'status')->where('id', $id)->first();
        
           
        if($offer)
        {
            if ($offer->status == 1) {
                $status = 0;
            } else {
                $status = 1;
            }
            Offer::where('id', $id)->update(['status' => $status]);
       
            return redirect()->back()->with(FlashMsg::item_new(__('Status Change Success')));
        }
        return redirect()->back()->with(FlashMsg::item_new(__('Offer not found')));
    }
    public function adminChangePrimaryOption($id)
    {
        $offer = Offer::select('id','is_primary')->where('id', $id)->first();
        
       
        
        if($offer){
            
            if ($offer->is_primary == 1) {
                $is_primary = '0';
            } else {
                $is_primary = '1';
                Offer::where('id','!=',$id)->update(['is_primary' => '0']);
            }
            Offer::where('id', $id)->update(['is_primary' => $is_primary]);
       
            return redirect()->back()->with(FlashMsg::item_new(__('Primary Option Change Success')));
            
        }
        return redirect()->back()->with(FlashMsg::item_new(__('Offer not found')));
        
      

        
    }
}
