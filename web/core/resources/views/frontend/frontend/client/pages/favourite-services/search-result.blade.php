<table class="table_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Category')}}</th>
        <th>{{__('Total Sold')}}</th>
        <th>{{__('Price')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($favoriteItems->total() >=1)
        @foreach($favoriteItems as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->favoritable?->title }}</td>
                <td>  {{ $item->favoritable?->category?->name}}  </td>
                <td>{{ $item->favoritable?->sold_count }} </td>
                <td>{{ $item->favoritable?->price}}</td>
               
                <td class="actions">
                    <div class="multiple_action_buttons_new d-flex gap-2 flex-wrap">
                        <x-status.remove-from-favourite :url="route('client.favourite.services.remove',$item->id)"/>
                        <x-icon.view-icon :url="route('client.favourite.services.details',$item->id)"/>
                    </div> 
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'7'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$favoriteItems"/>
