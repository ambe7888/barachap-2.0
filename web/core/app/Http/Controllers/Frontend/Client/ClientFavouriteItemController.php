<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoriteItem;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FlashMsg;
use App\Models\Service;


class ClientFavouriteItemController extends Controller
{
    public function favoriteLists(Request $request){

        

        $user_id = Auth::guard('sanctum')->user()->id;

        $favoriteItems = FavoriteItem::where('user_id', $user_id)
            ->latest()
            ->with('favoritable')
            ->paginate(10);

        foreach ($favoriteItems as $item) {
            $item->favoritable = Service::where('id', $item->item_id)->with('category')->first();
        }  
        
        
   

        return view('frontend.frontend.client.pages.favourite-services.all-favourite-services', compact('favoriteItems'));
    }

    // pagination
    public function pagination(Request $request)
    {
        if($request->ajax()){
            $user_id = Auth::guard('sanctum')->user()->id;
            $favoriteItems = FavoriteItem::where('user_id', $user_id)
                ->latest()
                ->with('favoritable')
                ->paginate(10);

                foreach ($favoriteItems as $item) {
                    $item->favoritable = Service::where('id', $item->item_id)->with('category')->first();
                }    
               
            return view('frontend.frontend.client.pages.favourite-services.search-result', compact('favoriteItems'))->render();
        }
    }


    //Details
    public function favoriteDetails($id)
    {
        $user_id = Auth::guard('sanctum')->user()->id;

        $favoriteItem = FavoriteItem::where('id', $id)
            ->where('user_id', $user_id)
            ->where('type', 'service')
            ->first();
        $service= Service::where('id', $favoriteItem->item_id)->with('category')->first();    

        if ($service) {
            return view('frontend.frontend.client.pages.favourite-services.favourite-service-details', compact('service'));
        } else {
            return back()->with(FlashMsg::item_new(__('Favorite Service not found.')));
        }
    }

   
    public function favoriteRemove(Request $request,$item_id){

        $user_id = Auth::guard('sanctum')->user()->id;

        // Find and remove the favorite item
        $favoriteItem = FavoriteItem::where('id', $item_id)
            ->where('user_id', $user_id)
            ->where('type', 'service')
            ->first();

        if ($favoriteItem) {
            $favoriteItem->delete();
            return back()->with(FlashMsg::item_new(__('Item removed from favorites.')));
            
        } else {

            return back()->with(FlashMsg::item_new(__('Favorite item not found.')));
           
        }

    }

}
