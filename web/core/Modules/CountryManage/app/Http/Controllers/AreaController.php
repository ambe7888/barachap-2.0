<?php

namespace Modules\CountryManage\app\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\CountryManage\app\Models\Area;
use Modules\CountryManage\app\Models\State;
use Modules\CountryManage\app\Models\City;

class AreaController extends Controller
{

    public function all_area(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'state'=> 'required',
                'city'=> 'required',
                'area'=> 'required|unique:areas|max:191',
            ]);

            Area::create([
                'area' => $request->area,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'status' => $request->status,
            ]);

            FlashMsg::item_new(__('New area Successfully Added'));
        }
        $all_states = State::all_states();
        $all_cities = City::all_cities();
        $all_areas = Area::latest()->paginate(10);

        return view('countrymanage::area.all-area', compact('all_cities', 'all_states', 'all_areas'));

    }

    // edit area
    public function edit_area(Request $request)
    {
        $request->validate([
            'area'=> 'required|max:191|unique:areas,area,'.$request->area_id,
            'state'=> 'required',
            'city'=> 'required',
        ]);
        Area::where('id',$request->area_id)->update([
            'area'=>$request->area,
            'city_id'=>$request->city,
            'state_id'=>$request->state,
        ]);
        return redirect()->back()->with(FlashMsg::item_new(__('Area Successfully Updated')));
    }

    // change status
    public function area_status($id)
    {
        $area = Area::select('status')->where('id',$id)->first();
        $area->status==1 ? $status=0 : $status=1;
        Area::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(FlashMsg::item_new(__('Area Successfully Changed')));
    }

    // delete single area
    public function delete_area($id)
    {
        Area::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_delete(__('Area Successfully Deleted')));
    }

    // delete multi area
    public function bulk_action_area(Request $request){
        Area::whereIn('id',$request->ids)->delete();
        return redirect()->back()->with(FlashMsg::item_new(__('Selected area Successfully Deleted')));
    }

    // import settings
    public function import_settings()
    {
        return view('countrymanage::area.import-area');
    }

    // import settings update
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
                return view('countrymanage::area.import-area', [
                    'import_data' => $csv_data,
                ]);
            }

        }
        FlashMsg::item_delete(__('something went wrong try again!'));
        return back();
    }

    // import city to database
    public function import_to_database_settings(Request $request)
    {
        $request->validate([
            'area' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
        ]);

        $file_tmp_name = Session::get('import_csv_file_name');
        $data = array_map('str_getcsv', file('assets/uploads/import/' . $file_tmp_name));

        $csv_data = current(array_slice($data, 0, 1));
        $csv_data = array_map(function ($item) {
            return trim($item);
        }, $csv_data);

        $imported_areas = 0;
        $x = 0;
        $area = array_search($request->area, $csv_data, true);

        foreach ($data as $index => $item) {
            if($x == 0){
                $x++;
                continue ;
            }
            if ($index === 0) {
                continue;
            }
            if (empty($item[$area])){
                continue;
            }

            $find_area = Area::where('area', $item[$area])
                ->where('state_id', $request->state_id)
                ->where('city_id', $request->city_id)
                ->count();

            if ($find_area < 1) {
                $area_data = [
                    'area' => $item[$area] ?? '',
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'status' => $request->status,
                ];
                Area::create($area_data);
                $imported_areas++;
            }

        }
        FlashMsg::item_new($imported_areas.' '. __('Areas imported successfully'));
        return redirect()->route('admin.area.import.csv.settings');
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_areas= Area::latest()->paginate(10);
            return view('countrymanage::area.search-result', compact('all_areas'))->render();
        }
    }

    // search city
    public function search_area(Request $request)
    {
        $all_areas= Area::where('area', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        if($all_areas->total() >= 1){
            return view('countrymanage::area.search-result', compact('all_areas'))->render();
        }else{
            return response()->json([
                'status'=>__('nothing')
            ]);
        }

    }
}
