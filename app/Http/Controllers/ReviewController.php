<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $reviews = Review::orderBy('review_date', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'data' => $reviews,
                'count' => $reviews->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch reviews: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch reviews'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'rating' => 'required|integer|between:1,5',
            'review_date' => 'nullable|date',
            'is_approved' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $reviewData = $validator->validated();

            // Set default review date to current time if not provided
            if (empty($reviewData['review_date'])) {
                $reviewData['review_date'] = Carbon::now();
            }

            // Default is_approved to false if not provided
            if (!isset($reviewData['is_approved'])) {
                $reviewData['is_approved'] = false;
            }

            $review = Review::create($reviewData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $review,
                'message' => 'Review created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Review creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Review creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Review not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch review: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch review'
            ], 500);
        }
    }

    /**
     * Update the specified review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|max:2000',
            'rating' => 'sometimes|integer|between:1,5',
            'review_date' => 'sometimes|date',
            'is_approved' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Review not found'
                ], 404);
            }

            $updateData = $validator->validated();
            $review->update($updateData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $review,
                'message' => 'Review updated successfully',
                'changes' => $review->getChanges()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Review update failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Review update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Review not found'
                ], 404);
            }

            $review->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Review deleted successfully',
                'deleted_id' => $id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Review deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Review deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}
