<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
            'data' => $books
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
            'data' => $book
        ]);
    }

    // Store a new book
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'published_year' => 'required|integer',
            'genre' => 'required|string|max:100',
            'cover_image' => 'nullable|string',
        ]);
        $book = Book::create($validated);
        return response()->json([
            'success' => true,
            'data' => $book,
            'message' => 'Book created successfully'
        ], 201);
    }

    // Update a book
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'published_year' => 'sometimes|required|integer',
            'genre' => 'sometimes|required|string|max:100',
            'cover_image' => 'nullable|string',
        ]);
        $book->update($validated);
        return response()->json([
            'success' => true,
            'data' => $book,
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
