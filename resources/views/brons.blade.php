@extends('layouts.app')

@section('title', 'Bronlashlar')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-calendar-check me-2"></i>Bronlashlar</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bronModal" onclick="openBronModal()">
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
                        <button class="btn btn-outline-primary btn-sm" onclick="loadBrons()">
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
                <tbody id="brons-container">
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

<!-- Bron Modal -->
<div class="modal fade" id="bronModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bronModalTitle">Yangi Bronlash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bronForm">
                    <input type="hidden" id="bronId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bronVenue" class="form-label">To'y Zali *</label>
                            <select class="form-control" id="bronVenue" required>
                                <option value="">Yuklanmoqda...</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bronService" class="form-label">Xizmat *</label>
                            <select class="form-control" id="bronService" required>
                                <option value="">Yuklanmoqda...</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bronDate" class="form-label">Tadbir sanasi *</label>
                            <input type="date" class="form-control" id="bronDate" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bronTime" class="form-label">Tadbir vaqti *</label>
                            <input type="time" class="form-control" id="bronTime" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bronGuests" class="form-label">Mehmonlar soni *</label>
                            <input type="number" class="form-control" id="bronGuests" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bronPrice" class="form-label">Umumiy narx (so'm) *</label>
                            <input type="number" class="form-control" id="bronPrice" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="bronNotes" class="form-label">Qo'shimcha ma'lumot</label>
                        <textarea class="form-control" id="bronNotes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                <button type="button" class="btn btn-primary" onclick="saveBron()" id="saveBronBtn">
                    <i class="fas fa-save me-1"></i>Saqlash
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentBronId = null;
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
            const venueSelect = document.getElementById('bronVenue');
            venueSelect.innerHTML = '<option value="">Tanlang...</option>' +
                venues.map(venue => `<option value="${venue.id}">${venue.name} - ${venue.location}</option>`).join('');

            // Populate service select
            const serviceSelect = document.getElementById('bronService');
            serviceSelect.innerHTML = '<option value="">Tanlang...</option>' +
                services.map(service => `<option value="${service.id}">${service.name} (${formatPrice(service.price)})</option>`).join('');

        } catch (error) {
            console.error('Error loading initial data:', error);
        }
    }

    // Load brons
    async function loadBrons() {
        try {
            let url = '/brons?';
            
            const statusFilter = document.getElementById('statusFilter').value;
            const upcomingFilter = document.getElementById('upcomingFilter').checked;

            if (statusFilter) url += `status=${statusFilter}&`;
            if (upcomingFilter) url += `upcoming=1&`;

            const response = await axios.get(url);
            const data = response.data.data;
            
            const container = document.getElementById('brons-container');
            
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

            container.innerHTML = data.data.map(bron => {
                const statusClass = {
                    'pending': 'warning',
                    'confirmed': 'info',
                    'cancelled': 'danger',
                    'completed': 'success'
                }[bron.status] || 'secondary';

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
                        <td>${new Date(bron.event_time).toLocaleTimeString('uz-UZ', {hour: '2-digit', minute: '2-digit'})}</td>
                        <td>${bron.guests_count}</td>
                        <td>${formatPrice(bron.total_price)}</td>
                        <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editBron(${bron.id})" title="Tahrirlash">
                                    <i class="fas fa-edit"></i>
                                </button>
                                ${bron.status === 'pending' ? `
                                    <button class="btn btn-outline-success" onclick="confirmBron(${bron.id})" title="Tasdiqlash">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" onclick="cancelBron(${bron.id})" title="Bekor qilish">
                                        <i class="fas fa-times"></i>
                                    </button>
                                ` : ''}
                                <button class="btn btn-outline-danger" onclick="deleteBron(${bron.id})" title="O'chirish">
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
            console.error('Error loading brons:', error);
            document.getElementById('brons-container').innerHTML = `
                <tr>
                    <td colspan="9" class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Bronlashlarni yuklab bo'lmadi
                    </td>
                </tr>
            `;
        }
    }

    // Open bron modal
    function openBronModal(bronId = null) {
        currentBronId = bronId;
        const modal = document.getElementById('bronModal');
        const title = document.getElementById('bronModalTitle');
        const form = document.getElementById('bronForm');
        
        form.reset();
        document.getElementById('bronId').value = bronId || '';
        
        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('bronDate').min = tomorrow.toISOString().split('T')[0];
        
        // Set default user to first user (for demo)
        
        if (bronId) {
            title.textContent = 'Bronlashni Tahrirlash';
            loadBronData(bronId);
        } else {
            title.textContent = 'Yangi Bronlash';
        }
    }

    // Load bron data for editing
    async function loadBronData(bronId) {
        try {
            const response = await axios.get(`/brons/${bronId}`);
            const bron = response.data.data;
            
            document.getElementById('bronVenue').value = bron.venue_id;
            document.getElementById('bronService').value = bron.service_id;
            document.getElementById('bronDate').value = bron.event_date;
            document.getElementById('bronTime').value = bron.event_time.split(' ')[1].slice(0, 5);
            document.getElementById('bronGuests').value = bron.guests_count;
            document.getElementById('bronPrice').value = bron.total_price;
            document.getElementById('bronNotes').value = bron.notes || '';
        } catch (error) {
            console.error('Error loading bron data:', error);
            alert('Bronlash ma\'lumotlarini yuklab bo\'lmadi');
        }
    }

    // Save bron
    async function saveBron() {
        const form = document.getElementById('bronForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const bronId = document.getElementById('bronId').value;
        const saveBtn = document.getElementById('saveBronBtn');
        const originalText = saveBtn.innerHTML;
        
        showLoading(saveBtn);

        const bronData = {
            user_id: 1, // Default to first user for demo
            venue_id: parseInt(document.getElementById('bronVenue').value),
            service_id: parseInt(document.getElementById('bronService').value),
            event_date: document.getElementById('bronDate').value,
            event_time: document.getElementById('bronTime').value,
            guests_count: parseInt(document.getElementById('bronGuests').value),
            total_price: parseFloat(document.getElementById('bronPrice').value),
            notes: document.getElementById('bronNotes').value
        };

        try {
            if (bronId) {
                await axios.put(`/brons/${bronId}`, bronData);
            } else {
                await axios.post('/brons', bronData);
            }

            hideLoading(saveBtn, originalText);
            bootstrap.Modal.getInstance(document.getElementById('bronModal')).hide();
            loadBrons();
            
            alert(bronId ? 'Bronlash muvaffaqiyatli yangilandi!' : 'Bronlash muvaffaqiyatli qo\'shildi!');
            
        } catch (error) {
            hideLoading(saveBtn, originalText);
            console.error('Error saving bron:', error);
            alert('Xatolik yuz berdi: ' + (error.response?.data?.message || 'Noma\'lum xatolik'));
        }
    }

    // Edit bron
    function editBron(bronId) {
        openBronModal(bronId);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('bronModal')).show();
    }

    // Confirm bron
    async function confirmBron(bronId) {
        try {
            await axios.patch(`/brons/${bronId}/confirm`);
            loadBrons();
            alert('Bronlash tasdiqlandi!');
        } catch (error) {
            console.error('Error confirming bron:', error);
            alert('Xatolik yuz berdi');
        }
    }

    // Cancel bron
    async function cancelBron(bronId) {
        if (!confirm('Bu bronlashni bekor qilishni xohlaysizmi?')) {
            return;
        }

        try {
            await axios.patch(`/brons/${bronId}/cancel`);
            loadBrons();
            alert('Bronlash bekor qilindi!');
        } catch (error) {
            console.error('Error cancelling bron:', error);
            alert('Xatolik yuz berdi');
        }
    }

    // Delete bron
    async function deleteBron(bronId) {
        if (!confirm('Bu bronlashni o\'chirishni xohlaysizmi?')) {
            return;
        }

        try {
            await axios.delete(`/brons/${bronId}`);
            loadBrons();
            alert('Bronlash o\'chirildi!');
        } catch (error) {
            console.error('Error deleting bron:', error);
            alert('Xatolik yuz berdi');
        }
    }

    // Load data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadInitialData();
        loadBrons();
    });
</script>
@endpush

