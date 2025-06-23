@extends('layouts.app')

@section('title', 'To\'y Zallari Boshqaruvi - Bosh Sahifa')

@section('content')
<!-- Hero Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-5">
                <h1 class="display-4 mb-3">
                    <i class="fas fa-ring me-3"></i>To'y Zallari Boshqaruvi
                </h1>
                <p class="lead mb-4">To'y zallari, xizmatlar va bronlashlarni boshqaring</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-5">
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-building fa-3x text-primary mb-3"></i>
                <h5 class="card-title">To'y Zallari</h5>
                <h2 class="text-primary mb-0" id="total-venues">-</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-concierge-bell fa-3x text-success mb-3"></i>
                <h5 class="card-title">Xizmatlar</h5>
                <h2 class="text-success mb-0" id="total-services">-</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-calendar-check fa-3x text-warning mb-3"></i>
                <h5 class="card-title">Bronlashlar</h5>
                <h2 class="text-warning mb-0" id="total-brons">-</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-check-circle fa-3x text-info mb-3"></i>
                <h5 class="card-title">Tasdiqlangan</h5>
                <h2 class="text-info mb-0" id="confirmed-brons">-</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-book fa-3x text-secondary mb-3"></i>
                <h5 class="card-title">Kitoblar</h5>
                <h2 class="text-secondary mb-0" id="total-books">-</h2>
            </div>
        </div>
    </div>
</div>

<!-- Popular Books -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2"></i>Mashhur Kitoblar
                </h5>
                <a href="/books" class="btn btn-outline-primary btn-sm">
                    Barchasini ko'rish <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row" id="popular-books">
                    <div class="col-12 text-center">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Yuklanmoqda...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Venues -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i>Mashhur To'y Zallari
                </h5>
                <a href="/venues" class="btn btn-outline-primary btn-sm">
                    Barchasini ko'rish <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row" id="popular-venues">
                    <div class="col-12 text-center">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Yuklanmoqda...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Services -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-concierge-bell me-2"></i>Mashhur Xizmatlar
                </h5>
                <a href="/services" class="btn btn-outline-primary btn-sm">
                    Barchasini ko'rish <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row" id="popular-services">
                    <div class="col-12 text-center">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Yuklanmoqda...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>So'nggi Bronlashlar
                </h5>
                <a href="/api/brons" class="btn btn-outline-primary btn-sm">
                    Barchasini ko'rish <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Bronlash ID</th>
                                <th>To'y Zali</th>
                                <th>Xizmat</th>
                                <th>Sana</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="recent-brons">
                            <tr>
                                <td colspan="5" class="text-center">
                                    <i class="fas fa-spinner fa-spin"></i> Yuklanmoqda...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Load dashboard data
    async function loadDashboardData() {
        try {
            // Load statistics
            const [venuesResponse, servicesResponse, bronsResponse, booksResponse] = await Promise.all([
                axios.get('/venues'),
                axios.get('/services'),
                axios.get('/brons'),
                axios.get('/books')
            ]);

            // Update statistics
            document.getElementById('total-venues').textContent = venuesResponse.data.data.length;
            document.getElementById('total-services').textContent = servicesResponse.data.data.length;
            document.getElementById('total-brons').textContent = bronsResponse.data.data.total;
            document.getElementById('total-books').textContent = booksResponse.data.data.total;
            
            // Count confirmed bookings
            const confirmedCount = bronsResponse.data.data.data.filter(bron => bron.status === 'confirmed').length;
            document.getElementById('confirmed-brons').textContent = confirmedCount;

            // Load popular books (first 3)
            loadPopularBooks(booksResponse.data.data.data.slice(0, 3));

            // Load popular venues
            loadPopularVenues(venuesResponse.data.data.slice(0, 6));
            
            // Load popular services
            loadPopularServices(servicesResponse.data.data.slice(0, 8));
            
            // Load recent bookings
            loadRecentBrons(bronsResponse.data.data.data.slice(0, 5));

        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    function loadPopularBooks(books) {
        const container = document.getElementById('popular-books');
        if (!books || books.length === 0) {
            container.innerHTML = '<div class="col-12 text-center"><p class="text-muted">Kitoblar topilmadi</p></div>';
            return;
        }
        container.innerHTML = books.map(book => `
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">${book.title}</h6>
                        <p class="card-text text-muted small">
                            <i class="fas fa-user me-1"></i>${book.author}
                        </p>
                        <span class="badge bg-primary mb-2">${book.genre}</span>
                        <span class="badge bg-secondary mb-2">${book.published_year}</span>
                        <p class="card-text small">${book.description}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function loadPopularVenues(venues) {
        const container = document.getElementById('popular-venues');
        if (venues.length === 0) {
            container.innerHTML = '<div class="col-12 text-center"><p class="text-muted">To\'y zallari topilmadi</p></div>';
            return;
        }

        container.innerHTML = venues.map(venue => `
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">${venue.name}</h6>
                        <p class="card-text text-muted small">
                            <i class="fas fa-map-marker-alt me-1"></i>${venue.location}
                        </p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i>${venue.capacity} kishi
                            </small>
                        </p>
                        <p class="card-text">
                            <strong class="text-primary">${formatPrice(venue.price)}</strong>
                        </p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function loadPopularServices(services) {
        const container = document.getElementById('popular-services');
        if (services.length === 0) {
            container.innerHTML = '<div class="col-12 text-center"><p class="text-muted">Xizmatlar topilmadi</p></div>';
            return;
        }

        container.innerHTML = services.map(service => `
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-concierge-bell fa-2x text-primary mb-2"></i>
                        <h6 class="card-title">${service.name}</h6>
                        <p class="card-text small text-muted">${service.description}</p>
                        <p class="card-text">
                            <strong class="text-success">${formatPrice(service.price)}</strong>
                        </p>
                        <span class="badge bg-secondary">${service.type}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function loadRecentBrons(brons) {
        const container = document.getElementById('recent-brons');
        if (brons.length === 0) {
            container.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Bronlashlar topilmadi</td></tr>';
            return;
        }

        container.innerHTML = brons.map(bron => {
            const statusClass = `status-${bron.status}`;
            const statusText = {
                'pending': 'Kutilmoqda',
                'confirmed': 'Tasdiqlangan',
                'cancelled': 'Bekor qilingan',
                'completed': 'Tugallangan'
            }[bron.status] || bron.status;

            return `
                <tr>
                    <td>#${bron.id}</td>
                    <td>${bron.venue.name}</td>
                    <td>${bron.service.name}</td>
                    <td>${formatDate(bron.event_date)}</td>
                    <td><span class="badge ${statusClass}">${statusText}</span></td>
                </tr>
            `;
        }).join('');
    }

    // Load data when page loads
    document.addEventListener('DOMContentLoaded', loadDashboardData);
</script>
@endpush

