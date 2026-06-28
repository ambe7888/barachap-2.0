<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Location\AreaResource;
use App\Http\Resources\Location\CityResource;
use App\Http\Resources\Location\StateResource;
use Illuminate\Http\Request;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\City;
use Modules\CountryManage\app\Models\State;

class StateCityAreaController extends Controller
{
    public function allState(Request $request)
    {
        $queryString = trim(strip_tags($request->input('q')));
        $states = State::where('state', 'LIKE', '%' . $queryString . '%')
            ->paginate(20)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'states' => $states->isEmpty()
                ? ['message' => __("No State Found")]
                : StateResource::collection($states),
            'pagination' => [
                'total' => $states->total(),
                'count' => $states->count(),
                'per_page' => $states->perPage(),
                'current_page' => $states->currentPage(),
                'last_page' => $states->lastPage(),
                'next_page_url' => $states->nextPageUrl(),
                'prev_page_url' => $states->previousPageUrl(),
            ]
        ], $states->isEmpty() ? 200 : 200);
    }

    public function allCity(Request $request)
    {
        $queryString = trim(strip_tags($request->input('q')));
        $stateId = $request->input('state_id');

        $cities = City::when($stateId, function ($query, $stateId) {
            return $query->where('state_id', $stateId);
        })
        ->where('city', 'LIKE', '%' . $queryString . '%')
        ->paginate(20)
        ->withQueryString();

        return response()->json([
            'success' => true,
            'states' => $cities->isEmpty()
                ? ['message' => __("No City Found")]
                : CityResource::collection($cities),
            'pagination' => [
                'total' => $cities->total(),
                'count' => $cities->count(),
                'per_page' => $cities->perPage(),
                'current_page' => $cities->currentPage(),
                'last_page' => $cities->lastPage(),
                'next_page_url' => $cities->nextPageUrl(),
                'prev_page_url' => $cities->previousPageUrl(),
            ]
        ], $cities->isEmpty() ? 200 : 200);
    }

    public function allArea(Request $request)
    {
        $queryString = trim(strip_tags($request->input('q')));
        $stateId = $request->input('state_id');
        $cityId = $request->input('city_id');

        $areas = Area::when($stateId, function ($query, $stateId) {
            return $query->where('state_id', $stateId);
        })
         ->when($cityId, function ($query, $cityId) {
                return $query->where('city_id', $cityId);
         })
        ->where('area', 'LIKE', '%' . $queryString . '%')
        ->paginate(20)
        ->withQueryString();

        return response()->json([
            'success' => true,
            'states' => $areas->isEmpty()
                ? ['message' => __("No Area Found")]
                : AreaResource::collection($areas),
            'pagination' => [
                'total' => $areas->total(),
                'count' => $areas->count(),
                'per_page' => $areas->perPage(),
                'current_page' => $areas->currentPage(),
                'last_page' => $areas->lastPage(),
                'next_page_url' => $areas->nextPageUrl(),
                'prev_page_url' => $areas->previousPageUrl(),
            ]
        ], $areas->isEmpty() ? 200 : 200);
    }
}
