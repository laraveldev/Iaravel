<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'To\'y Zallari Boshqaruvi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .btn-outline-primary:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background-color: #d1edff;
            color: #084298;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-completed {
            background-color: #d1e7dd;
            color: #0a3622;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-ring me-2"></i>To'y Zallari
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-home me-1"></i>Bosh sahifa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/venues">
                            <i class="fas fa-building me-1"></i>To'y Zallari
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/services">
                            <i class="fas fa-concierge-bell me-1"></i>Xizmatlar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/books">
                            <i class="fas fa-calendar-check me-1"></i>Bronlashlar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js"></script>
    
    <script>
        // Global API configuration
        axios.defaults.baseURL = 'http://localhost:8000/api/v1';
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.headers.common['Accept'] = 'application/json';

        // Format price function
        function formatPrice(price) {
            return new Intl.NumberFormat('uz-UZ').format(price) + ' so\'m';
        }

        // Format date function
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('uz-UZ');
        }

        // Show loading state
        function showLoading(element) {
            element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Yuklanmoqda...';
            element.disabled = true;
        }

        // Hide loading state
        function hideLoading(element, originalText) {
            element.innerHTML = originalText;
            element.disabled = false;
        }
    </script>

    @stack('scripts')
</body>
</html>

