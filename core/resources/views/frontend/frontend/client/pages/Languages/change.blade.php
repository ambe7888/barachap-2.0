@extends('frontend.frontend.client.client-master')
@section('site-title')
 {{ __('Change Language') }}
@endsection
@section('content')
    <div class="row w-100 g-4 mt-0">
        <div class="col-xl-8 col-lg-8">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('Change Language') }}</h4>
                        </div>
                    </div>
                </div>
                <x-validation.error/>
                <div class="customMarkup__single__inner mt-4">
                    <form action="{{route('client.change.language')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form__input__single">
                                <label for="selected_lang" class="form__input__single__label"> {{__('Select Language')}}
                                    <span class="text-danger">*</span> </label>
                                <select name="selected_lang" id="selected_lang" class="select2_activation radius-5 form-control">
                                    <option value="">{{ __('Select Language') }}</option>
                                    @foreach($all_lang as $lang)
                                        <option value="{{ $lang->slug }}" @if($lang->slug  == $client->selected_lang) selected @endif>{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                          

                        </div>
                        <br>
                        <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5">{{__('Submit')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                $(document).on('click', '.schedule_for_day_all', function () {
                    $('#schedule_for_all_days').val($('#schedule_for_all_days').is(':checked') ? '1' : '');
                });
            });
        })(jQuery)
    </script>
@endsection
