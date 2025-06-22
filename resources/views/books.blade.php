@extends('layouts.app')

@section('title', 'Kitoblar')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-book me-2"></i>Kitoblar</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookModal" onclick="openBookModal()">
            <i class="fas fa-plus me-1"></i>Yangi Kitob Qo'shish
        </button>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6 offset-md-3">
        <input type="text" id="bookSearch" class="form-control" placeholder="Qidirish: nomi yoki muallif...">
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row" id="books-container">
            <div class="col-12 text-center">
                <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                <p class="text-muted mt-2">Yuklanmoqda...</p>
            </div>
        </div>
    </div>
</div>

<!-- Book Modal -->
<div class="modal fade" id="bookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalTitle">Yangi Kitob</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="bookErrors" class="alert alert-danger d-none"></div>
                <form id="bookForm">
                    <input type="hidden" id="bookId">
                    <div class="mb-3">
                        <label for="bookTitle" class="form-label">Kitob nomi *</label>
                        <input type="text" class="form-control" id="bookTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookAuthor" class="form-label">Muallif *</label>
                        <input type="text" class="form-control" id="bookAuthor" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookGenre" class="form-label">Janr *</label>
                        <input type="text" class="form-control" id="bookGenre" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookYear" class="form-label">Yili *</label>
                        <input type="number" class="form-control" id="bookYear" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookDescription" class="form-label">Izoh *</label>
                        <textarea class="form-control" id="bookDescription" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="bookCover" class="form-label">Muqova (URL)</label>
                        <input type="text" class="form-control" id="bookCover">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentBookId = null;
let allBooks = [];

// Render books to the UI
function renderBooks(books) {
    const container = document.getElementById('books-container');
    if (!books.length) {
        container.innerHTML = `<div class='col-12 text-center text-muted'>Kitoblar topilmadi</div>`;
        return;
    }
    container.innerHTML = books.map(book => `
        <div class='col-md-4 mb-3'>
            <div class='card h-100'>
                <div class='card-body'>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class='card-title'>${book.title}</h5>
                            <h6 class='card-subtitle mb-2 text-muted'>${book.author}</h6>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/books/${book.id}"><i class="fas fa-eye me-1"></i>Ko'rish</a></li>
                                <li><a class="dropdown-item" href="#" onclick='editBook(${JSON.stringify(book)})'><i class="fas fa-edit me-1"></i>Tahrirlash</a></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick='deleteBook(${book.id})'><i class="fas fa-trash me-1"></i>O'chirish</a></li>
                            </ul>
                        </div>
                    </div>
                    <span class='badge bg-primary mb-2'>${book.genre}</span>
                    <span class='badge bg-secondary mb-2'>${book.published_year}</span>
                    <p class='card-text'>${book.description}</p>
                </div>
            </div>
        </div>
    `).join('');
}

// Load books from the server
function loadBooks() {
    const container = document.getElementById('books-container');
    container.innerHTML = `<div class='col-12 text-center'><i class='fas fa-spinner fa-spin fa-2x text-muted'></i><p class='text-muted mt-2'>Yuklanmoqda...</p></div>`;
    axios.get('/books')
        .then(res => {
            allBooks = res.data.data.data;
            renderBooks(allBooks);
        })
        .catch(() => {
            container.innerHTML = `<div class='col-12 text-center text-danger'>Xatolik yuz berdi</div>`;
        });
}

// Open modal for new book
function openBookModal() {
    currentBookId = null;
    document.getElementById('bookForm').reset();
    document.getElementById('bookModalTitle').textContent = 'Yangi Kitob';
}

// Edit book
function editBook(book) {
    currentBookId = book.id;
    document.getElementById('bookTitle').value = book.title;
    document.getElementById('bookAuthor').value = book.author;
    document.getElementById('bookGenre').value = book.genre;
    document.getElementById('bookYear').value = book.published_year;
    document.getElementById('bookDescription').value = book.description;
    document.getElementById('bookCover').value = book.cover_image || '';
    document.getElementById('bookModalTitle').textContent = 'Kitobni tahrirlash';
    new bootstrap.Modal(document.getElementById('bookModal')).show();
}

// Save book (create or update)
document.getElementById('bookForm').onsubmit = function(e) {
    e.preventDefault();
    const errorDiv = document.getElementById('bookErrors');
    errorDiv.classList.add('d-none');
    errorDiv.innerHTML = '';
    const data = {
        title: document.getElementById('bookTitle').value,
        author: document.getElementById('bookAuthor').value,
        genre: document.getElementById('bookGenre').value,
        published_year: document.getElementById('bookYear').value,
        description: document.getElementById('bookDescription').value,
        cover_image: document.getElementById('bookCover').value,
    };
    const url = currentBookId ? `books/${currentBookId}` : 'books';
    const method = currentBookId ? 'put' : 'post';
    axios[method](url, data)
        .then(() => {
            bootstrap.Modal.getInstance(document.getElementById('bookModal')).hide();
            loadBooks();
        })
        .catch(err => {
            if (err.response && err.response.status === 422) {
                const errors = err.response.data.errors;
                errorDiv.innerHTML = Object.values(errors).map(e => `<div>${e[0]}</div>`).join('');
                errorDiv.classList.remove('d-none');
            }
        });
};

// Delete book
function deleteBook(id) {
    if (!confirm('Rostdan ham o‘chirmoqchimisiz?')) return;
    axios.delete(`books/${id}`)
        .then(() => loadBooks());
}

// Search/filter books
document.getElementById('bookSearch').addEventListener('input', function() {
    const q = this.value.trim().toLowerCase();
    const filtered = allBooks.filter(book =>
        book.title.toLowerCase().includes(q) ||
        book.author.toLowerCase().includes(q)
    );
    renderBooks(filtered);
});

document.addEventListener('DOMContentLoaded', loadBooks);
</script>
@endpush
