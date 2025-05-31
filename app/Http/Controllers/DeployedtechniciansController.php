<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Deployedtechnicians};
use App\Http\Requests\StoreDeployedtechniciansRequest;
use App\Http\Requests\UpdateDeployedtechniciansRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeployedtechniciansController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('deployedtechnicians.deployedtechnicians', [
            'deployedtechnicians' => Deployedtechnicians::where('isTrash', '0')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('deployedtechnicians.trash-deployedtechnicians', [
            'deployedtechnicians' => Deployedtechnicians::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($deployedtechniciansId)
    {
        /* Log ************************************************** */
        // $oldName = Deployedtechnicians::where('id', $deployedtechniciansId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Deployedtechnicians "'.$oldName.'".']);
        /******************************************************** */

        Deployedtechnicians::where('id', $deployedtechniciansId)->update(['isTrash' => '0']);

        return redirect('/deployedtechnicians');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('deployedtechnicians.create-deployedtechnicians');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeployedtechniciansRequest $request)
    {
        Deployedtechnicians::create(['sites_id' => $request->sites_id,'technicians_id' => $request->technicians_id]);

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' created a new Deployedtechnicians '.'"'.$request->name.'"']);
        /******************************************************** */

        return back()->with('success', 'Deployedtechnicians Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Deployedtechnicians $deployedtechnicians, $deployedtechniciansId)
    {
        return view('deployedtechnicians.show-deployedtechnicians', [
            'item' => Deployedtechnicians::where('id', $deployedtechniciansId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deployedtechnicians $deployedtechnicians, $deployedtechniciansId)
    {
        return view('deployedtechnicians.edit-deployedtechnicians', [
            'item' => Deployedtechnicians::where('id', $deployedtechniciansId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeployedtechniciansRequest $request, Deployedtechnicians $deployedtechnicians, $deployedtechniciansId)
    {
        /* Log ************************************************** */
        // $oldName = Deployedtechnicians::where('id', $deployedtechniciansId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated a Deployedtechnicians from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        Deployedtechnicians::where('id', $deployedtechniciansId)->update(['sites_id' => $request->sites_id,'technicians_id' => $request->technicians_id]);

        return back()->with('success', 'Deployedtechnicians Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Deployedtechnicians $deployedtechnicians, $deployedtechniciansId)
    {
        return view('deployedtechnicians.delete-deployedtechnicians', [
            'item' => Deployedtechnicians::where('id', $deployedtechniciansId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deployedtechnicians $deployedtechnicians, $deployedtechniciansId)
    {

        /* Log ************************************************** */
        // $oldName = Deployedtechnicians::where('id', $deployedtechniciansId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a Deployedtechnicians "'.$oldName.'".']);
        /******************************************************** */

        Deployedtechnicians::where('id', $deployedtechniciansId)->update(['isTrash' => '1']);

        return redirect('/deployedtechnicians');
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Deployedtechnicians::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Deployedtechnicians "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Deployedtechnicians::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Deployedtechnicians::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Deployedtechnicians "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Deployedtechnicians::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Deployedtechnicians::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Deployedtechnicians "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Deployedtechnicians::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}