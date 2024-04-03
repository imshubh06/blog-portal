@php
$userInfo = app('App\Models\SiteSetting')->first();

@endphp

<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="/admin/dashboard">
                <img src="/storage/{{ $userInfo->logo??'logo-3.png' }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="/storage/{{ $userInfo->logo??'logo-3.png' }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="/storage/{{ $userInfo->logo??'logo-3.png' }}" class="header-brand-img light-logo" alt="logo">
                <img src="/storage/{{ $userInfo->logo??'logo-3.png' }}" class="header-brand-img light-logo1" alt="logo" width="50" height="50">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">

                @can('Dashboard')
                <li class="sub-category">
                    <h3>{{ __('Main') }}</h3>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('dashboard') }}"><i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">{{ __('Dashboard') }}</span></a>
                </li>
                @endcan

                <li class="sub-category">
                    <h3>{{ __('Information') }}</h3>
                </li>

                @can('Listing (Main Menu)')
                <li class="slide {{ (request()->is('aadmin/listing/*') || request()->is('admin/listing') || request()->is('admin/database-listing') || request()->is('admin/database-listing/*')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-list"></i><span class="side-menu__label">{{ __('Product Listing') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                        @can('Listing create')
                        <li><a href="{{ route('listing.create') }}" class="slide-item {{ (request()->is('admin/images/*')) ? 'active' : '' }}">{{ __('Create New Listing') }}</a></li>
                        @endcan
                        @can('Listing create ( DB )')
                        <li><a href="{{ route('database-listing.create') }}" class="slide-item {{ (request()->is('admin/database-listing/create')) ? 'active' : '' }}">{{ __('Create New Listing ( DB )') }}</a></li>
                        @endcan
                        @can('Pending Listing ( DB )')
                    <li><a href="{{ route('database-listing.index', ['status' => 0, 'startIndex' => 1, 'category' => '']) }}" class="slide-item {{ (request()->is('admin/database-listing')) ? 'active' : '' }}">{{ __('Pending Listing ( DB )') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('Image Creation (Main Menu)')
                <li class="slide {{ (request()->is('admin/images/single/create') || request()->is('admin/images/combo/create') || request()->is('admin/images/gallery')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-list"></i><span class="side-menu__label">{{ __('Images Creation') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        @can('Image Creation -> Single Image Maker')
                        <li><a href="{{ route('image.single.create', ['maker' => 'w-watermark']) }}" class="slide-item {{ (request()->is('admin/images/single/create')) ? 'active' : '' }}">{{ __('Single Image Maker') }}</a></li>
                        @endcan
                        @can('Image Creation -> Combo Image Maker')
                        <li><a href="{{ route('image.combo.create', ['maker' => 'w-watermark']) }}" class="slide-item {{ (request()->is('admin/images/combo/create')) ? 'active' : '' }}">{{ __('Combo Image Maker') }}</a></li>
                        @endcan
                        @can('Image Creation -> Gallery ( DB )')
                        <li><a href="{{ route('image.gallery') }}" class="slide-item {{ (request()->is('admin/images/gallery')) ? 'active' : '' }}">{{ __('Gallery ( DB )') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('Inventory (Main Menu)')
                <li class="slide {{ (request()->is('admin/inventory') || request()->is('admin/inventory/drafted') || request()->is('admin/inventory/review') || request()->is('admin/google/products/list') || request()->is('admin/profile/listings')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-box"></i><span class="side-menu__label">{{ __('Inventory') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>
                        @can('Inventory -> Manage Inventory')
                        <li><a href="{{ route('inventory.index', ['startIndex' => 1, 'category' => 'Product']) }}" class="slide-item {{ request()->is('admin/inventory') ? 'active' : '' }}">{{ __('Manage Inventory') }}</a></li>
                        @endcan
                        @can('Inventory -> Drafted Inventory')
                        <li><a href="{{ route('inventory.drafted') }}" class="slide-item {{ request()->is('admin/inventory/drafted') ? 'active' : '' }}">{{ __('Drafted Inventory') }}</a></li>
                        @endcan

                        @can('Inventory -> Under Review Inventory')
                        <li><a href="{{ route('inventory.review', ['startIndex' => 1, 'category' => 'Product', 'updated_before' => 3]) }}" class="slide-item {{ request()->is('admin/inventory/review') ? 'active' : '' }}">{{ __('Under Review Inventory') }}</a></li>
                        @endcan
                        <li><a href="{{ route('profile.listing', ['user' => 'all']) }}" class="slide-item {{ (request()->is('admin/profile/listings')) ? 'active' : '' }}">{{ __('Listing Reports ( DB )') }}</a></li>
                    </ul>
                </li>
                @endcan

                @can('User Details (Main Menu)')
                <li class="slide {{ (request()->is('admin/users/*') || request()->is('admin/users')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">{{ __('Users Details') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>

                        @can('User create')
                        <li><a href="{{ route('users.create') }}" class="slide-item {{ request()->is('admin/users/create') ? 'active' : '' }}">{{ __('Create New Users') }}</a></li>
                        @endcan

                        @can('User approved')
                        <li><a href="{{ route('verified.users') }}" class="slide-item {{ request()->is('admin/users/verified/approved') ? 'active' : '' }}">{{ __('Approved Users') }}</a>
                        </li>
                        @endcan

                        @can('User Details -> All Users List')
                        <li><a href="{{ route('users.index') }}" class="slide-item {{ (request()->is('admin/users') || request()->is('admin/users/*/edit')) ? 'active' : '' }}">{{ __('All Users List') }}</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @if(auth()->user()->can('Roles & Permissions (Main Menu)'))
                <li class="slide {{ (request()->is('admin/roles/*') || request()->is('admin/roles')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item  {{ request()->is('admin/roles*') ? 'active is-expanded' : '' }}" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-lock"></i><span class="side-menu__label">{{ __('Roles & Permissions') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        @can('Role create')
                        <li>
                            <a href="{{ route('roles.create') }}" class="slide-item  {{ request()->is('admin/roles/create') ? 'active' : '' }}">{{ __('Create New Roles') }}</a>
                        </li>
                        @endcan
                        @can('Roles & Permissions -> Assign Permissions to Roles')
                        <li>
                            <a href="{{ route('roles.index') }}" class="slide-item  {{ (request()->is('admin/roles') || request()->is('admin/roles/edit')) ? 'active' : '' }}">{{ __('Assign Permissions to Roles') }}</a>
                        </li>
                        @endcan

                        @can('Roles & Permissions -> View All Roles & Permissions')
                        <li>
                            <a href="{{ route('view.roles') }}" class="slide-item  {{ (request()->is('admin/roles/all/view')) ? 'active' : '' }}">{{ __('View All Roles & Permissions') }}</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                @if(auth()->user()->can('Settings (Main Menu)'))
                <li class="slide {{ (request()->is('admin/settings/*') || request()->is('admin/settings') || request()->is('admin/backup/emails') || request()->is('admin/backup/logs')) ? 'is-expanded' : '' }}">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fa fa-gear"></i><span class="side-menu__label">{{ __('Settings') }}</span><i class="angle fe fe-chevron-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0)">Apps</a></li>

                        @can('Settings -> Site Access')
                        <li><a href="{{ route('settings.site') }}" class="slide-item {{ request()->is('admin/settings/site') ? 'active' : '' }}">{{ __('Site Settings') }}</a></li>
                        @endcan

                        @can('Settings -> Backup E-Mail')
                        <li><a href="{{ route('settings.emails') }}" class="slide-item {{ request()->is('admin/backup/emails') ? 'active' : '' }}">{{ __('Backup E-Mail') }}</a></li>
                        @endcan
                        
                        @can('Settings -> Backup Logs & Links')
                        <li><a href="{{ route('backup.logs') }}" class="slide-item {{ request()->is('admin/backup/logs') ? 'active' : '' }}">{{ __('Backup Logs & Links') }}</a></li>
                        @endcan
                        
                        @can('Settings -> Validations')
                        <li><a href="{{ route('settings.keywords.valid') }}" class="slide-item {{ request()->is('admin/names/validations') ? 'active' : '' }}">{{ __('Validations') }}</a></li>
                        @endcan
                        
                        @can('Settings -> Configure Blog')
                        <li><a href="{{ route('settings.blog') }}" class="slide-item {{ request()->is('admin/settings/blog') ? 'active' : '' }}">{{ __('Confiure Blog') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>