<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Models\Projects;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectsController extends Controller
{
    protected $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $projects = $this->service->listAll();
        return response()->json(['status' => 'success', 'data' => $projects]);
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            $data = $request->validated();
            $files = $request->file('images', []);
            $features = $request->input('features', []);

            $project = $this->service->create($data, $files, $features);

            return response()->json(['status' => 'success', 'data' => $project], 201);
        } catch (\Exception $e) {
            Log::error('Project creation failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Project creation failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $project = $this->service->find($id);
        if (! $project) {
            return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $project]);
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Projects::find($id);
        if (! $project) {
            return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
        }

        try {
            $data = $request->validated();
            $files = $request->file('images', []);
            $deleted = $request->input('deleted_images', []);
            $features = $request->input('features', []);

            $updated = $this->service->update($project, $data, $files, $deleted, $features);
            return response()->json(['status' => 'success', 'data' => $updated]);
        } catch (\Exception $e) {
            Log::error('Project update failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Project update failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $project = Projects::with('images')->find($id);
        if (! $project) {
            return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
        }

        try {
            $this->service->delete($project);
            return response()->json(['status' => 'success', 'message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Project deletion failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Project deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
