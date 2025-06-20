@extends('layouts.app')

@section('title', 'To\'y Zallari')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-building me-2"></i>To'y Zallari</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#venueModal" onclick="openVenueModal()">
            <i class="fas fa-plus me-1"></i>Yangi Zal Qo'shish
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row" id="venues-container">
            <div class="col-12 text-center">
                <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                <p class="text-muted mt-2">Yuklanmoqda...</p>
            </div>
        </div>
    </div>
</div>

<!-- Venue Modal -->
<div class="modal fade" id="venueModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="venueModalTitle">Yangi To'y Zali</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="venueForm">
                    <input type="hidden" id="venueId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="venueName" class="form-label">Zal nomi *</label>
                            <input type="text" class="form-control" id="venueName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="venueLocation" class="form-label">Manzil *</label>
                            <input type="text" class="form-control" id="venueLocation" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="venueCapacity" class="form-label">Sig'im (kishi) *</label>
                            <input type="number" class="form-control" id="venueCapacity" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="venuePrice" class="form-label">Narx (so'm) *</label>
                            <input type="number" class="form-control" id="venuePrice" min="0" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="venuePhone" class="form-label">Telefon</label>
                            <input type="tel" class="form-control" id="venuePhone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="venueEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="venueEmail">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="venueDescription" class="form-label">Tavsif</label>
                        <textarea class="form-control" id="venueDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                <button type="button" class="btn btn-primary" onclick="saveVenue()" id="saveVenueBtn">
                    <i class="fas fa-save me-1"></i>Saqlash
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentVenueId = null;

    // Load venues
    async function loadVenues() {
        try {
            const response = await axios.get('/venues');
            const venues = response.data.data;
            
            const container = document.getElementById('venues-container');
            
            if (venues.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">To'y zallari topilmadi</h5>
                        <p class="text-muted">Birinchi to'y zalini qo'shish uchun yuqoridagi tugmani bosing</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = venues.map(venue => `
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title">${venue.name}</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="editVenue(${venue.id})">
                                            <i class="fas fa-edit me-1"></i>Tahrirlash
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteVenue(${venue.id})">
                                            <i class="fas fa-trash me-1"></i>O'chirish
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>${venue.location}
                            </p>
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <i class="fas fa-users text-primary"></i>
                                    <div class="small text-muted">Sig'im</div>
                                    <strong>${venue.capacity}</strong>
                                </div>
                                <div class="col-6">
                                    <i class="fas fa-money-bill text-success"></i>
                                    <div class="small text-muted">Narx</div>
                                    <strong>${formatPrice(venue.price)}</strong>
                                </div>
                            </div>
                            ${venue.description ? `<p class="card-text small">${venue.description}</p>` : ''}
                            ${venue.phone ? `<p class="card-text small"><i class="fas fa-phone me-1"></i>${venue.phone}</p>` : ''}
                            ${venue.email ? `<p class="card-text small"><i class="fas fa-envelope me-1"></i>${venue.email}</p>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');

        } catch (error) {
            console.error('Error loading venues:', error);
            document.getElementById('venues-container').innerHTML = `
                <div class="col-12 text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger">Xatolik yuz berdi</h5>
                    <p class="text-muted">To'y zallarini yuklab bo'lmadi</p>
                </div>
            `;
        }
    }

    // Open venue modal
    function openVenueModal(venueId = null) {
        currentVenueId = venueId;
        const modal = document.getElementById('venueModal');
        const title = document.getElementById('venueModalTitle');
        const form = document.getElementById('venueForm');
        
        form.reset();
        document.getElementById('venueId').value = venueId || '';
        
        if (venueId) {
            title.textContent = 'To\'y Zalini Tahrirlash';
            loadVenueData(venueId);
        } else {
            title.textContent = 'Yangi To\'y Zali';
        }
    }

    // Load venue data for editing
    async function loadVenueData(venueId) {
        try {
            const response = await axios.get(`/venues/${venueId}`);
            const venue = response.data.data;
            
            document.getElementById('venueName').value = venue.name;
            document.getElementById('venueLocation').value = venue.location;
            document.getElementById('venueCapacity').value = venue.capacity;
            document.getElementById('venuePrice').value = venue.price;
            document.getElementById('venuePhone').value = venue.phone || '';
            document.getElementById('venueEmail').value = venue.email || '';
            document.getElementById('venueDescription').value = venue.description || '';
        } catch (error) {
            console.error('Error loading venue data:', error);
            alert('To\'y zali ma\'lumotlarini yuklab bo\'lmadi');
        }
    }

    // Save venue
    async function saveVenue() {
        const form = document.getElementById('venueForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const venueId = document.getElementById('venueId').value;
        const saveBtn = document.getElementById('saveVenueBtn');
        const originalText = saveBtn.innerHTML;
        
        showLoading(saveBtn);

        const venueData = {
            name: document.getElementById('venueName').value,
            location: document.getElementById('venueLocation').value,
            capacity: parseInt(document.getElementById('venueCapacity').value),
            price: parseFloat(document.getElementById('venuePrice').value),
            phone: document.getElementById('venuePhone').value,
            email: document.getElementById('venueEmail').value,
            description: document.getElementById('venueDescription').value
        };

        try {
            if (venueId) {
                await axios.put(`/venues/${venueId}`, venueData);
            } else {
                await axios.post('/venues', venueData);
            }

            hideLoading(saveBtn, originalText);
            bootstrap.Modal.getInstance(document.getElementById('venueModal')).hide();
            loadVenues();
            
            // Show success message (you can implement a toast notification here)
            alert(venueId ? 'To\'y zali muvaffaqiyatli yangilandi!' : 'To\'y zali muvaffaqiyatli qo\'shildi!');
            
        } catch (error) {
            hideLoading(saveBtn, originalText);
            console.error('Error saving venue:', error);
            alert('Xatolik yuz berdi: ' + (error.response?.data?.message || 'Noma\'lum xatolik'));
        }
    }

    // Edit venue
    function editVenue(venueId) {
        openVenueModal(venueId);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('venueModal')).show();
    }

    // Delete venue
    async function deleteVenue(venueId) {
        if (!confirm('Bu to\'y zalini o\'chirishni xohlaysizmi?')) {
            return;
        }

        try {
            await axios.delete(`/venues/${venueId}`);
            loadVenues();
            alert('To\'y zali muvaffaqiyatli o\'chirildi!');
        } catch (error) {
            console.error('Error deleting venue:', error);
            alert('Xatolik yuz berdi: ' + (error.response?.data?.message || 'Noma\'lum xatolik'));
        }
    }

    // Load venues when page loads
    document.addEventListener('DOMContentLoaded', loadVenues);
</script>
@endpush

