<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\TeamService;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service = app(TeamService::class);
        $teams = $service->index();
        return response()->json(['status' => 'success', 'data' => $teams]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:teams,email',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'github_url' => 'nullable|url',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'specialization' => 'required|string',
            'position' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['photo','cv_file']);
            $photo = $request->file('photo');
            $cv = $request->file('cv_file');
            $service = app(TeamService::class);
            $team = $service->store($data, $photo, $cv);
            return response()->json(['status' => 'success', 'data' => $team, 'photo_url' => $team->photo ?? null, 'cv_file_url' => $team->cv_file ?? null], 201);
        } catch (\Exception $e) {
            Log::error('Team member creation failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Team member creation failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return response()->json(['status' => 'success', 'data' => $team]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:teams,email,' . $team->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'github_url' => 'nullable|url',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'specialization' => 'sometimes|string',
            'position' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['photo','cv_file']);
            $photo = $request->file('photo');
            $cv = $request->file('cv_file');
            $service = app(TeamService::class);
            $team = $service->update($team, $data, $photo, $cv);
            return response()->json(['status' => 'success', 'data' => $team, 'photo_url' => $team->photo ?? null, 'cv_file_url' => $team->cv_file ?? null]);
        } catch (\Exception $e) {
            Log::error('Team member update failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Team member update failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete file from storage
     */
    protected function deleteFile($fileUrl)
    {
        try {
            $filePath = parse_url($fileUrl, PHP_URL_PATH);
            $filePath = str_replace('api/storage/', '', $filePath);

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
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        try {
            $service = app(TeamService::class);
            $service->delete($team);
            return response()->json(['status' => 'success', 'message' => 'Team member deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Team member deletion failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Team member deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
