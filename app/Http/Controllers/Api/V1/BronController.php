<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Bron;
use \App\Http\Requests\BronRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BronController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bron::with(['user:id,name', 'venue:id,name,location', 'service:id,name,type']);

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

        $brons = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $brons
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bron = Bron::with(['user:id,name,email', 'venue', 'service'])->find($id);

        if (!$bron) {
            return response()->json([
                'success' => false,
                'message' => 'Bron not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $bron
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BronRequest $request)
    {


        $bron = Bron::create([
            'user_id' => $request->user_id,
            'venue_id' => $request->venue_id,
            'service_id' => $request->service_id,
            'event_date' => $request->event_date,
            'event_time' => $request->event_date . ' ' . $request->event_time,
            'guests_count' => $request->guests_count,
            'total_price' => $request->total_price,
            'status' => Bron::STATUS_PENDING,
            'notes' => $request->notes,
        ]);

        // Clear home page cache
        Cache::forget('home_page_data');

        return response()->json([
            'success' => true,
            'data' => $bron->load(['user:id,name', 'venue:id,name', 'service:id,name']),
            'message' => 'Bron created successfully'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BronRequest $request, $id)
    {
        $bron = Bron::find($id);

        if (!$bron) {
            return response()->json([
                'success' => false,
                'message' => 'Bron not found'
            ], 404);
        }


        $updateData = $request->only([
            'venue_id', 'service_id', 'event_date', 'guests_count', 
            'total_price', 'status', 'notes'
        ]);

        if ($request->has('event_time')) {
            $updateData['event_time'] = $request->event_date . ' ' . $request->event_time;
        }

        $bron->update($updateData);

        // Clear home page cache
        Cache::forget('home_page_data');

        return response()->json([
            'success' => true,
            'data' => $bron->load(['user:id,name', 'venue:id,name', 'service:id,name']),
            'message' => 'Bron updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bron = Bron::find($id);

        if (!$bron) {
            return response()->json([
                'success' => false,
                'message' => 'Bron not found'
            ], 404);
        }

        $bron->delete();

        // Clear home page cache
        Cache::forget('home_page_data');

        return response()->json([
            'success' => true,
            'message' => 'Bron deleted successfully'
        ]);
    }

    /**
     * Confirm booking
     */
    public function confirm($id)
    {
        $bron = Bron::find($id);

        if (!$bron) {
            return response()->json([
                'success' => false,
                'message' => 'Bron not found'
            ], 404);
        }

        $bron->update(['status' => Bron::STATUS_CONFIRMED]);

        return response()->json([
            'success' => true,
            'data' => $bron,
            'message' => 'Bron confirmed successfully'
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        $bron = Bron::find($id);

        if (!$bron) {
            return response()->json([
                'success' => false,
                'message' => 'Bron not found'
            ], 404);
        }

        $bron->update(['status' => Bron::STATUS_CANCELLED]);

        return response()->json([
            'success' => true,
            'data' => $bron,
            'message' => 'Bron cancelled successfully'
        ]);
    }
}
