<div class="row g-4">
    <div class="col-12">
                
    <!-- map section start-->
        <div class="dashboard_table__wrapper dashboard_border  padding-20 radius-10 bg-white">
            <div class="dashboard_table__title__flex">
                <h6 class="dashboard_table__title"> {{__('Service Zone Settings')}} </h6>
                <div class="btn-wrapper" data-bs-toggle="modal" data-bs-target="#openTicket">  </div>
            </div>
            <div class="notice-board">
                <p class="text-info">{{__('Search your service location, pick a location, and submit.')}}
                    <a href="https://drive.google.com/file/d/1BwDAjSLAeb4LaxzOkrdsgGO_Io2jM6S6/view?usp=sharing" target="_blank">
                        <strong class="text-warning">{{__('Video link')}}</strong></a></p>
            </div>
            <div class="row">
                    <!-- google map show -->
                    <div class="col-lg-8 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <!-- Start Map -->
                                <div class="map-warper dark-support rounded overflow-hidden">
                                    <input id="pac-input" class="controls rounded"
                                            type="text" placeholder="{{ __('Search your Zone')}}"/>
                                    <div id="map_canvas" style="height: 480px"></div>
                                </div>
                                <!-- End Map -->
                            </div>
                        </div>
                    </div>

                    <!-- lat lon section start -->
                    <div class="col-lg-4">
                        
                        <form action="{{route('admin.service.location')}}" enctype="multipart/form-data" method="POST">
                            @csrf
                        <div class="mb-30">
                            <div class="form-group mt-3">
                                <label for="seller_address" class="label_title"> {{ __('Service Location') }} <span class="text-danger">*</span> </label>
                                <input type="text" name="seller_address" id="seller_address" class="form-control"  placeholder="{{ __('Service Location') }}"  value="{{ $serviceLocation->address ?? "" }}">
                            </div>
                        </div>

                    <div class="mb-30">
                            <div class="form-group mt-3">
                                <label for="latitude" class="label_title"> {{ __('Latitude') }} <span class="text-danger">*</span> </label>
                                <input type="text" name="latitude" id="latitude" class="form-control" placeholder="{{ __('Latitude') }}" value="{{ $serviceLocation->latitude ?? ""}}">
                            </div>
                        </div>
                        <div class="mb-30">
                            <div class="form-group mt-3">
                                <label for="longitude" class="label_title"> {{ __('Longitude') }} <span class="text-danger">*</span> </label>
                                <input type="text" name="longitude" id="longitude" class="form-control"  placeholder="{{ __('Longitude') }}" value="{{ $serviceLocation->longitude ?? "" }}">
                            </div>
                        </div>
                        <div class="mb-30">
                            <div class="form-group mt-3">
                                <label class="form__input__single__label">{{ __('State') }}  <span class="text-danger">*</span> </label>
                                <select name="state_id" id="state" class="form-select" >
                                    <option value="">{{__('Select State')}}</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" @if(isset($serviceLocation->state_id) && $serviceLocation->state_id == $state->id) selected @endif>{{ $state->state}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-30">
                            <div class="form-group mt-3">
                                <label class="form__input__single__label">{{ __('City') }}  <span class="text-danger">*</span> </label>
                                <select name="city_id" id="city" class="form-select">
                                    <option value="">{{__('Select City')}}</option>
                                    @if ($serviceLocation?->city_id)
                                    <option value="{{$serviceLocation->city_id ?? ""}}"  selected>{{ $serviceLocation?->city?->city }}</option>
                                @endif
                                </select>
                            </div>
                        </div>
                        <div class="mb-30">
                            <div class="form-group mt-3">
                                <label class="form__input__single__label">{{ __('Area') }}  <span class="text-danger">*</span> </label>
                                <select name="area_id" id="area" class="form-select" data-id="{{ $serviceLocation?->area_id }}">
                                    <option value="">{{__('Select Area')}}</option>
                                    @if ($serviceLocation?->area_id)
                                        <option value="{{$serviceLocation->area_id ?? ""}}"  selected>{{ $serviceLocation?->area?->area }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="mb-30">
                            <div class="form-group mt-3">
                                <label for="zipcode" class="label_title"> {{ __('Zip code') }} <span class="text-danger">*</span> </label>
                                <input type="text" name="zipcode" id="zipcode" class="form-control"  placeholder="{{ __('Zip Code') }}" value="{{ $serviceLocation->post_code ?? "" }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5">
                            <button href="#" class="dashboard_table__title__btn btn-bg-1 radius-5" type="submit" style="border: none">{{ __('submit')}}</button>
                            <button href="#" class="dashboard_table__title__btn btn btn-danger mx-3 clear_all_value" type="reset">{{ __('Clear')}}</button>
                        </div>

                        </form>
                        
                    </div>
                    <!-- lat lon section end -->
            </div>
        </div>
           
        <div class="col-sm-12 text-end">
        
        </div>    
    </div>    
</div>






