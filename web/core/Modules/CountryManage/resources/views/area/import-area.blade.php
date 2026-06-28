@extends('backend.admin-master')
@section('title', __('Import Areas'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-8">
                <x-validation.error/>
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Import Areas (only csv file)') }}</h4>
                        <div class="customMarkup__single__inner mt-4">
                            @if(empty($import_data))
                                <form action="{{route('admin.area.import.csv.update.settings')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="#" class="label-title">{{__('File')}}</label>
                                        <input type="file" name="csv_file" accept=".csv" class="form-control" required>
                                        <div class="text-info">{{__('only csv file are allowed with separate by (,) comma.')}}</div>
                                    </div>
                                    <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 loading-btn">{{__('Submit')}}</button>
                                </form>
                            @else
                                @php
                                    $option_markup = '';
                                        foreach(current($import_data) as $map_item ){
                                            $option_markup .= '<option value="'.trim($map_item).'">'.$map_item.'</option>';
                                        }
                                @endphp

                                <form action="{{route('admin.area.import.database')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @csrf
                                    <table class="table table-striped">
                                        <thead>
                                        <th style="width: 200px">{{{__('Field Name')}}}</th>
                                        <th>{{{__('Set Field')}}}</th>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><h6>{{__('State')}}</h6></td>
                                            @php $all_states = \Modules\CountryManage\app\Models\State::all_states(); @endphp
                                            <td>
                                                <div class="form-group">
                                                    <select class="form__control radius-5 select2_activation" name="state_id" id="state_id">
                                                        <option value="">{{ __('Select State') }}</option>
                                                        @foreach($all_states as $states)
                                                            <option value="{{ $states->id }}">{{ $states->state }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <p class="text-info">{{ __('Select State') }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><h6>{{__('City')}}</h6></td>
                                            @php $cities = \Modules\CountryManage\app\Models\City::all_cities(); @endphp
                                            <td>
                                                <div class="form-group">
                                                    <select name="city_id" id="city_id" class="get_state_city form__control radius-5 select2_activation">
                                                        <option value="">{{ __('Select City') }}</option>
                                                    </select>
                                                </div>
                                                <p class="text-info">{{ __('Select City') }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><h6>{{__('Area')}}</h6></td>
                                            <td>
                                                <div class="form__input__single">
                                                    <select name="area" class="form__control radius-5 select2_activation">
                                                        <option value="">{{__('Select Field')}}</option>
                                                        {!! $option_markup !!}
                                                    </select>
                                                </div>
                                                <p class="text-info">{{ __('Select area and only unique areas added automatically according to the selected state.') }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><h6>{{__('Status')}}</h6></td>
                                            <td>
                                                <div class="form__input__single">
                                                    <select class="form-control mapping_select">
                                                        <option value="1">{{__('Publish')}}</option>
                                                        <option value="0">{{__('Draft')}}</option>
                                                    </select>
                                                    <input type="hidden" name="status" value="1">
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                    <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 loading-btn">{{__('Import')}}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){

                $(document).on('click','.loading-btn',function (){
                    $(this).append('<i class="ml-2 fas fa-spinner fa-spin"></i>')
                });

                $(document).on('change','.mapping_select',function (){
                    $('.mapping_select option').attr('disabled',false);
                    $(this).next('input').val($(this).val());
                    let allValue = $('.mapping_select');
                    $.each(allValue,function (index,item){
                        $('.mapping_select option[value="'+$(this).val()+'"]').attr('disabled',true);
                    });
                })

                // change country and get state
                $(document).on('change','#state_id',function (){
                    let state = $(this).val();
                    $.ajax({
                        method: 'post',
                        url: "{{ route('au.state.all') }}",
                        data: {
                            state: state
                        },
                        success: function(res) {
                            if (res.status == 'success') {
                                let all_options = "<option value=''>{{__('Select State')}}</option>";
                                let all_cities = res.states;
                                $.each(all_cities, function(index, value) {
                                    all_options += "<option value='" + value.id +
                                        "'>" + value.city + "</option>";
                                });
                                $(".get_state_city").html(all_options);
                                if(all_cities.length <= 0){
                                    $(".info_msg").html('<span class="text-danger"> {{ __('No state found for selected country!') }} <span>');
                                }
                            }
                        }
                    });
                });
            });
        }(jQuery));
    </script>
@endsection
