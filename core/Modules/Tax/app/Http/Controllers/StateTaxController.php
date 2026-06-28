<?php

namespace Modules\Tax\app\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\CountryManage\app\Models\State;
use Modules\Tax\app\Models\StateTax;


class StateTaxController extends Controller
{
    public function index()
    {
        $all_states = State::all();
        $all_states_tax = StateTax::with('state')->get();
        return view('tax::backend.state-tax', compact('all_states_tax', 'all_states'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required|unique:state_taxes,state_id,' . ($request->state_id ?? 'NULL'),
            'tax_rate' => 'required',
        ]);

        // convert numeric value
        $formatted_tax_rate = number_format($request->tax_rate, 2);

        $state_tax = StateTax::create([
            'state_id' => $request->state_id,
            'tax_rate' => $formatted_tax_rate,
        ]);

        return $state_tax->id
            ? back()->with(FlashMsg::item_new('State Tax'))
            : back()->with(FlashMsg::item_delete('State Tax'));
    }

    public function update(Request $request)
    {

        $request->validate([
            'state_id' => [
                'required',
                // Ensure uniqueness excluding the current record
                Rule::unique('state_taxes', 'state_id')->ignore($request->state_id),
            ],
            'tax_rate' => 'required',
        ]);

        $state_tax = StateTax::findOrFail($request->state_id);
        $formatted_tax_rate = number_format($request->tax_rate, 2);

        // Update the StateTax record
        $updated = $state_tax->update([
            'state_id' => $request->state_id,
            'tax_rate' => $formatted_tax_rate,
        ]);

        return $updated
            ? back()->with(FlashMsg::item_new('State Tax'))
            : back()->with(FlashMsg::item_delete('State Tax'));
    }


    public function destroy(StateTax $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::item_delete('State Tax'))
            : back()->with(FlashMsg::item_delete('State Tax'));
    }

    public function bulk_action(Request $request)
    {
        $deleted = StateTax::where('id', $request->ids)->delete();
        if ($deleted) {
            return 'ok';
        }
    }
}
