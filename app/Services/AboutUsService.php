<?php

namespace App\Services;

use App\Models\About_Us;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AboutUsService
{
    public function index()
    {
        return About_Us::first();
    }

    public function store(array $data, $file = null)
    {
        if (About_Us::exists()) {
            return ['error' => 'exists'];
        }

        DB::beginTransaction();
        try {
            if (! empty($data['foundation_date'])) {
                $data['foundation_date'] = Carbon::createFromFormat('m/d/Y', $data['foundation_date'])->format('Y-m-d');
            }

            if ($file) {
                $logoName = Str::random(32).'.'.$file->getClientOriginalExtension();
                $logoPath = 'company_logos/' . $logoName;
                Storage::disk('public')->put($logoPath, file_get_contents($file));
                $data['company_logo'] = url('storage/' . $logoPath);
            }

            $aboutUs = About_Us::create($data);
            DB::commit();
            return $aboutUs;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AboutUsService store failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function show()
    {
        return About_Us::first();
    }

    public function update(array $data, $file = null)
    {
        $aboutUs = About_Us::first();
        if (! $aboutUs) return null;

        DB::beginTransaction();
        try {
            if (! empty($data['foundation_date'])) {
                $data['foundation_date'] = Carbon::createFromFormat('m/d/Y', $data['foundation_date'])->format('Y-m-d');
            }

            if ($file) {
                if ($aboutUs->company_logo) {
                    $oldLogoPath = str_replace(url('storage/'), '', $aboutUs->company_logo);
                    Storage::disk('public')->delete($oldLogoPath);
                }
                $logoName = Str::random(32).'.'.$file->getClientOriginalExtension();
                $logoPath = 'company_logos/' . $logoName;
                Storage::disk('public')->put($logoPath, file_get_contents($file));
                $data['company_logo'] = url('storage/' . $logoPath);
            }

            $aboutUs->update($data);
            DB::commit();
            return $aboutUs;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AboutUsService update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function destroy()
    {
        $aboutUs = About_Us::first();
        if (! $aboutUs) return null;

        DB::beginTransaction();
        try {
            if ($aboutUs->company_logo) {
                $logoPath = str_replace(url('storage/'), '', $aboutUs->company_logo);
                Storage::disk('public')->delete($logoPath);
            }
            $aboutUs->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AboutUsService destroy failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
