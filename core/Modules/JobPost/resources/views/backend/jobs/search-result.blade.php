<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Budget')}}</th>
        <th>{{__('view')}}</th>
        <th>{{__('Date & Time')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Published Date')}}</th>
        <th>{{__('Publishing Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_jobs as $job)
        <tr>
            <td>{{ $job->id }}</td>
            <td>{{ $job->title }}</td>
            <td>{{ float_amount_with_currency_symbol($job->budget) }}</td>
            <td>{{ $job->view }}</td>
            <td>
                <div class="table_customer">
                    <div class="table_customer__flex">
                        <div class="table_customer__contents">
                            <h6 class="table_customer__title">{{ __('Date:') }} {{ \Carbon\Carbon::parse($job->date)->format('d M Y') }} </h6>
                            <h6 class="table_customer__title">{{ __('Time:') }} {{ \Carbon\Carbon::parse($job->time)->format('H:i:s') }}</h6>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <x-status.table.active-inactive :status="$job->status"/>
                <x-status.table.status-change :url="route('admin.jobs.status',$job->id)"/>
            </td>
            <td>
                {{ $job->published_at ? \Carbon\Carbon::parse($job->published_at)->format('F j, Y') : __('No date yet') }}
            </td>
            <td>
                @if($job->is_published == 1)
                    <span class="alert alert-success">{{__('Published')}}</span>
                @else
                    <span class="alert alert-warning">{{__('Unpublished')}}</span>
                @endif
                 <span class="my-2"><x-status.admin-services-published-change :url="route('admin.job.published.status.change',$job->id)"/></span>
            </td>

            <td class="actions">
                <a class="cmnBtn btn_5 btn_bg_info radius-5" href="{{ route('admin.jobs.offers.all', $job->id) }}">
                    {{ __('Job Offers') }}
                </a>
                <x-icon.view-icon :url="route('admin.jobs.details',$job->id)"/>
                <x-popup.delete-popup :url="route('admin.jobs.delete',$job->id)"/>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    {{ $all_jobs->links() }}
</div>
