<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Taskassignments, Tasks, Technicians, User};
use App\Http\Requests\StoreTechniciansRequest;
use App\Http\Requests\UpdateTechniciansRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TechniciansController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('technicians.technicians', [
            'technicians' => User::where('role', 'technician')->orderBy('id', 'desc')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('technicians.trash-technicians', [
            'technicians' => Technicians::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($techniciansId)
    {
        /* Log ************************************************** */
        // $oldName = Technicians::where('id', $techniciansId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a technicians "'.$oldName.'".']);
        /******************************************************** */

        Technicians::where('id', $techniciansId)->update(['isTrash' => '0']);

        return redirect('/technicians');
    }

    public function updateEmployeeProfile(Request $request, $userId)
    {
        $request->validate([
            'firstname' => 'string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'string|max:255',
            'dateofbirth' => 'nullable|date',  // Validate date format
            'gender' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:100',
            'maritalstatus' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',  // You can adjust the length as needed
            'emergencycontact' => 'nullable|string|max:20',
            'employeeid' => 'nullable|string|max:50',
            'jobtitle' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'sss' => 'nullable|string|max:50',  // Add specific validation if you want to ensure the format
            'pagibig' => 'nullable|string|max:50',
            'philhealth' => 'nullable|string|max:50',
        ]);

        // Update the user's details with validated data
        User::where('id', $userId)->update([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'dateofbirth' => $request->dateofbirth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'maritalstatus' => $request->maritalstatus,
            'address' => $request->address,
            'phone' => $request->phone,
            'emergencycontact' => $request->emergencycontact,
            'employeeid' => $request->employeeid,
            'jobtitle' => $request->jobtitle,
            'department' => $request->department,
            'sss' => $request->sss,
            'pagibig' => $request->pagibig,
            'philhealth' => $request->philhealth
        ]);

        return back();
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('technicians.create-technicians');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTechniciansRequest $request)
    {
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        /* Log ************************************************** */
        Logs::create(['log' => Auth::user()->name.' created a new employee '.'"'.$request->name.'"']);
        /******************************************************** */

        return redirect('show-technicians/'.$newUser->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Technicians $technicians, $techniciansId)
    {
        return view('technicians.show-technicians', [
            'item' => User::where('id', $techniciansId)->first(),
            'taskAssignments' => Taskassignments::where('users_id', $techniciansId)
                                      ->whereDate('created_at', Carbon::today())
                                      ->orderBy('id', 'desc')
                                      ->paginate(20),
            'unfinished_taskAssignments' => Taskassignments::where('users_id', $techniciansId)
                ->whereHas('tasks', function ($query) {
                    $query->where('status', 'pending');
                })->orderBy('id', 'desc')->paginate(20),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technicians $technicians, $techniciansId)
    {
        return view('technicians.edit-technicians', [
            'item' => Technicians::where('id', $techniciansId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTechniciansRequest $request, Technicians $technicians, $techniciansId)
    {
        /* Log ************************************************** */
        // $oldName = Technicians::where('id', $techniciansId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated a Technicians from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        User::where('id', $techniciansId)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return back()->with('success', 'Technicians Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Technicians $technicians, $techniciansId)
    {
        return view('technicians.delete-technicians', [
            'item' => User::where('id', $techniciansId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technicians $technicians, $techniciansId)
    {

        /* Log ************************************************** */
        // $oldName = Technicians::where('id', $techniciansId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a Technicians "'.$oldName.'".']);
        /******************************************************** */

        User::where('id', $techniciansId)->delete();

        return redirect('/technicians');
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Technicians::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Technicians "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Technicians::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Technicians::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Technicians "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Technicians::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Technicians::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Technicians "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Technicians::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}