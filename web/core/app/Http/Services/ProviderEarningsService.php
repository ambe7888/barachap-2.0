<?php

namespace App\Http\Services;

use App\Models\UserBalance;
use App\Models\SubOrder;

class ProviderEarningsService
{
    public function updateProviderEarnings(SubOrder $subOrder)
    {
        if ($subOrder->order->payment_gateway == "cash_on_delivery") {
            $providerId = $subOrder->provider_id;
            $complete_order_balance_with_tax = $subOrder->total; // Total amount including tax
            $complete_order_tax = $subOrder->tax;  // Tax amount
            $admin_commission_amount = $subOrder->commission_amount;   // Admin commission amount
            // provider's balance
            $providerBalance = UserBalance::where('user_id', $providerId)->first();
            if ($providerBalance) {
                // Calculate order balance without tax
                $complete_order_balance_without_tax = $complete_order_balance_with_tax - $complete_order_tax;
                // Calculate provider's earning amount
                $provider_earning_amount_per_order = $complete_order_balance_without_tax;
                // Update the provider's balance
                $providerBalance->update([
                    'available_balance' => $providerBalance->available_balance -$admin_commission_amount,
                    'total_earnings' => $providerBalance->total_earnings + $provider_earning_amount_per_order,
                ]);
            }
        }
        else{
            $providerId = $subOrder->provider_id;
            $complete_order_balance_with_tax = $subOrder->total; // Total amount including tax
            $complete_order_tax = $subOrder->tax;  // Tax amount
            $admin_commission_amount = $subOrder->commission_amount;   // Admin commission amount
            // provider's balance
            $providerBalance = UserBalance::where('user_id', $providerId)->first();
            if ($providerBalance) {
                // Calculate order balance without tax
                $complete_order_balance_without_tax = $complete_order_balance_with_tax - $complete_order_tax;
                // Calculate provider's earning amount
                $provider_earning_amount_per_order = $complete_order_balance_without_tax - $admin_commission_amount;
                // Update the provider's balance
                $providerBalance->update([
                    'available_balance' => $providerBalance->available_balance + $provider_earning_amount_per_order,
                    'total_earnings' => $providerBalance->total_earnings + $provider_earning_amount_per_order,
                ]);
            }
        }
    }
}
