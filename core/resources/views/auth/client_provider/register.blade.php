@extends('layouts.client-login-screens')
@section('title')
    {{ __('Registration') }} - {{ get_static_option('site_title') }}
@endsection
@section('content')
<div class="full-width login">
    <div class="row g-4 align-items-center">
        <div class="col-md-6">
            <div class="loginpart-wraper">
                <div class="logo-part">
                    {{-- <a href="{{ route('homepage') }}" class="logo">
                        {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                    </a> --}}
                    <img src="./assets/frontend/img/group-11712750601729768553.png" alt="prohandy" class="logo">
                </div>
                <div class="form-part">
                    <h2 class="semibold-heading">Sign Up</h2>
                    <div class="error-message"></div>
                    <form action="{{route('user.frontend.register')}}" method="post">
                        @csrf 
                        <input type="hidden" name="user_type" id="user_type">
                        <section class="choose-account-area pat-100 pab-100 user_type_area">
                            <div class="container">
                                <div class="choose-account center-text">
                                    <h4 class="choose-account-title">{{__('Choose a Role') }}</h4>
                                    <div class="choose-account-flex d-flex mt-4 justify-content-center">
                                        <div class="choose-account-single selected join_as_a_provider">
                                            <div class="choose-account-single-thumb">
                                              <button type="button" class="border-0" id="join_as_provider">
                                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M40 70.625C41.2944 70.625 42.3438 69.5757 42.3438 68.2812C42.3438 66.9868 41.2944 65.9375 40 65.9375C38.7056 65.9375 37.6562 66.9868 37.6562 68.2812C37.6562 69.5757 38.7056 70.625 40 70.625Z"
                                                    fill="#6176F6" />
                                                <path
                                                    d="M2.34375 80H77.6562C78.9506 80 80 78.9506 80 77.6562V75.3125C80 70.09 75.6075 65.9375 70.4688 65.9375C69.6594 65.9375 68.8623 66.0442 68.0941 66.2456L70.4238 54.5192C70.5605 53.8311 70.3817 53.1178 69.9366 52.5755C69.4914 52.0331 68.8267 51.7188 68.125 51.7188H65.7812V49.375C65.7812 42.9133 60.5242 37.6562 54.0625 37.6562H49.375C48.0827 37.6562 47.0312 36.6048 47.0312 35.3125V35.3061C49.8756 33.1659 51.7188 29.7631 51.7188 25.9375C51.7188 24.4055 51.7188 13.3697 51.7188 11.7188C51.7188 7.84172 48.5645 4.6875 44.6875 4.6875H37.6562C36.3639 4.6875 35.3125 3.63609 35.3125 2.34375C35.3125 1.04937 34.2631 0 32.9688 0H30.625C25.4556 0 21.25 4.20563 21.25 9.375C21.25 13.7353 24.2423 17.41 28.2812 18.4533V25.9375C28.2812 29.7631 30.1244 33.1659 32.9688 35.3061V35.3125C32.9688 36.6048 31.9173 37.6562 30.625 37.6562H25.9375C19.4758 37.6562 14.2188 42.9133 14.2188 49.375V51.7188H11.875C11.1733 51.7188 10.5086 52.0331 10.0634 52.5755C9.61828 53.1178 9.43953 53.8311 9.57625 54.5192L11.9059 66.2456C11.1377 66.0442 10.3406 65.9375 9.53125 65.9375C4.39141 65.9375 0 70.0912 0 75.3125V77.6562C0 78.9506 1.04937 80 2.34375 80V80ZM65.2698 56.4062L61.5136 75.3125H18.4864L14.7302 56.4062H65.2698ZM70.4688 70.625C73.0944 70.625 75.3125 72.7716 75.3125 75.3125H66.2927L66.897 72.2706C67.932 71.1598 69.108 70.625 70.4688 70.625ZM40 32.9688C36.123 32.9688 32.9688 29.8145 32.9688 25.9375V18.75H47.0312V25.9375C47.0312 29.8145 43.877 32.9688 40 32.9688ZM25.9375 9.375C25.9375 6.79031 28.0403 4.6875 30.625 4.6875H31.0266C31.9939 7.41578 34.6006 9.375 37.6562 9.375H44.6875C45.9798 9.375 47.0312 10.4264 47.0312 11.7188V14.0625H30.625C28.0403 14.0625 25.9375 11.9597 25.9375 9.375ZM40 37.6562C40.9102 37.6562 41.7952 37.5486 42.6463 37.3513C43.2692 39.4036 44.8073 41.0602 46.7834 41.8467C45.9702 44.8305 43.238 47.0312 40 47.0312C36.762 47.0312 34.0298 44.8305 33.2166 41.8467C35.1927 41.0602 36.7308 39.4036 37.3537 37.3513C38.2048 37.5486 39.0898 37.6562 40 37.6562ZM18.9062 49.375C18.9062 45.498 22.0605 42.3438 25.9375 42.3438H28.517C29.6058 47.6863 34.3408 51.7188 40 51.7188C45.6592 51.7188 50.3941 47.6863 51.483 42.3438H54.0625C57.9395 42.3438 61.0938 45.498 61.0938 49.375V51.7188H18.9062V49.375ZM9.53125 70.625C10.8784 70.625 12.0572 71.1489 13.103 72.2706L13.7073 75.3125H4.6875C4.6875 72.7716 6.90562 70.625 9.53125 70.625V70.625Z"
                                                    fill="#667085" />
                                                </svg>
                                              </button>
                                            </div>
                                            <div class="choose-account-single-contents mt-3">
                                                <h6 class="choose-account-single-contents-title">{{ __('Join as a Provider') }}</h6>
                                            </div>
                                        </div>
                                        <div class="choose-account-single join_as_a_client ms-5">
                                            <div class="choose-account-single-thumb">
                                               <button type="button" class="border-0 px-2" id="join_as_client">
                                                    <svg width="62" height="80" viewBox="0 0 62 80" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M57.4738 56.8037C54.8975 53.6169 51.272 51.3891 47.265 50.5305L39.5938 48.8866V44.4384C43.6458 41.9366 46.5327 37.7223 47.2381 32.8125H48.1875C52.0645 32.8125 55.2188 29.6583 55.2188 25.7812C55.2188 21.9042 52.0645 18.75 48.1875 18.75H47.4062V8.59375C47.4062 3.85516 43.5511 0 38.8125 0H20.0625C13.6008 0 8.34375 5.25703 8.34375 11.7188C8.34375 14.6011 9.39172 17.242 11.1241 19.2853C8.57719 20.3433 6.78125 22.8559 6.78125 25.7812C6.78125 29.6583 9.93547 32.8125 13.8125 32.8125H14.7619C15.4673 37.7223 18.3542 41.9366 22.4062 44.4384V48.8866L14.735 50.5305C10.728 51.3891 7.1025 53.617 4.52625 56.8037C1.95 59.9905 0.53125 64.0023 0.53125 68.1003V77.6562C0.53125 78.9506 1.58062 80 2.875 80H59.125C60.4194 80 61.4688 78.9506 61.4688 77.6562V68.1003C61.4688 64.0023 60.05 59.9905 57.4738 56.8037ZM47.4062 23.4375H48.1875C49.4798 23.4375 50.5312 24.4889 50.5312 25.7812C50.5312 27.0736 49.4798 28.125 48.1875 28.125H47.4062V23.4375ZM14.5938 28.125H13.8125C12.5202 28.125 11.4688 27.0736 11.4688 25.7812C11.4688 24.4889 12.5202 23.4375 13.8125 23.4375H14.5938V28.125ZM17.7188 14.8438V18.3484C14.9905 17.3813 13.0312 14.7745 13.0312 11.7188C13.0312 7.84172 16.1855 4.6875 20.0625 4.6875H38.8125C40.9664 4.6875 42.7188 6.43984 42.7188 8.59375V12.5H20.0625C18.7681 12.5 17.7188 13.5494 17.7188 14.8438ZM19.2812 30.4688V23.4086C19.5398 23.4258 19.7997 23.4375 20.0625 23.4375C21.3569 23.4375 22.4062 22.3881 22.4062 21.0938V17.1875H42.7188V30.4688C42.7188 36.9305 37.4617 42.1875 31 42.1875C24.5383 42.1875 19.2812 36.9305 19.2812 30.4688ZM22.7877 75.3125H5.21875V68.1003C5.21875 61.8791 9.63406 56.4173 15.717 55.1139L23.1898 53.5125L25.4628 60.3314L22.7877 75.3125ZM27.5494 75.3125L29.8373 62.5H32.1627L34.4506 75.3125H27.5494ZM28.4223 54.3867C29.2756 54.5219 30.1377 54.5913 31 54.5913C31.8623 54.5913 32.7244 54.522 33.5777 54.3867L32.4356 57.8125H29.5642L28.4223 54.3867ZM34.9062 49.2355C32.3877 50.1292 29.6123 50.1292 27.0938 49.2355V46.4039C28.3463 46.7109 29.6542 46.875 31 46.875C32.3458 46.875 33.6538 46.7109 34.9062 46.4039V49.2355ZM56.7812 75.3125H39.2123L36.5372 60.3314L38.8102 53.5125L46.283 55.1139C52.3659 56.4173 56.7812 61.8789 56.7812 68.1003V75.3125Z"
                                                        fill="#667085" />
                                                    </svg>
                                               </button>
                                            </div>
                                            <div class="choose-account-single-contents mt-3">
                                                <h6 class="choose-account-single-contents-title">{{ __('Join as a Client') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                    
                                </div>
                            </div>
                        </section>
                        <br>
                        <div class="namewraper d-flex flex-wrap justify-content-between gap-4">
                            <div class="firstname flex-grow-1">
                                <label class="custom-label" for="fname">{{__('First name')}}</label>
                                <div class="input-with-icon">
                                    <input class="custom-input w-100 " type="text" id="fname" name="fname" placeholder="{{__('Enter first name')}}">
                                    <div class="icon"><span><i class="las la-user-alt"></i></span></div>
                                </div>
                            </div>
                            <div class="lastname flex-grow-1">
                                <label class="custom-label" for="lname">{{__('Last name')}}</label>
                                <div class="input-with-icon">
                                    <input class="custom-input w-100" type="text" id="lname" name="lname" placeholder="{{__('Enter last name')}}">
                                    <div class="icon"><span><i class="las la-user-alt"></i></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="namewraper d-flex flex-wrap justify-content-between gap-4">
                            <div class="email-wraper mt-4">
                                <label class="custom-label" for="user_name">{{__('User Name')}}</label>
                                <div class="d-flex flex-column">
                                    <div class="input-with-icon">
                                        <input class="custom-input w-100" type="text" id="user_name" name="user_name" placeholder="{{__('Enter Your User Name')}}">
                                        <div class="icon"><span><i class="las la-user-alt"></i></span></div>
                                    </div> 
                                    <div>
                                        <span id="user_name_availability"></span>
                                    </div>       
                                </div>
                            </div>
                            <div class="email-wraper mt-4">
                                <label class="custom-label" for="email">{{__('Email')}}</label>
                                <div class="d-flex flex-column">
                                    <div class="input-with-icon">
                                        <img src="./assets/frontend/img/sms.svg" class="input-icon" alt="sms">
                                        <input class="custom-input w-100" type="email" id="email" name="email" placeholder="{{__('Enter Your Email')}}">
                                    </div>    
                                    <div>
                                        <span id="email_availability"></span>
                                    </div>    
                                   
                                    
                                </div>
                            </div>
                        </div>
                        <div class="namewraper d-flex flex-wrap justify-content-between gap-4">
                            <div class="password-wraper mt-4">
                                <label class="custom-label" for="password">{{__('Password')}}</label>
                                <div class="d-flex flex-column">
                                    <div class="input-with-icon">
                                        <img src="./assets/frontend/img/lock.png" class="input-icon" alt="">
                                        <input class="custom-input w-100" type="password" id="password" name="password" placeholder="{{__('Enter Your Password')}}">
                                        <div class="toggle-password">
                                            <div class="show-icon "> <i class="fas fa-eye-slash"></i> </div>
                                            <span class="hide-icon"> <i class="fas fa-eye"></i> </span>
                                           
                                        </div>
                                    </div>    
                                    <div>
                                        <span class="length-check"></span>
                                    </div>    
                                </div>
                              
                            </div>
                            <div class="password-wraper mt-4">
                                <label class="custom-label" for="confirm_password">{{__('Confirm Password')}}</label>
                                <div class="d-flex flex-column">
                                    <div class="input-with-icon">
                                        <img src="./assets/frontend/img/lock.png" class="input-icon" alt="">
                                        <input class="custom-input w-100" type="password" id="confirm_password" name="confirm_password" placeholder="{{__('Confirm Your Password')}}">
                                        <div class="toggle-password">
                                            <div class="show-icon "> <i class="fas fa-eye-slash"></i> </div>
                                            <span class="hide-icon"> <i class="fas fa-eye"></i> </span>
                                        </div>
                                    </div>    
                                    <div>
                                        <span id="check_password_match"></span>
                                    </div>    
                                </div>
                            </div>
                        </div>
                       
                        </br>
                        <input type="checkbox" class="form-check-input" id="terms_condition"
                        name="terms_condition">
                       <label class="form-check-label" for="toc_and_privacy">
                        {{ __('Accept all') }}
                        <a target="_blank" href="{{ url(get_static_option('toc_page_link') ?? '/login') }}"
                            class="fw-bold">{{ __('Terms and Conditions') }}</a> &amp;
                        <a target="_blank" href="{{ url(get_static_option('privacy_policy_link') ?? '/login') }}"
                            class="fw-bold">{{ __('Privacy Policy') }}</a>
                        </label>
                        </br>

                        @if((!empty(get_static_option('site_google_captcha_enable'))))
                            <div class="col-md-12 my-3">
                                <div class="g-recaptcha" id="recaptcha_element_register" data-sitekey="{{ get_static_option('recaptcha_site_key') ?? '' }}"></div>
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                @endif
                            </div>
                        @endif
                        <div class="login-btn-wraper">
                            <button class="cmn-btn fill-btn w-100 sign_up_now_button" type="submit"> {{ __('Sign Up Now') }} <span id="user_register_load_spinner"></span></button>
                            <a class="cmn-btn transparent-btn-one w-100"  href="{{ route('login.google.redirect') }}"><img src="./assets/frontend/img/google.png" alt="google"><span class="ms-2">{{__('Log in with Google')}}</span></a>
                            <a class="cmn-btn transparent-btn-one w-100"  href="{{ route('login.facebook.redirect') }}"><img src="./assets/frontend/img/facebook.png" alt="facebook"><span class="ms-2">{{__('Log in with Facebook')}}</span></a>
                        </div>
                        <div class="sign-up-link">
                            Already have an account? <a href="{{ route('user.login')}}">{{__('Login')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 p-0">
            <div class="full-size-side-image-part">
                <img src="./assets/frontend/img/login.png" class="img-fluid w-100" alt="login-page">
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src='https://www.google.com/recaptcha/api.js'></script>
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

            $(document).on('click', '.join_as_a_client', function() {
                $('#user_type').val('1')
                console.log( $('#user_type').val());

            });
            $(document).on('click', '.join_as_a_provider', function() {
                $('#user_type').val('0')
                console.log( $('#user_type').val());
            });

            $(document).on('click', '#join_as_client', function() {
                $('#join_as_provider').removeClass('selected').addClass('border-0').removeClass('border border-2 border-primary rounded-3');
                $(this).addClass('selected').removeClass('border-0').addClass('border border-2 border-primary rounded-3');
            });

            $(document).on('click', '#join_as_provider', function() {
                $('#join_as_client').removeClass('selected').addClass('border-0').removeClass('border border-2 border-primary rounded-3');
                $(this).addClass('selected').removeClass('border-0').addClass('border border-2 border-primary rounded-3');
            });

            $(document).on('keyup', '#user_name', function() {
                console.log("username: ",$(this).val());
                let username = $(this).val();
                let usernameRegex = /^[a-zA-Z0-9]+$/;
                if (usernameRegex.test(username) && username != '') {
                    $.ajax({
                        url: "{{ route('user.frontend.name.availability') }}",
                        type: 'post',
                        data: {
                            username: username
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            console.log("ras: ",res);
                            if (res.status == 'available') {
                                $("#user_name_availability").html(
                                    "<span style='color: green;'>" + res.msg +
                                    "</span>");
                            } else {
                                $("#user_name_availability").html(
                                    "<span style='color: red;'>" + res.msg +
                                    "</span>");
                            }
                        }
                    });
                } else {
                    $("#user_name_availability").html(
                        "<span style='color: red;'>{{ __('Enter valid username') }}</span>");
                }
            });

            $(document).on('keyup', '#email', function() {
                let email = $(this).val();
                let emailRegex = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
                if (emailRegex.test(email) && email != '') {
                    $.ajax({
                        url: "{{ route('user.frontend.email.availability') }}",
                        type: 'post',
                        data: {
                            email: email
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            if (res.status == 'available') {
                                $("#email_availability").html(
                                    "<span style='color: green;'>" + res.msg +
                                    "</span>");
                            } else {
                                $("#email_availability").html(
                                    "<span style='color: red;'>" + res.msg +
                                    "</span>");
                            }
                        }
                    });
                } else {
                    $("#email_availability").html(
                        "<span style='color: red;'>{{ __('Enter valid email') }}</span>");
                }
            });

            $(document).on('keyup', '#confirm_password', function() {
                let password = $("#password").val();
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
            $('#password').on('keyup', function() {
                var password = $(this).val();
                if (password.length >= 8) {
                $('.length-check').text('Password length is okay').css('color', 'green');
                } else {
                $('.length-check').text('Password must be at least 8 characters').css('color', 'red');
                }
            });

              //confirm signup
              $(document).on('click', '.sign_up_now_button', function(e) {
                    e.preventDefault()
                    $('#user_register_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>')

                    let fname = $('#fname').val();
                    let lname = $('#lname').val();
                    let username = $('#user_name').val();
                    let email = $('#email').val();
                    let password = $('#password').val();
                    let confirm_password = $('#confirm_password').val();
                    let user_type = $('#user_type').val();
                    let terms_condition = $('#terms_condition:checked').val();

                    let recaptchaResponse;
                    if (document.getElementById('recaptcha_element_register')) {
                        recaptchaResponse = grecaptcha.getResponse();
                    }

                    let erContainer = $(".error-message");
                    erContainer.html('');

                     $.ajax({
                            url: "{{ route('user.frontend.register') }}",
                            type: 'post',
                            data: {user_type:user_type,fname: fname,lname:lname,username:username,email:email,password:password,confirm_password:confirm_password,terms_condition:terms_condition,recaptchaResponse:recaptchaResponse},
                            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                             error:function(res){
                                 let errors = res.responseJSON;
                                 erContainer.html('<div class="alert alert-danger"></div>');
                                 $.each(errors.errors, function(index,value){
                                     erContainer.find('.alert.alert-danger').append('<p>'+value+'</p>');
                                 });
                                 $('#user_register_load_spinner').html('')
                             },
                             success: function(res) {
                                    if (res.status == 'success') {
                                        erContainer.html('<div class="alert alert-success">'+res.msg+'</div>');
                                        $('#user_register_load_spinner').html('')
                                        window.location.href = "{{ route('user.frontend.email.verify') }}";
                                    } else {
                                        erContainer.html('<div class="alert alert-danger">'+res.msg+'</div>');
                                        $('#user_register_load_spinner').html('')
                                        window.location.href = "{{ route('user.frontend.register') }}";
                                    }
                               
                             }
                     });
                })
        });
    </script>   
    
@endsection
