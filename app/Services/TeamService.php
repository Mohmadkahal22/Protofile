<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TeamService
{
    public function index()
    {
        return Team::all();
    }

    public function store(array $data, $photo = null, $cv = null)
    {
        DB::beginTransaction();
        try {
            $payload = $data;

            if ($photo) {
                $photoName = Str::random(32).'.'.$photo->getClientOriginalExtension();
                $photoPath = 'team_photos/' . $photoName;
                Storage::disk('public')->put($photoPath, file_get_contents($photo));
                $payload['photo'] = url('api/storage/' . $photoPath);
            }

            if ($cv) {
                $cvName = Str::random(32).'.'.$cv->getClientOriginalExtension();
                $cvPath = 'team_cvs/' . $cvName;
                Storage::disk('public')->put($cvPath, file_get_contents($cv));
                $payload['cv_file'] = url('api/storage/' . $cvPath);
            }

            $team = Team::create($payload);
            DB::commit();
            return $team;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TeamService store failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function show(Team $team)
    {
        return $team;
    }

    public function update(Team $team, array $data, $photo = null, $cv = null)
    {
        DB::beginTransaction();
        try {
            $payload = $data;

            if ($photo) {
                if ($team->photo) {
                    $old = str_replace('api/storage/', '', parse_url($team->photo, PHP_URL_PATH));
                    Storage::disk('public')->delete($old);
                }
                $photoName = Str::random(32).'.'.$photo->getClientOriginalExtension();
                $photoPath = 'team_photos/' . $photoName;
                Storage::disk('public')->put($photoPath, file_get_contents($photo));
                $payload['photo'] = url('api/storage/' . $photoPath);
            }

            if ($cv) {
                if ($team->cv_file) {
                    $oldcv = str_replace('api/storage/', '', parse_url($team->cv_file, PHP_URL_PATH));
                    Storage::disk('public')->delete($oldcv);
                }
                $cvName = Str::random(32).'.'.$cv->getClientOriginalExtension();
                $cvPath = 'team_cvs/' . $cvName;
                Storage::disk('public')->put($cvPath, file_get_contents($cv));
                $payload['cv_file'] = url('api/storage/' . $cvPath);
            }

            $team->update($payload);
            DB::commit();
            return $team;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TeamService update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Team $team)
    {
        try {
            if ($team->photo) {
                $old = str_replace('api/storage/', '', parse_url($team->photo, PHP_URL_PATH));
                Storage::disk('public')->delete($old);
            }
            if ($team->cv_file) {
                $oldcv = str_replace('api/storage/', '', parse_url($team->cv_file, PHP_URL_PATH));
                Storage::disk('public')->delete($oldcv);
            }
            $team->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('TeamService delete failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
