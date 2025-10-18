<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\ComplaintAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the complaints for the authenticated user.
     */
    public function index()
    {
        $complaints = Complaint::where('email', Auth::user()->email)
                    ->orWhere('voter_id_number', Auth::user()->voter_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($complaints);
    }

    /**
     * Store a newly created complaint in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone_no' => 'required|string|max:20',
                'email' => 'required|email',
                'voter_id_number' => 'required|string|max:50',
                'permanent_address' => 'required|string',
                'present_address' => 'required_if:is_same_address,false|string|nullable',
                'is_same_address' => 'boolean',
                'complaint_type' => 'required|in:lost_item,land_dispute,harassment,theft,fraud,domestic_violence,public_nuisance,cyber_crime,other',
                'police_station_id' => 'required|exists:police_stations,id',
                'description' => 'required|string',
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|max:10240'
            ]);

            // Handle same address
            if ($request->boolean('is_same_address')) {
                $validated['present_address'] = $validated['permanent_address'];
            }

            // Create complaint
            $complaint = Complaint::create($validated);

            // Handle file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('complaint-attachments');
                    
                    $complaint->attachments()->create([
                        'file_name' => basename($path),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'original_name' => $file->getClientOriginalName()
                    ]);
                }
            }

            // Create initial status history
            $complaint->statusHistories()->create([
                'status' => 'pending',
                'remarks' => 'Complaint filed successfully',
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Complaint submitted successfully',
                'complaint_number' => $complaint->complaint_number
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting complaint: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified complaint.
     */
    public function show($id)
    {
        $complaint = Complaint::with(['statusHistories', 'attachments'])
                    ->where('id', $id)
                    ->where(function($query) {
                        $query->where('email', Auth::user()->email)
                              ->orWhere('voter_id_number', Auth::user()->voter_id);
                    })
                    ->firstOrFail();
        
        return response()->json($complaint);
    }

    /**
     * Get complaint statistics for dashboard.
     */
    public function getStats()
    {
        $totalComplaints = Complaint::where('email', Auth::user()->email)
                        ->orWhere('voter_id_number', Auth::user()->voter_id)
                        ->count();
        
        $pendingComplaints = Complaint::where(function($query) {
                            $query->where('email', Auth::user()->email)
                                  ->orWhere('voter_id_number', Auth::user()->voter_id);
                        })
                        ->where('status', 'pending')
                        ->count();
        
        $inProgressComplaints = Complaint::where(function($query) {
                                $query->where('email', Auth::user()->email)
                                      ->orWhere('voter_id_number', Auth::user()->voter_id);
                            })
                            ->where('status', 'under_investigation')
                            ->count();
        
        $resolvedComplaints = Complaint::where(function($query) {
                                $query->where('email', Auth::user()->email)
                                      ->orWhere('voter_id_number', Auth::user()->voter_id);
                            })
                            ->where('status', 'resolved')
                            ->count();

        return [
            'totalComplaints' => $totalComplaints,
            'pendingComplaints' => $pendingComplaints,
            'inProgressComplaints' => $inProgressComplaints,
            'resolvedComplaints' => $resolvedComplaints
        ];
    }

    /**
     * Get recent complaints for dashboard.
     */
    public function getRecentComplaints()
    {
        return Complaint::where('email', Auth::user()->email)
                ->orWhere('voter_id_number', Auth::user()->voter_id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
    }
}