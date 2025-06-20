@extends('layouts.app')

@section('title', 'Bronlashlar')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-calendar-check me-2"></i>Bronlashlar</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookModal" onclick="openBookModal()">
            <i class="fas fa-plus me-1"></i>Yangi Bronlash
        </button>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">Barcha statuslar</option>
                            <option value="pending">Kutilmoqda</option>
                            <option value="confirmed">Tasdiqlangan</option>
                            <option value="cancelled">Bekor qilingan</option>
                            <option value="completed">Tugallangan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="upcomingFilter">
                            <label class="form-check-label" for="upcomingFilter">
                                Faqat kelgusi tadbirlar
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-outline-primary btn-sm" onclick="loadBooks()">
                            <i class="fas fa-search me-1"></i>Qidirish
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>To'y Zali</th>
                        <th>Xizmat</th>
                        <th>Sana</th>
                        <th>Vaqt</th>
                        <th>Mehmonlar</th>
                        <th>Narx</th>
                        <th>Status</th>
                        <th>Amallar</th>
                    </tr>
                </thead>
                <tbody id="books-container">
                    <tr>
                        <td colspan="9" class="text-center">
                            <i class="fas fa-spinner fa-spin"></i> Yuklanmoqda...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="pagination-container" class="mt-3"></div>
    </div>
</div>

<!-- Book Modal -->
<div class="modal fade" id="bookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalTitle">Yangi Bronlash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bookForm">
                    <input type="hidden" id="bookId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bookVenue" class="form-label">To'y Zali *</label>
                            <select class="form-control" id="bookVenue" required>
                                <option value="">Yuklanmoqda...</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bookService" class="form-label">Xizmat *</label>
                            <select class="form-control" id="bookService" required>
                                <option value="">Yuklanmoqda...</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bookDate" class="form-label">Tadbir sanasi *</label>
                            <input type="date" class="form-control" id="bookDate" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bookTime" class="form-label">Tadbir vaqti *</label>
                            <input type="time" class="form-control" id="bookTime" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bookGuests" class="form-label">Mehmonlar soni *</label>
                            <input type="number" class="form-control" id="bookGuests" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bookPrice" class="form-label">Umumiy narx (so'm) *</label>
                            <input type="number" class="form-control" id="bookPrice" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="bookNotes" class="form-label">Qo'shimcha ma'lumot</label>
                        <textarea class="form-control" id="bookNotes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                <button type="button" class="btn btn-primary" onclick="saveBook()" id="saveBookBtn">
                    <i class="fas fa-save me-1"></i>Saqlash
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentBookId = null;
    let venues = [];
    let services = [];

    // Load initial data
    async function loadInitialData() {
        try {
            const [venuesResponse, servicesResponse] = await Promise.all([
                axios.get('/venues'),
                axios.get('/services')
            ]);

            venues = venuesResponse.data.data;
            services = servicesResponse.data.data;

            // Populate venue select
            const venueSelect = document.getElementById('bookVenue');
            venueSelect.innerHTML = '<option value="">Tanlang...</option>' +
                venues.map(venue => `<option value="${venue.id}">${venue.name} - ${venue.location}</option>`).join('');

            // Populate service select
            const serviceSelect = document.getElementById('bookService');
            serviceSelect.innerHTML = '<option value="">Tanlang...</option>' +
                services.map(service => `<option value="${service.id}">${service.name} (${formatPrice(service.price)})</option>`).join('');

        } catch (error) {
            console.error('Error loading initial data:', error);
        }
    }

    // Load books
    async function loadBooks() {
        try {
            let url = '/books?';
            
            const statusFilter = document.getElementById('statusFilter').value;
            const upcomingFilter = document.getElementById('upcomingFilter').checked;

            if (statusFilter) url += `status=${statusFilter}&`;
            if (upcomingFilter) url += `upcoming=1&`;

            const response = await axios.get(url);
            const data = response.data.data;
            
            const container = document.getElementById('books-container');
            
            if (data.data.length === 0) {
                container.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Bronlashlar topilmadi</p>
                        </td>
                    </tr>
                `;
                return;
            }

            container.innerHTML = data.data.map(book => {
                const statusClass = {
                    'pending': 'warning',
                    'confirmed': 'info',
                    'cancelled': 'danger',
                    'completed': 'success'
                }[book.status] || 'secondary';

                const statusText = {
                    'pending': 'Kutilmoqda',
                    'confirmed': 'Tasdiqlangan',
                    'cancelled': 'Bekor qilingan',
                    'completed': 'Tugallangan'
                }[book.status] || book.status;

                return `
                    <tr>
                        <td>#${book.id}</td>
                        <td>${book.venue.name}</td>
                        <td>${book.service.name}</td>
                        <td>${formatDate(book.event_date)}</td>
                        <td>${new Date(book.event_time).toLocaleTimeString('uz-UZ', {hour: '2-digit', minute: '2-digit'})}</td>
                        <td>${book.guests_count}</td>
                        <td>${formatPrice(book.total_price)}</td>
                        <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editBook(${book.id})" title="Tahrirlash">
                                    <i class="fas fa-edit"></i>
                                </button>
                                ${book.status === 'pending' ? `
                                    <button class="btn btn-outline-success" onclick="confirmBook(${book.id})" title="Tasdiqlash">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" onclick="cancelBook(${book.id})" title="Bekor qilish">
                                        <i class="fas fa-times"></i>
                                    </button>
                                ` : ''}
                                <button class="btn btn-outline-danger" onclick="deleteBook(${book.id})" title="O'chirish">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            // Update pagination (simplified)
            const paginationContainer = document.getElementById('pagination-container');
            if (data.last_page > 1) {
                paginationContainer.innerHTML = `
                    <div class="text-center">
                        <p class="text-muted">
                            ${data.from}-${data.to} / ${data.total} ta natija ko'rsatilmoqda
                        </p>
                    </div>
                `;
            } else {
                paginationContainer.innerHTML = '';
            }

        } catch (error) {
            console.error('Error loading books:', error);
            document.getElementById('books-container').innerHTML = `
                <tr>
                    <td colspan="9" class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Bronlashlarni yuklab bo'lmadi
                    </td>
                </tr>
            `;
        }
    }

    // Open book modal
    function openBookModal(bookId = null) {
        currentBookId = bookId;
        const modal = document.getElementById('bookModal');
        const title = document.getElementById('bookModalTitle');
        const form = document.getElementById('bookForm');
        
        form.reset();
        document.getElementById('bookId').value = bookId || '';
        
        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('bookDate').min = tomorrow.toISOString().split('T')[0];
        
        // Set default user to first user (for demo)
        
        if (bookId) {
            title.textContent = 'Bronlashni Tahrirlash';
            loadBookData(bookId);
        } else {
            title.textContent = 'Yangi Bronlash';
        }
    }

    // Load book data for editing
    async function loadBookData(bookId) {
        try {
            const response = await axios.get(`/books/${bookId}`);
            const book = response.data.data;
            
            document.getElementById('bookVenue').value = book.venue_id;
            document.getElementById('bookService').value = book.service_id;
            document.getElementById('bookDate').value = book.event_date;
            document.getElementById('bookTime').value = book.event_time.split(' ')[1].slice(0, 5);
            document.getElementById('bookGuests').value = book.guests_count;
            document.getElementById('bookPrice').value = book.total_price;
            document.getElementById('bookNotes').value = book.notes || '';
        } catch (error) {
            console.error('Error loading book data:', error);
            alert('Bronlash ma\'lumotlarini yuklab bo\'lmadi');
        }
    }

    // Save book
    async function saveBook() {
        const form = document.getElementById('bookForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const bookId = document.getElementById('bookId').value;
        const saveBtn = document.getElementById('saveBookBtn');
        const originalText = saveBtn.innerHTML;
        
        showLoading(saveBtn);

        const bookData = {
            user_id: 1, // Default to first user for demo
            venue_id: parseInt(document.getElementById('bookVenue').value),
            service_id: parseInt(document.getElementById('bookService').value),
            event_date: document.getElementById('bookDate').value,
            event_time: document.getElementById('bookTime').value,
            guests_count: parseInt(document.getElementById('bookGuests').value),
            total_price: parseFloat(document.getElementById('bookPrice').value),
            notes: document.getElementById('bookNotes').value
        };

        try {
            if (bookId) {
                await axios.put(`/books/${bookId}`, bookData);
            } else {
                await axios.post('/books', bookData);
            }

            hideLoading(saveBtn, originalText);
            bootstrap.Modal.getInstance(document.getElementById('bookModal')).hide();
            loadBooks();
            
            alert(bookId ? 'Bronlash muvaffaqiyatli yangilandi!' : 'Bronlash muvaffaqiyatli qo\'shildi!');
            
        } catch (error) {
            hideLoading(saveBtn, originalText);
            console.error('Error saving book:', error);
            alert('Xatolik yuz berdi: ' + (error.response?.data?.message || 'Noma\'lum xatolik'));
        }
    }

    // Edit book
    function editBook(bookId) {
        openBookModal(bookId);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('bookModal')).show();
    }

    // Confirm book
    async function confirmBook(bookId) {
        try {
            await axios.patch(`/books/${bookId}/confirm`);
            loadBooks();
            alert('Bronlash tasdiqlandi!');
        } catch (error) {
            console.error('Error confirming book:', error);
            alert('Xatolik yuz berdi');
        }
    }

    // Cancel book
    async function cancelBook(bookId) {
        if (!confirm('Bu bronlashni bekor qilishni xohlaysizmi?')) {
            return;
        }

        try {
            await axios.patch(`/books/${bookId}/cancel`);
            loadBooks();
            alert('Bronlash bekor qilindi!');
        } catch (error) {
            console.error('Error cancelling book:', error);
            alert('Xatolik yuz berdi');
        }
    }

    // Delete book
    async function deleteBook(bookId) {
        if (!confirm('Bu bronlashni o\'chirishni xohlaysizmi?')) {
            return;
        }

        try {
            await axios.delete(`/books/${bookId}`);
            loadBooks();
            alert('Bronlash o\'chirildi!');
        } catch (error) {
            console.error('Error deleting book:', error);
            alert('Xatolik yuz berdi');
        }
    }

    // Load data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadInitialData();
        loadBooks();
    });
</script>
@endpush

