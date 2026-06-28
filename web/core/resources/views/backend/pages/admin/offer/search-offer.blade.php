<table class="dataTablesExample">
    <thead>
    @can('admin-offer-bulk-delete')
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
    @endcan
    <th>{{__('ID')}}</th>
    <th>{{__('Title')}}</th>
    <th>{{__('image')}}</th>
    <th>{{__('Expire Date')}}</th>
    <th>{{__('Notification Send')}}</th>
    <th>{{__('Primary Offer')}}</th>
    <th>{{__('status')}}</th>
    <th>{{__('Action')}}</th>
    </thead>
    <tbody>
    @if(!empty($offers) && $offers->count())
        @foreach($offers as $data)
            <tr>
                @can('admin-offer-bulk-delete')
                    <td>
                        <x-bulk-action.bulk-delete-checkbox :id="$data->id"/>
                    </td>
                @endcan
                <td>{{$data->id}}</td>
                <td>{{$data->title}}</td>
                <td>
                    {!! render_image_markup_by_attachment_id($data->image,' ','thumb') !!}
                </td>
                <td>{{$data->expires_at}}</td>
                <td>
                    <div class="d-flex">
                        <!--provider Form -->
                        <form method="post" action="{{ route('Provider.sendNotification')}}" class="me-2">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="offer_id" value="{{ $data->id }}">
                                <input type="hidden" class="form-control" name="offer_title" value="{{ $data->title }}">
                                <input type="hidden" class="form-control" name="offer_subtitle" value="{{ $data->subTitle }}">
                                <button type="submit" class="btn btn-warning">Provider</button>
                            </div>
                        </form>   
                        <!--client Form -->
                        <form method="post" action="{{ route('Client.sendNotification')}}">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="offer_id" value="{{ $data->id }}">
                                <input type="hidden" class="form-control" name="offer_title" value="{{ $data->title }}">
                                <input type="hidden" class="form-control" name="offer_subtitle" value="{{ $data->subTitle }}">
                                <button type="submit" class="btn btn-info">Client</button>
                            </div>
                        </form>    
                    </div>
                </td>   
                <td>
                    @if($data->is_primary==1)
                        <span class="alert alert-success">{{__('Yes')}}</span>
                    @else
                        <span class="alert alert-danger">{{__('No')}}</span>
                    @endif
                    @can('offer-status-change')
                       <span class="my-2"><x-primary.primary-change :url="route('admin.offer.primaryOption.change',$data->id)"/></span>
                    @endcan
               </td> 
                <td>
                    @if($data->status==1)
                        <span class="alert alert-success">{{__('Active')}}</span>
                    @else
                        <span class="alert alert-danger">{{__('Inactive')}}</span>
                    @endif
                    @can('offer-status-change')
                       <span class="my-2"><x-status.status-change :url="route('admin.offer.status.change',$data->id)"/></span>
                    @endcan
               </td>

            <td>
            @can('admin-offer-edit')
                    <x-icon.edit-icon :url="route('admin.offer.edit',$data->id)"/>
                @endcan
                <x-icon.view-icon :url="route('admin.offer.details',$data->id)"/>
                @can('admin-offer-delete')
                    <x-popup.delete-popup :url="route('admin.offer.delete',$data->id)"/>
                @endcan
            </td>
            </tr>
        @endforeach
    @else
        <span>{{ __('Offer No Found') }}</span>
    @endif
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    {{ $offers->links() }}
</div>
