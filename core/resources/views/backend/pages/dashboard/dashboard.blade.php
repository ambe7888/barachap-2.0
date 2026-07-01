@extends('backend.admin-master')
@section('site-title')
    {{__('Dashboard')}}
@endsection
@section('style')
<style>
/* ==============================================
   BARACHAP ADMIN DASHBOARD – Clean & Responsive
   ============================================== */
:root {
    --ds-primary:    #4F46E5;
    --ds-primary-lt: rgba(79,70,229,.10);
    --ds-success:    #10B981;
    --ds-warning:    #F59E0B;
    --ds-danger:     #EF4444;
    --ds-bg:         #F3F4F6;
    --ds-card:       #ffffff;
    --ds-text:       #1F2937;
    --ds-muted:      #6B7280;
    --ds-border:     #E5E7EB;
    --ds-shadow:     0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04);
    --ds-shadow-lg:  0 10px 25px rgba(0,0,0,.08);
    --ds-radius:     14px;
}

/* prevent any container from overflowing horizontally */
.dashboard__area,
.dashboard__contents__wrapper,
.dashboard__inner,
.dashboard__inner__item,
.dashboard__inner__item__flex,
.dashboard__inner__item__left,
.container-fluid {
    overflow-x: hidden !important;
    max-width: 100% !important;
}

/* page root */
.ds-page { padding: 8px 0 40px; width: 100%; }

/* ---- Welcome Banner ---- */
.ds-banner {
    background: linear-gradient(135deg, #4F46E5 0%, #6366F1 55%, #3B82F6 100%);
    border-radius: var(--ds-radius);
    padding: 28px 36px;
    color: #fff;
    box-shadow: var(--ds-shadow-lg);
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.ds-banner::before {
    content:''; position:absolute;
    right:-60px; top:-60px;
    width:220px; height:220px;
    background:rgba(255,255,255,.08); border-radius:50%;
}
.ds-banner h4 { color:#fff; font-size:1.55rem; font-weight:700; margin-bottom:6px; }
.ds-banner p  { color:rgba(255,255,255,.85); font-size:.97rem; margin:0; }

/* ---- KPI Cards ---- */
.ds-stat {
    background:var(--ds-card);
    border-radius:var(--ds-radius);
    padding:18px 20px;
    box-shadow:var(--ds-shadow);
    border:1px solid var(--ds-border);
    height:100%;
    display:flex; flex-direction:column; gap:10px;
    transition: box-shadow .25s, transform .25s;
    overflow:hidden;
}
.ds-stat:hover { transform:translateY(-3px); box-shadow:var(--ds-shadow-lg); }
.ds-stat__head { display:flex; justify-content:space-between; align-items:center; }
.ds-stat__label {
    font-size:.78rem; font-weight:600; color:var(--ds-muted);
    text-transform:uppercase; letter-spacing:.04em;
    overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:110px;
}
.ds-stat__icon {
    width:34px; height:34px; border-radius:10px;
    background:var(--ds-primary-lt);
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.ds-stat__icon i { color:var(--ds-primary); font-size:1.1rem; }
.ds-stat__value {
    font-size:1.85rem; font-weight:700; color:var(--ds-text);
    line-height:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.ds-stat__link {
    font-size:.76rem; color:var(--ds-primary); text-decoration:none;
    display:inline-flex; align-items:center; gap:4px; opacity:.8;
    transition:opacity .2s;
}
.ds-stat__link:hover { opacity:1; }

/* ---- Generic Card ---- */
.ds-card {
    background:var(--ds-card);
    border-radius:var(--ds-radius);
    padding:20px 22px;
    box-shadow:var(--ds-shadow);
    border:1px solid var(--ds-border);
    margin-bottom:24px;
    overflow:hidden;
    width:100%;
    box-sizing:border-box;
}
.ds-card-header {
    display:flex; justify-content:space-between; align-items:center;
    flex-wrap:wrap; gap:10px;
    margin-bottom:16px; padding-bottom:14px;
    border-bottom:1px solid var(--ds-border);
}
.ds-card-title {
    font-size:1rem; font-weight:600; color:var(--ds-text);
    margin:0; display:flex; align-items:center; gap:8px;
}
.ds-card-title i { font-size:1.15rem; }
.ds-card-badge {
    font-size:.75rem; color:var(--ds-muted);
    background:var(--ds-bg); padding:2px 9px; border-radius:20px;
}

/* ---- Select ---- */
.ds-select {
    border:1px solid var(--ds-border); border-radius:8px;
    padding:5px 10px; font-size:.8rem; color:var(--ds-text);
    background:#fff; outline:none; cursor:pointer;
}
.ds-select:focus { border-color:var(--ds-primary); }

/* ---- Chart containers (fixed height stops overflow) ---- */
.ds-chart-wrap {
    position:relative; width:100%; height:220px; overflow:hidden;
}
.ds-chart-wrap canvas { max-width:100% !important; max-height:100% !important; }
#sales_pipeline { width:100% !important; height:100% !important; }

/* ---- Table ---- */
.ds-table { width:100%; border-collapse:separate; border-spacing:0; table-layout:fixed; }
.ds-table th {
    color:var(--ds-muted); font-size:.76rem; font-weight:600;
    text-transform:uppercase; letter-spacing:.05em;
    padding:9px 13px; border-bottom:2px solid var(--ds-border); text-align:left;
}
.ds-table td {
    padding:11px 13px; vertical-align:middle;
    border-bottom:1px solid #F9FAFB; color:var(--ds-text);
    font-size:.88rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}
.ds-table tr:last-child td { border-bottom:none; }
.ds-table tr:hover td { background:#F9FAFB; }

/* ---- Misc ---- */
.ds-badge-id {
    background:var(--ds-bg); padding:3px 8px;
    border-radius:6px; font-size:.8rem; font-weight:600; color:var(--ds-muted);
}
.ds-avatar {
    width:36px; height:36px; border-radius:50%;
    object-fit:cover; border:2px solid var(--ds-border); flex-shrink:0;
}
.ds-svc-img { width:46px; height:36px; border-radius:7px; object-fit:cover; }
.ds-action {
    width:30px; height:30px; background:var(--ds-primary-lt); color:var(--ds-primary);
    border-radius:7px; display:inline-flex; align-items:center; justify-content:center;
    text-decoration:none; transition:background .2s, color .2s; cursor:pointer;
}
.ds-action:hover { background:var(--ds-primary); color:#fff; }
.ds-user-cell { display:flex; align-items:center; gap:9px; overflow:hidden; }
.ds-user-cell span {
    font-weight:600; font-size:.88rem;
    overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}
.ds-empty { text-align:center; color:var(--ds-muted); padding:26px 0; font-size:.88rem; }
</style>
@endsection

@section('content')
<div class="ds-page">

    {{-- WELCOME BANNER --}}
    <div class="ds-banner">
        <h4><strong id="greeting"></strong>, {{ Auth::guard('admin')->user()->name }} 👋</h4>
        <p>{{ __('Bienvenue sur votre tableau de bord BaraChap. Voici un aperçu de vos activités.') }}</p>
    </div>

    {{-- KPI STAT CARDS --}}
    <div class="row g-3 mb-4">
        @foreach($dashboardData as $item)
        <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
            <div class="ds-stat">
                <div class="ds-stat__head">
                    <span class="ds-stat__label" title="{{ $item['title'] ?? '' }}">{{ $item['title'] ?? '' }}</span>
                    <div class="ds-stat__icon"><i class="las la-chart-bar"></i></div>
                </div>
                <div class="ds-stat__value">{{ $item['value'] ?? 0 }}</div>
                @if(isset($item['route']))
                    <a class="ds-stat__link" href="{{ isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']) }}">
                        {{ __('Voir') }} <i class="las la-arrow-right"></i>
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- CHARTS ROW --}}
    <div class="row g-4 mb-2">
        <div class="col-xl-6 col-lg-6">
            <div class="ds-card">
                <div class="ds-card-header">
                    <h5 class="ds-card-title">
                        <i class="las la-users text-primary"></i>
                        {{ __('Utilisateurs') }}
                        <span class="ds-card-badge">Total : {{ $total_user }}</span>
                    </h5>
                    <select id="timeIntervalSelect" class="ds-select">
                        @foreach(['This Week','Last Week','This Month','Last Month','This Year','Last Year'] as $k => $o)
                            <option value="{{ $k }}">{{ $o }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ds-chart-wrap"><canvas id="lineChartCustomer"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="ds-card">
                <div class="ds-card-header">
                    <h5 class="ds-card-title">
                        <i class="las la-tools text-success"></i>
                        {{ __('Services') }}
                        <span class="ds-card-badge">Total : {{ $total_services }}</span>
                    </h5>
                    <select id="serviceTimeIntervalSelect" class="ds-select">
                        @foreach(['This Week','Last Week','This Month','Last Month','This Year','Last Year'] as $k => $o)
                            <option value="{{ $k }}">{{ $o }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ds-chart-wrap"><canvas id="lineChartListings"></canvas></div>
            </div>
        </div>
    </div>

    {{-- TABLES ROW --}}
    <div class="row g-4 mb-2">
        {{-- Recent Users --}}
        <div class="col-lg-6">
            <div class="ds-card">
                <div class="ds-card-header">
                    <h5 class="ds-card-title">
                        <i class="las la-user-plus text-primary"></i>
                        {{ __('Nouveaux Utilisateurs') }}
                    </h5>
                </div>
                <div class="table-responsive">
                    @if($recent_users->count() > 0)
                        <table class="ds-table">
                            <colgroup><col style="width:55px"><col><col style="width:95px"></colgroup>
                            <thead><tr>
                                <th>ID</th><th>{{ __('Utilisateur') }}</th><th>{{ __('Date') }}</th>
                            </tr></thead>
                            <tbody>
                            @foreach($recent_users as $user)
                                <tr>
                                    <td><span class="ds-badge-id">#{{ $user->id }}</span></td>
                                    <td>
                                        <div class="ds-user-cell">
                                            @if(!empty($user->image))
                                                {!! render_image_markup_by_attachment_id($user->image, 'ds-avatar') !!}
                                            @else
                                                <img class="ds-avatar" src="{{ asset('assets/frontend/img/static/user-no-image.webp') }}" alt="">
                                            @endif
                                            <span>{{ trim($user->fullname) !== "" ? $user->fullname : $user->email }}</span>
                                        </div>
                                    </td>
                                    <td><span class="text-muted">{{ $user->created_at->format('d M Y') }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="ds-empty">{{ __('Aucun utilisateur récent') }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Services --}}
        <div class="col-lg-6">
            <div class="ds-card">
                <div class="ds-card-header">
                    <h5 class="ds-card-title">
                        <i class="las la-concierge-bell text-success"></i>
                        {{ __('Derniers Services') }}
                    </h5>
                </div>
                <div class="table-responsive">
                    @if($recent_services->count() > 0)
                        <table class="ds-table">
                            <colgroup><col style="width:60px"><col><col style="width:48px"></colgroup>
                            <thead><tr>
                                <th>{{ __('Image') }}</th><th>{{ __('Service') }}</th><th></th>
                            </tr></thead>
                            <tbody>
                            @foreach($recent_services as $service)
                                <tr>
                                    <td>{!! render_image_markup_by_attachment_id($service->image, 'ds-svc-img') !!}</td>
                                    <td>
                                        <a href="{{ route('admin.service.details', $service->id) }}" class="fw-semibold text-decoration-none" style="color:var(--ds-text)">
                                            {{ Str::limit($service->title, 35) }}
                                        </a>
                                        <div class="text-muted" style="font-size:.75rem;margin-top:2px">{{ $service->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.service.details', $service->id) }}" class="ds-action">
                                            <i class="las la-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="ds-empty">{{ __('Aucun service récent') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- REVENUE CHART --}}
    <div class="row g-4">
        <div class="col-xl-7 col-lg-9">
            <div class="ds-card">
                <div class="ds-card-header">
                    <h5 class="ds-card-title">
                        <i class="las la-wallet text-warning"></i>
                        {{ __('Revenus') }}
                    </h5>
                    <select id="totalIncomeIntervalSelectAll" class="ds-select">
                        @foreach(['Today','Yesterday','This Week','Last Week','This Month','Last Month','This Year','Last Year'] as $k => $o)
                            <option value="{{ $k }}">{{ $o }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ds-chart-wrap" style="height:250px">
                    <div id="sales_pipeline"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    let h = new Date().getHours();
    let g = h < 12 ? "{{ __('Bonjour') }}" : (h < 18 ? "{{ __('Bon après-midi') }}" : "{{ __('Bonsoir') }}");
    $('#greeting').text(g);
});
</script>
@include('backend.pages.dashboard.line-graph-js')
@include('backend.pages.dashboard.total-income-graph-js')
@endsection
