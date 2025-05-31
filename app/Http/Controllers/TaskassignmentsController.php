<?php

namespace App\Http\Controllers;

use App\Models\{Comments, InternalNotification, Logs, Taskassignments, Tasks, Tasktimelogs, User};
use App\Http\Requests\StoreTaskassignmentsRequest;
use App\Http\Requests\UpdateTaskassignmentsRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskassignmentsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('taskassignments.taskassignments', [
            'taskassignments' => Taskassignments::where('isTrash', '0')->paginate(10)
        ]);
    }

    public function trash()
    {
        return view('taskassignments.trash-taskassignments', [
            'taskassignments' => Taskassignments::where('isTrash', '1')->paginate(10)
        ]);
    }

    public function restore($taskassignmentsId)
    {
        /* Log ************************************************** */
        // $oldName = Taskassignments::where('id', $taskassignmentsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Taskassignments "'.$oldName.'".']);
        /******************************************************** */

        Taskassignments::where('id', $taskassignmentsId)->update(['isTrash' => '0']);

        return redirect('/taskassignments');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('taskassignments.create-taskassignments');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskassignmentsRequest $request)
    {
        // logic
        /**
         * if i am the lead assignee and the task is HIGH Priority
         * then loop all through the tasks assignments table
         * and if the tasks_id->task is not a priority
         * then query the task_time_logs table and pause the timer of that task
         */

        $assignee = $request->users_id;
        $isHighPriority = (Tasks::where('id', $request->tasks_id)->value('priority') === 'high') ? true : false;

        $isLeadAssignee = $request->has('isLeadAssignee') ? 1 : 0;

        if ($isLeadAssignee) {
            if ($isHighPriority) {
                $usersTaskAssignments = Taskassignments::where('users_id', $assignee)->where('isLeadAssignee', 1)->get();
                foreach ($usersTaskAssignments as $key => $value) {
                    // $isOldTasksLowPriority = (Tasks::where('id', $value['tasks_id'])->where('status', 'pending')->value('priority') == 'high') ? 1 : 0;
                    $priority = Tasks::where('id', $value['tasks_id'])->where('status', 'pending')->value('priority');
                    if ($priority === 'low') {
                        $taskTimeLog = Tasktimelogs::where('tasks_id', $value['tasks_id'])
                            ->whereNull('stop_time')
                            ->whereNull('pause_time')
                            ->first();
    
                        if ($taskTimeLog) {
                            $latestElapseTime = $taskTimeLog->elapsed_time;
    
                            $startTime = Carbon::parse($taskTimeLog->start_time); // Assuming this is a Carbon instance
                            $now = Carbon::now();
        
                            $diffInSeconds = $startTime->diffInSeconds($now);
        
                            $minus = $diffInSeconds - $latestElapseTime;
        
                            $final = $latestElapseTime + $minus;
        
                            $taskTimeLog->update([
                                'pause_time'   => now(),
                                'elapsed_time' => $final, // Save total elapsed time
                                'status'       => 'paused', // Update status
                            ]);
                        }   
    
                    }
                }
            }
        }

        Taskassignments::create([
            'tasks_id' => $request->tasks_id,
            'tasks_projects_id' => $request->tasks_projects_id,
            'tasks_projects_workspaces_id' => $request->tasks_projects_workspaces_id,
            'users_id' => $request->users_id,
            'role' => $request->role,
            'isLeadAssignee' => $isLeadAssignee
        ]);

        $tasksProjectsId = Tasks::where('id', $request->tasks_id)->value('projects_id');
        $tasksWorkspaceId = Tasks::where('id', $request->tasks_id)->value('projects_workspaces_id');

        // comment notification that i started the timer

        Comments::create([
            'comment' => Auth::user()->name . ' (' . Auth::user()->role . ') assigned ' . 
            User::where('id', $request->users_id)->value('name') . ' as ' . 
            (!$isLeadAssignee ? 'assignee' : 'lead assignee') . ' - '.$request->role,
            'tasks_id' => $request->tasks_id,
            'tasks_projects_id' => $tasksProjectsId,
            'tasks_projects_workspaces_id' => $tasksWorkspaceId,
            'users_id' => Auth::user()->id,
            'hasImage' => 0,
        ]);

        InternalNotification::create([
            'users_senders_id' => Auth::user()->id,
            'tasks_id' => $request->tasks_id,
            'notification' => Auth::user()->name ." (".Auth::user()->role.") assigned " . User::where('id', $request->users_id)->value('name') . " on the task ".Tasks::where('id', $request->tasks_id)->value('name')
        ]);

        /* Log ************************************************** */
        // Logs::create(['log' => Auth::user()->name.' created a new Taskassignments '.'"'.$request->name.'"']);
        /******************************************************** */

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Taskassignments $taskassignments, $taskassignmentsId)
    {
        return view('taskassignments.show-taskassignments', [
            'item' => Taskassignments::where('id', $taskassignmentsId)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Taskassignments $taskassignments, $taskassignmentsId)
    {
        return view('taskassignments.edit-taskassignments', [
            'item' => Taskassignments::where('id', $taskassignmentsId)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskassignmentsRequest $request, Taskassignments $taskassignments, $taskassignmentsId)
    {
        /* Log ************************************************** */
        // $oldName = Taskassignments::where('id', $taskassignmentsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' updated a Taskassignments from "'.$oldName.'" to "'.$request->name.'".']);
        /******************************************************** */

        Taskassignments::where('id', $taskassignmentsId)->update(['tasks_id' => $request->tasks_id,'tasks_projects_id' => $request->tasks_projects_id,'tasks_projects_workspaces_id' => $request->tasks_projects_workspaces_id,'users_id' => $request->users_id,'role' => $request->role]);

        return back();
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Taskassignments $taskassignments, $taskassignmentsId)
    {
        return view('taskassignments.delete-taskassignments', [
            'item' => Taskassignments::where('id', $taskassignmentsId)->first()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Taskassignments $taskassignments, $taskassignmentsId)
    {

        $isLeadAssignee = Taskassignments::where('id', $taskassignmentsId)->value('isLeadAssignee');
        $taskId = Taskassignments::where('id', $taskassignmentsId)->value('tasks_id');
        $userId = Taskassignments::where('id', $taskassignmentsId)->value('users_id');
        
        $isHighPriority = Tasks::where('id', $taskId)->value('priority');

        if ($isLeadAssignee) {
            if ($isHighPriority) {
                $usersTaskAssignments = Taskassignments::where('users_id', $userId)->where('isLeadAssignee', 1)->get();
                foreach ($usersTaskAssignments as $key => $value) {
                    // $isOldTasksLowPriority = (Tasks::where('id', $value['tasks_id'])->where('status', 'pending')->value('priority') == 'high') ? 1 : 0;
                    $priority = Tasks::where('id', $value['tasks_id'])->where('status', 'pending')->value('priority');
                    if ($priority === 'low') {
                        $taskTimeLog = Tasktimelogs::where('tasks_id', $value['tasks_id'])
                            ->whereNotNull('pause_time')
                            ->whereNull('stop_time')
                            ->first();

                        if ($taskTimeLog) {

                            $taskTimeLog->update([
                                'pause_time' => null,   // Clear pause time
                                'status'     => 'running', // Update status
                            ]);
                        }

                    }
                    // echo $priority;
                }
            }
        }

        /* Log ************************************************** */
        // $oldName = Taskassignments::where('id', $taskassignmentsId)->value('name');
        // Logs::create(['log' => Auth::user()->name.' deleted a Taskassignments "'.$oldName.'".']);
        /******************************************************** */

        $tasksProjectsId = Tasks::where('id', $taskId)->value('projects_id');
        $tasksWorkspaceId = Tasks::where('id', $taskId)->value('projects_workspaces_id');

        // comment notification that i started the timer

        Comments::create([
            'comment' => Auth::user()->name . ' ('.Auth::user()->role.') removed '.User::where('id', $userId)->value('name').' as assignee.',
            'tasks_id' => $taskId,
            'tasks_projects_id' => $tasksProjectsId,
            'tasks_projects_workspaces_id' => $tasksWorkspaceId,
            'users_id' => Auth::user()->id,
            'hasImage' => 0,
        ]);

        Taskassignments::where('id', $taskassignmentsId)->delete();

        InternalNotification::create([
            'users_senders_id' => Auth::user()->id,
            'tasks_id' => $taskId,
            'notification' => Auth::user()->name ." (".Auth::user()->role.") removed " . User::where('id', $userId)->value('name') . " as assignee on the task ".Tasks::where('id', $taskId)->value('name')
        ]);

        return back();
    }

    public function bulkDelete(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Taskassignments::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' deleted a Taskassignments "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Taskassignments::find($value);
            $deletable->delete();
        }
        return response()->json("Deleted");
    }

    public function bulkMoveToTrash(Request $request) {

        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Taskassignments::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a Taskassignments "'.$oldName.'".']);
            /******************************************************** */

            $deletable = Taskassignments::find($value);
            $deletable->update(['isTrash' => '1']);
        }
        return response()->json("Deleted");
    }

    public function bulkRestore(Request $request)
    {
        foreach ($request->ids as $value) {

            /* Log ************************************************** */
            // $oldName = Taskassignments::where('id', $value)->value('name');
            // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a Taskassignments "'.$oldName.'".']);
            /******************************************************** */

            $restorable = Taskassignments::find($value);
            $restorable->update(['isTrash' => '0']);
        }
        return response()->json("Restored");
    }
}