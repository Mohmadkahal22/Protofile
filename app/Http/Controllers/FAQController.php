<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FAQController extends Controller
{
    /**
     * Display a listing of all FAQs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $faqs = FAQ::all();

            return response()->json([
                'status' => 'success',
                'data' => $faqs,
                'count' => $faqs->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch FAQs: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch FAQs'
            ], 500);
        }
    }

    /**
     * Store a newly created FAQ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:1000',
            'answer' => 'required|string|max:5000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $faq = FAQ::create([
                'question' => $request->question,
                'answer' => $request->answer
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $faq,
                'message' => 'FAQ created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FAQ creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'FAQ creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified FAQ.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $faq = FAQ::find($id);

            if (!$faq) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'FAQ not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $faq
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch FAQ: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch FAQ'
            ], 500);
        }
    }

    /**
     * Update the specified FAQ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'sometimes|string|max:1000',
            'answer' => 'sometimes|string|max:5000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $faq = FAQ::find($id);

            if (!$faq) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'FAQ not found'
                ], 404);
            }

            $faq->update($validator->validated());

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $faq,
                'message' => 'FAQ updated successfully',
                'changes' => $faq->getChanges()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FAQ update failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'FAQ update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified FAQ.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $faq = FAQ::find($id);

            if (!$faq) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'FAQ not found'
                ], 404);
            }

            $faq->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'FAQ deleted successfully',
                'deleted_id' => $id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FAQ deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'FAQ deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
