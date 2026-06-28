<header class="">
    <nav class="navbar navbar-expand-lg">
        <div class="container custom-container gap-3">
            <div class="logo-wraper">
                <a href="./index.html" class="logo">
                    <img src="/assets/frontend/img/group-11712750601729768553.png" alt="prohandy" class="logo">
                </a>
            </div>
            <div class="responsive-mobile-menu ms-auto">
                <button class="navbar-toggler bg-w" type="button" data-bs-toggle="collapse"
                    data-bs-target="#listing-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a href="#" class="click-nav-right-icon">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
            </div>
            <div class="navbar-right-content">
                @if(!auth()->check())
                    <div class="single-right-content ">
                        <div class="navbar-right-flex d-flex">
                            <div class="navbar-right-btn-1">
                                <a href="./login.html" class="cmn-btn transparent-btn">{{__('Login')}}</a>
                            </div>
                            <div class="navbar-right-btn-2">
                                <a href="./add-property.html" class="cmn-btn fill-btn"><span class="me-2"><i
                                            class="fa-solid fa-plus"></i></span>{{__('Add Property')}}</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="after-login-content">
                        @include('frontend.frontend.client.partials.notifications')

                        <div class="profile-part">
                            <div class="profile-img">
                                {!! render_attachment_preview_for_admin(auth()->user()->image ?? '') !!}

                            </div>
                            <div class="profile-item">
                                <div class="item"><a href="{{route('client.frontend.dashboard')}}">{{__('Dashbord')}}</a></div>
                                <div class="item"><a href="{{route('client.profile.password.change')}}">{{__('Setting')}}</a></div>
                                <div class="logout"><a href="{{route('client.logout')}}">{{__('Log Out')}}</a></div>
                            </div>
                        </div>
                        <div class="country-flag">
                            <img src="./assets/images/flag.png" alt="">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </nav>
</header>
