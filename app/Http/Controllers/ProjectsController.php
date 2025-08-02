<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Projects;
use App\Models\Imag_Progect;

use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Projects::with(['service', 'images', 'features'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $projects
        ]);
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'project_url' => 'nullable|url',
            'description' => 'required|string',
            'video_url' => 'nullable|url',
            'service_id' => 'required|exists:services,id',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->except(['images', 'features']);

            // Create the project
            $project = Projects::create($data);

            // Handle images upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $imageName = Str::random(32).'.'.$imageFile->getClientOriginalExtension();
                    $imagePath = 'projects/' . $imageName;
                    Storage::disk('public')->put($imagePath, file_get_contents($imageFile));

                    Imag_Progect::create([
                        'project_id' => $project->id,
                        'image_path' => url('api/storage/' . $imagePath)
                    ]);
                }
            }

            // Handle features if provided
            if ($request->has('features')) {
                foreach ($request->features as $feature) {
                    $project->features()->create([
                        'feature_text' => $feature
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $project->load(['images', 'features']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Project creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified project.
     */
    public function show($id)
    {
        $project = Projects::with(['service', 'images', 'features'])->find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $project
        ]);
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, $id)
    {
        $project = Projects::find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'project_url' => 'nullable|url',
            'description' => 'sometimes|string',
            'video_url' => 'nullable|url',
            'service_id' => 'sometimes|exists:services,id',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'deleted_images' => 'sometimes|array',
            'deleted_images.*' => 'exists:imag__progects,id',
            'features' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->except(['images', 'deleted_images', 'features']);

            // Handle images deletion
            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $imageId) {
                    $image = Imag_Progect::find($imageId);
                    if ($image) {
                        $this->deleteFile($image->image_path);
                        $image->delete();
                    }
                }
            }

            // Handle new images upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $imageName = Str::random(32).'.'.$imageFile->getClientOriginalExtension();
                    $imagePath = 'projects/' . $imageName;
                    Storage::disk('public')->put($imagePath, file_get_contents($imageFile));

                    Imag_Progect::create([
                        'project_id' => $project->id,
                        'image_path' => url('api/storage/' . $imagePath)
                    ]);
                }
            }

            // Handle features update
            if ($request->has('features')) {
                $project->features()->delete(); // Remove existing features
                foreach ($request->features as $feature) {
                    $project->features()->create([
                        'feature' => $feature
                    ]);
                }
            }

            $project->update($data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $project->fresh(['images', 'features'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Project update failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Project update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified project.
     */
    public function destroy($id)
    {
        $project = Projects::with('images')->find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Delete all associated images
            foreach ($project->images as $image) {
                $this->deleteFile($image->image_path);
                $image->delete();
            }

            // Delete the project
            $project->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Project deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Project deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Project deletion failed',
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
