@extends('frontend.frontend.client.client-master')
@section('site-title')
    {{__('Add New Address')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
    <style>
         #pac-input {
         height: 3em;
         width:75%;
         margin-left: 140px;
         border: 1px solid;
         top: 4px;
         font-size: 16px;
     }

    /* Media query for screens smaller than 768px */
    @media (max-width: 1499px) {
        #pac-input {
            width: 100%;
            margin-left: 0;
        }
    }
        span {
            display: inline;
        }
    </style>
@endsection
@section('content')
    <div class="row w-100 g-4 mt-0">
        <div class="col-12 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="header-wrap d-flex justify-content-between mb-4">
                    <div class="left-content">
                        <h4 class="header-title">{{__('Add New Address')}}   </h4>
                    </div>
                    <div class="right-content">
                        <a class="cmnBtn btn_5 btn_bg_info radius-5" href="{{route('client.location.all')}}">{{__('All Addresses')}}</a>
                    </div>
                </div>
                <x-validation.error/>
               
                @include('frontend.frontend.client.pages.location.map.location-general-info')
             
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('scripts')
<x-media.js />
    <x-frontend.js.new-tag-add-js/>
<script src="{{asset('assets/backend/js/sweetalert2.js')}}"></script>
 <!-- google api key  -->
<script src="https://maps.googleapis.com/maps/api/js?key={{get_static_option('google_map_api_key')}}&libraries=places&v=3.46.0"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#viewer').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileEg1").change(function () {
        readURL(this);
    });

    $(document).ready(function () {
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
                    else{
                       
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
        function initAutocomplete() {
            var myLatLng = {
                lat: <?= $location->latitude ?? 0 ?>,
                lng: <?= $location->longitude ?? 0 ?>
            };

            const map = new google.maps.Map(document.getElementById("map_canvas"), {
                center: myLatLng,
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = new google.maps.Geocoder();

            // new start
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);
                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                // Perform reverse geocoding to get the address details
                geocoder.geocode({ 'location': latlng }, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var countryName = '';
                            var cityName = '';
                            var zipCode = '';


                            for (var i = 0; i < results[0].address_components.length; i++) {
                                var addressComponent = results[0].address_components[i];

                                if (addressComponent.types.includes('country')) {
                                    countryName = addressComponent.long_name;
                                }
                                if (addressComponent.types.includes('locality') || addressComponent.types.includes('postal_town')) {
                                    cityName = addressComponent.long_name;
                                }
                                

                                // Extract ZIP Code
                                if (addressComponent.types.includes('postal_code')) {
                                    zipCode = addressComponent.long_name;
                                }
                            }
                            document.getElementById('zipcode').value = zipCode;
                            // Update #seller_address element with the complete address
                            var final_address = cityName + ', ' + countryName;
                            $('#address').val(final_address);
                        } else {
                            console.log('No results found');
                        }
                    } else {
                        console.log('Geocoder failed due to: ' + status);
                    }
                });

            });
            //// new end

            // Search box create
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Google map Search current view
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            let markers = [];
            // info place
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();
                if (places.length == 0) { return; }
                // select old marker remove
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // icon, name, location each
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function (event) {

                        // for full address title start
                            var coordinates = JSON.stringify(event.latLng.toJSON(), null, 2);
                            var coordinates = JSON.parse(coordinates);
                            var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                            marker.setPosition(latlng);
                            map.panTo(latlng);
                        // for full address title end

                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                        // for full address title start
                        // Perform reverse geocoding to get the address details
                        geocoder.geocode({ 'location': latlng }, function (results, status) {
                            if (status === google.maps.GeocoderStatus.OK) {
                                if (results[0]) {
                                    var countryName = '';
                                    var cityName = '';

                                    for (var i = 0; i < results[0].address_components.length; i++) {
                                        var addressComponent = results[0].address_components[i];

                                        if (addressComponent.types.includes('country')) {
                                            countryName = addressComponent.long_name;
                                        }
                                        if (addressComponent.types.includes('locality') || addressComponent.types.includes('postal_town') || addressComponent.types.includes('administrative_area_level_1') || addressComponent.types.includes('administrative_area_level_2')) {
                                            cityName = addressComponent.long_name;
                                        }
                                    }
                                    // Update #seller_address element with the complete address
                                    var final_address = cityName + ', ' + countryName;
                                    $('#seller_address').val(final_address);
                                } else {
                                    console.log('No results found');
                                }
                            } else {
                                console.log('Geocoder failed due to: ' + status);
                            }
                        });
                        // for full address title end

                    });
                    markers.push(mrkr);
                    if (place.geometry.viewport) { bounds.union(place.geometry.viewport); } else { bounds.extend(place.geometry.location); }
                });
                map.fitBounds(bounds);
            });
        }
        initAutocomplete();
    });

   

    // clear all value
    $('.clear_all_value').click(function () {
        $('#name').val(null);
        $('#pac-input').val(null);
    });
</script>
@endsection
