<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{ __('ID') }}</th>
        <th>{{ __('Amount') }}</th>
        <th>{{ __('Withdraw Fee') }}</th>
        <th>{{ __('Gateway Name') }}</th>
        <th>{{ __('Balance Info') }}</th>
        <th>{{ __('Action') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($all_request as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ float_amount_with_currency_symbol($request->amount) }}</td>
            <td>{{ float_amount_with_currency_symbol($request->fee) }}</td>
            <td>{{ ucfirst($request?->gateway_name->name) }}</td>
            <td>
                <p class="mt-2"> <strong class="text-info"> {{ __('Available Balance:') }}  </strong>
                    {{ float_amount_with_currency_symbol($request?->user?->balance?->available_balance) }}
                </p>
                <p> <strong class="text-primary"> {{ __('Total Earnings:') }}  </strong>
                    {{ float_amount_with_currency_symbol($request?->user?->balance?->total_earnings) }}
                </p>
                <p> <strong class="text-success">{{ __('Total Withdrawn:') }}</strong>
                    {{ float_amount_with_currency_symbol($request?->user?->balance?->total_withdrawn) }}
                </p>
            </td>
            <td class="actions">

                <x-icon.view-icon :url="route('provider.withdraw.request.details', $request->id)"/>

              
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_request"/>
