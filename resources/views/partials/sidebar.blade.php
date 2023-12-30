<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="index.html">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo"
                    alt="logo">
                <img src="{{ asset('assets/images/brand/logo-1.png') }}" class="header-brand-img toggle-logo"
                    alt="logo">
                <img src="{{ asset('assets/images/brand/logo-2.png') }}" class="header-brand-img light-logo"
                    alt="logo">
                <img src="{{ asset('assets/images/brand/logo-3.png') }}" class="header-brand-img light-logo1"
                    alt="logo">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>{{ __('Main') }}</h3>
                </li>
                <li class="slide">
<<<<<<< HEAD
                    <a class="side-menu__item" data-bs-toggle="slide" href="index.html"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">{{ __('Dashboard') }}</span></a>
=======
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('dashboard') }}"><i
                            class="side-menu__icon fe fe-home"></i><span
                            class="side-menu__label">{{ __('Dashboard') }}</span></a>
>>>>>>> 6892cc288438bec0e136420bfd32331227f539fd
                </li>
                <li class="sub-category">
                    <h3>{{ __('Accounts') }}</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">{{ __('Users') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                        <li><a href="{{ route('users.index') }}" class="slide-item">{{ __('Users') }}</a></li>
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">{{ __('Settings') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                        <li><a href="{{ route('setting.index') }}" class="slide-item">{{ __('Settings') }}</a></li>
                    </ul>
                </li>
            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">

                @can('User access')
                    <li class="slide {{ request()->is('admin/users*') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ request()->is('admin/users*') ? 'active is-expanded' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)"><i
                                class="side-menu__icon fe fe-users"></i><span
                                class="side-menu__label">{{ __('Users') }}</span><i
                                class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                            <li><a href="{{ route('users.index') }}"
                                    class="slide-item {{ request()->is('admin/users*') ? 'active' : '' }}">{{ __('Users') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('Role access')
                    <li class="slide {{ request()->is('admin/roles*') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item  {{ request()->is('admin/roles*') ? 'active is-expanded' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-lock"></i><span
                                class="side-menu__label">{{ __('Role & Permission') }}</span><i
                                class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                            <li><a href="{{ route('roles.index') }}"
                                    class="slide-item  {{ request()->is('admin/roles*') ? 'active' : '' }}">{{ __('Roles') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </div>
    <!--/APP-SIDEBAR-->
</div>