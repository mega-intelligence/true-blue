{{--
<ul class="navbar-nav mr-auto">
    <li class="nav-item">
        <a class="nav-link "
           href="{{ route('backend.dashboard') }}">@lang('gui.dashboard')</a>
    </li>
</ul>
--}}
<div class="menu">
    <div class="main-menu">
        <div class="scroll">
            <ul class="list-unstyled">
                <li class="{{ activateClass('backend.dashboard') }}">
                    <a href="#dashboard">
                        <i class="iconsminds-shop-4"></i>
                        <span>Dashboards</span>
                    </a>
                </li>
                <li>
                    <a href="#orders">
                        <i class="iconsminds-shopping-cart"></i> Orders
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @include('_partials.menu.sub-menus')
</div>
