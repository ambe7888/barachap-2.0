<div class="table_wrapper custom_dataTable">
    <table class="dataTablesExample">
        <thead>
        <th>{{__('ID')}}</th>
        <th>{{__('Identity')}}</th>
        <th>{{__('Image')}}</th>
        <th>{{__('Type')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
        </thead>
        <tbody>
        @if($sliders->count() > 0)
            @foreach($sliders as $data)
                <tr>
                    <td>{{$data->id}}</td>
                    <td>{{$data->identity}}</td>

                    <td>{!! render_image_markup_by_attachment_id($data->image,'','thumb') !!}</td>

                    <td>
                        @if($data->type == 'service')
                            <a href="javascript:void(0)" class="alert alert-info">{{__("Service")}}</a>
                        @elseif($data->type == 'category')
                            <a href="javascript:void(0)" class="alert alert-info">{{__("Category")}}</a>
                        @elseif($data->type == 'offer')
                            <a href="javascript:void(0)" class="alert alert-info">{{__("Offer")}}</a>    
                        @endif
                    </td>

                    <td>
                        @if($data->status == 1)
                            <a href="javascript:void(0)" class="alert alert-success">{{__("Publish")}}</a>
                        @elseif($data->status === 0)
                            <a href="javascript:void(0)" class="alert alert-warning">{{__("Unpublished")}}</a>
                        @endif
                    </td>
                    <td>
                        <x-popup.delete-popup :url="route('admin.slider.delete',$data->id)"/>
                        <a href="{{route('admin.slider.edit', $data->id)}}" class="cmnBtn btn_5 btn_bg_info radius-5">{{__('Edit')}}</a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
