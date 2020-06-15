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
    <li class="nav-item"><a
            class="nav-link {{ activateClass('backend.order.index')  }}"
            href="/">@lang('gui.orders')</a>
    </li>
</ul>
