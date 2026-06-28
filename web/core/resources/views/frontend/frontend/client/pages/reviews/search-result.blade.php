<table class="table_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Reviewer Name')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Type')}}</th>
        <th>{{__('Rating')}}</th>
        <th>{{__('Created at')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($all_reviews->total() >=1)
        @foreach($all_reviews as $review)
            <tr>
                <td>{{ $review->id }}</td>
                <td>{{ $review->reviewer?->first_name.' '.$review->reviewer?->last_name }}</td>
                @php
                    if($review->service_id)
                    {
                        $title=$review->service?->title;
                    }else if($review->job_id)
                    {
                        $title=$review->jobpost?->title;
                    }
                @endphp
                <td>  {{ $title }}  </td>
                <td>{{ $review->type }} </td>
                <td>{{ $review->rating }}</td>
                <td>{{ $review?->created_at->format('d-m-Y') }}</td>
               
                <td class="actions">
                    <a class="cmnBtn btn_5 btn_bg_info radius-5 review_details"
                       data-bs-toggle="modal"
                       data-bs-target="#reviewDetailsModal"
                       data-review_id="{{ $review->id }}"
                       data-first_name="{{ $review->reviewer?->first_name }}"
                       data-last_name="{{ $review->reviewer?->last_name }}"
                       data-title="{{ $title }}"
                       data-type="{{ $review->type }}"
                       data-rating="{{ $review->rating }}"
                       data-message="{{ $review->message }}"
                     > {{ __('Review Details') }}
                    </a>
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'7'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_reviews"/>
