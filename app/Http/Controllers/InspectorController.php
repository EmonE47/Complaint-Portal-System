<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Complaint;
use App\Models\CaseAssignment;
use App\Models\ComplaintStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InspectorController extends Controller
{
    public function dashboard()
    {

        // Get all sub inspectors (users with role 2)
        $subInspectors = User::where('role', 2)->withCount('activeCases')->get();

        // Get complaint status history
        $statusHistories = ComplaintStatusHistory::with(['complaint'])->orderBy('created_at', 'desc')->limit(20)->get();
        // Get users eligible for promotion (role=1)
        $usersForPromotion = User::where('role', 1)->get();
        //  select police station id the email is same as the user email from the inspector table
        $policeStationId = DB::table('inspectors')->where('email', Auth::user()->email)->value('police_station_id');
        $UserPolicestaion= DB::table('police_stations')->where('id', $policeStationId)->value('name');
        // Get the complaints with their status and polistation is equal to the inspector police station
        $complaints = Complaint::where('police_station_id', $policeStationId)->with(['statusHistories', 'currentAssignment.user'])->get();
        return view('inspector.dashboard', compact('complaints', 'subInspectors', 'statusHistories', 'usersForPromotion', 'UserPolicestaion'));
    }

    public function createComplaint(Request $request)
    {
        $request->validate([
            'complaint_type' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'complainant_name' => 'required|string|max:255',
            'complainant_contact' => 'required|string|max:255',
            'incident_location' => 'required|string|max:255',
            'incident_datetime' => 'required|date',
            'description' => 'required|string|max:1000',
            'evidence' => 'nullable|array',
            'evidence.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:2048',
        ]);

        $user = Auth::user();

        // Check if user is an inspector (role=3)
        if ($user->role !== 3) {
            return response()->json([
                'success' => false,
                'message' => 'Only inspectors can create complaints.',
            ], 403);
        }

        // Get police station ID for the inspector
        $policeStationId = DB::table('inspectors')->where('email', $user->email)->value('police_station_id');

        if (!$policeStationId) {
            return response()->json([
                'success' => false,
                'message' => 'Inspector police station not found.',
            ], 400);
        }

        DB::transaction(function () use ($request, $user, $policeStationId) {
            // Create the complaint
            $complaint = new Complaint();
            $complaint->complaint_type = $request->complaint_type;
            $complaint->priority = $request->priority;
            $complaint->complainant_name = $request->complainant_name;
            $complaint->complainant_contact = $request->complainant_contact;
            $complaint->incident_location = $request->incident_location;
            $complaint->incident_datetime = $request->incident_datetime;
            $complaint->description = $request->description;
            $complaint->police_station_id = $policeStationId;
            $complaint->status = 'pending'; // New complaints start as pending
            $complaint->created_by = $user->id;
            $complaint->save();

            // Handle file uploads if any
            if ($request->hasFile('evidence')) {
                $evidencePaths = [];
                foreach ($request->file('evidence') as $file) {
                    $path = $file->store('evidence', 'public');
                    $evidencePaths[] = $path;
                }
                $complaint->evidence = json_encode($evidencePaths);
                $complaint->save();
            }

            // Create initial status history
            ComplaintStatusHistory::create([
                'complaint_id' => $complaint->id,
                'status' => 'pending',
                'remarks' => 'Complaint created by Inspector',
                'updated_by' => $user->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Complaint created successfully.',
        ]);
    }

    public function assignCase(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'user_id' => 'required|exists:users,id',
            'assignment_notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Check if the selected user is a Sub-Inspector (role=2)
        $subInspector = User::find($request->user_id);
        if ($subInspector->role !== 2) {
            return redirect()->back()->with('error', 'Only Sub-Inspectors can be assigned to cases.');
        }

        // Check if complaint is already assigned
        $existingAssignment = CaseAssignment::where('complaint_id', $request->complaint_id)->first();
        if ($existingAssignment) {
            // Update existing assignment (reassignment)
            $existingAssignment->user_id = $request->user_id;
            $existingAssignment->assigned_by = $user->name;
            $existingAssignment->assignment_notes = $request->assignment_notes;
            $existingAssignment->assigned_at = now();
            $existingAssignment->save();
        } else {
            // Create new assignment
            $assignment = new CaseAssignment();
            $assignment->complaint_id = $request->complaint_id;
            $assignment->user_id = $request->user_id;
            $assignment->assigned_by = $user->name;
            $assignment->assignment_notes = $request->assignment_notes;
            $assignment->status = 'assigned';
            $assignment->assigned_at = now();
            $assignment->save();
        }

        // Update complaint status to assigned
        $complaint = Complaint::find($request->complaint_id);
        $complaint->status = 'assigned';
        $complaint->save();

        // Create status history entry
        ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'status' => 'assigned',
            'remarks' => 'Case assigned to SI: ' . $subInspector->name,
            'updated_by' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Case assigned successfully.');
    }

    public function promoteToSI(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();

        // Check if the current user is an inspector (role=3)
        if ($user->role !== 3) {
            return response()->json([
                'success' => false,
                'message' => 'Only inspectors can promote users to SI.',
            ]);
        }

        $targetUser = User::find($request->user_id);

        // Check if the target user is not already SI or inspector
        if ($targetUser->role >= 2) {
            return response()->json([
                'success' => false,
                'message' => 'User is already SI or higher.',
            ]);
        }

        $targetUser->role = 2;
        $targetUser->save();

        return response()->json([
            'success' => true,
            'message' => 'User promoted to SI successfully.',
        ]);
    }

    public function siDashboard()
    {
        $user = Auth::user();

        // Check if user is SI (role=2)
        if ($user->role !== 2) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        // Get assigned complaints for this SI
        $assignedComplaints = Complaint::whereHas('currentAssignment', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['statusHistories', 'currentAssignment'])->get();

        // Get recent status updates
        $recentUpdates = ComplaintStatusHistory::whereHas('complaint.currentAssignment', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('complaint')->orderBy('created_at', 'desc')->limit(10)->get();

        return view('si.dashboard', compact('assignedComplaints', 'recentUpdates'));
    }

    public function updateComplaintStatus(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'status' => 'required|in:' . implode(',', array_keys(Complaint::STATUSES)),
            'remarks' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Check if user is SI (role=2)
        if ($user->role !== 2) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only Sub-Inspectors can update complaint status.',
            ], 403);
        }

        // Check if complaint is assigned to this SI
        $complaint = Complaint::where('id', $request->complaint_id)
            ->whereHas('currentAssignment', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->first();

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found or not assigned to you.',
            ], 404);
        }

        // Check if status is actually changing
        if ($complaint->status === $request->status) {
            return response()->json([
                'success' => false,
                'message' => 'Status is already set to ' . Complaint::STATUSES[$request->status] . '.',
            ], 400);
        }

        DB::transaction(function () use ($complaint, $request, $user) {
            // Update complaint status
            $oldStatus = $complaint->status;
            $complaint->status = $request->status;
            $complaint->save();

            // Create status history entry
            ComplaintStatusHistory::create([
                'complaint_id' => $complaint->id,
                'status' => $request->status,
                'remarks' => $request->remarks ?: 'Status updated by Sub-Inspector',
                'updated_by' => $user->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Complaint status updated successfully.',
            'new_status' => Complaint::STATUSES[$request->status],
        ]);
    }

    public function personnel()
    {
        // Get inspector details from inspectors table
        $inspectorDetails = DB::table('inspectors')->where('email', Auth::user()->email)->first();

        // Get police station name
        $policeStation = DB::table('police_stations')->where('id', $inspectorDetails->police_station_id)->first();

        return view('inspector.dashboard', compact('inspectorDetails', 'policeStation'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        // Update inspectors table if needed
        DB::table('inspectors')->where('email', $user->email)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }

    public function reports()
    {
        $user = Auth::user();

        // Get police station ID
        $policeStationId = DB::table('inspectors')->where('email', $user->email)->value('police_station_id');

        // Get complaints statistics
        $totalComplaints = Complaint::where('police_station_id', $policeStationId)->count();
        $pendingComplaints = Complaint::where('police_station_id', $policeStationId)->where('status', 'pending')->count();
        $resolvedComplaints = Complaint::where('police_station_id', $policeStationId)->where('status', 'resolved')->count();
        $underInvestigationComplaints = Complaint::where('police_station_id', $policeStationId)->where('status', 'under_investigation')->count();

        // Get monthly complaint counts for the last 6 months
        $monthlyComplaints = Complaint::where('police_station_id', $policeStationId)
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get complaint types distribution
        $complaintTypes = Complaint::where('police_station_id', $policeStationId)
            ->selectRaw('complaint_type, COUNT(*) as count')
            ->groupBy('complaint_type')
            ->get();

        return view('inspector.dashboard', compact(
            'totalComplaints',
            'pendingComplaints',
            'resolvedComplaints',
            'underInvestigationComplaints',
            'monthlyComplaints',
            'complaintTypes'
        ));
    }

    public function siReports()
    {
        $user = Auth::user();

        // Get assigned complaints for this SI
        $assignedComplaints = Complaint::whereHas('currentAssignment', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['statusHistories', 'currentAssignment'])->get();

        // Get statistics for SI's cases
        $totalCases = $assignedComplaints->count();
        $pendingCases = $assignedComplaints->where('status', 'pending')->count();
        $investigatingCases = $assignedComplaints->where('status', 'under_investigation')->count();
        $resolvedCases = $assignedComplaints->where('status', 'resolved')->count();
        $closedCases = $assignedComplaints->where('status', 'closed')->count();

        // Get monthly case assignments for the last 6 months
        $monthlyCases = CaseAssignment::where('user_id', $user->id)
            ->selectRaw('MONTH(assigned_at) as month, YEAR(assigned_at) as year, COUNT(*) as count')
            ->where('assigned_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get case types distribution
        $caseTypes = $assignedComplaints->groupBy('complaint_type')->map(function($group) {
            return $group->count();
        });

        return view('si.reports', compact(
            'totalCases',
            'pendingCases',
            'investigatingCases',
            'resolvedCases',
            'closedCases',
            'monthlyCases',
            'caseTypes'
        ));
    }

    public function siProfile()
    {
        $user = Auth::user();

        // Get additional inspector details if available
        $inspectorDetails = DB::table('inspectors')->where('email', $user->email)->first();

        return view('si.profile', compact('user', 'inspectorDetails'));
    }

    public function siCases()
    {
        $user = Auth::user();

        // Check if user is SI (role=2)
        if ($user->role !== 2) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        // Get assigned complaints for this SI
        $assignedComplaints = Complaint::whereHas('currentAssignment', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['statusHistories', 'currentAssignment'])->get();

        return view('si.cases', compact('assignedComplaints'));
    }
}
