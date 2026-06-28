@extends('frontend.frontend.provider.provider-master')
@section('site-title')
    {{__('Change Password')}}
@endsection
@section('style')
   <style>
    .bg-light-red {
        background-color: #f8d7da;
        width:100%;
    }
    .error-text{
        text-align: center;
    }

   </style>
@endsection
@section('content')
<div class="main-content w-100">
    <div class="user-setting-wraper ">
        <div class="userProfile border-box">
            <div class="top-part">
                <div class="img">
                    
                    {!! render_attachment_preview_for_admin(auth()->user()->image ?? '') !!}
                  
                </div>
                <div class="member">
                    @php
                        $fullname=Auth::user()->first_name." ".Auth::user()->last_name
                    @endphp
                    <div class="name">{{$fullname}}</div>
                </div>
                <span class="verified-icon"><i class="fa-solid fa-check"></i></span>
            </div>
            <div class="devider"></div>
            <div class="bottom-part">
                <div class="contact">
                    <div class="phone"><span class="icon"><svg width="24" height="24"
                                viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.45 22.75C16.32 22.75 15.13 22.48 13.9 21.96C12.7 21.45 11.49 20.75 10.31 19.9C9.14 19.04 8.01 18.08 6.94 17.03C5.88 15.96 4.92 14.83 4.07 13.67C3.21 12.47 2.52 11.27 2.03 10.11C1.51 8.87 1.25 7.67 1.25 6.54C1.25 5.76 1.39 5.02 1.66 4.33C1.94 3.62 2.39 2.96 3 2.39C3.77 1.63 4.65 1.25 5.59 1.25C5.98 1.25 6.38 1.34 6.72 1.5C7.11 1.68 7.44 1.95 7.68 2.31L10 5.58C10.21 5.87 10.37 6.15 10.48 6.43C10.61 6.73 10.68 7.03 10.68 7.32C10.68 7.7 10.57 8.07 10.36 8.42C10.21 8.69 9.98 8.98 9.69 9.27L9.01 9.98C9.02 10.01 9.03 10.03 9.04 10.05C9.16 10.26 9.4 10.62 9.86 11.16C10.35 11.72 10.81 12.23 11.27 12.7C11.86 13.28 12.35 13.74 12.81 14.12C13.38 14.6 13.75 14.84 13.97 14.95L13.95 15L14.68 14.28C14.99 13.97 15.29 13.74 15.58 13.59C16.13 13.25 16.83 13.19 17.53 13.48C17.79 13.59 18.07 13.74 18.37 13.95L21.69 16.31C22.06 16.56 22.33 16.88 22.49 17.26C22.64 17.64 22.71 17.99 22.71 18.34C22.71 18.82 22.6 19.3 22.39 19.75C22.18 20.2 21.92 20.59 21.59 20.95C21.02 21.58 20.4 22.03 19.68 22.32C18.99 22.6 18.24 22.75 17.45 22.75ZM5.59 2.75C5.04 2.75 4.53 2.99 4.04 3.47C3.58 3.9 3.26 4.37 3.06 4.88C2.85 5.4 2.75 5.95 2.75 6.54C2.75 7.47 2.97 8.48 3.41 9.52C3.86 10.58 4.49 11.68 5.29 12.78C6.09 13.88 7 14.95 8 15.96C9 16.95 10.08 17.87 11.19 18.68C12.27 19.47 13.38 20.11 14.48 20.57C16.19 21.3 17.79 21.47 19.11 20.92C19.62 20.71 20.07 20.39 20.48 19.93C20.71 19.68 20.89 19.41 21.04 19.09C21.16 18.84 21.22 18.58 21.22 18.32C21.22 18.16 21.19 18 21.11 17.82C21.08 17.76 21.02 17.65 20.83 17.52L17.51 15.16C17.31 15.02 17.13 14.92 16.96 14.85C16.74 14.76 16.65 14.67 16.31 14.88C16.11 14.98 15.93 15.13 15.73 15.33L14.97 16.08C14.58 16.46 13.98 16.55 13.52 16.38L13.25 16.26C12.84 16.04 12.36 15.7 11.83 15.25C11.35 14.84 10.83 14.36 10.2 13.74C9.71 13.24 9.22 12.71 8.71 12.12C8.24 11.57 7.9 11.1 7.69 10.71L7.57 10.41C7.51 10.18 7.49 10.05 7.49 9.91C7.49 9.55 7.62 9.23 7.87 8.98L8.62 8.2C8.82 8 8.97 7.81 9.07 7.64C9.15 7.51 9.18 7.4 9.18 7.3C9.18 7.22 9.15 7.1 9.1 6.98C9.03 6.82 8.92 6.64 8.78 6.45L6.46 3.17C6.36 3.03 6.24 2.93 6.09 2.86C5.93 2.79 5.76 2.75 5.59 2.75ZM13.95 15.01L13.79 15.69L14.06 14.99C14.01 14.98 13.97 14.99 13.95 15.01Z"
                                    fill="#667085"></path>
                            </svg>
                        </span>{{Auth::user()->phone}}</div>
                    <div class="email"><span class="icon"><svg width="24" height="24"
                                viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17 21.25H7C3.35 21.25 1.25 19.15 1.25 15.5V8.5C1.25 4.85 3.35 2.75 7 2.75H17C20.65 2.75 22.75 4.85 22.75 8.5V15.5C22.75 19.15 20.65 21.25 17 21.25ZM7 4.25C4.14 4.25 2.75 5.64 2.75 8.5V15.5C2.75 18.36 4.14 19.75 7 19.75H17C19.86 19.75 21.25 18.36 21.25 15.5V8.5C21.25 5.64 19.86 4.25 17 4.25H7Z"
                                    fill="#667085"></path>
                                <path
                                    d="M11.9988 12.87C11.1588 12.87 10.3088 12.61 9.6588 12.08L6.5288 9.57997C6.2088 9.31997 6.14881 8.84997 6.4088 8.52997C6.66881 8.20997 7.13881 8.14997 7.45881 8.40997L10.5888 10.91C11.3488 11.52 12.6388 11.52 13.3988 10.91L16.5288 8.40997C16.8488 8.14997 17.3288 8.19997 17.5788 8.52997C17.8388 8.84997 17.7888 9.32997 17.4588 9.57997L14.3288 12.08C13.6888 12.61 12.8388 12.87 11.9988 12.87Z"
                                    fill="#667085"></path>
                            </svg>
                        </span>{{Auth::user()->email}}</div>
                </div>
                <div class="btn-wraper">
                    <a href="{{route('provider.profile.update')}}" type="button" class="cmn-small-btn fill-btn">{{__('Edit Profile')}}</a>
                </div>
            </div>
        </div>
        <div class="password border-box">
            <div class="text">
                <div class="pass">{{__('Password')}}</div>
                @php
                   use Carbon\Carbon;
                   $password_changed_at=Auth::user()->password_changed_at;
                   $daysAgo = Carbon::parse($password_changed_at)->diffInDays(Carbon::now());
                   if($daysAgo== 0)
                   {
                    $context="Last changed today";
                   }
                   else {
                    $context="Last changed ".$daysAgo." days ago";
                   }
                   

                @endphp
                <p>{{$context}}</p>
            </div>
            <div class="btn-wraper">
                <button type="button" class="cmn-small-btn delete-btn"
                    data-bs-toggle="modal" data-bs-target="#password_modal">{{__('Change Password')}}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="password_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
           <form class="custom_form">
                <div class="error-text mt-1 bg-light-red">
                    <span class="text-danger error-password "></span>
                </div>    
               

                <div class="bg_white">
                    <div class="password-wraper mt-2">
                        <label class="custom-label" for="old_password">{{__('Enter Old Password')}}</label>
                        <div class="input-with-icon">
                            <img src="./assets/images/lock.png" class="input-icon" alt="">
                            <input class="custom-input w-100"  type="password" id="old_password" name="old_password" placeholder="{{__('Enter old Password')}}">
                            <div class="toggle-password">
                                <div class="show-icon "> <i class="fas fa-eye-slash"></i> </div>
                                <span class="hide-icon"> <i class="fas fa-eye"></i> </span>
                               
                            </div>
                        </div>

                    </div>
                    <div class="password-wraper mt-4">
                        <label class="custom-label" for="new_password">{{__('Enter New Password')}}</label>
                        <div class="input-with-icon">
                            <img src="./assets/images/lock.png" class="input-icon" alt="">
                            <input class="custom-input w-100"  type="password" id="new_password" name="new_password" placeholder="{{__('Enter new Password')}}">
                            <div class="toggle-password">
                                <div class="show-icon "> <i class="fas fa-eye-slash"></i> </div>
                                <span class="hide-icon"> <i class="fas fa-eye"></i> </span>
                               
                            </div>
                        </div>
                        <div>
                            <span class="length-check"></span>
                        </div> 

                    </div>
                    <div class="password-wraper mt-4">
                        <label class="custom-label" for="confirm_password">{{__('Confirm New Password')}}</label>
                        <div class="input-with-icon">
                            <img src="./assets/images/lock.png" class="input-icon" alt="">
                            <input class="custom-input w-100" type="password" id="confirm_password" name="confirm_password" placeholder="{{__('Confirm New Password')}}">
                            <div class="toggle-password">
                                <div class="show-icon "> <i class="fas fa-eye-slash"></i> </div>
                                <span class="hide-icon"> <i class="fas fa-eye"></i> </span>
                               
                            </div>
                        </div>
                        <div>
                            <span id="check_password_match"></span>
                        </div>  
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="cancel_password" class="cmn-small-btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="update_password" class="cmnBtn btn_5 btn_bg_blue radius-5">{{ __('Update') }}</button>
                    </div>
                </div>
           </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
       
        $(document).ready(function () {
            $('.toggle-password').click(function () {
                $(this).toggleClass("active");
                var input = $(this).parent().find("input");
                if (input.attr("type") === "password") {

                    input.attr("type", "text");
                    $(this).find('.show-icon').hide();
                    $(this).find('.hide-icon').show();
                  
                  
                   
                } else {
                    input.attr("type", "password");
                  
                    $(this).find('.show-icon').show();
                    $(this).find('.hide-icon').hide();
                  
                }
            });

            $(document).on('keyup', '#confirm_password', function() {
                let password = $("#new_password").val();
                let confirm_password = $("#confirm_password").val();
                if(password.length >= 8 && confirm_password.length >= 8) {
                    if (password != confirm_password) {
                        $("#check_password_match").html("Password does not match !").css("color",
                            "red");
                    } else {
                        $("#check_password_match").html("Password match !").css("color", "green");
                    }
                }else{
                    $("#check_password_match").html("Password does not match !").css("color",
                    "red");
                }
            });
            $('#new_password').on('keyup', function() {
                var password = $(this).val();
                if (password.length >= 8) {
                $('.length-check').text('Password length is okay').css('color', 'green');
                } else {
                $('.length-check').text('Password must be at least 8 characters').css('color', 'red');
                }
            });

            $('#cancel_password').on('click',function(){
                $('#old_password').val('');
                $("#new_password").val('');
                $("#confirm_password").val('');
                $('.error-password').text('');
                $('.length-check').text('');
                $("#check_password_match").html('');

                $('#password_modal').modal('hide');
            })

            $("#update_password").on('click',function(){

                var old_password = $('#old_password').val();
                var new_password = $('#new_password').val();
                var new_password_confirmation = $('#confirm_password').val();
           
                $.ajax({
                            method: 'post',
                            url: "{{ route('provider.profile.password.change') }}",
                            data: {
                                old_password:old_password,
                                new_password:new_password,
                                new_password_confirmation:new_password_confirmation
                            },
                            success: function(res) 
                            {
                                if (res.status == 'success') 
                                {
                                    toastr.success("{{__('Success')}}");
                                    $('#old_password').val('');
                                    $("#new_password").val('');
                                    $('#password_modal').modal('hide');
                                    window.location.href = '/login';

                                }
                                else if (res.status == 'validation_error')
                                {
                                    let errorMessage = "";
                                    $.each(res.errors, function (key, value) {
                                        
                                        
                                    $('.error-password').text(value[0]);
                                    });
                                
                                
                                    
                                }
                                else
                                {
                                    $('.error-password').text(res.error);
                                }
                                
                            },
                            error:function(xhr)
                            {
                                if (xhr.status === 422) { 
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessage = "";
                                    $.each(errors, function (key, value) {
                                        errorMessage += value[0] + "<br>";
                                    });
                                    toastr.error(errorMessage);
                                } else {
                                    toastr.error("An error occurred. Please try again.");
                                }
                            }
                    });
            })

           
    

        });
    </script>
@endsection        
