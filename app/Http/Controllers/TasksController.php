<?php

namespace App\Http\Controllers;

use App\Models\{Logs, Taskassignments, Tasks};
use App\Http\Requests\StoreTasksRequest;
use App\Http\Requests\UpdateTasksRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adminRole = Auth::user()->role;

        return view('tasks.tasks', [
            'tasks' => ($adminRole == 'admin') ?

                Tasks::where('isTrash', '0')->orderBy('id', 'desc')->paginate(12) :

                Tasks::whereHas('workspaces', function ($query) use ($adminRole) {
                        $query->where('added_by_admin_type', $adminRole)
                            ->orWhere('added_by_admin_type', 'admin');
                    })
                    ->where('isTrash', '0')
                    ->orderBy('id', 'desc')->paginate(12)
        ]);
    }

    public function myTasks()
    {
        return view('technicians.my-tasks', [
            'tasks' => Taskassignments::where('users_id', Auth::user()->id)
                                      ->whereDate('created_at', Carbon::today())
                                      ->orderBy('id', 'desc')
                                      ->paginate(20),
        
            'unfinished_tasks' => Taskassignments::where('users_id', Auth::user()->id)
                ->whereHas('tasks', function ($query) {
                    $query->where('status', 'pending');
                })
                ->orderBy('id', 'desc')
                ->paginate(20),
        
            'public_tasks' => Taskassignments::whereHas('tasks', function ($query) {
                    $query->where('isPrivate', false); // Only include public tasks
                })
                ->orderBy('id', 'desc')
                ->paginate(20),
        ]);        
    }

    public function trash()
    {
        return view('tasks.trash-tasks', [
            'tasks' => Tasks::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($tasksId)
    {
        /* Log ************************************************** */
        // $oldName = Tasks::where('id', $tasksId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Tasks "'.$oldName.'".']);
        /******************************************************** */

        Tasks::where('id', $tasksId)->update(['isTrash' => '0']);

        return redirect('/tasks');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create-tasks');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTasksRequest $request)
    {
        Tasks::create([
            'name' => $request->name,
            'status' => $request->status,
            'projects_id' => $request->projects_id,
            'projects_workspaces_id' => $request->projects_workspaces_id,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'isScheduled' => $request->isScheduled,
            'isPrivate' => $request->audience,
        ]);

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' created a new Tasks '.'"'.$request->name.'"']);
        /******************************************************** */

        return back()->with('success', 'Tasks Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tasks $tasks, $tasksId)
    {
        return view('tasks.show-tasks', [
            'item' => Tasks::where('id', $tasksId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tasks $tasks, $tasksId)
    {
        return view('tasks.edit-tasks', [
            'item' => Tasks::where('id', $tasksId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTasksRequest $request, Tasks $tasks, $tasksId)
    {
        /* Log ************************************************** */
        // $oldName = Tasks::where('id', $tasksId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated a Tasks from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        Tasks::where('id', $tasksId)->update([
            'name' => $request->name,
            'status' => $request->status,
            'projects_id' => $request->projects_id,
            'projects_workspaces_id' => $request->projects_workspaces_id,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'isPrivate' => $request->audience,
        ]);

        return back()->with('success', 'Tasks Updated Successfully!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Tasks $tasks, $tasksId)
    {
        return view('tasks.delete-tasks', [
            'item' => Tasks::where('id', $tasksId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tasks $tasks, $tasksId)
    {

        /* Log ************************************************** */
        // $oldName = Tasks::where('id', $tasksId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a Tasks "'.$oldName.'".']);
        /******************************************************** */

        Tasks::where('id', $tasksId)->update(['isTrash' => '1']);

        return redirect('/tasks');
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Tasks::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Tasks "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Tasks::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Tasks::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Tasks "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Tasks::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Tasks::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Tasks "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Tasks::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}