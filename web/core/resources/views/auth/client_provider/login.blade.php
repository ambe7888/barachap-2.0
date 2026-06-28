@extends('layouts.client-login-screens')
@section('title')
    {{ __('Login') }} - {{ get_static_option('site_title') }}
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
                    <div class="error-message text-start">
                        <x-msg.response-message/>
                    </div>
                    <h2 class="semibold-heading">{{__('Welcome! Login to continue')}}</h2>
                    <form action="#" method="get">
                        <div class="email-wraper">
                            <div class="input-with-icon">
                                <label class="label_title">{{ __('Username or Email') }}</label>
                                <div class="include_icon">
                                    <input class="custom-input w-100" type="text" id="username" name="username" placeholder="{{ __('Username or Email') }}">
                                    <div class="icon"><span><i class="las la-user-alt"></i></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="password-wraper mt-4">
                            <label class="custom-label" for="password">{{__('Password')}}</label>
                            <div class="input-with-icon">
                                <img src="./assets/frontend/img/lock.png" class="input-icon" alt="">
                                <input class="custom-input w-100" type="password" id="password" placeholder="{{__('Enter Your Password')}}">
                                <span class="length-check"></span>
                            </div>
                        </div>
                        <div class="remembarForget">
                            <label class="rememberMe"><input type="checkbox" name="remember">{{__('Remember me')}}</label>
                            <a href="{{route('user.forget.password')}}" class="forget-pass">{{__('Forget Password')}}</a>
                        </div>
                        <div class="login-btn-wraper">
                            <button id="form_submit" class="cmn-btn fill-btn w-100">{{__('Login')}}</button>
                            <a class="cmn-btn transparent-btn-one w-100" href="{{ route('login.google.redirect') }}"><img src="./assets/frontend/img/google.png" alt="google"><span class="ms-2">{{__('Log in with Google')}}</span></a>
                            <a class="cmn-btn transparent-btn-one w-100" href="{{ route('login.facebook.redirect') }}"><img src="./assets/frontend/img/facebook.png" alt="facebook"><span class="ms-2">{{__('Log in with Facebook')}}</span></a>
                        </div>
                        <div class="sign-up-link">
                            Don't have an account? <a href="{{ route('user.frontend.register') }}">Sign Up</a>
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
    <script>
        (function($){
           "use strict";
            $(document).ready(function ($){


                $('#password').on('keyup', function() {
                    var password = $(this).val();
                    if (password.length >= 8) {
                    $('.length-check').text('Password length is okay').css('color', 'green');
                    } else {
                    $('.length-check').text('Password must be at least 8 characters').css('color', 'red');
                    }
                });


                // $(document).on('click','#autoLogin',function(){
                //     let el = $(this);
                //     let username = $('#td_username').text();
                //     let passwrod = $('#td_password').text();
                //     $('#username').val(username);
                //     $('#password').val(passwrod);
                //     $('#form_submit').trigger('click');
                // });

                // user login
                $(document).on('click','#form_submit',function (e){
                    e.preventDefault();
                    var el = $(this);
                    var erContainer = $(".error-message");
                    erContainer.html('');
                    el.text('{{__('Please Wait..')}}');
                    $.ajax({
                        url: "{{route('user.login')}}",
                        type: "post",
                        data: {
                            _token : "{{csrf_token()}}",
                            username : $('#username').val(),
                            password : $('#password').val(),
                            remember : $('#remember').val(),
                        },
                        error:function(data){
                            var errors = data.responseJSON;
                            erContainer.html('<div class="alert alert-danger">'+errors.msg+'</div>');

                            el.text('{{__('Login')}}');
                        },
                        success:function (data){
                            $('.alert.alert-danger').remove();
                            if (data.status == 'ok'){
                                el.text('{{__('Redirecting')}}..');
                                erContainer.html('<div class="alert alert-'+data.type+'">'+data.msg+'</div>');
                                if(data.user_type== 0)
                                {
                                    window.location.href="{{route('user.frontend.dashboard')}}";
                                }
                                else if(data.user_type== 1)
                                {
                                    window.location.href="{{route('client.frontend.dashboard')}}";
                                }



                            }else{

                                erContainer.html('<div class="alert alert-danger">'+data.msg+'</div>');
                                el.text('{{__('Login')}}');
                            }
                        }
                    });
                });

           });
        })(jQuery);
    </script>
@endsection
