<?php

namespace App\Http\Controllers\Api\Provider;

use App\Actions\Media\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Resources\Staffs\StaffAllProviderStaffResource;
use App\Http\Services\StaffManageService;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProviderStaffController extends Controller
{
    protected $staffManageService;

    public function __construct(StaffManageService $staffManageService)
    {
        $this->staffManageService = $staffManageService;
    }
    public function staffList(Request $request)
    {
        $provider_id = auth('sanctum')->user()->id;
        $name = trim(strip_tags($request->input('name')));
        $status = $request->input('status');

        $query = Staff::whereNotNull('provider_id')
            ->where('provider_id', $provider_id);

        if ($name) {
            $query->where(function($query) use ($name) {
                $query->where('first_name', 'like', '%' . $name . '%')
                    ->orWhere('last_name', 'like', '%' . $name . '%');
            });
        }

        if ($status !== null) {
            $query->where('status', intval($status));
        }


        $all_staffs = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'all_staffs' => $all_staffs->isEmpty()
                ? ['message' => __("No Staff Found")]
                : StaffAllProviderStaffResource::collection($all_staffs),
            'pagination' => [
                'total' => $all_staffs->total(),
                'count' => $all_staffs->count(),
                'per_page' => $all_staffs->perPage(),
                'current_page' => $all_staffs->currentPage(),
                'last_page' => $all_staffs->lastPage(),
                'next_page_url' => $all_staffs->nextPageUrl(),
                'prev_page_url' => $all_staffs->previousPageUrl(),
            ]
        ], $all_staffs->isEmpty() ? 200 : 200);

    }

    public function staffCreate(Request $request)
    {

        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $auth_user = Auth::guard('sanctum')->user();
        if ($auth_user->type === 1) {
            return response()->json([
                'message' => __('Staff creation is only allowed for Provider.'),
            ], 200);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'admin_id' => 'nullable',
            'first_name' => 'required|max:191',
            'last_name' => 'required|min:2',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'required|unique:staff,phone',
            'about' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB Max
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if($request->file('image')){
            MediaHelper::insert_media_image($request,'web','image');
            $image_id = DB::getPdo()->lastInsertId();
        }

         Staff::create([
            'provider_id' => $auth_user->id,
            'admin_id' => null,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $image_id ?? null,
            'about' => $request->about,
            'status' => 1,
        ]);


        return response()->json([
            'message' => __('Staff Successfully Created'),
        ], 201);
    }

    public function staffEdit(Request $request, $staffId = null)
    {

        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $auth_user = Auth::guard('sanctum')->user();
        if ($auth_user->type === 1) {
            return response()->json([
                'message' => __('Staff creation is only allowed for Provider.'),
            ], 200);
        }

        $staff = Staff::find($staffId);

        if (!$staff) {
            return response()->json(['error' => __('Staff not found')], 201);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:191',
            'last_name' => 'required|min:2',
            'email' => 'required|email|unique:staff,email,' . $staffId,
            'phone' => 'required|unique:staff,phone,' . $staffId,
            'about' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB Max
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle image upload if present
        if ($request->hasFile('image')) {
            MediaHelper::insert_media_image($request, 'web', 'image');
            $image_id = DB::getPdo()->lastInsertId();
            $staff->image = $image_id;
        }


        $staff->provider_id = $auth_user->id;
        $staff->admin_id = null;
        $staff->first_name = $request->first_name;
        $staff->last_name = $request->last_name;
        $staff->email = $request->email;
        $staff->phone = $request->phone;
        $staff->about = $request->about;
        $staff->status = 1;
        $staff->save();

        return response()->json([
            'message' => __('Staff Successfully Updated'),
            'staff' => $staff
        ]);
    }

    public function staffDelete(Request $request)
    {

        $request->validate([
            'id' => 'required',
        ]);
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $staff = Staff::find($request->id);
        if (!$staff) {
            return response()->json(['error' => __('Staff not found')], 404);
        }
        $staff->delete();
        return response()->json([
            'message' => __('Staff successfully deleted'),
        ], 200);
    }
}
