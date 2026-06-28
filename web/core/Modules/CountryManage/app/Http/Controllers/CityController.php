<?php

namespace Modules\CountryManage\app\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\CountryManage\app\Models\State;
use Modules\CountryManage\app\Models\City;

class CityController extends Controller
{
    public function getCityByState(Request $request){
        $request->validate(['id' => 'required|exists:states']);
        return City::where('state_id', $request->id)
            ->where('status', 1)
            ->get();
    }
    public function all_city(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'city'=> 'required|unique:cities|max:191',
                'timezone'=> 'required',
            ]);
            City::create([
                'city' => $request->city,
                'state_id' => $request->state,
                'timezone' => $request->timezone,
                'status' => $request->status,
            ]);
            FlashMsg::item_new(__('New State Successfully Added'));
        }
        $all_cities = City::latest()->paginate(10);
        $all_states = State::all_states();
        return view('countrymanage::city.all-city',compact('all_cities','all_states'));
    }

    public function edit_city(Request $request)
    {
        $request->validate([
            'edit_city'=> 'required|max:191|unique:cities,city,'.$request->city_id,
            'edit_timezone'=> 'required',
        ]);

        City::where('id',$request->city_id)->update([
            'city'=>$request->edit_city,
            'state_id'=>$request->edit_state,
            'timezone'=>$request->edit_timezone,
        ]);
        return redirect()->back()->with(FlashMsg::item_new(__('City Successfully Updated')));
    }

    public function change_status_city($id)
    {
        $state = City::select('status')->where('id',$id)->first();
        $state->status==1 ? $status=0 : $status=1;
        City::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(FlashMsg::item_new(__('Status Successfully Changed')));
    }

    public function delete_city($id)
    {
        City::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_delete(__('City Successfully Deleted')));
    }

    public function bulk_action_city(Request $request){
        City::whereIn('id',$request->ids)->delete();
        return redirect()->back()->with(FlashMsg::item_new(__('Selected City Successfully Deleted')));
    }

    public function import_settings()
    {
        return view('countrymanage::city.import-city');
    }

    public function update_import_settings(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:150000'
        ]);

        // work on file mapping
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

                return view('countrymanage::city.import-city', [
                    'import_data' => $csv_data,
                ]);
            }

        }
        FlashMsg::item_delete(__('something went wrong try again!'));
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

        $imported_cities = 0;
        $x = 0;
        $city = array_search($request->city, $csv_data, true);

        foreach ($data as $index => $item) {
            if($x == 0){
                $x++;
                continue ;
            }
            if ($index === 0) {
                continue;
            }
            if (empty($item[$city])){
                continue;
            }

            $find_state = City::where('city', $item[$city])->where('state_id', $request->state_id)->count();

            if ($find_state < 1) {
                $state_data = [
                    'city' => $item[$city] ?? '',
                    'state_id' => $request->state_id,
                    'timezone' => $request->timezone,
                    'status' => $request->status,
                ];
            }
            if ($find_state < 1) {
                City::create($state_data);
                $imported_cities++;
            }
        }
        FlashMsg::item_new($imported_cities.' '. __('States imported successfully'));
        return redirect()->route('admin.city.import.csv.settings');
    }


    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_cities = City::latest()->paginate(10);
            return view('countrymanage::city.search-result', compact('all_cities'))->render();
        }
    }

    // search category
    public function search_city(Request $request)
    {
        $all_cities= City::where('state', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->paginate(10);

        if($all_cities->total() >= 1){
            return view('countrymanage::city.search-result', compact('all_cities'))->render();
        }else{
            return response()->json([
                'status'=>__('nothing')
            ]);
        }
    }
}
