@extends('backend.admin-master')
@section('site-title')
    {{__('Dashboard')}}
@endsection
@section('style')
    <style>
        /* Modernized Dashboard Styles */
        :root {
            --primary-color: #3B82F6;
            --secondary-color: #10B981;
            --bg-color: #F3F4F6;
            --card-bg: #ffffff;
            --text-main: #1F2937;
            --text-muted: #6B7280;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .dashboard__body {
            background-color: var(--bg-color);
            font-family: 'Inter', sans-serif;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #4F46E5 0%, #3B82F6 100%);
            border-radius: 16px;
            padding: 30px 40px;
            color: white;
            box-shadow: var(--shadow-md);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        .welcome-banner::after {
            content: '';
            position: absolute;
            right: -50px;
            top: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .welcome-banner h4 {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .welcome-banner p {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
            margin: 0;
        }

        /* Stat Cards */
        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.02);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        .stat-card .title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.95rem;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 15px;
        }
        .stat-card i {
            color: var(--primary-color);
            background: rgba(59, 130, 246, 0.1);
            padding: 8px;
            border-radius: 10px;
        }

        /* General Cards (Charts & Tables) */
        .modern-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }
        .modern-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #F3F4F6;
            padding-bottom: 15px;
        }
        .modern-card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-main);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modern-card-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 400;
            margin-left: 10px;
        }

        /* Select styling */
        .modern-select {
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.9rem;
            color: var(--text-main);
            outline: none;
        }

        /* Table styling improvements */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .modern-table th {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 12px 15px;
            border-bottom: 2px solid #F3F4F6;
            text-align: left;
        }
        .modern-table td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #F3F4F6;
            color: var(--text-main);
        }
        .modern-table tr:hover td {
            background-color: #F9FAFB;
        }
        
        .user-info-flex {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #E5E7EB;
        }
        .user-name {
            font-weight: 600;
            margin: 0;
            font-size: 0.95rem;
        }
        .badge-id {
            background: #F3F4F6;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
        }
        .service-image {
            width: 60px;
            border-radius: 8px;
        }
        .action-btn {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .action-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        #sales_pipeline { width: 100%!important; height: 300px!important; }
    </style>
@endsection
@section('content')
    <div class="dashboard__body posPadding">
        <div class="dashboard__inner">
            <div class="dashboard__inner__item">
                <div class="dashboard__inner__item__flex">
                    <div class="dashboard__inner__item__left bodyItemPadding">
                        
                        <!-- NEW WELCOME BANNER -->
                        <div class="welcome-banner">
                            <h4><strong id="greeting"></strong>, {{ Auth::guard('admin')->user()->name }} 👋</h4>
                            <p>{{ __('Bienvenue sur votre tableau de bord BaraChap. Voici un aperçu de vos activités.') }}</p>
                        </div>

                        <!-- STAT CARDS -->
                        <div class="row g-4 mb-4">
                            @foreach($dashboardData as $item)
                                <div class="col-xxl-2 col-xl-3 col-sm-6">
                                    <div class="stat-card">
                                        <div class="title-row">
                                            <span>{{ $item['title'] ?? '' }}</span>
                                            @if(isset($item['route']))
                                                <a href="{{ isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']) }}">
                                                    <i class="las la-external-link-alt fs-5"></i>
                                                </a>
                                            @else
                                                <i class="las la-chart-bar fs-5"></i>
                                            @endif
                                        </div>
                                        <div class="value">{{ $item['value'] ?? 0 }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- CHARTS ROW -->
                        <div class="row g-4 mb-4">
                            <div class="col-xl-6 col-lg-6">
                                <div class="modern-card">
                                    <div class="modern-card-header">
                                        <h5 class="modern-card-title">
                                            <i class="las la-users text-primary fs-3"></i> {{ __('Utilisateurs') }}
                                            <span class="modern-card-subtitle">{{ __('Total:') }} {{ $total_user }}</span>
                                        </h5>
                                        <select id="timeIntervalSelect" class="modern-select">
                                            @foreach(['This Week','Last Week','This Month','Last Month','This Year','Last Year'] as $key => $option)
                                                <option value="{{ $key }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="chart__item__inner mt-4">
                                        <canvas id="lineChartCustomer" height="120"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="modern-card">
                                    <div class="modern-card-header">
                                        <h5 class="modern-card-title">
                                            <i class="las la-tools text-success fs-3"></i> {{ __('Services') }}
                                            <span class="modern-card-subtitle">{{ __('Total:') }} {{ $total_services }}</span>
                                        </h5>
                                        <select id="serviceTimeIntervalSelect" class="modern-select">
                                            @foreach(['This Week','Last Week','This Month','Last Month','This Year','Last Year'] as $key => $option)
                                                <option value="{{ $key }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="chart__item__inner mt-4">
                                        <canvas id="lineChartListings" height="120"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TABLES ROW -->
                        <div class="row g-4 mb-4">
                            <div class="col-lg-6">
                                <div class="modern-card">
                                    <div class="modern-card-header">
                                        <h5 class="modern-card-title">{{ __('Nouveaux Utilisateurs') }}</h5>
                                    </div>
                                    <div class="table-responsive">
                                        @if($recent_users->count() > 0)
                                            <table class="modern-table">
                                                <thead>
                                                <tr>
                                                    <th>{{ __('ID') }}</th>
                                                    <th>{{ __('Utilisateur') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($recent_users as $user)
                                                    <tr>
                                                        <td><span class="badge-id">#{{ $user->id }}</span></td>
                                                        <td>
                                                            <div class="user-info-flex">
                                                                @if(!empty($user->image))
                                                                    {!! render_image_markup_by_attachment_id($user->image, 'user-avatar') !!}
                                                                @else
                                                                    <img class="user-avatar" src="{{ asset('assets/frontend/img/static/user-no-image.webp') }}" alt="No Image">
                                                                @endif
                                                                <p class="user-name">{{ trim($user->fullname) !== "" ? $user->fullname : $user->email }}</p>
                                                            </div>
                                                        </td>
                                                        <td><span class="text-muted">{{ $user->created_at->format('d M Y') }}</span></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="text-center text-muted py-4">{{ __('Aucun utilisateur récent') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="modern-card">
                                    <div class="modern-card-header">
                                        <h5 class="modern-card-title">{{ __('Derniers Services') }}</h5>
                                    </div>
                                    <div class="table-responsive">
                                        @if($recent_services->count() > 0)
                                        <table class="modern-table">
                                            <thead>
                                            <tr>
                                                <th>{{ __('Image') }}</th>
                                                <th>{{ __('Service') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($recent_services as $service)
                                                <tr>
                                                    <td>
                                                        {!! render_image_markup_by_attachment_id($service->image, 'service-image') !!}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.service.details', $service->id) }}" class="user-name text-decoration-none">
                                                            {{ Str::limit($service->title, 30) }}
                                                        </a>
                                                        <div class="text-muted small mt-1">{{ $service->created_at->format('d M Y') }}</div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.service.details', $service->id) }}" class="action-btn">
                                                            <i class="las la-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @else
                                            <div class="text-center text-muted py-4">{{ __('Aucun service récent') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- REVENUE CHART -->
                        <div class="row g-4 mt-1">
                            <div class="col-xl-6 col-lg-6">
                                <div class="modern-card">
                                    <div class="modern-card-header">
                                        <h5 class="modern-card-title">
                                            <i class="las la-wallet text-warning fs-3"></i> {{ __('Revenus') }}
                                        </h5>
                                        <select id="totalIncomeIntervalSelectAll" class="modern-select">
                                            @foreach(['Today', 'Yesterday', 'This Week','Last Week','This Month','Last Month','This Year','Last Year'] as $key => $option)
                                                <option value="{{ $key }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="chart__item__inner mt-4">
                                        <div class="sales_pipeline_chart">
                                            <div id="sales_pipeline"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            let currentTime = new Date().getHours();
            let morningGreeting = "{{ __('Bonjour') }}";
            let afternoonGreeting = "{{ __('Bon après-midi') }}";
            let eveningGreeting = "{{ __('Bonsoir') }}";
            if (currentTime >= 0 && currentTime < 12) {
                $('#greeting').text(morningGreeting);
            } else if (currentTime >= 12 && currentTime < 18) {
                $('#greeting').text(afternoonGreeting);
            } else {
                $('#greeting').text(eveningGreeting);
            }
        });
    </script>
    @include('backend.pages.dashboard.line-graph-js')
    @include('backend.pages.dashboard.total-income-graph-js')
@endsection
