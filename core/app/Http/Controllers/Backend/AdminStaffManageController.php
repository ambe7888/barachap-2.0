<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminStaffManageController extends Controller
{
    public function add_staff(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'first_name' => 'required|max:191',
                'last_name' => 'required|max:191',
                'email' => 'required|email|unique:staff,email|max:191',
                'phone' => 'required|unique:staff,phone|max:191',
                'image' => 'nullable',
                'about' => 'nullable',
            ]);

            $admin_id = Auth::guard('admin')->user()->id;

            Staff::create([
                'admin_id' => $admin_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'image' => $request->image,
                'about'=> $request->about,
            ]);

            return back()->with(FlashMsg::item_new(__('Staff Successfully Created')));
        }
        return view('backend.pages.staffs.add-staff');
    }
    //all user
    public function all_staffs()
    {
        $admin_id = Auth::guard('admin')->id();
        $all_staffs = Staff::whereNotNull('admin_id')
        ->where('admin_id', $admin_id)
        ->latest()
        ->paginate(10);

        return view('backend.pages.staffs.all-staffs',compact('all_staffs'));
    }


    public function changeStatus($id){
        $admin_id = Auth::guard('admin')->id();
        $staff = Staff::whereNotNull('admin_id')
            ->where('admin_id', $admin_id)
            ->where('id',$id)
            ->first();

        if($staff->status==1){
            Staff::where('id',$id)->update(['status' => 0]);
        }else{
            Staff::where('id',$id)->update([
                'status' => 1,
            ]);
        }
        return redirect()->back()->with(FlashMsg::item_new(__('Status Change Success')));
    }

    // user pagination
    function staff_paginate(Request $request)
    {
        if($request->ajax()){
            $admin_id = Auth::guard('admin')->id();
            $all_staffs = Staff::whereNotNull('admin_id')
                ->where('admin_id', $admin_id)
                ->latest()
                ->paginate(10);

            return view('backend.pages.staffs.search-result',compact('all_staffs'));
        }
    }


    // search user
    public function search_staff(Request $request)
    {
        $admin_id = Auth::guard('admin')->id();
        $all_staffs= Staff::whereNotNull('admin_id')
            ->where('admin_id', $admin_id)->where(function($q) use($request){
            $q->where('first_name', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('last_name', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('email', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('phone', 'LIKE', "%". strip_tags($request->string_search) ."%");
        })->paginate(10);
        return $all_staffs->total() >= 1 ? view('backend.pages.staffs.search-result', compact('all_staffs'))->render() : response()->json(['status'=>__('nothing')]);
    }

    public function edit_info(Request $request, $id)
    {

        $admin_id = Auth::guard('admin')->id();
        if ($request->isMethod('post')) {
            $request->validate([
                'first_name'=>'required',
                'last_name'=>'required',
                'email'=>'required|max:191|unique:staff,email,'.$request->id,
                'phone'=>'required|max:191|unique:staff,phone,'.$request->id,
            ]);

            Staff::whereNotNull('admin_id')
                ->where('admin_id', $admin_id)
                ->where('id', $id)->update([
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'email'=>$request->email,
                    'phone'=>$request->phone,
                    'about'=>$request->about,
                    'image'=>$request->image,
                ]);

            FlashMsg::item_new(__('Staff Info Successfully Updated'));
            return redirect()->route('admin.staff.all')->with(FlashMsg::item_new(__('Staff Info Successfully Updated')));
        }

         $staff =  Staff::whereNotNull('admin_id')->where('id', $request->id)->first();

        return view('backend.pages.staffs.edit-staff', compact('staff'));

    }

    public function staffDelete($id){
        try {
            $staff = Staff::findOrFail($id);
            $staff->delete();
            return redirect()->back()->with(FlashMsg::item_delete(__('Staff Deleted Success')));
        }catch (\Exception $e) {
            return redirect()->back()->with('error', __('An error occurred while deleting the service.'));
        }
    }
}
