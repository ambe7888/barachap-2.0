@extends('frontend.frontend.provider.provider-master')
@section('site-title')
    {{__('Job Settings')}}
@endsection
@section('style')
    <style>
      .job_details{
         background-color: #f9f9f9;
      }
      .job-title{
        font-weight: 500; 
        color: #333;
      }
      .budget-label{
        font-weight: 400; 
        color: #555; 
        font-size: 16px;
      }
      .budget-amount{
        font-weight: 400;
        color: #28a745;
        font-size: 18px;

      }
    </style>
@endsection
@section('content')
    <div class="col-lg-6 col-ml-12 padding-bottom-30">
        <div class="row m-0 p-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Send A Job Offer')}} </h4>
                               
                            </div>
                        </div>
                        <div class="mb-2 mt-2">
                            <x-msg.success/>
                            <x-msg.error/>
                        </div>
                        <div class="job_details p-4 rounded shadow-sm mt-4">
                            <h5 class="job-title mb-3">{{ __($job->title) }}</h5>
                            <div class="budget-box d-flex align-items-center">
                                <span class="budget-label">{{ __("Budget:") }}</span>
                                <span class="budget-amount ml-2">
                                    {{ float_amount_with_currency_symbol($job->budget) }}
                                </span>
                            </div>
                        </div>
                            
                        <form action="{{route('provider.job.offer.send', $job->id)}}" method="post">
                            @csrf
                            <div class="tab-content margin-top-10">
                                <div class="form-group mt-4">
                                    <label for="budget" class="label-title">{{__('Budget')}}</label>
                                    <input type="number" name="budget" id="budget" value="{{ old('budget') }}" placeholder="{{ __('Enter a Offer Amount') }}" class="form-control" >
                                   
                                </div>
                                <div class="form-group mt-3">
                                    <label for="cover_letter" class="label-title">{{__('Cover Letter')}}</label>
                                    <textarea class="textarea--form" name="cover_letter" placeholder="{{__('Write about the service you are offering')}}" rows="8" cols="8">{{ old('cover_letter') }}</textarea>
                                </div>    
                                <button type="submit" class="btn btn-primary mt-3 submit_btn">{{__('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
