<?php

namespace App\Http\Controllers;

use App\Models\Backend\FormBuilder;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function orderInvoiceGenerate($id=null)
    {
        $order_details = Order::with(
            'client',
            'subOrders.subOrderAddons',
            'subOrders.subOrderLocations',
            'subOrders.subOrderLocations.city',
            'subOrders.subOrderLocations.area',
            'subOrders.staff',
            'subOrders.service'
        )->find($id);

        // Check if order exists
        if (!$order_details) {
            abort(404, __('Order not found'));
        }

        // Get the site logo for the order invoice
        $site_logo = get_image_url_id_wise(get_static_option('site_logo'));
        // Generate PDF from the view
        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('backend.pages.orders.invoice1.orders-invoice', compact('order_details', 'site_logo'))
            ->setPaper('A4') // Set A4 paper size
            ->stream();
    }
}
