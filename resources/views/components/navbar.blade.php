<nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom" style="position: relative; z-index: 1050;">
    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
            <i class="fa-solid fa-earth-asia me-2"></i>WebGIS
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Tambahkan style visibility: visible !important; untuk melawan Tailwind CSS -->
<div class="collapse navbar-collapse" id="navbarNav" style="visibility: visible !important;">

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium" href="{{ route('home') }}">
                        <i class="fa-solid fa-house me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium" href="{{ route('peta') }}">
                        <i class="fa-solid fa-map me-1"></i> Peta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium" href="{{ route('tabel') }}">
                        <i class="fa-solid fa-table-list me-1"></i> Tabel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-medium" href="#">
                        <i class="fa-solid fa-circle-info me-1"></i> Tentang
                    </a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <div class="vr h-100 mx-1"></div>
                </li>

                @guest
                    <li class="nav-item mt-2 mt-lg-0">
                        <a class="btn btn-primary px-4 rounded shadow-sm" href="{{ route('login') }}">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Login
                        </a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item mt-2 mt-lg-0">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger px-4 rounded text-white fw-medium shadow-sm">
                                <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>

        </div>
    </div>
</nav>
