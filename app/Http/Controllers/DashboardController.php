<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get authenticated user's complaints
        $complaints = Complaint::where('email', auth()->user()->email)
                    ->orWhere('voter_id_number', auth()->user()->voter_id_number)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $recentComplaints = Complaint::where('email', auth()->user()->email)
                    ->orWhere('voter_id_number', auth()->user()->voter_id_number)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

        // Get complaint statistics for the user
        $totalComplaints = Complaint::where('email', auth()->user()->email)
                        ->orWhere('voter_id_number', auth()->user()->voter_id_number)
                        ->count();

        $pendingComplaints = Complaint::where(function($query) {
                            $query->where('email', auth()->user()->email)
                                  ->orWhere('voter_id_number', auth()->user()->voter_id_number);
                        })
                        ->where('status', 'pending')
                        ->count();

        $inProgressComplaints = Complaint::where(function($query) {
                                $query->where('email', auth()->user()->email)
                                      ->orWhere('voter_id_number', auth()->user()->voter_id_number);
                            })
                            ->where('status', 'under_investigation')
                            ->count();

        $resolvedComplaints = Complaint::where(function($query) {
                                $query->where('email', auth()->user()->email)
                                      ->orWhere('voter_id_number', auth()->user()->voter_id_number);
                            })
                            ->where('status', 'resolved')
                            ->count();

        return view('dashboard', compact('complaints', 'recentComplaints', 'totalComplaints', 'pendingComplaints', 'inProgressComplaints', 'resolvedComplaints'));
    }
}
