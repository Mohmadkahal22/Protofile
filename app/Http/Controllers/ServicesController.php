<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Services::with('projects')->get();

        return response()->json([
            'status' => 'success',
            'data' => $services
        ]);
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->except(['image']);

            // Handle image upload
            if ($request->hasFile('image')) {
                $imageName = Str::random(32).'.'.$request->file('image')->getClientOriginalExtension();
                $imagePath = 'services/' . $imageName;
                Storage::disk('public')->put($imagePath, file_get_contents($request->file('image')));
                $data['image_path'] = url('api/storage/' . $imagePath);
            }

            $service = Services::create($data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $service,
                'image_url' => $data['image_path']
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Service creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Service creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified service.
     */
    public function show( $id)
    {
        $service=Services::find($id);

        $service->load('projects');

        return response()->json([
            'status' => 'success',
            'data' => $service
        ]);
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $service=Services::find($id);

        DB::beginTransaction();

        try {
            $data = $request->except(['image']);

            // Handle image update
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($service->image_path) {
                    $this->deleteFile($service->image_path);
                }

                $imageName = Str::random(32).'.'.$request->file('image')->getClientOriginalExtension();
                $imagePath = 'services/' . $imageName;
                Storage::disk('public')->put($imagePath, file_get_contents($request->file('image')));
                $data['image_path'] = url('api/storage/' . $imagePath);
            }

            $service->update($data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $service,
                'image_url' => $data['image_path'] ?? $service->image_path
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Service update failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Service update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified service.
     */
    public function destroy( $id)
    {
        DB::beginTransaction();
        $service=Services::find($id);

        try {
            // Delete associated image
            if ($service->image_path) {
                $this->deleteFile($service->image_path);
            }

            $service->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Service deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Service deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Service deletion failed',
                'error' => $e->getMessage()
            ], 500);
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
