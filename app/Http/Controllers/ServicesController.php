<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Services\ServicesService;
use Illuminate\Support\Facades\Log;

class ServicesController extends Controller
{
    protected $service;

    public function __construct(ServicesService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $services = $this->service->index();
        return response()->json(['status' => 'success', 'data' => $services]);
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->except(['image']);
            $image = $request->file('image');
            $res = $this->service->store($data, $image);
            return response()->json(['status' => 'success', 'data' => $res, 'image_url' => $res->image_path ?? null], 201);
        } catch (\Exception $e) {
            Log::error('Service creation failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Service creation failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified service.
     */
    public function show($id)
    {
        $res = $this->service->show($id);
        if (! $res) return response()->json(['status' => 'error', 'message' => 'Service not found'], 404);
        return response()->json(['status' => 'success', 'data' => $res]);
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->except(['image']);
            $image = $request->file('image');
            $res = $this->service->update($id, $data, $image);
            if (! $res) return response()->json(['status' => 'error', 'message' => 'Service not found'], 404);
            return response()->json(['status' => 'success', 'data' => $res, 'image_url' => $res->image_path ?? null]);
        } catch (\Exception $e) {
            Log::error('Service update failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Service update failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified service.
     */
    public function destroy($id)
    {
        try {
            $res = $this->service->delete($id);
            if (! $res) return response()->json(['status' => 'error', 'message' => 'Service not found'], 404);
            return response()->json(['status' => 'success', 'message' => 'Service deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Service deletion failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Service deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete file from storage
     */
    protected function deleteFile($fileUrl)
    {
        try {
            $filePath = parse_url($fileUrl, PHP_URL_PATH);
            $filePath = str_replace('/storage/', '', $filePath);

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to delete file: ' . $e->getMessage());
            return false;
        }
    }
}
