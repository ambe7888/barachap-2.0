<?php

namespace Modules\Tax\app\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\CountryManage\app\Models\State;
use Modules\Tax\app\Models\CityTax;

class CityTaxController extends Controller
{
    public function index()
    {
        $all_states = State::where('status', 1)->get();
        $all_city_tax = CityTax::with('city')->get();
        return view('tax::backend.city-tax', compact('all_city_tax', 'all_states'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'state_id' => 'required',
            'city_id' => 'required|unique:city_taxes',
            'tax_rate' => 'required',
        ]);

        $city_tax = CityTax::create([
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'tax_rate' => $request->tax_rate,
        ]);

        return $city_tax->id
            ? back()->with(FlashMsg::item_new('city Tax'))
            : back()->with(FlashMsg::item_delete('city Tax'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
            'city_id' => 'required',
            'tax_rate' => 'required',
        ]);

        $updated = CityTax::findOrFail($request->id)->update([
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'tax_rate' => $request->tax_rate,
        ]);

        return $updated
            ? back()->with(FlashMsg::item_update('City Tax'))
            : back()->with(FlashMsg::item_delete('City Tax'));
    }


    public function destroy(CityTax $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::item_update('City Tax'))
            : back()->with(FlashMsg::item_delete('City Tax'));
    }

    public function bulk_action(Request $request)
    {
        $deleted = CityTax::where('id', $request->ids)->delete();
        if ($deleted) {
            return 'ok';
        }
    }
}
