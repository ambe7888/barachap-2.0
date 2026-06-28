<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            // Initialize Select2 for add modal
            $('.select2-country, .select2-state').select2({
                dropdownParent: $('#addModal')
            });

            // Initialize Select2 for edit modal
            $('.select22-country, .select22-state').select2({
                dropdownParent: $('#editAreaModal')
            });


            // add country
            $(document).on('click','.add_area',function(e){
                let area = $('#area').val();
                let city = $('#city').val();
                let state = $('#state').val();
                if(area == '' || city == '' || state == ''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }

            });

            // show city in edit modal
            $(document).on('click','.edit_area_modal',function(){
                let area = $(this).data('area');
                let area_id = $(this).data('area_id');
                let city_id = $(this).data('city_id');
                let state_id = $(this).data('state_id');

                $('#area_name').val(area).trigger("change");
                $('#area_id').val(area_id).trigger("change");
                $('#city_id').val(city_id).trigger('change');
                $('#state_id').val(state_id).trigger("change");
            });

            // update city
            $(document).on('click','.edit_area',function(e){
                let area = $('#area_name').val();
                let city = $('#city_id').val();
                let state = $('#state_id').val();
                if(area == '' || city == '' || state == ''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }
            });

            //change state and get city
            $('#state_id').on('change', function() {
                let state = $(this).val();
                if (state) {
                    $.ajax({
                        method: 'post',
                        url: "{{ route('au.city.all') }}",
                        data: {
                            state: state
                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                let all_options = "<option value=''>{{__('Select City')}}</option>";
                                let all_cities = res.cities;

                                $.each(all_cities, function (index, value) {
                                    all_options += "<option value='" + value.id + "'>" + value.city + "</option>";
                                });

                                $(".get_state_city").html(all_options);

                                if (all_cities.length <= 0) {
                                    $(".info_msg").html('<span class="text-danger"> {{ __('No state found for selected state!') }} <span>');
                                } else {
                                    $(".info_msg").html('');
                                }
                            }
                        }
                    });
                }else {
                    // Reset city dropdown if no state is selected
                    $(".get_state_city").html("<option value=''>{{ __('Select City') }}</option>");
                    $(".info_msg").html('');
                }
            });

            // change state and get city
            $('#state').on('change', function() {
                let state = $(this).val();
                $.ajax({
                    method: 'post',
                    url: "{{ route('au.city.all') }}",
                    data: {
                        state: state
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            let all_options = "<option value=''>{{__('Select City')}}</option>";
                            let all_cities = res.cities;
                            $.each(all_cities, function (index, value) {
                                all_options += "<option value='" + value.id + "'>" + value.city + "</option>";
                            });

                            $(".get_state_city").html(all_options);

                            if (all_cities.length <= 0) {
                                $(".info_msg").html('<span class="text-danger"> {{ __('No state found for selected state!') }} <span>');
                            } else {
                                $(".info_msg").html('');
                            }
                        }
                    }
                })
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                countries(page);
            });
            function countries(page){
                $.ajax({
                    url:"{{ route('admin.area.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search state
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.area.search') }}",
                    method:'GET',
                    data:{string_search:string_search},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }
                });
            });


            // change status
            $(document).on('click','.swal_status_change',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to change status complete? Once you done you can not revert this !!")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, change it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });
        });
    }(jQuery));
</script>
