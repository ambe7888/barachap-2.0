<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Models\Backend\Category;
use App\Models\Offer;
use App\Models\Service;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function add_new_slider(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(
                [
                'type' => 'required',
                'status' => 'required',
                ]);

            // type check
            if($request->type == 'service'){
             $identity =  $request->identity_service_id;
                $type =  'service';
            }else if($request->type == 'offer'){
                $identity =  $request->identity_offer_id;
                $type =  'offer';
            }
            else{
               $identity = $request->identity_category_id;
               $type =  'category';
            }

            Slider::create([
                'image' => $request->image,
                'identity' => $identity,
                'type' => $type ,
                'status' => $request->status,
            ]);
            return redirect()->back()->with(FlashMsg::item_new(__('New Slider Added')));
        }

        $sliders = Slider::latest()->get();
        $services = Service::select('id', 'title')->where('status', 1)->get();
        $categories = Category::select('id', 'name')->where('status', 1)->get();
        $offers = Offer::select('id', 'title')->where('status', 1)->where('expires_at', '>=', now())->get();

        return view('backend.pages.slider.add_slider',compact('sliders', 'services', 'categories','offers'));
    }

    public function edit_slider(Request $request, $id=null)
    {

        if($request->isMethod('post')){
            $request->validate([
               'type' => 'required',
                'status' => 'required',
            ]);

            $old_image = Slider::select('image')->where('id',$id)->first();
            // type check
            if($request->type == 'service'){
                $identity =  $request->identity_service_id;
                $type =  'service';
            }else if($request->type == 'offer'){
                $identity =  $request->identity_offer_id;
                $type =  'offer';
            }
            else{
                $identity = $request->identity_category_id;
                $type =  'category';
            }

            Slider::where('id',$id)->update([
                'identity'=> $identity,
                'type'=> $type,
                'image'=>$request->image ?? $old_image->image,
                'status'=>  $request->status,
            ]);

            return redirect()->route('admin.slider.add')->with(FlashMsg::item_new(__('Slider Update Success')));
        }

        $slider = Slider::find($id);
        $sliders = Slider::latest()->get();
        $services = Service::select('id', 'title')->where('status', 1)->get();
        $categories = Category::select('id', 'name')->where('status', 1)->get();
        $offers = Offer::select('id', 'title')->where('status', 1)->where('expires_at', '>=', now())->get();

        return view('backend.pages.slider.edit_slider',compact('slider', 'services', 'categories', 'sliders','offers'));
    }

    public function delete_slider($id){
        Slider::find($id)->delete();
        return redirect()->route('admin.slider.add')->with(FlashMsg::item_new(__('Slider Deleted Success')));
    }

    public function bulk_action(Request $request){
        slider::whereIn('id',$request->ids)->delete();
        return response()->json([
            'status' => 'ok'
        ]);
    }
}
