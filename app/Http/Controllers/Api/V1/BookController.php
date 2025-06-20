<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with(['user:id,name', 'venue:id,name,location', 'service:id,name,type']);

        // Filter by status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        // Filter by upcoming events
        if ($request->has('upcoming') && $request->upcoming) {
            $query->upcoming();
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $books = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $book = Book::with(['user:id,name,email', 'venue', 'service'])->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'venue_id' => 'required|exists:venues,id',
            'service_id' => 'required|exists:services,id',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'guests_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $book = Book::create([
            'user_id' => $request->user_id,
            'venue_id' => $request->venue_id,
            'service_id' => $request->service_id,
            'event_date' => $request->event_date,
            'event_time' => $request->event_date . ' ' . $request->event_time,
            'guests_count' => $request->guests_count,
            'total_price' => $request->total_price,
            'status' => Book::STATUS_PENDING,
            'notes' => $request->notes,
        ]);

        // Clear home page cache
        Cache::forget('home_page_data');

        return response()->json([
            'success' => true,
            'data' => $book->load(['user:id,name', 'venue:id,name', 'service:id,name']),
            'message' => 'Booking created successfully'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $request->validate([
            'venue_id' => 'sometimes|required|exists:venues,id',
            'service_id' => 'sometimes|required|exists:services,id',
            'event_date' => 'sometimes|required|date|after:today',
            'event_time' => 'sometimes|required|date_format:H:i',
            'guests_count' => 'sometimes|required|integer|min:1',
            'total_price' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        $updateData = $request->only([
            'venue_id', 'service_id', 'event_date', 'guests_count', 
            'total_price', 'status', 'notes'
        ]);

        if ($request->has('event_time')) {
            $updateData['event_time'] = $request->event_date . ' ' . $request->event_time;
        }

        $book->update($updateData);

        // Clear home page cache
        Cache::forget('home_page_data');

        return response()->json([
            'success' => true,
            'data' => $book->load(['user:id,name', 'venue:id,name', 'service:id,name']),
            'message' => 'Booking updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $book->delete();

        // Clear home page cache
        Cache::forget('home_page_data');

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ]);
    }

    /**
     * Confirm booking
     */
    public function confirm($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $book->update(['status' => Book::STATUS_CONFIRMED]);

        return response()->json([
            'success' => true,
            'data' => $book,
            'message' => 'Booking confirmed successfully'
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $book->update(['status' => Book::STATUS_CANCELLED]);

        return response()->json([
            'success' => true,
            'data' => $book,
            'message' => 'Booking cancelled successfully'
        ]);
    }
}
