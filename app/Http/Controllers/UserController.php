<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'email' =>'required|email',
            'password' => 'required|confirmed',
        ]);
        $data['role'] = 1; // Default role for new users
        $user = User :: create($data);
        if($user){
            return redirect()->route('login');
        }

    }
    public function login(Request $request){
        $credentials = $request->validate([
            'email' =>'required|email',
            'password' => 'required',
        ]);
    
      
         if(Auth::attempt($credentials)){
       
            return redirect()->route('dashboard');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function dashboardPage(){
        if(Auth::check()){
            $user = Auth::user();

            // Redirect based on role
            if ($user->role == 2) { // SI
                return redirect()->route('si.dashboard');
            } elseif ($user->role == 3) { // Inspector
                return redirect()->route('inspector.dashboard');
            } else { // Regular user (role=1)
                // Get user-specific complaint statistics
                $totalComplaints = Complaint::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('voter_id_number', $user->voter_id);
                })->count();

                $pendingComplaints = Complaint::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('voter_id_number', $user->voter_id);
                })->where('status', 'pending')->count();

                $inProgressComplaints = Complaint::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('voter_id_number', $user->voter_id);
                })->where('status', 'under_investigation')->count();

                $resolvedComplaints = Complaint::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('voter_id_number', $user->voter_id);
                })->where('status', 'resolved')->count();

                // Get recent complaints for the user
                $recentComplaints = Complaint::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('voter_id_number', $user->voter_id);
                })->orderBy('created_at', 'desc')->take(5)->get();

                // Get all complaints for the user (for the complaints section)
                $complaints = Complaint::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('voter_id_number', $user->voter_id);
                })->orderBy('created_at', 'desc')->get();

                return view('User.dashboard', compact(
                    'totalComplaints',
                    'pendingComplaints',
                    'inProgressComplaints',
                    'resolvedComplaints',
                    'recentComplaints',
                    'complaints'
                ));
                
            }
        }
        else{
            return redirect()->route('login');
        }
    }
    

    public function logout(){
        Auth::logout();
        return view('login');
    }

    public function updateProfile(Request $request){
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_no' => 'nullable|string|max:20',
            'voter_id_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!'
        ]);
    }

    // public function Registration(){
    //    return view('register');
    // }

}
