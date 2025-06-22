@extends('layouts.app')

@section('title', 'Xizmatlar')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-concierge-bell me-2"></i>Xizmatlar</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal" onclick="openServiceModal()">
            <i class="fas fa-plus me-1"></i>Yangi Xizmat Qo'shish
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row" id="services-container">
            <div class="col-12 text-center">
                <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                <p class="text-muted mt-2">Yuklanmoqda...</p>
            </div>
        </div>
    </div>
</div>

<!-- Service Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalTitle">Yangi Xizmat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="serviceForm">
                    <input type="hidden" id="serviceId">
                    <div class="mb-3">
                        <label for="serviceName" class="form-label">Xizmat nomi *</label>
                        <input type="text" class="form-control" id="serviceName" required>
                    </div>
                    <div class="mb-3">
                        <label for="serviceType" class="form-label">Turi *</label>
                        <select class="form-control" id="serviceType" required>
                            <option value="">Tanlang...</option>
                            <option value="photography">Fotografiya</option>
                            <option value="videography">Videosurat</option>
                            <option value="music">Musiqa</option>
                            <option value="decoration">Bezak</option>
                            <option value="transport">Transport</option>
                            <option value="catering">Oshpazlik</option>
                            <option value="other">Boshqa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="servicePrice" class="form-label">Narx (so'm) *</label>
                        <input type="number" class="form-control" id="servicePrice" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="serviceDescription" class="form-label">Tavsif *</label>
                        <textarea class="form-control" id="serviceDescription" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                <button type="button" class="btn btn-primary" onclick="saveService()" id="saveServiceBtn">
                    <i class="fas fa-save me-1"></i>Saqlash
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentServiceId = null;

    // Load services
    async function loadServices() {
        try {
            const response = await axios.get('/services');
            const services = response.data.data;
            
            const container = document.getElementById('services-container');
            
            if (services.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center">
                        <i class="fas fa-concierge-bell fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Xizmatlar topilmadi</h5>
                        <p class="text-muted">Birinchi xizmatni qo'shish uchun yuqoridagi tugmani bosing</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = services.map(service => `
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title">${service.name}</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/services/${service.id}"><i class="fas fa-eye me-1"></i>Ko'rish</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="editService(${service.id})">
                                            <i class="fas fa-edit me-1"></i>Tahrirlash
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteService(${service.id})">
                                            <i class="fas fa-trash me-1"></i>O'chirish
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                            <span class="badge bg-secondary mb-2">${service.type}</span>
                            <p class="card-text">${service.description}</p>
                            <div class="text-center mt-auto">
                                <h4 class="text-success">${formatPrice(service.price)}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

        } catch (error) {
            console.error('Error loading services:', error);
            document.getElementById('services-container').innerHTML = `
                <div class="col-12 text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger">Xatolik yuz berdi</h5>
                    <p class="text-muted">Xizmatlarni yuklab bo'lmadi</p>
                </div>
            `;
        }
    }

    // Open service modal
    function openServiceModal(serviceId = null) {
        currentServiceId = serviceId;
        const modal = document.getElementById('serviceModal');
        const title = document.getElementById('serviceModalTitle');
        const form = document.getElementById('serviceForm');
        
        form.reset();
        document.getElementById('serviceId').value = serviceId || '';
        
        if (serviceId) {
            title.textContent = 'Xizmatni Tahrirlash';
            loadServiceData(serviceId);
        } else {
            title.textContent = 'Yangi Xizmat';
        }
    }

    // Load service data for editing
    async function loadServiceData(serviceId) {
        try {
            const response = await axios.get(`/services/${serviceId}`);
            const service = response.data.data;
            
            document.getElementById('serviceName').value = service.name;
            document.getElementById('serviceType').value = service.type;
            document.getElementById('servicePrice').value = service.price;
            document.getElementById('serviceDescription').value = service.description;
        } catch (error) {
            console.error('Error loading service data:', error);
            alert('Xizmat ma\'lumotlarini yuklab bo\'lmadi');
        }
    }

    // Save service
    async function saveService() {
        const form = document.getElementById('serviceForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const serviceId = document.getElementById('serviceId').value;
        const saveBtn = document.getElementById('saveServiceBtn');
        const originalText = saveBtn.innerHTML;
        
        showLoading(saveBtn);

        const serviceData = {
            name: document.getElementById('serviceName').value,
            type: document.getElementById('serviceType').value,
            price: parseFloat(document.getElementById('servicePrice').value),
            description: document.getElementById('serviceDescription').value
        };

        try {
            if (serviceId) {
                await axios.put(`/services/${serviceId}`, serviceData);
            } else {
                await axios.post('/services', serviceData);
            }

            hideLoading(saveBtn, originalText);
            bootstrap.Modal.getInstance(document.getElementById('serviceModal')).hide();
            loadServices();
            
            alert(serviceId ? 'Xizmat muvaffaqiyatli yangilandi!' : 'Xizmat muvaffaqiyatli qo\'shildi!');
            
        } catch (error) {
            hideLoading(saveBtn, originalText);
            console.error('Error saving service:', error);
            alert('Xatolik yuz berdi: ' + (error.response?.data?.message || 'Noma\'lum xatolik'));
        }
    }

    // Edit service
    function editService(serviceId) {
        openServiceModal(serviceId);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('serviceModal')).show();
    }

    // Delete service
    async function deleteService(serviceId) {
        if (!confirm('Bu xizmatni o\'chirishni xohlaysizmi?')) {
            return;
        }

        try {
            await axios.delete(`/services/${serviceId}`);
            loadServices();
            alert('Xizmat muvaffaqiyatli o\'chirildi!');
        } catch (error) {
            console.error('Error deleting service:', error);
            alert('Xatolik yuz berdi: ' + (error.response?.data?.message || 'Noma\'lum xatolik'));
        }
    }

    // Load services when page loads
    document.addEventListener('DOMContentLoaded', loadServices);
</script>
@endpush

