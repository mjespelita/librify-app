<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Projects};
use App\Http\Requests\StoreProjectsRequest;
use App\Http\Requests\UpdateProjectsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adminRole = Auth::user()->role;

        return view('projects.projects', [
            'projects' => ($adminRole === 'admin') ?

                Projects::where('isTrash', '0')->paginate(10) :

                Projects::whereHas('workspaces', function ($query) use ($adminRole) {
                        $query->where('added_by_admin_type', $adminRole)
                            ->orWhere('added_by_admin_type', 'admin');
                    })
                    ->where('isTrash', '0')
                    ->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('projects.trash-projects', [
            'projects' => Projects::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($projectsId)
    {
        /* Log ************************************************** */
        // $oldName = Projects::where('id', $projectsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Projects "'.$oldName.'".']);
        /******************************************************** */

        Projects::where('id', $projectsId)->update(['isTrash' => '0']);

        return redirect('/projects');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create-projects');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectsRequest $request)
    {
        Projects::create(['name' => $request->name,'workspaces_id' => $request->workspaces_id]);

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' created a new Projects '.'"'.$request->name.'"']);
        /******************************************************** */

        return back()->with('success', 'Projects Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Projects $projects, $projectsId)
    {
        return view('projects.show-projects', [
            'item' => Projects::where('id', $projectsId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Projects $projects, $projectsId)
    {
        return view('projects.edit-projects', [
            'item' => Projects::where('id', $projectsId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectsRequest $request, Projects $projects, $projectsId)
    {
        /* Log ************************************************** */
        // $oldName = Projects::where('id', $projectsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated a Projects from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        Projects::where('id', $projectsId)->update(['name' => $request->name,'workspaces_id' => $request->workspaces_id]);

        return back()->with('success', 'Projects Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Projects $projects, $projectsId)
    {
        return view('projects.delete-projects', [
            'item' => Projects::where('id', $projectsId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Projects $projects, $projectsId)
    {

        /* Log ************************************************** */
        // $oldName = Projects::where('id', $projectsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a Projects "'.$oldName.'".']);
        /******************************************************** */

        Projects::where('id', $projectsId)->update(['isTrash' => '1']);

        return redirect('/projects');
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Projects::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Projects "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Projects::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Projects::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Projects "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Projects::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Projects::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Projects "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Projects::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}