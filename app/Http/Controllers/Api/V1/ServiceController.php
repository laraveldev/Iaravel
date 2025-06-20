<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Cache::remember('services_list', 3600, function () {
            return Service::active()->get(['id', 'name', 'description', 'price', 'type']);
        });

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Cache::remember("service_{$id}", 3600, function () use ($id) {
            return Service::active()->find($id);
        });

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'type' => $service->type
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string|max:255',
        ]);

        $service = Service::create($request->all());

        // Clear cache
        Cache::forget('services_list');

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Service created successfully'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'type' => 'sometimes|required|string|max:255',
        ]);

        $service->update($request->all());

        // Clear cache
        Cache::forget('services_list');
        Cache::forget("service_{$id}");

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Service updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        $service->delete();

        // Clear cache
        Cache::forget('services_list');
        Cache::forget("service_{$id}");

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ]);
    }
}
