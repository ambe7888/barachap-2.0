<table class="dataTablesExample">
    <thead>
    @can('admin-service-bulk-delete')
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
    @endcan
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
            @can('admin-service-bulk-delete')
                <td>
                    <x-bulk-action.bulk-delete-checkbox :id="$data->id"/>
                </td>
            @endcan
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
                @can('admin-service-published-status-change')
                     <span class="my-2"><x-status.admin-services-published-change :url="route('admin.service.published.status.change.by',$data->id)"/></span>
                @endcan
            </td>

            <!--status -->
            <td>
                @if($data->status==1)
                    <span class="alert alert-success">{{__('Approved')}}</span>
                @else
                    <span class="alert alert-warning">{{__('Pending')}}</span>
                @endif
                @can('admin-listing-status-change')
                  <span class="my-2"><x-status.status-change :url="route('admin.service.status.change.by',$data->id)"/></span>
                @endcan
            </td>

            <!--Action -->
            <td class="p-1 text-center new_style_added">
                {{-- Make Featured --}}
               <div class="multiple_action_buttons_new d-flex gap-2 flex-wrap">
                   @can('admin-service-edit')
                       @if($data->is_featured === 1)
                           <x-status.make-features-added :url="route('admin.service.make.featured',$data->id)"/>
                       @else
                           <x-status.make-features :url="route('admin.service.make.featured',$data->id)"/>
                       @endif
                   @endcan
                   @can('admin-service-edit')
                       <x-icon.edit-icon :url="route('admin.edit.service',$data->id)"/>
                   @endcan
                   <x-icon.view-icon :url="route('admin.service.details',$data->id)"/>
                   @can('admin-service-delete')
                       <x-popup.delete-popup :url="route('admin.service.delete',$data->id)"/>
                   @endcan
               </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    {{ $all_services->links() }}
</div>
