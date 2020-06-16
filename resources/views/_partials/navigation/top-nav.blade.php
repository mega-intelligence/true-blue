<ul class="navbar-nav mr-auto">
    <li class="nav-item">
        <a class="nav-link {{ activateClass('backend.dashboard') }}"
           href="{{ route('backend.dashboard') }}">@lang('gui.dashboard')</a>
    </li>
    <li class="nav-item"><a
            class="nav-link {{ activateClass('backend.category.index')  }}"
            href="/">@lang('gui.categories')</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ activateClass('backend.product.index')  }}"
           href="{{ route('backend.product.index') }}">@lang('gui.products')</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ activateClass('backend.order.index')  }}"
           href="#" data-toggle="dropdown">@lang('gui.orders')</a>

        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item active" href="#">Drafts</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Quotes</a>
            <a class="dropdown-item" href="#">Purchase</a>
            <a class="dropdown-item" href="#">Orders</a>
        </div>
    </li>
</ul>
