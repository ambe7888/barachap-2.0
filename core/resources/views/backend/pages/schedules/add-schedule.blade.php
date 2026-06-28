@extends('backend.admin-master')
@section('site-title')
 {{ __('Add New Schedule') }}
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-6 col-lg-6">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('Add New Schedule') }}</h4>
                        </div>
                        <div class="dashboard__inner__header__right">
                            <div class="btn-wrapper">
                                <a href="{{ route('admin.schedule.all') }}" class="cmnBtn btn_5 btn_bg_info radius-5">{{ __('All Schedules') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <x-validation.error/>
                <div class="customMarkup__single__inner mt-4">
                    <form action="{{route('admin.schedule.add')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form__input__single">
                                <label for="category" class="form__input__single__label"> {{__('Select Day')}}
                                    <span class="text-danger">*</span> </label>
                                <select name="day" id="day" class="select2_activation radius-5 form-control">
                                    <option value="">{{ __('Select Day') }}</option>
                                    @php
                                        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                                    @endphp
                                    @foreach($days as $day)
                                        <option value="{{ strtolower($day) }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Schedule Input -->
                            <div class="col-lg-6">
                                <x-form.text
                                    :title="__('Schedule')"
                                    :type="'text'"
                                    :name="'schedule'"
                                    :required="true"
                                    :value="old('schedule', '')"
                                    :placeholder="__('Enter Schedule')"
                                />
                            </div>

                            <!-- featured services -->
                            <div class="form__input__single mt-4 mx-3">
                                <div class="checkBox">
                                    <label class="schedule_for_day_all form__input__single__label d-flex gap-2">
                                        <input class="checkBox__input effectBorder" type="checkbox" name="schedule_for_all_days" id="schedule_for_all_days" value="">
                                        {{ __('Add this schedule time for all days') }}
                                    </label>
                                </div>
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
