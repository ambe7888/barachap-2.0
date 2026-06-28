<?php

namespace Modules\Coupon\app\Http\Controllers;

use App\Enums\CouponEnum;
use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Models\Backend\Category;
use App\Models\Backend\ChildCategory;
use App\Models\Backend\SubCategory;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Coupon\app\Models\Coupon;

class AdminCouponController extends Controller
{
    public function index()
    {
        $all_coupons = Coupon::all();
        return view('coupon::all-coupon')->with([
            'all_coupons' => $all_coupons
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'code' => 'required|string|max:191|unique:coupons',
            'discount' => 'required|string|max:191',
            'discount_type' => 'required|string|max:191',
            'expire_date' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);

        $product_coupon = Coupon::create([
            'title' => $request->title,
            'code' => $request->code,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
            'expire_date' => $request->expire_date,
            'status' => $request->status,
        ]);

        return $product_coupon->id
            ? back()->with(FlashMsg::item_new(' Coupon'))
            : back()->with(FlashMsg::item_delete(' Coupon'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'code' => 'required|string|max:191',
            'discount' => 'required|string|max:191',
            'discount_type' => 'required|string|max:191',
            'expire_date' => 'required|string|max:191',
            'status' => 'required|string|max:191',
        ]);


        $updated = Coupon::find($request->id)->update([
            'title' => $request->title,
            'code' => $request->code,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
            'expire_date' => $request->expire_date,
            'status' => $request->status,
        ]);

        return $updated
            ? back()->with(FlashMsg::item_new(' Coupon'))
            : back()->with(FlashMsg::item_delete(' Coupon'));
    }


    public function destroy(Coupon $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::item_new(' Coupon'))
            : back()->with(FlashMsg::item_delete(' Coupon'));
    }

    public function check(Request $request)
    {
        return (bool) Coupon::where('code', $request->code)->count();
    }

    public function bulk_action(Request $request)
    {
        Coupon::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function allProductsAjax()
    {
        $all_products = Service::select('id', 'name')->where('status_id', 1)->get();
        return response()->json($all_products);
    }
}
