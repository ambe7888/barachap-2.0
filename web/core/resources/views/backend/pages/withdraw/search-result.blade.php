<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{ __('ID') }}</th>
        <th>{{ __('Amount') }}</th>
        <th>{{ __('Withdraw Fee') }}</th>
        <th>{{ __('Gateway Name') }}</th>
        <th>{{ __('Provider Info') }}</th>
        <th>{{ __('Status') }}</th>
        <th>{{ __('Action') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($all_request as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ float_amount_with_currency_symbol($request->amount) }}</td>
            <td>{{ float_amount_with_currency_symbol($request->fee) }}</td>
            <td>{{ ucfirst($request?->gateway_name?->name) }}</td>
            <td>
                <p>{{ __('Name:') }} {{ $request?->user?->fullname }}</p>
                <p>{{ __('Email:') }} {{ $request?->user?->email }}</p>

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
            <td>
                <x-status.table.withdraw-request-status :status="$request->status" />
            </td>
            <td class="actions">

                <x-icon.view-icon :url="route('admin.withdraw.request.details', $request->id)"/>

                @if($request->status == 3)
                    <span class="text-danger"> {{ __('Cancelled') }}</span>
                @else
                    @can('withdraw-status-change')
                        <a class="cmnBtn btn_5 btn_bg_warning radius-5 edit_gateway_modal update-request"
                           data-bs-toggle="modal"
                           data-bs-target="#edit-request-modal"
                           data-amount="{{ $request->amount }}"
                           data-id="{{ $request->id }}"
                           data-status="{{ $request->status }}">
                            {{ __('Update Status') }}
                        </a>
                    @endcan
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_request"/>
