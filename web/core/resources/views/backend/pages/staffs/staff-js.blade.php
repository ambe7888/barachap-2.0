<script>
    (function($){
        "use strict";
        $(document).ready(function(){
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

            $(document).on('click','.swal_email_verify_button',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("To verify this user email.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, verify it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            // show user details in modal
            $(document).on('click','.user_details',function(){
                let user_id = $(this).data('user_id');
                let first_name = $(this).data('first_name');
                let last_name = $(this).data('last_name');
                let full_name = first_name + last_name;
                let email = $(this).data('email');
                let phone = $(this).data('phone');
                let about = $(this).data('about');

                $('#user_details .full_name').text(full_name);
                $('#user_details .email').text(email);
                $('#user_details .phone').text(phone);
                $('#user_details .about').text(about);

                //edit user info
                $('#edit_user_details #edit_user_id').val(user_id);
                $('#edit_user_details #edit_first_name').val(first_name);
                $('#edit_user_details #edit_last_name').val(last_name);
                $('#edit_user_details #edit_email').val(email);
                $('#edit_user_details #edit_phone').val(phone);
                $('#edit_user_details #edit_about').val(about);
            });

            // user info edit
            $(document).on('click','.user_info_edit',function(e){
                e.preventDefault();
                $('#userDetailsModal').modal('hide');
                $('#userDetailsEditModal').modal('show');
            });


            //validation while update user info
            $(document).on('click','.update_user_info',function(){
                $('.email_send_message').removeClass("d-none");
                let first_name = $('#edit_user_details #edit_first_name').val();
                let last_name = $('#edit_user_details #edit_last_name').val();
                let email = $('#edit_user_details #edit_email').val();
                let phone = $('#edit_user_details #edit_phone').val();
                let about = $('#edit_user_details #edit_about').val();


                if(first_name == '' || last_name == '' || email == '' || phone == ''){
                    toastr_warning_js("{{__('Please fill all fields')}}")
                    return false;
                }
                $(".email_send_message").html("{{ __('Please wait while email is sending... !') }}").css("color", "green");
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                countries(page);
            });
            function countries(page){
                $.ajax({
                    url:"{{ route('admin.staff.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search state
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.staff.search') }}",
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

        });
    }(jQuery));

    // toastr success
    function toastr_success_js(msg){
        Command: toastr["success"](msg, "Success !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }

    // toastr warning
    function toastr_warning_js(msg){
        Command: toastr["warning"](msg, "Warning !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }
</script>
