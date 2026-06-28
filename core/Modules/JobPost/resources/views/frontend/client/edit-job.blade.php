@extends('frontend.frontend.client.client-master')
@section('site-title')
 {{ __('Edit Job') }}
@endsection
@section('style')

<x-media.css/>

<style>
    #category{
        height: 48px !important;
    }
    #address{
        height: 48px !important;
    }

    input{

        color:var(--bs-body-color)!important;
        border: 1px solid #ced4da !important;
        border-radius: .375rem !important;
    }

    input::placeholder{
        color: var(--bs-body-color) !important;
      
    }

    textarea{
        color:var(--bs-body-color)!important;
        border: 1px solid #ced4da !important;
        border-radius: .375rem !important;

    }
    textarea::placeholder{
        color: var(--bs-body-color) !important;
    }
</style>    
   
@endsection
@section('content')
    <div class="row w-100 g-4 mt-0 ms-3">
        <div class="col-xl-10 col-lg-10">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('Edit Job') }}</h4>
                        </div>
                      
                    </div>
                </div>
                
                <x-validation.error/>
                <div class="customMarkup__single__inner mt-4">
                    <form action="{{route('client.jobs.edit',$job->id)}}" enctype="multipart/form-data" method="POST">
                        @csrf
                            <div class="mb-30 row">
                                <div class="form-group col-6 ">
                                    <input type="hidden" name="job_id" id="job_id" value="{{ $job->id }}">
                                    <label for="title" class="label_title"> {{ __('Title') }} <span class="text-danger">*</span> </label>
                                    <input type="text" name="title" id="title" class="form-control"  placeholder="{{ __('Title') }}"  value="{{ $job->title }}">
                                    <div class="form__input__single mt-2">
                                        <div class="input-form input-form2 permalink_label">
                                            <label for="title" class="form__input__single__label text-dark"> {{__('Permalink')}}  <span class="text-danger">*</span>  </label>
                                            <span id="slug_show" class="display-inline"></span>
                                            <span id="slug_edit" class="display-inline d-inline">
                                            <button class="btn btn-warning btn-sm slug_edit_button">  <i class="las la-edit"></i> </button>
                                            <input class="listing_slug form__control radius-5" name="slug" value="{{ $job->slug}}" id="slug" style="display: none" type="text">
                                            <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none">{{__('Update')}}</button>
                                       </span>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="form-group col-6">
                                    <label class="label_title">{{ __('Category') }}  <span class="text-danger">*</span> </label>
                                    <select name="category" id="category" class="form-select" >
                                        <option value="">{{__('Select Category')}}</option>
                                       
                                        @foreach($category as $cat)
                                            <option value="{{ $cat->id }}" @if($job->category_id == $cat->id) selected @endif>{{ $cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-30">
                                <div class="form-group">
                                    <label class="form__input__single__label">{{ __('Description') }} <span class="text-danger">*</span> <span class="text-danger">{{ __('minimum 150 characters.') }}</span> </label>
                                    <div class="input-form input-form2">
                                        <textarea class="textarea--form" name="description" placeholder="{{__('Type Description')}}" rows="8" cols="8">{{ $job->description }}</textarea>
                                    </div>
                                </div>
                             
                            </div>
                            
                            <div class="mb-30 row">
                                <div class="form-group col-6 ">
                                    <label for="date" class="label_title"> {{ __('Date') }} <span class="text-danger">*</span> </label>
                                    <input type="date" name="date" id="date" class="form-control"  placeholder="{{ __('Date') }}"  value="{{ $job->date }}">
                                </div>
                                <div class="form-group col-6">
                                    <label for="time" class="label_title"> {{ __('Time') }} <span class="text-danger">*</span> </label>
                                    <input type="text" name="time" id="time" class="form-control"  placeholder="{{ __('Time Format H:i') }}"  value="{{ \Carbon\Carbon::parse($job->time)->format('H:i') }}">
                                </div>
                            </div>
                           
                            <div class="mb-30 row">
                                <div class="form-group col-6 ">
                                    <label for="budget" class="label_title"> {{ __('Budget') }} <span class="text-danger">*</span> </label>
                                    <input type="number" name="budget" id="budget" class="form-control"  placeholder="{{ __('Budget') }}" value="{{ $job->budget }}">
                                </div>

                                <div class="form-group col-6 ">
                                    <label class="label_title">{{ __('Address') }}  <span class="text-danger">*</span> </label>
                                    <select name="address" id="address" class="form-select" >
                                        <option value="">{{__('Select Address')}}</option>
                                        @foreach($user_location as $location)
                                            <option value="{{ $location->id }}" @if($job->job_location?->user_location_id == $location->id) selected @endif>{{ $location->address}}</option>
                                        @endforeach
                                        <option value="0"><a href="">{{__('other')}}</a></option>
                                    </select>
                                </div>
                             
                            </div>

                            <div class="mb-30 row">
                                <div class="col-lg-6">
                                    <div class="upload-img">
                                        <div class="media-upload-btn-wrapper">
                                            <div class="img-wrap mb-2">
                                                {!! render_gallery_image_attachment_preview($job->gallery_images ?? '') !!}
                                            </div>
                                            <input type="hidden" name="gallery_images">
                                            <button type="button" class="btn btn-info media_upload_form_btn"
                                                    data-btntitle="{{__('Select Image')}}"
                                                    data-modaltitle="{{__('Upload Image')}}"
                                                    data-mulitple="true"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#media_upload_modal">
                                                {{__('Upload Gallery Images')}}
                                            </button>
                                            <br>
                                            <small>{{ __('image format: jpg,jpeg,png,gif,webp')}}</small> <br>
                                            <small>{{ __('recommended size 810x450') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="mb-30">
                                <div class="d-flex justify-content-end mt-5">
                                    <button href="#" class="dashboard_table__title__btn btn btn-primary" type="submit" style="border: none">{{ __('submit')}}</button>
                                    <button href="#" class="dashboard_table__title__btn btn btn-danger mx-3 clear_all_value" type="reset">{{ __('Clear')}}</button>
                                </div>
    
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
        $(document).ready(function() {

            const savedData = sessionStorage.getItem('editJobFormData');
            if (savedData) {
                const formData = JSON.parse(savedData);

                $('#title').val(formData.title);
                $('#category').val(formData.category);
                $('textarea[name="description"]').val(formData.description);
                $('#date').val(formData.date);
                $('#time').val(formData.time);
                $('#budget').val(formData.budget);
                $('#job_id').val(formData.job_id);


                sessionStorage.removeItem('editJobFormData');
            }
            
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

        
                $('#address').change(function() {
                    if ($(this).val() == '0') {
                        // Store all form values
                        const formData = {
                            title: $('#title').val(),
                            category: $('#category').val(),
                            description: $('textarea[name="description"]').val(),
                            date: $('#date').val(),
                            time: $('#time').val(),
                            budget: $('#budget').val(),
                            job_id: $('#job_id').val(),
                        };

                        let job_id = $('#job_id').val();
                        sessionStorage.setItem('editJobFormData', JSON.stringify(formData));


                        const url = "{{ route('client.location.add', ['flag' => 'from-job-edit', 'job_id' => 'JOB_ID']) }}".replace('JOB_ID', job_id);
                        window.location.href = url;
             
                    }
                });

                 //Permalink Code
                 $('.permalink_label').hide();
                $(document).on('keyup', '#title', function (e) {
                    let slug = converToSlug($(this).val());
                    let url = "{{url('/client/job/')}}/" + slug;
                    $('.permalink_label').show();
                    let data = $('#slug_show').text(url).css('color', '#3c3cf7');
                    $('.listing_slug').val(slug);
                });

                function converToSlug(slug){
                    let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                    //remove multiple space to single
                    finalSlug = slug.replace(/  +/g, ' ');
                    // remove all white spaces single or multiple spaces
                    finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                    return finalSlug;
                }

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.listing_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    var update_input = $('.listing_slug').val();
                    var slug = converToSlug(update_input);
                    var url = `{{url('/client/job')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.listing_slug').hide();
                });
        });        


    </script>    
@endsection
