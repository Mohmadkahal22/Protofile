<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::all();
        return response()->json([
            'status' => 'success',
            'data' => $teams
        ]);
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

        DB::beginTransaction();

        try {
            $data = $request->except(['photo', 'cv_file']);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photoName = Str::random(32).'.'.$request->file('photo')->getClientOriginalExtension();
                $photoPath = 'team_photos/' . $photoName;
                Storage::disk('public')->put($photoPath, file_get_contents($request->file('photo')));
                $data['photo'] = url('api/storage/' . $photoPath);
            }

            // Handle CV file upload (similar to images in first example)
            if ($request->hasFile('cv_file')) {
                $cvName = Str::random(32).'.'.$request->file('cv_file')->getClientOriginalExtension();
                $cvPath = 'team_cvs/' . $cvName;
                Storage::disk('public')->put($cvPath, file_get_contents($request->file('cv_file')));
                $data['cv_file'] = url('api/storage/' . $cvPath);
            }

            $team = Team::create($data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $team,
                'photo_url' => $data['photo'] ?? null,
                'cv_file_url' => $data['cv_file'] ?? null
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Team member creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Team member creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return response()->json([
            'status' => 'success',
            'data' => $team
        ]);
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

        DB::beginTransaction();

        try {
            $data = $request->except(['photo', 'cv_file']);

            // Handle photo update
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($team->photo) {
                    $this->deleteFile($team->photo);
                }

                $photoName = Str::random(32).'.'.$request->file('photo')->getClientOriginalExtension();
                $photoPath = 'team_photos/' . $photoName;
                Storage::disk('public')->put($photoPath, file_get_contents($request->file('photo')));
                $data['photo'] = url('api/storage/' . $photoPath);
            }

            // Handle CV file update
            if ($request->hasFile('cv_file')) {
                // Delete old CV if exists
                if ($team->cv_file) {
                    $this->deleteFile($team->cv_file);
                }

                $cvName = Str::random(32).'.'.$request->file('cv_file')->getClientOriginalExtension();
                $cvPath = 'team_cvs/' . $cvName;
                Storage::disk('public')->put($cvPath, file_get_contents($request->file('cv_file')));
                $data['cv_file'] = url('api/storage/' . $cvPath);
            }

            $team->update($data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $team,
                'photo_url' => $data['photo'] ?? $team->photo,
                'cv_file_url' => $data['cv_file'] ?? $team->cv_file
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Team member update failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Team member update failed',
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
        // Delete associated files
        if ($team->photo) {
            Storage::disk('public')->delete($team->photo);
        }
        if ($team->cv_file) {
            Storage::disk('public')->delete($team->cv_file);
        }

        $team->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Team member deleted successfully'
        ]);
    }
}
