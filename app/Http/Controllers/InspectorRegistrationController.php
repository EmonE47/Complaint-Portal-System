<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Inspector;
use Illuminate\Http\Request;
use App\Models\PoliceStation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class InspectorRegistrationController extends Controller
{   
    public function showRegistrationForm()
    {
        $policeStations = PoliceStation::all();
        // $policeStations = DB::select('SELECT * FROM police_stations WHERE Inspector_Assigned = 0');
        return view('inspector_reg', compact('policeStations'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'number' => 'required|string|max:15',
            'nid_number' => 'required|string|max:20|unique:users',
            'rank' => 'required|in:inspector,si,asi',
            'police_station_id' => 'required|exists:police_stations,id',
            'password' => 'required|string|min:6|confirmed',
        ]);
         if($request->rank == 'inspector'){
             $duplicate = DB::select('SELECT * FROM police_stations WHERE Inspector_Assigned = 1 AND id = ?', [$request->police_station_id]);
                if($duplicate){
                    return redirect()->back()->withErrors(['police_station_id' => 'An Inspector is already assigned to this police station.'])->withInput();
                }
            DB::table('police_stations')->where('id', $request->police_station_id)->update(['Inspector_Assigned' => 1]);
             $rol = 3;
         }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if($request->rank == 'si'){
             $rol = 2;
        }
        else if($request->rank == 'asi'){
             $rol = 4;
        }
        // Create user record
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $rol, // Inspector role
            'phone_no' => $request->number,
            'nid_number' => $request->nid_number,
            'rank' => $request->rank,
            'police_station_id' => $request->police_station_id,
        ]);

        // Create inspector record
        Inspector::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'nid_number' => $request->nid_number,
            'rank' => $request->rank,
            'police_station_id' => $request->police_station_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Inspector registered successfully. Please login.');
    }
}
