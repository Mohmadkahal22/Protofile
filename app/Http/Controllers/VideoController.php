<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\VideoService;

class VideoController extends Controller
{
    /**
     * Display a listing of all videos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $service = app(VideoService::class);
            $videos = $service->index();
            return response()->json(['status' => 'success', 'data' => $videos, 'count' => $videos->count()]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch videos: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch videos'], 500);
        }
    }

    /**
     * Store a newly created video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'video_url' => 'required|url|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $service = app(VideoService::class);
            $video = $service->store($request->only(['title','description','location','video_url']));
            return response()->json(['status' => 'success', 'data' => $video, 'message' => 'Video created successfully'], 201);
        } catch (\Exception $e) {
            Log::error('Video creation failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Video creation failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified video.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $service = app(VideoService::class);
            $video = $service->show($id);
            if (! $video) return response()->json(['status' => 'error', 'message' => 'Video not found'], 404);
            return response()->json(['status' => 'success', 'data' => $video]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch video: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch video'], 500);
        }
    }

    /**
     * Update the specified video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'sometimes|string|max:255',
            'video_url' => 'sometimes|url|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $service = app(VideoService::class);
            $video = $service->update($id, $validator->validated());
            if (! $video) return response()->json(['status' => 'error', 'message' => 'Video not found'], 404);
            return response()->json(['status' => 'success', 'data' => $video, 'message' => 'Video updated successfully', 'changes' => $video->getChanges()]);
        } catch (\Exception $e) {
            Log::error('Video update failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Video update failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified video.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $service = app(VideoService::class);
            $res = $service->delete($id);
            if (! $res) return response()->json(['status' => 'error', 'message' => 'Video not found'], 404);
            return response()->json(['status' => 'success', 'message' => 'Video deleted successfully', 'deleted_id' => $id]);
        } catch (\Exception $e) {
            Log::error('Video deletion failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Video deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

}
