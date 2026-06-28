<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderManageApiController extends Controller
{
    public  function sliderLists(){

        $sliders = Slider::select('id', 'image', 'type', 'identity', 'status')->where('status', 1)
            ->latest()
            ->paginate(10);

        return response()->json([
            'sliders' => SliderResource::collection($sliders->items()),
            'current_page' => $sliders->currentPage(),
            'total_pages' => $sliders->lastPage(),
            'total_items' => $sliders->total(),
        ]);
    }
}
