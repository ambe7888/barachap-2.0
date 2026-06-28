<table class="table_activation">
    <thead>
        
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Budget')}}</th>
        <th>{{__('view')}}</th>
    <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($all_jobs->total() >=1)
        @foreach($all_jobs as $job)
            <tr>
                <td>{{ $job->id }}</td>
                <td>{{ $job->title }}</td>
                <td>{{ float_amount_with_currency_symbol($job->budget) }}</td>
                <td>{{ $job->view }}</td>
                <td>
                    
                    <x-popup.restore-popup :title="__('Restore Service')" :url="route('admin.job.restore',$job->id)" :class="'cmnBtn btn_5 btn_bg_blue radius-5'"/>
                    @if(isset($isDemoMiddlewareIsEnabled))
                        <button class="btn btn-warning" onclick="alert('This is a demo website. You cannot delete permanently.');">
                            {{ __('Delete Permanently')}}
                        </button>  
                    @else  
                        <x-popup.delete :title="__('Delete Permanently')" :url="route('admin.job.permanent.delete',$job->id)"/>           
                    @endif        
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'5'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_jobs"/>
