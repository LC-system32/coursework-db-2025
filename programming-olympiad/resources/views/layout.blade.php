<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Олімпіада з програмування')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    @stack('styles')

</head>

<body class="d-flex flex-column min-vh-100 bg-light text-dark">

    <!-- Header / Navbar -->
    <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm p-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="/">
                <i class="bi bi-code-slash me-2"></i> Олімпіада з програмування
            </a>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">

                    @guest
                        <li class="nav-item">
                            <a class="nav-link fs-5 me-2 text-light" href="/login">
                                Вхід <i class="bi bi-box-arrow-in-right"></i>
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link fs-5 text-light">
                                    Вихід <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </li>
                    @endauth

                </ul>
            </div>
        </div>
    </nav>
</header>


    <!-- Main Content -->
    <main class="container py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-center text-white py-4 mt-auto">
        <div class="container">
            <p class="mb-1">&copy; {{ now()->year }} Система обліку результатів олімпіади</p>
        </div>
    </footer>
    @stack('scripts')
</body>


</html>