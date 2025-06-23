<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // List all books
    public function index()
    {
        $books = Book::latest()->paginate(15);
        return response()->json([
            'success' => true,
            'data'    => $books
        ]);
    }

    // Show a single book
    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $book
        ]);
    }

    // Store a new book
    public function store(BookRequest $request)
    {
        $validated = $request->validated();
        $book = Book::create($validated);
        return response()->json([
            'success' => true,
            'data'    => $book,
            'message' => 'Book created successfully'
        ], 201);
    }

    // Update a book
    public function update(BookRequest $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }
        $validated = $request->validated();
        $book->update($validated);
        return response()->json([
            'success' => true,
            'data'    => $book,
            'message' => 'Book updated successfully'
        ]);
    }

    // Delete a book
    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }
        $book->delete();
        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully'
        ]);
    }
}
