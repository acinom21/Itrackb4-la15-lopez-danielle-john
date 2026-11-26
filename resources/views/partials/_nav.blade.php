<nav class="navbar navbar-expand-lg bg-dark sticky-top shadow-sm" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand d-flex flex-column lh-sm py-1" href="{{ route('home') }}">
            <span class="fw-bold"><i class="bi bi-book-half me-1"></i>Library Portal</span>
            <small class="text-secondary" style="font-size: 0.7rem;">Prepared by: Danielle John T. Lopez</small>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('books.*') && ! request()->routeIs('books.feature') ? 'active' : '' }}"
                       href="{{ route('books.index') }}">Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('books.feature') ? 'active' : '' }}"
                       href="{{ route('books.feature') }}">Featured</a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-lg-center">
                @auth
                    <li class="nav-item me-lg-3">
                        <span class="navbar-text">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            @if (auth()->user()->role === 'admin')
                                <span class="badge bg-warning text-dark ms-1">admin</span>
                            @endif
                        </span>
                    </li>
                    @if (Route::has('logout'))
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                            </form>
                        </li>
                    @endif
                @else
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="btn btn-sm btn-light" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>