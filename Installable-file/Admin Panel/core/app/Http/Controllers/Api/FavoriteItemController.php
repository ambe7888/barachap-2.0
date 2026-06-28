<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteItemResource;
use App\Http\Resources\FavoriteServiceStaffResource;
use App\Models\FavoriteItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoriteItemController extends Controller
{

    public function favoriteLists(Request $request){

        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'message' => __('Please Login'),
            ], 401);
        }

        $user_id = Auth::guard('sanctum')->user()->id;

        $favoriteItems = FavoriteItem::where('user_id', $user_id)
            ->latest()
            ->with('favoritable')
            ->paginate(10);

        if ($favoriteItems->isNotEmpty()) {
            return response()->json([
                'favorite_items' => FavoriteItemResource::collection($favoriteItems->items()),
                'pagination' => [
                    'total' => $favoriteItems->total(),
                    'count' => $favoriteItems->count(),
                    'per_page' => $favoriteItems->perPage(),
                    'current_page' => $favoriteItems->currentPage(),
                    'last_page' => $favoriteItems->lastPage(),
                    'next_page_url' => $favoriteItems->nextPageUrl(),
                    'prev_page_url' => $favoriteItems->previousPageUrl(),
                ]
            ]);
        }

        return response()->json([
            'message' => __('Favorite Not Available'),
        ]);
    }


    public function addFavorite(Request $request){

        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'message' => __('Please Login'),
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'type' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ],422);
        }

        $user_id = Auth::guard('sanctum')->user()->id;

        // find user service
        $service = FavoriteItem::where('item_id', $request->item_id)
            ->where('user_id', $user_id)
            ->where('type', $request->type)
            ->first();

        if (!empty($service)){
            return response()->json([
                'message' => __('Already added to favorites.'),
            ]);
        }

        FavoriteItem::create([
            'user_id' => $user_id,
            'item_id' => $request->item_id,
            'type' => $request->type,
        ]);

        return response()->json([
            'message' => __('Item successfully added to favorites.'),
        ]);
    }

    public function favoriteRemove(Request $request){

        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 422);
        }

        // Check if the user is authenticated
        if (!Auth::guard('sanctum')->check()) {
            return response()->json([
                'message' => __('Please Login'),
            ], 401);
        }

        $user_id = Auth::guard('sanctum')->user()->id;

        // Find and remove the favorite item
        $favoriteItem = FavoriteItem::where('item_id', $request->item_id)
            ->where('user_id', $user_id)
            ->where('type', $request->type)
            ->first();

        if ($favoriteItem) {
            $favoriteItem->delete();
            return response()->json([
                'message' => __('Item removed from favorites.'),
            ]);
        } else {
            return response()->json([
                'message' => __('Favorite item not found.'),
            ], 404);
        }

    }


}
