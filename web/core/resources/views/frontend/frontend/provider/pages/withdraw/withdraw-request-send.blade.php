@extends('frontend.frontend.provider.provider-master')
@section('title', __('Withdraw Request Send'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body w-50 ms-2">
        <div class="row ">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item">
                            <h4 class="customMarkup__single__title">{{ __('Withdraw Request Send') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('provider.withdraw.request')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input my-5">
                                    <label for="amount" class="label-title"> {{ __('Amount') }}</label>
                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="{{ __("Enter Amount...") }}">
                                </div>
                                <div class="single-input my-5">
                                    <label class="label-title">{{ __('Withdraw Method') }}</label>
                                    <select name="withdraw_method" id="withdraw_method" class="form-control">
                                        <option value="">{{__("Select Method")}}</option>
                                        @foreach($withdraw_methods as $method)
                                            <option value="{{ $method->id }}"  data-fields='@json(unserialize($method->field))'>
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div id="method-fields" name="method-fields" class="mt-3"></div>



                                <div class="btn_wrapper mt-4">
                                    <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Update') }}</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
    <script>
     $(document).ready(function () {
        $('#withdraw_method').on('change', function() {
            var fields = $(this).find('option:selected').data('fields');
            var container = $('#method-fields');
            container.empty();

            if (Array.isArray(fields)) {
                $.each(fields, function(index, field) {
                    container.append(`
                        <div class="mb-2">
                            <label class="form-label">${field}</label>
                            <input type="text" name="method_fields[${field}]" class="form-control" placeholder="${field}" required>
                        </div>
                    `);
                });
            }
        });

     });


    </script>
@endsection

