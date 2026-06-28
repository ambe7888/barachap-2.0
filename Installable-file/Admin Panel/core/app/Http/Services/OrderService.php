<?php

namespace App\Http\Services;

use App\Models\Order;
use Intervention\Image\Facades\Image;
use Modules\JobPost\app\Models\JobPostOffer;


class OrderService
{
    public function manual_order($request, $client_id,$order_id)
    {

        if($request->hasFile('manual_payment_image')){
            $manual_payment_image = $request->manual_payment_image;
            $img_ext = $manual_payment_image->extension();
            $manual_payment_image_name = 'manual_attachment_'.time().'.'.$img_ext;

            if(in_array($img_ext,['jpg','jpeg','png','pdf'])){
                $manual_image_path = 'assets/uploads/manual-payment/order';
                if (!file_exists($manual_image_path)) {
                    mkdir($manual_image_path, 0755, true);
                }
                if (in_array($img_ext,['jpg','jpeg','png'])) {
                    $resize_full_image = Image::make($request->manual_payment_image);
                    $resize_full_image->save($manual_image_path .'/'. $manual_payment_image_name);
                }else{
                    $manual_payment_image->move($manual_image_path,$manual_payment_image_name);
                }

                $order=Order::find($order_id);
                $order->payment_gateway = 'manual_payment';
                $order->payment_attachment = $manual_payment_image_name;
                $order->save();


                toastr_success('Order successfully completed.');
                return redirect()->route('client.order.payment.process.success',$order_id);
            }else{
                return back()->with(toastr_warning(__('Image type not supported')));
            }
        }
    }
    public function manual_job_order($request, $client_id,$order_id,$job_post_id,$job_post_offer_id)
    {

        if($request->hasFile('manual_payment_image')){
            $manual_payment_image = $request->manual_payment_image;
            $img_ext = $manual_payment_image->extension();
            $manual_payment_image_name = 'manual_attachment_'.time().'.'.$img_ext;

            if(in_array($img_ext,['jpg','jpeg','png','pdf'])){
                $manual_image_path = 'assets/uploads/manual-payment/order';
                if (!file_exists($manual_image_path)) {
                    mkdir($manual_image_path, 0755, true);
                }
                if (in_array($img_ext,['jpg','jpeg','png'])) {
                    $resize_full_image = Image::make($request->manual_payment_image);
                    $resize_full_image->save($manual_image_path .'/'. $manual_payment_image_name);
                }else{
                    $manual_payment_image->move($manual_image_path,$manual_payment_image_name);
                }

                $order=Order::find($order_id);
                $order->payment_gateway = 'manual_payment';
                $order->payment_attachment = $manual_payment_image_name;
                $order->save();
                $job_post_offer=JobPostOffer::where('id',$job_post_offer_id)->where('job_post_id',$job_post_id)->first();
                if ($job_post_offer) {
                    $job_post_offer->status = 1;
                    $job_post_offer->save();
                }


                toastr_success('Order successfully completed.');
                return redirect()->route('client.order.payment.process.success',$order_id);
            }else{
                return back()->with(toastr_warning(__('Image type not supported')));
            }
        }
    }
}
