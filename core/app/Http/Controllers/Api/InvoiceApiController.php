<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SubOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceApiController extends Controller
{
    public function orderInvoiceGenerate($id=null)
    {

        $order_details = Order::with(
            'client',
            'subOrders.subOrderAddons',
            'subOrders.subOrderLocations.city',
            'subOrders.subOrderLocations.area',
            'subOrders.staff',
            'subOrders.service'
        )->where('id', $id)
        ->first();

        // Check if order exists
        if (!$order_details) {
            abort(404, __('Order not found'));
        }

        // Get the site logo for the order invoice
        $site_logo = get_image_url_id_wise(get_static_option('site_logo'));
        // Generate PDF from the view
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('backend.pages.orders.invoice1.orders-invoice', compact('order_details', 'site_logo'))->setPaper('A4') ;

        // Return the PDF as a downloadable file
        return response()->stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="invoice.pdf"',
            ]
        );
    }

    public function providerOrderInvoiceGenerate($id=null)
    {

        $order_details = SubOrder::with(
            'order',
            'client',
            'service',
            'subOrderAddons',
            'subOrderLocations.city',
            'subOrderLocations.area',
            'staff',
        )->where('id', $id)
        ->first();

        // Check if order exists
        if (!$order_details) {
            abort(404, __('Order not found'));
        }

        // Get the site logo for the order invoice
        $site_logo = get_image_url_id_wise(get_static_option('site_logo'));
        // Generate PDF from the view
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('backend.pages.orders.invoices.provider.orders-invoice', compact('order_details', 'site_logo'));

        // Return the PDF as a downloadable file
        return response()->stream(
            function () use ($pdf) {
                echo $pdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="invoice.pdf"',
            ]
        );
    }
}
