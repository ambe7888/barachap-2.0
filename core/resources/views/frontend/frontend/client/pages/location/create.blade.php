@extends('frontend.frontend.client.client-master')
@section('site-title')
 {{ __('Add New Location') }}
@endsection
@section('style')
   
@endsection
@section('content')
    <div class="row w-100 g-4 mt-0">
        <div class="col-xl-10 col-lg-10">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('Add New Location') }}</h4>
                        </div>
                        <div class="dashboard__inner__header__right">
                            <a href="{{route('client.location.map',['flag' => $flag, 'job_id' => $job_id])}}" class="btn btn-primary">Add Using Map</a>
                        </div>
                    </div>
                </div>

                
                <x-validation.error/>
                <div class="customMarkup__single__inner mt-4">
                    <form action="{{route('client.location.add')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                            <div class="mb-30 row">
                                <div class="form-group col-6 mt-3">
                                    <input type="hidden" name="flag" value="{{ $flag }}">
                                    <input type="hidden" name="job_id" value="{{ $job_id }}">
                                    <label for="title" class="label_title"> {{ __('Title') }} <span class="text-danger">*</span> </label>
                                    <input type="text" name="title" id="title" class="form-control"  placeholder="{{ __('Title') }}"  value="{{old('title')}}">
                                </div>
                                <div class="form-group col-6 mt-3">
                                    <label for="address" class="label_title"> {{ __('Address') }} <span class="text-danger">*</span> </label>
                                    <input type="text" name="address" id="address" class="form-control" placeholder="{{ __('Address') }}" value="{{ old('address') }}">
                                </div>
                            </div>
                            
                            <div class="mb-30">
                                <div class="form-group mt-3">
                                    <label class="form__input__single__label">{{ __('State') }}  <span class="text-danger">*</span> </label>
                                    <select name="state_id" id="state" class="form-select" >
                                        <option value="">{{__('Select State')}}</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-30">
                                <div class="form-group mt-3">
                                    <label class="form__input__single__label">{{ __('City') }}  <span class="text-danger">*</span> </label>
                                    <select name="city_id" id="city" class="form-select">
                                        <option value="">{{__('Select City')}}</option>
                                  
                                    </select>
                                </div>
                            </div>
                            <div class="mb-30">
                                <div class="form-group mt-3">
                                    <label class="form__input__single__label">{{ __('Area') }}  <span class="text-danger">*</span> </label>
                                    <select name="area_id" id="area" class="form-select">
                                        <option value="">{{__('Select Area')}}</option>
                                     
                                    </select>
                                </div>
                            </div>
                            <div class="mb-30 row">
                                <div class="form-group col-6 mt-3">
                                    <label for="zipcode" class="label_title"> {{ __('Zip code') }} <span class="text-danger">*</span> </label>
                                    <input type="text" name="zipcode" id="zipcode" class="form-control"  placeholder="{{ __('Zip Code') }}" value="{{ old('zipcode') }}">
                                </div>
                                <div class="form-group col-6">
                                    <label class="form__input__single__label">{{ __('Type') }}  <span class="text-danger">*</span> </label>
                                    <select name="type" id="type" class="form-select" >
                                        <option value="">{{__('Select Type')}}</option>
                                       
                                        <option value="0">{{__('Home')}}</option>
                                        <option value="1">{{__('Office')}}</option>
                                       
                                    </select>
                                </div>
                            </div>

                            <div class="mb-30 row">
                                <div class="form-group col-6 mt-3">
                                    <label for="phone" class="label_title"> {{ __('phone') }} <span class="text-danger">*</span> </label>
                                    <input type="text" name="phone" id="phone" class="form-control"  placeholder="{{ __('Phone') }}" value="{{old('phone')}}"> 
                                </div>
                                <div class="form-group col-6 mt-3">
                                    <label for="emergency_phone" class="label_title"> {{ __('Emergency Phone Number') }} <span class="text-danger">*</span> </label>
                                    <input type="text" name="emergency_phone" id="emergency_phone" class="form-control"  placeholder="{{ __('Emergency Phone Number') }}" value="{{old('emergency_phone')}}">
                                </div>
                            </div>
                           
                            <div class="d-flex justify-content-end mt-5">
                                <button href="#" class="dashboard_table__title__btn btn btn-primary" type="submit" style="border: none">{{ __('submit')}}</button>
                                <button href="#" class="dashboard_table__title__btn btn btn-danger mx-3 clear_all_value" type="reset">{{ __('Clear')}}</button>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
    <x-media.js />
    <script>
         $('#state').change(function () {
        const stateId = this.value;
        const citySelect = document.getElementById('city');
        const areaSelect = document.getElementById('area');
       
        // Clear previous city options
        citySelect.innerHTML = '<option value="">{{ __("Select City") }}</option>';
        
        if (stateId) {
            // Fetch cities based on the selected state
            $.ajax({
                method: 'GET',
                url: '/client/location/get-cities/'+stateId,
                success: function (data) {
                  
                    
                    if (data.cities) {
                        data.cities.forEach(city => {
                            
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.city;
                            citySelect.appendChild(option);

                        });
                    
                    }
                    else{
                       
                    }
                  
                    $('#area').val(null).trigger('change')
                },
                error: function (xhr, status, error) {
                    
                }    
    
            })
          
        }
    });
    $('#city').change(function () {
        const cityId = this.value;
        const areaSelect = document.getElementById('area');
        const stateId = document.getElementById('state').value;
        // Clear previous city options
        areaSelect.innerHTML = '<option value="">{{ __("Select area") }}</option>';
       
        
        if (cityId) {
            // Fetch cities based on the selected state
            $.ajax({
                method: 'GET',
                url: '/client/location/get-areas/'+cityId+'/'+stateId,
                success: function (data) {
                   
                    if (data.areas) {
                        data.areas.forEach(area => {
                            
                            const option = document.createElement('option');
                            option.value = area.id;
                            option.textContent = area.area;
                           
                            areaSelect.appendChild(option);
                        });
                    }
                  
                },
                error: function (xhr, status, error) {
                    
                }    
    
            })
          
        }
    });
        $('#state').select2({
                placeholder: '{{ __('Select State') }}', 
                allowClear: true,
        });
        $('#city').select2({
            placeholder: '{{ __('Select City') }}',
            allowClear: true, 
        });
        $('#area').select2({
            placeholder: '{{ __('Select Area') }}',  
            allowClear: true, 
        });

        $('#type').select2({
            placeholder: '{{ __('Select Type') }}',  
            allowClear: true, 
        });
    </script>    
@endsection
