<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Workspaces};
use App\Http\Requests\StoreWorkspacesRequest;
use App\Http\Requests\UpdateWorkspacesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspacesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addedByAdminType = Auth::user()->role;

        return view('workspaces.workspaces', [
            'workspaces' => (Auth::user()->role === 'admin') ?

            Workspaces::where('isTrash', '0')->paginate(10) :

            Workspaces::where(function ($query) use ($addedByAdminType) {
                    $query->where('added_by_admin_type', $addedByAdminType)
                        ->orWhere('added_by_admin_type', 'admin');
                })
                ->where('isTrash', '0')
                ->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('workspaces.trash-workspaces', [
            'workspaces' => Workspaces::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($workspacesId)
    {
        /* Log ************************************************** */
        // $oldName = Workspaces::where('id', $workspacesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Workspaces "'.$oldName.'".']);
        /******************************************************** */

        Workspaces::where('id', $workspacesId)->update(['isTrash' => '0']);

        return redirect('/workspaces');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('workspaces.create-workspaces');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkspacesRequest $request)
    {

        $addedByAdminType = Auth::user()->role;

        Workspaces::create([
            'name' => $request->name,
            'added_by_admin_type' => $addedByAdminType,
        ]);

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' created a new Workspaces '.'"'.$request->name.'"']);
        /******************************************************** */

        return back()->with('success', 'Workspaces Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspaces $workspaces, $workspacesId)
    {
        return view('workspaces.show-workspaces', [
            'item' => Workspaces::where('id', $workspacesId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workspaces $workspaces, $workspacesId)
    {
        return view('workspaces.edit-workspaces', [
            'item' => Workspaces::where('id', $workspacesId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkspacesRequest $request, Workspaces $workspaces, $workspacesId)
    {
        /* Log ************************************************** */
        // $oldName = Workspaces::where('id', $workspacesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated a Workspaces from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        $workspace = Workspaces::findOrFail($workspacesId);

        $workspace->name = $request->name;

        $workspace->save();

        return back()->with('success', 'Workspaces Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Workspaces $workspaces, $workspacesId)
    {
        return view('workspaces.delete-workspaces', [
            'item' => Workspaces::where('id', $workspacesId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspaces $workspaces, $workspacesId)
    {

        /* Log ************************************************** */
        // $oldName = Workspaces::where('id', $workspacesId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a Workspaces "'.$oldName.'".']);
        /******************************************************** */

        Workspaces::where('id', $workspacesId)->update(['isTrash' => '1']);

        return redirect('/workspaces');
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Workspaces::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Workspaces "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Workspaces::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Workspaces::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Workspaces "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Workspaces::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Workspaces::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Workspaces "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Workspaces::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}
