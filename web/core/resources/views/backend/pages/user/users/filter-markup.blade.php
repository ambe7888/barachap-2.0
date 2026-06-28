<div class="dashboard__inner__item__header padding-20">
    <div class="dashboard__inner__item__header__flex">
        <div class="dashboard__inner__item__header__left">
            <div class="dashboard_order__tab d-flex flex-wrap gap-3">
                <ul class="tabs">
                    <li class="{{ $current_status === 'all' ? 'active' : '' }}">
                        <a href="{{ route('admin.user.all') }}">
                            {{ __('All Users') }}
                            <span class="badge_notification">{{ $total_users  }}</span>
                        </a>
                    </li>
                    <li class="{{ $current_status == '1' ? 'active' : '' }}">
                        <a href="{{ route('admin.user.all', ['status' => '1']) }}">{{ __('Clients') }}
                            <span class="badge_notification">{{ $total_client }}</span>
                        </a>
                    </li>
                    <li class="{{ $current_status == '0' ? 'active' : '' }}">
                        <a href="{{ route('admin.user.all', ['status' => '0']) }}">
                            {{ __('Providers') }} <span class="badge_notification">{{ $total_provider }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="dashboard__inner__item__header__right">
            <div class="dashboard__inner__item__header__right__flex">
                <div class="dashboard__inner__item__header__right__item">
                    <div class="porduct_search">
                        <input type="text" class="form--control porduct_search__input radius-5 string_search"
                               name="string_search" id="string_search" placeholder="{{ __('Search order') }}">
                        <button type="submit" class="porduct_search__icon"><i class="material-symbols-outlined">{{ __('search') }}</i> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
