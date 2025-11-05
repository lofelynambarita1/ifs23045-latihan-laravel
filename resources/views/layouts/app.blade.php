<!doctype html>
<html lang="id">

<head>
    {{-- Meta --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Icon --}}
    <link rel="icon" href="/logo.png" type="image/x-icon" />

    {{-- Judul --}}
    <title>Catatan Keuangan</title>

    {{-- Styles --}}
    @livewireStyles
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="bg-light">
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('app.transactions') }}">💰 Catatan Keuangan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('app.transactions') ? 'active' : '' }}" 
                           href="{{ route('app.transactions') }}">
                            📊 Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('app.home') ? 'active' : '' }}" 
                           href="{{ route('app.home') }}">
                            📝 Todo List
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">👤 {{ auth()->user()->name }}</span>
                    <a href="{{ route('auth.logout') }}" class="btn btn-warning btn-sm">Keluar</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        @yield('content')
    </div>

    {{-- Scripts --}}
    <script src="/assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener("livewire:initialized", () => {
            Livewire.on("closeModal", (data) => {
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById(data.id)
                );
                if (modal) {
                    modal.hide();
                }
            });

            Livewire.on("showModal", (data) => {
                const modal = bootstrap.Modal.getOrCreateInstance(
                    document.getElementById(data.id)
                );
                if (modal) {
                    modal.show();
                }
            });
        });
    </script>
    
    @stack('scripts')
    @livewireScripts
</body>

</html>