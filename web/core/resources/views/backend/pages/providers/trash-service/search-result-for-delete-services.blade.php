<table class="table_activation">
    <thead>
        
    <tr>
    <th>{{__('ID')}}</th>
    <th>{{__('Image')}}</th>
    <th>{{__('Title')}}</th>
    <th>{{__('Category')}}</th>
    <th>{{__('Price')}}</th>
    <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($all_services->total() >=1)
        @foreach($all_services as $data)
            <tr>
            <td>{{$data->id}}</td>
            <td> {!! render_image_markup_by_attachment_id($data->image,'','thumb') !!}</td>
            <td>{{$data->title}}</td>
            <td>{{optional($data->category)->name}}</td>

            <td>
                @if($data->discount_price > 0)
                    <span class="discount-price"> {{ float_amount_with_currency_symbol($data->discount_price) }} </span>
                @else
                    {{ float_amount_with_currency_symbol($data->price) }}
                @endif
            </td>

                <td>
                    <x-popup.restore-popup :title="__('Restore Service')" :url="route('admin.service.restore',$data->id)" :class="'cmnBtn btn_5 btn_bg_blue radius-5'"/>
                    @if(isset($isDemoMiddlewareIsEnabled))  
                        <button class="btn btn-warning" onclick="alert('This is a demo website. You cannot delete permanently.');">
                            {{ __('Delete Permanently')}}
                        </button>   
                        
                    @else     
                        <x-popup.delete :title="__('Delete Permanently')" :url="route('admin.service.permanent.delete',$data->id)"/>
                    @endif        
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'5'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_services"/>
