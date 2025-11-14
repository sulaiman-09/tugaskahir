<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Life Media CMS')</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <!-- ✅ Tambahkan Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Versi terbaru -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        /* --- HEADER --- */
        .header {
            background: #2c3e50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            position: fixed;
            top: 0;
            left: 250px;
            /* tetap 250px */
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: 70px;
            transition: all 0.3s ease-in-out;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s ease-in-out;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            /* tetap di kiri */
            bottom: 0;
            width: 250px;
            height: 100vh;
            background: #34495e;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            transition: all 0.3s ease-in-out;
        }

        .sidebar-header {
            padding: 20px;
            background: #2c3e50;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            height: 70px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            border-bottom: 1px solid #2c3e50;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #2c3e50;
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu .icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .sidebar-menu a.active {
            transform: none !important;
            /* pastikan menu aktif tidak geser */
        }

        /* --- KONTEN UTAMA --- */
        .main-content {
            margin-left: 250px;
            /* tetap 250px agar sesuai sidebar */
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
            transition: all 0.3s ease-in-out;
            animation: fadeIn 0.25s ease-in;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
        }

        .page-title {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
        }

        .refresh-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .refresh-btn:hover {
            background: #2980b9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
        }

        .access-denied {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        /* === FIX SIDEBAR BERGESER & TAMBAH TRANSISI SMOOTH === */
        html,
        body {
            overflow-x: hidden;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }



        /* ===== Media Query ===== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <style>
        .sidebar ol,
        ul {
            padding-left: 0 !important;
        }
        
    </style>

    {{-- Tempat CSS tambahan dari setiap halaman --}}
    @stack('styles')
</head>

<body>
    <header class="header">
        <div class="user-menu">
            <div class="user-info">
                <span>{{ auth()->user()->name ?? 'Admin User' }}</span>
                <span style="font-size: 12px; opacity: 0.8;">({{ ucfirst(auth()->user()->role ?? 'admin') }})</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <nav class="sidebar shadow-sm">
        <div class="sidebar-header text-center py-3">
            <img src="{{ asset('images/logolifemedia.png') }}" alt="Life Media Logo" style="height:55px;width:auto;" />
        </div>

        <ul class="sidebar-menu">

            {{-- Dashboard --}}
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'report')
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endif

            {{-- Customer --}}
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'sales')
                <li>
                    <a href="{{ route('customer.index') }}"
                        class="sidebar-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        <span>Customer</span>
                    </a>
                </li>
            @endif

            {{-- Sudirman Park --}}
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'sudirman park')
                <li>
                    <a href="{{ route('sudirmanpark.index') }}"
                        class="sidebar-item {{ request()->routeIs('sudirmanpark.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-building"></i>
                        <span>Sudirman Park</span>
                    </a>
                </li>
            @endif

            {{-- Product --}}
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'sales')
                <li>
                    <a href="{{ route('product.index') }}"
                        class="sidebar-item {{ request()->routeIs('product.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-box-open"></i>
                        <span>Product</span>
                    </a>
                </li>
            @endif

            {{-- Banner --}}
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'marketing')
                <li>
                    <a href="{{ route('banner.index') }}"
                        class="sidebar-item {{ request()->routeIs('banner.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-image"></i>
                        <span>Banner</span>
                    </a>
                </li>
            @endif

            {{-- Division --}}
            @if (auth()->user()->role == 'admin')
                <li>
                    <a href="{{ route('division.index') }}"
                        class="sidebar-item {{ request()->routeIs('division.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>Division</span>
                    </a>
                </li>
            @endif

            {{-- Career --}}
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'hr')
                <li>
                    <a href="{{ route('career.index') }}"
                        class="sidebar-item {{ request()->routeIs('career.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-briefcase"></i>
                        <span>Career</span>
                    </a>
                </li>
            @endif

            {{-- News --}}
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'marketing')
                <li>
                    <a href="{{ route('news.index') }}"
                        class="sidebar-item {{ request()->routeIs('news.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>News</span>
                    </a>
                </li>
            @endif

            {{-- Settings Content --}}
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'marketing')
                <li>
                    <a href="{{ route('settings-content.index') }}"
                        class="sidebar-item {{ request()->routeIs('settings-content.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Settings Content</span>
                    </a>
                </li>
            @endif

            {{-- User Management (Dropdown) --}}
            @if (auth()->user()->role == 'admin')
                <li class="sidebar-dropdown">

                    {{-- Trigger --}}
                    <a class="sidebar-item d-flex justify-content-between align-items-center
            {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#userMenu" role="button"
                        aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'true' : 'false' }}"
                        aria-controls="userMenu">

                        <span><i class="fa-solid fa-user-gear me-2"></i> User Management</span>
                        <i class="fa-solid fa-chevron-down small transition rotate-icon"></i>
                    </a>

                    {{-- Submenu --}}
                    <ul id="userMenu"
                        class="collapse submenu ps-4 ms-2
            {{ request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'show' : '' }}">

                        <li>
                            <a href="{{ route('users.index') }}"
                                class="submenu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-user me-2"></i> User
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('roles.index') }}"
                                class="submenu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-key me-2"></i> Role
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('permissions.index') }}"
                                class="submenu-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-lock me-2"></i> Permission
                            </a>
                        </li>

                    </ul>
                </li>
            @endif
        </ul>
    </nav>


    <main class="main-content">
        @if (session('error') && !session('success'))
            <div class="access-denied">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    {{-- Tempat script tambahan dari setiap halaman --}}
    @stack('scripts')

    <!-- ✅ Tambahkan Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ SweetAlert2 & Script Efek fade-out -->
    <script>
        // SweetAlert2 Notifications
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 5000,
                showConfirmButton: true
            });
        @endif

        // Efek fade-out halus sebelum pindah halaman
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', e => {
                // Hanya fade out main content, jangan sentuh sidebar
                const main = document.querySelector('.main-content');
                main.style.transition = 'opacity 0.15s ease-in-out';
                main.style.opacity = 0;

                // Biarkan link normal tetap jalan setelah delay
                setTimeout(() => {
                    window.location.href = link.href;
                }, 150);

                e.preventDefault();
            });
        });
    </script>
    <script src="{{ asset('js/admin-ui.js') }}"></script>
</body>

</html>
