<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Provider Info')}}</th>
        <th>{{__('Budget')}}</th>
        <th>{{__('Cover Letter')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_offers as $offer)
        <tr>
            <td>{{ $offer->id }}</td>
            <td>
                <div class="table_customer">
                    <div class="table_customer__flex">
                        <div class="table_customer__thumb">
                           {!! render_image_markup_by_attachment_id($offer->provider?->image) !!}
                        </div>
                        <div class="table_customer__contents">
                            <h6 class="table_customer__title">{{ $offer->provider?->fullname }}</h6>
                            <p class="table_customer__para mt-1">{{ $offer->provider?->email }}</p>
                            <p class="table_customer__para mt-1">{{ $offer->provider?->phone }}</p>
                        </div>
                    </div>
                </div>
            </td>

            <td>{{ float_amount_with_currency_symbol($offer->budget) }}</td>

            <td class="cover-letter" data-cover-letter="{{ $offer->cover_letter }}">
                {{ Str::limit($offer->cover_letter, 20) }} <span class="btn btn-info btn-sm">{{__('Details')}}</span>
            </td>

            <td>
                @if($offer->is_hired == 1)
                    <span class="btn btn-danger">{{ __('Hired') }}</span>
                @else
                    <span class="btn btn-light">{{ __('Not Hired') }}</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    {{ $all_offers->links() }}
</div>
