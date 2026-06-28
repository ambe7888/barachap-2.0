<?php

namespace Modules\CountryManage\app\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\CountryManage\app\Models\State;

class StateController extends Controller
{
    public function all_state(Request $request)
    {
        if($request->isMethod('post')){

            $request->validate([
                'state'=> 'required|unique:states|max:191',
            ]);

          $state = State::create([
                'state' => $request->state,
                'status' => $request->status,
            ]);

            if (!empty($state->state)) {
                try {
                    $response = Http::get('https://restcountries.com/v3.1/name/'.$state->state);
                    if ($response->ok()) {
                        $stateData = $response->json()[0] ?? null;
                        $dialCode = null;

                        // Check if the country data has the 'idd' key and retrieve the dialing code
                        if (isset($stateData['idd']['root'])) {
                            $dialCode = $stateData['idd']['root'];
                            if (isset($stateData['idd']['suffixes'][0])) {
                                $dialCode .= $stateData['idd']['suffixes'][0];
                            }
                        }

                        if ($stateData) {
                            $state->update([
                                'country_code' => $stateData['cca2'] ?? null,
                                'dial_code' =>  $dialCode,
                                'latitude' => $stateData['latlng'][0] ?? null,
                                'longitude' => $stateData['latlng'][1] ?? null,
                            ]);
                        } else {
                            FlashMsg::error(__('Country data not found for ' . $state->state));
                        }

                    } else {
                        FlashMsg::error(__('Failed to fetch state data for ' . $state->state));
                    }
                } catch (\Exception $e) {
                    FlashMsg::error(__('Error occurred while fetching state data: ') . $e->getMessage());
                }
            } else {
                FlashMsg::error(__('Invalid state name'));
            }

            FlashMsg::item_new(__('New state Successfully Added'));
        }

        $all_states = State::latest()->paginate(10);
        return view('countrymanage::state.all-state',compact('all_states'));
    }

    public function change_status_state($id)
    {
        $state = State::select('status')->where('id',$id)->first();
        $state->status==1 ? $status=0 : $status=1;
        State::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(FlashMsg::item_new(__('Status Successfully Changed')));
    }

    public function edit_state(Request $request)
    {
        $request->validate([
            'edit_state'=> 'required|max:191|unique:states,state,'.$request->state_id,
        ]);
        State::where('id',$request->state_id)->update([
            'state'=>$request->edit_state,
        ]);
        return redirect()->back()->with(FlashMsg::item_new(__('State Successfully Updated')));
    }

    public function delete_state($id)
    {
        State::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_delete(__('State Successfully Deleted')));
    }

    public function bulk_action_state(Request $request){
        State::whereIn('id',$request->ids)->delete();
        return redirect()->back()->with(FlashMsg::item_delete(__('Selected State Successfully Deleted')));
    }

    public function import_settings()
    {
        return view('countrymanage::state.import-state');
    }

    public function update_import_settings(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:150000'
        ]);

        //: work on file mapping
        if ($request->hasFile('csv_file')) {
            $file = $request->csv_file;
            $extenstion = $file->getClientOriginalExtension();
            if ($extenstion == 'csv') {
                //copy file to temp folder

                $old_file = Session::get('import_csv_file_name');
                if (file_exists('assets/uploads/import/' . $old_file)) {
                    @unlink('assets/uploads/import/' . $old_file);
                }
                $file_name_with_ext = $file->getClientOriginalName();

                $file_name = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
                $file_name = strtolower(Str::slug($file_name));

                $file_tmp_name = $file_name . time() . '.' . $extenstion;
                $file->move('assets/uploads/import', $file_tmp_name);

                $data = array_map('str_getcsv', file('assets/uploads/import/' . $file_tmp_name));
                $csv_data = array_slice($data, 0, 1);

                Session::put('import_csv_file_name', $file_tmp_name);

                return view('countrymanage::state.import-state', [
                    'import_data' => $csv_data,
                ]);
            }

        }
        FlashMsg::item_update(__('something went wrong try again!'));
        return back();
    }

    public function import_to_database_settings(Request $request)
    {
        $file_tmp_name = Session::get('import_csv_file_name');
        $data = array_map('str_getcsv', file('assets/uploads/import/' . $file_tmp_name));

        $csv_data = current(array_slice($data, 0, 1));
        $csv_data = array_map(function ($item) {
            return trim($item);
        }, $csv_data);

        $imported_states = 0;
        $x = 0;
        $state = array_search($request->state, $csv_data, true);

        foreach ($data as $index => $item) {
            if($x == 0){
                $x++;
                continue ;
            }
            $find_state = State::where('state', $item[$state] )->count();

            if ($find_state < 1) {
                $state_data = [
                    'state' => $item[$state] ?? '',
                    'status' => $request->status,
                ];
            }
            if ($find_state < 1) {
                State::create($state_data);
                $imported_states++;
            }
        }
        FlashMsg::item_new($imported_states.' '. __('States imported successfully'));
        return redirect()->route('admin.state.import.csv.settings');
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_states = State::latest()->paginate(10);
            return view('countrymanage::state.search-result', compact('all_states'))->render();
        }
    }

    // search category
    public function search_state(Request $request)
    {
        $all_states= State::where('state', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->paginate(10);
        if($all_states->total() >= 1){
            return view('countrymanage::state.search-result', compact('all_states'))->render();
        }else{
            return response()->json([
                'status'=>__('nothing')
            ]);
        }
    }
}
