<?php

namespace App\Http\Controllers;

use App\Models\Contact_Us;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\ContactUsService;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the contact messages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected $service;

    public function __construct(ContactUsService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $contacts = $this->service->index();
            return response()->json(['status' => 'success', 'data' => $contacts]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch contact messages: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch contact messages'], 500);
        }
    }

    /**
     * Store a newly created contact message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->only(['name','email','phone','subject','message','status']);
            $contact = $this->service->store($data);
            return response()->json(['status' => 'success', 'data' => $contact, 'message' => 'Contact message created successfully'], 201);
        } catch (\Exception $e) {
            Log::error('Contact message creation failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Contact message creation failed'], 500);
        }
    }

    /**
     * Display the specified contact message.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $contact = $this->service->show($id);
            if (! $contact) return response()->json(['status' => 'error', 'message' => 'Contact message not found'], 404);
            return response()->json(['status' => 'success', 'data' => $contact]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch contact message: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch contact message'], 500);
        }
    }

    /**
     * Update the specified contact message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'subject' => 'sometimes|string|max:255',
            'message' => 'sometimes|string',
            'status' => 'sometimes|string|in:pending,in_progress,completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->only(['name','email','phone','subject','message','status']);
            $contact = $this->service->update($id, $data);
            if (! $contact) return response()->json(['status' => 'error', 'message' => 'Contact message not found'], 404);
            return response()->json(['status' => 'success', 'data' => $contact, 'message' => 'Contact message updated successfully']);
        } catch (\Exception $e) {
            Log::error('Contact message update failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Contact message update failed'], 500);
        }
    }

    /**
     * Remove the specified contact message.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $res = $this->service->delete($id);
            if (! $res) return response()->json(['status' => 'error', 'message' => 'Contact message not found'], 404);
            return response()->json(['status' => 'success', 'message' => 'Contact message deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Contact message deletion failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Contact message deletion failed'], 500);
        }
    }
}
