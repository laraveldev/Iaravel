@extends('layouts.app')

@section('title', $service->name . ' - Xizmat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <i class="fas fa-concierge-bell fa-2x me-3"></i>
                <h4 class="mb-0">{{ $service->name }}</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>Turi:</strong> {{ e($service->type) }}</li>
                    <li class="list-group-item"><strong>Narx:</strong> <span class="text-success">{{ number_format($service->price, 0, '.', ' ') }} so'm</span></li>
                </ul>
                @if($service->description)
                    <div class="mb-3">
                        <strong>Tavsif:</strong>
                        <div class="border rounded p-2 bg-light">{{ e($service->description) }}</div>
                    </div>
                @endif
                <a href="/services" class="btn btn-outline-success"><i class="fas fa-arrow-left me-1"></i>Orqaga</a>
            </div>
        </div>
    </div>
</div>
@endsection
