@extends('layouts.app')

@section('title', $book->title . ' - Kitob')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <i class="fas fa-book fa-2x me-3"></i>
                <h4 class="mb-0">{{ $book->title }}</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>Muallif:</strong> {{ e($book->author) }}</li>
                    <li class="list-group-item"><strong>Janr:</strong> {{ e($book->genre) }}</li>
                    <li class="list-group-item"><strong>Yili:</strong> {{ e($book->published_year) }}</li>
                    @if($book->cover_image)
                        <li class="list-group-item"><strong>Muqova:</strong> <a href="{{ e($book->cover_image) }}" target="_blank">Ko'rish</a></li>
                    @endif
                </ul>
                @if($book->description)
                    <div class="mb-3">
                        <strong>Izoh:</strong>
                        <div class="border rounded p-2 bg-light">{{ e($book->description) }}</div>
                    </div>
                @endif
                <a href="/books" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Orqaga</a>
            </div>
        </div>
    </div>
</div>
@endsection
