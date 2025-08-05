<?php

namespace App\Http\Controllers;

use App\Models\About_Us;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUs = About_Us::first();

        return response()->json([
            'status' => 'success',
            'data' => $aboutUs
        ]);
    }

    public function store(Request $request)
    {
        // Check if record already exists
        if (About_Us::exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'About Us record already exists. Use update instead.'
            ], 409);
        }

        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_description' => 'required|string',
            'website_url' => 'nullable|url',
            'foundation_date' => 'nullable|date',
            'contact_email' => 'nullable|email',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->except(['company_logo']);

            // Format foundation_date properly
            if (!empty($data['foundation_date'])) {
                $data['foundation_date'] = Carbon::createFromFormat('m/d/Y', $data['foundation_date'])->format('Y-m-d');
            }

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                $logoName = Str::random(32).'.'.$request->file('company_logo')->getClientOriginalExtension();
                $logoPath = 'company_logos/' . $logoName;
                Storage::disk('public')->put($logoPath, file_get_contents($request->file('company_logo')));
                $data['company_logo'] = url('storage/' . $logoPath);
            }

            $aboutUs = About_Us::create($data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $aboutUs,
                'logo_url' => $data['company_logo'] ?? null
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('About Us creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'About Us creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show()
    {
        $aboutUs = About_Us::first();

        if (!$aboutUs) {
            return response()->json([
                'status' => 'error',
                'message' => 'About Us record not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $aboutUs
        ]);
    }

    public function update(Request $request)
    {
        $aboutUs = About_Us::first();

        if (!$aboutUs) {
            return response()->json([
                'status' => 'error',
                'message' => 'About Us record not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'company_name' => 'sometimes|string|max:255',
            'company_description' => 'sometimes|string',
            'website_url' => 'nullable|url',
            'foundation_date' => 'nullable|date',
            'contact_email' => 'nullable|email',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->except(['company_logo']);

            // Format foundation_date properly
            if (!empty($data['foundation_date'])) {
                $data['foundation_date'] = Carbon::createFromFormat('m/d/Y', $data['foundation_date'])->format('Y-m-d');
            }

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                // Delete old logo if exists
                if ($aboutUs->company_logo) {
                    $oldLogoPath = str_replace(url('storage/'), '', $aboutUs->company_logo);
                    Storage::disk('public')->delete($oldLogoPath);
                }

                $logoName = Str::random(32).'.'.$request->file('company_logo')->getClientOriginalExtension();
                $logoPath = 'company_logos/' . $logoName;
                Storage::disk('public')->put($logoPath, file_get_contents($request->file('company_logo')));
                $data['company_logo'] = url('storage/' . $logoPath);
            }

            $aboutUs->update($data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $aboutUs,
                'logo_url' => $data['company_logo'] ?? $aboutUs->company_logo
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('About Us update failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'About Us update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy()
    {
        $aboutUs = About_Us::first();

        if (!$aboutUs) {
            return response()->json([
                'status' => 'error',
                'message' => 'About Us record not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Delete logo if exists
            if ($aboutUs->company_logo) {
                $logoPath = str_replace(url('storage/'), '', $aboutUs->company_logo);
                Storage::disk('public')->delete($logoPath);
            }

            $aboutUs->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'About Us record deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('About Us deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'About Us deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
