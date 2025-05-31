<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Workspaceusers};
use App\Http\Requests\StoreWorkspaceusersRequest;
use App\Http\Requests\UpdateWorkspaceusersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceusersController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('workspaceusers.workspaceusers', [
            'workspaceusers' => Workspaceusers::where('isTrash', '0')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('workspaceusers.trash-workspaceusers', [
            'workspaceusers' => Workspaceusers::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($workspaceusersId)
    {
        /* Log ************************************************** */
        $oldName = Workspaceusers::where('id', $workspaceusersId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Workspaceusers "'.$oldName.'".']);
        /******************************************************** */

        Workspaceusers::where('id', $workspaceusersId)->update(['isTrash' => '0']);

        return redirect('/workspaceusers');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('workspaceusers.create-workspaceusers');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkspaceusersRequest $request)
    {
        Workspaceusers::create(['users_id' => $request->users_id,'workspaces_id' => $request->workspaces_id]);

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' created a new Workspaceusers '.'"'.$request->name.'"']);
        /******************************************************** */

        return back()->with('success', 'Workspaceusers Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspaceusers $workspaceusers, $workspaceusersId)
    {
        return view('workspaceusers.show-workspaceusers', [
            'item' => Workspaceusers::where('id', $workspaceusersId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workspaceusers $workspaceusers, $workspaceusersId)
    {
        return view('workspaceusers.edit-workspaceusers', [
            'item' => Workspaceusers::where('id', $workspaceusersId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkspaceusersRequest $request, Workspaceusers $workspaceusers, $workspaceusersId)
    {
        /* Log ************************************************** */
        $oldName = Workspaceusers::where('id', $workspaceusersId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated a Workspaceusers from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        Workspaceusers::where('id', $workspaceusersId)->update(['users_id' => $request->users_id,'workspaces_id' => $request->workspaces_id]);

        return back()->with('success', 'Workspaceusers Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Workspaceusers $workspaceusers, $workspaceusersId)
    {
        return view('workspaceusers.delete-workspaceusers', [
            'item' => Workspaceusers::where('id', $workspaceusersId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspaceusers $workspaceusers, $workspaceusersId)
    {

        /* Log ************************************************** */
        // $oldName = Workspaceusers::where('id', $workspaceusersId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a Workspaceusers "'.$oldName.'".']);
        /******************************************************** */

        Workspaceusers::where('id', $workspaceusersId)->delete();

        return back()->with('success', 'Participant Removed Successfully!');
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            $oldName = Workspaceusers::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Workspaceusers "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Workspaceusers::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            $oldName = Workspaceusers::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Workspaceusers "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Workspaceusers::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            $oldName = Workspaceusers::where('id', $value)->value('name');
            Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Workspaceusers "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Workspaceusers::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}