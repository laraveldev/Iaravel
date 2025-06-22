@extends('layouts.app')

@section('title', $venue->name . ' - To\'y Zali')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-building fa-2x me-3"></i>
                <h4 class="mb-0">{{ $venue->name }}</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>Manzil:</strong> {{ e($venue->location) }}</li>
                    <li class="list-group-item"><strong>Sig'im:</strong> {{ e($venue->capacity) }} kishi</li>
                    <li class="list-group-item"><strong>Narx:</strong> <span class="text-success">{{ number_format($venue->price, 0, '.', ' ') }} so'm</span></li>
                    @if($venue->phone)
                        <li class="list-group-item"><strong>Telefon:</strong> {{ e($venue->phone) }}</li>
                    @endif
                    @if($venue->email)
                        <li class="list-group-item"><strong>Email:</strong> {{ e($venue->email) }}</li>
                    @endif
                    <li class="list-group-item"><strong>Holati:</strong> {!! $venue->is_active ? '<span class="badge bg-success">Aktiv</span>' : '<span class="badge bg-secondary">Noaktiv</span>' !!}</li>
                </ul>
                @if($venue->description)
                    <div class="mb-3">
                        <strong>Tavsif:</strong>
                        <div class="border rounded p-2 bg-light">{{ e($venue->description) }}</div>
                    </div>
                @endif
                <a href="/venues" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-1"></i>Orqaga</a>
            </div>
        </div>
    </div>
</div>
@endsection
