@extends('backend.admin-master')
@section('site-title')
    {{__('All Integrations')}}
@endsection
@section('style')
    <style>
        .plugin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .plugin-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-align: center;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0,0,0,0.02);
            position: relative;
        }

        .plugin-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .plugin-card .thumb-bg-color {
            padding: 40px 20px;
            color: #fff;
            position: relative;
            z-index: 1;
        }
        
        /* Modern Gradients for Brands */
        .plugin-card .thumb-bg-color.google_analytics, .plugin-card .thumb-bg-color.captcha {
            background: linear-gradient(135deg, #FFB75E 0%, #ED8F03 100%);
        }
        .plugin-card .thumb-bg-color.google_tags {
            background: linear-gradient(135deg, #66a6ff 0%, #397bee 100%);
        }
        .plugin-card .thumb-bg-color.facebook_pixels {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .plugin-card .thumb-bg-color.addroll {
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
        }
        .plugin-card .thumb-bg-color.whatsapp {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .plugin-card .thumb-bg-color.twakto {
            background: linear-gradient(135deg, #00b447 0%, #008735 100%);
        }
        .plugin-card .thumb-bg-color.crisp {
            background: linear-gradient(135deg, #1a72f5 0%, #0d5dd1 100%);
        }
        .plugin-card .thumb-bg-color.tidio {
            background: linear-gradient(135deg, #0567ff 0%, #034fce 100%);
        }
        .plugin-card .thumb-bg-color.messenger {
            background: linear-gradient(135deg, #A334FA 0%, #7B12DF 100%);
        }
        .plugin-card .thumb-bg-color.instagram {
            background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
        }
        .plugin-card .thumb-bg-color.google_adsense {
            background: linear-gradient(135deg, #148f57 0%, #4ebe94 100%);
        }

        .plugin-card .thumb-bg-color strong {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }

        .plugin-meta {
            font-size: 15px;
            color: #6B7280;
            padding: 30px 24px;
            margin: 0;
            flex-grow: 1;
            line-height: 1.6;
            font-weight: 500;
        }

        .padding-30 {
            padding: 40px;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #F3F4F6;
        }

        .plugin-card .btn-group-wrap {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 0 24px;
        }

        .plugin-card .btn-group-wrap a {
            flex: 1;
            padding: 12px 20px;
            background-color: #F3F4F6;
            border-radius: 50px;
            color: #374151;
            font-weight: 700;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .plugin-card .btn-group-wrap a.pl_active_deactive {
            background-color: #111827;
            color: #ffffff;
        }
        .plugin-card .btn-group-wrap a.pl_active_deactive:hover {
            background-color: #374151;
            box-shadow: 0 6px 15px rgba(17, 24, 39, 0.25);
            transform: translateY(-3px);
        }

        .plugin-card .btn-group-wrap a.pl_delete {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: #ffffff;
        }
        .plugin-card .btn-group-wrap a.pl_delete:hover {
            box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3);
            transform: translateY(-3px);
        }
        
        .header-wrap {
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 2px solid #F3F4F6;
            text-align: center;
        }
        .header-wrap h4 {
            font-size: 32px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -1px;
            margin-bottom: 10px;
        }
        .header-wrap p {
            font-size: 18px;
            color: #6B7280;
            font-weight: 500;
        }

    </style>
@endsection
@section('content')
    @php
     $method = "get_static_option";
    @endphp
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="recent-order-wrapper dashboard-table bg-white padding-30">
                    <div class="header-wrap">
                        <h4 class="header-title mb-2">{{__("All Integrations")}}</h4>
                        <p class="mb-3">{{__("Manage all integrations from here, you can active/deactivate integrations.")}}</p>
                    </div>
                    <div class="plugin-grid">
                        <div class="plugin-card">
                            <div class="thumb-bg-color google_analytics">
                                <strong class="google_analytics">{{__("Google Analytics GT4")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure google analytics (GT4) into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="google_analytics_gt4_status"
                                   data-status="{{$method("google_analytics_gt4_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("google_analytics_gt4_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#google_analytics_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color google_tags">

                                <strong class="google_tags">{{__("Google Tags Manager")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure google tag manager into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="google_tag_manager_status"
                                   data-status="{{$method("google_tag_manager_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("google_tag_manager_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#google_tag_manager_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color facebook_pixels">

                                <strong class="facebook_pixels">{{__("Facebook Pixels")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure facebook pixels into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="facebook_pixels_status"
                                   data-status="{{$method("facebook_pixels_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("facebook_pixels_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#facebook_pixels_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color addroll">

                                <strong class="addroll">{{__("Adroll")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure AdRoll into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="adroll_pixels_status"
                                   data-status="{{$method("adroll_pixels_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("adroll_pixels_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#adroll_pixels_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color whatsapp">

                                <strong class="whatsapp">{{__("What's App")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure What's App into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="whatsapp_status" data-status="{{$method("whatsapp_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("whatsapp_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#whatsapp_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color messenger">

                                <strong class="messenger">{{__("Messenger")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure messenger into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="messenger_status" data-status="{{$method("messenger_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("messenger_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#messenger_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                                <small> <a href="https://www.facebook.com/business/help/1524587524402327"
                                           target="_blank">
                                        <i class="fa-solid fa-circle-question"></i>
                                    </a></small>
                            </div>

                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color twakto">
                                <strong class="twakto">{{__("Twak.to Api")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Twak.to into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="twakto_status" data-status="{{$method("twakto_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("twakto_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#twakto_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>

                        <div class="plugin-card">
                            <div class="thumb-bg-color crisp">
                                <strong class="crisp">{{__("Crsip")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Crsip into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="crsip_status" data-status="{{$method("crsip_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("crsip_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#crsip_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color tidio">
                                <strong class="tidio">{{__("Tidio")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Tidio into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="tidio_status" data-status="{{$method("tidio_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("tidio_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#tidio_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>

                        <div class="plugin-card">
                            <div class="thumb-bg-color captcha">
                                <strong class="captcha">{{__("Google Captcha V3")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Google Captcha into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="captcha_status" data-status="{{$method("captcha_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("captcha_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#google_captcha_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>

                        <div class="plugin-card">
                            <div class="thumb-bg-color instagram">
                                <strong class="instagram">{{__("Instagram")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Instagram into the website. It will work if any instagram feature is available")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="instagram_status" data-status="{{$method("instagram_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("instagram_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#instagram_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>

                        <div class="plugin-card">
                            <div class="thumb-bg-color social_login">
                                <strong class="social_login">{{__("Social Login")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure social login into the website")}}
                            </p>

                            <div class="btn-group-wrap">
                                <a href="#"
                                   data-option="social_login_status"
                                   data-status="{{$method("social_login_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("social_login_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#"
                                   data-bs-target="#social_login"
                                   data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="messenger_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Messenger")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="messenger">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Messenger Page ID")}}</label>
                            <input type="text"
                                   name="messenger_page_id"
                                   class="form-control"
                                   value="{{get_static_option("messenger_page_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="tidio_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Tidio")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="tidio">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Chat Page ID")}}</label>
                            <input type="text"
                                   name="tidio_chat_page_id"
                                   class="form-control"
                                   value="{{get_static_option("tidio_chat_page_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="crsip_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Crsip")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="crsip">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Website ID")}}</label>
                            <input type="text"
                                   name="crsip_website_id"
                                   class="form-control"
                                   value="{{get_static_option("crsip_website_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="twakto_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Twak.to")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="twakto">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Widget ID")}}</label>
                            <input type="text"
                                   name="twakto_widget_id"
                                   class="form-control"
                                   value="{{get_static_option("twakto_widget_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="whatsapp_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("What's App")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="whatsapp">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("What's App Mobile Number With Country Code")}}</label>
                            <input type="text"
                                   name="whatsapp_mobile_number"
                                   class="form-control"
                                   value="{{get_static_option("whatsapp_mobile_number")}}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="#">{{__("Initial Message")}}</label>
                            <input type="text"
                                   name="whatsapp_initial_text"
                                   class="form-control"
                                   value="{{get_static_option("whatsapp_initial_text")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="adroll_pixels_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("AdRoll Pixels")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="adroll_pixels">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Adroll Adviser ID")}}</label>
                            <input type="text"
                                   name="adroll_adviser_id"
                                   class="form-control"
                                   value="{{$method("adroll_adviser_id")}}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="#">{{__("Adroll Publisher ID")}}</label>
                            <input type="text"
                                   name="adroll_publisher_id"
                                   class="form-control"
                                   value="{{$method("adroll_publisher_id")}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="facebook_pixels_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Facebook Pixels")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="facebook_pixels">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Facebook Pixels ID")}}</label>
                            <input type="text"
                                   name="facebook_pixels_id"
                                   class="form-control"
                                   value="{{$method("facebook_pixels_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="google_analytics_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Google Analytics GT4")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="google_analytics">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Google Analytics GT4 ID")}}</label>
                            <input type="text"
                                   name="google_analytics_gt4_ID"
                                   class="form-control"
                                   value="{{$method("google_analytics_gt4_ID")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="google_tag_manager_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Google Tag Manager")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="google_tag_manager">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Google Tag Manager ID")}}</label>
                            <input type="text"
                                   name="google_tag_manager_ID"
                                   class="form-control"
                                   value="{{$method("google_tag_manager_ID")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="google_captcha_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Google Captcha V3")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="google_captcha_v3">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Google Captcha V3 Site Key")}}</label>
                            <input type="text"
                                   name="site_google_captcha_v3_site_key"
                                   class="form-control"
                                   value="{{$method("site_google_captcha_v3_site_key")}}"
                            >
                        </div>

                        <div class="form-group">
                            <label for="#">{{__("Google Captcha V3 Secret Key")}}</label>
                            <input type="text"
                                   name="site_google_captcha_v3_secret_key"
                                   class="form-control"
                                   value="{{$method("site_google_captcha_v3_secret_key")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="instagram_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Instagram")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="instagram">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Instagram Access Token")}}</label>
                            <input type="text"
                                   name="instagram_access_token"
                                   class="form-control"
                                   value="{{get_static_option("instagram_access_token")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="google_adsense">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Google Adsense")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="google_adsense">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Google Adsense Publisher ID")}}</label>
                            <input type="text" name="google_adsense_publisher_id" id="google_adsense_id"  class="form-control" value="{{get_static_option("google_adsense_publisher_id")}}">
                        </div>
                        <div class="form-group">
                            <label for="#">{{__("Google Adsense Customer ID")}}</label>
                            <input type="text" name="google_adsense_customer_id" id="google_adsense_id"  class="form-control" value="{{get_static_option("google_adsense_customer_id")}}">
                        </div>
                        <p class="info-text">{{ __('follow doc for Google Adsence Publisher ID and Customer ID') }}
                            <a href="#" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Social login -->
    <div class="modal fade" tabindex="-1" id="social_login">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Social Login")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="social_login">
                    <div class="modal-body">
                        <div class="form__input__single d-grid mt-3">
                            <label for="enable_facebook_login"><strong>{{__('Enable/Disable Facebook Login')}}</strong></label>
                            <div class="switch_box style_7">
                                <input type="checkbox" name="enable_facebook_login"  @if(!empty(get_static_option('enable_facebook_login'))) checked @endif>
                                <label></label>
                            </div>
                            <small class="form-text text-muted">  {{__('Enable, means Frontend register page show social login')}} </small>
                        </div>

                            <div class="form-group">
                                <label for="facebook_client_id">{{__('Facebook Client ID')}}</label>
                                <input type="text" name="facebook_client_id"  class="form-control" value="{{get_static_option('facebook_client_id')}}">
                            </div>
                            <div class="form-group">
                                <label for="facebook_client_secret">{{__('Facebook Client Secret')}}</label>
                                <input type="text" name="facebook_client_secret"  class="form-control" value="{{get_static_option('facebook_client_secret')}}">
                            </div>
                            <div class="form-group">
                                <label for="facebook_callback_url">{{__('Facebook Callback URL')}}</label>
                                <input type="text" name="facebook_callback_url"  class="form-control" value="{{get_static_option('facebook_callback_url')}}">
                            </div>
                            <p class="info-text">{{__('facebook callback url for your app')}} <code>{{url('/')}}/facebook/callback</code>
                                <a href="https://bytesed.com/docs/facebook-login/" target="_blank"><i class="fas fa-external-link-alt"></i>
                                </a>
                            </p>

                            <div class="form__input__single d-grid mt-3">
                                <label for="enable_google_login"><strong>{{__('Enable/Disable Google Login')}}</strong></label>
                                <div class="switch_box style_7">
                                    <input type="checkbox" name="enable_google_login"  @if(!empty(get_static_option('enable_google_login'))) checked @endif>
                                    <label></label>
                                </div>
                                <small class="form-text text-muted">  {{__('Enable, means Frontend register page show social login')}} </small>
                            </div>

                            <div class="form-group">
                                <label for="google_client_id">{{__('Google Client ID')}}</label>
                                <input type="text" name="google_client_id"  class="form-control" value="{{get_static_option('google_client_id')}}">
                            </div>
                            <div class="form-group">
                                <label for="google_client_secret">{{__('Google Client Secret')}}</label>
                                <input type="text" name="google_client_secret"  class="form-control" value="{{get_static_option('google_client_secret')}}">
                            </div>
                            <div class="form-group">
                                <label for="google_callback_url">{{__('Google Callback URL')}}</label>
                                <input type="text" name="google_callback_url"  class="form-control" value="{{get_static_option('google_callback_url')}}">
                            </div>
                            <p class="info-text">{{__('google callback url for your app')}} <code>{{url('/')}}/google/callback</code>
                                <a href="https://bytesed.com/docs/google-login/" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                            </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function ($) {
            "use strict";

            $(document).on("click", '.pl_active_deactive', function (e) {
                e.preventDefault();
                var el = $(this);
                Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("you will able revert your decision anytime")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes!')}}",
                    cancelButtonText: "{{__('Cancel')}}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax call
                        let optionName = el.data('option');
                        let status = el.data('status');

                        $.ajax({
                            method:"POST",
                            url:"{{ route("admin.integration.activation") }}",
                            data:{ option_name: optionName, status: status },
                            success:function(res){
                                    if (res.type === 'success') {
                                        if(res.status == 'on'){
                                            toastr_warning_js("{{ __('Successfully Deactivated') }}")
                                        }else{
                                            toastr_success_js("{{ __('Successfully Activated') }}")
                                        }
                                        location.reload();
                                    }
                            }
                        })
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
