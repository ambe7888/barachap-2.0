<table class="dataTablesExample">
    <thead>
    <th class="no-sort">
        <div class="mark-all-checkbox">
            <input type="checkbox" class="all-checkbox">
        </div>
    </th>
    <th>{{__('ID')}}</th>
    <th>{{__('Image')}}</th>
    <th>{{__('Title')}}</th>
    <th>{{__('Category')}}</th>
    <th>{{__('Price')}}</th>
    <th>{{__('Crate Date')}}</th>
    <th>{{__('Publishing Status')}}</th>
    <th>{{__('Status')}}</th>
    <th>{{__('Action')}}</th>
    </thead>
    <tbody>
    @foreach($all_services as $data)
        <tr>
            <td>
                <x-bulk-action.bulk-delete-checkbox :id="$data->id"/>
            </td>
          
            <td>{{$data->id}}</td>
            <td> {!! render_image_markup_by_attachment_id($data->image,'','thumb') !!}</td>
            <td>{{$data->title}}</td>
            <td>{{optional($data->category)->name}}</td>
            <td>
                 @if($data->discount_price > 0)
                <span class="discount-price">
                   {{ float_amount_with_currency_symbol($data->discount_price) }}
                </span>
                @else
                    {{ float_amount_with_currency_symbol($data->price) }}
                @endif
            </td>

            <td>
                <strong class="subCap">{{ $data->created_at->diffForHumans() }}</strong>
            </td>
            <!--published -->
            <td>
                @if($data->is_published === 1)
                    <span class="alert alert-success custom_status_style">{{__('Published')}}</span>
                @else
                    <span class="alert alert-warning custom_status_style">{{__('Unpublished')}}</span>
                @endif
                <span class="my-2"><x-status.admin-services-published-change :url="route('provider.service.published.status.change.by',$data->id)"/></span>
               
            </td>

            <!--status -->
            <td>
                @if($data->status==1)
                    <span class="alert alert-success">{{__('Approved')}}</span>
                @else
                    <span class="alert alert-warning">{{__('Pending')}}</span>
                @endif
               
                <span class="my-2"><x-status.status-change :url="route('provider.service.status.change.by',$data->id)"/></span>
                
            </td>

            <!--Action -->
            <td class="p-1 text-center new_style_added">
                {{-- Make Featured --}}
               <div class="multiple_action_buttons_new d-flex gap-2 flex-wrap">
                    @if($data->is_featured === 1)
                        <x-status.make-features-added :url="route('provider.service.make.featured',$data->id)"/>
                    @else
                        <x-status.make-features :url="route('provider.service.make.featured',$data->id)"/>
                    @endif
                  
                    <x-icon.edit-icon :url="route('provider.edit.service',$data->id)"/>
                  
                   <x-icon.view-icon :url="route('provider.service.details',$data->id)"/>
                    <x-popup.delete-popup :url="route('provider.delete.service',$data->id)"/>
               </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    {{ $all_services->links() }}
</div>
