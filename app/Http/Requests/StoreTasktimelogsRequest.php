<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTasktimelogsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_time' => 'nullable|date', // Can be null when paused or stopped
            'pause_time' => 'nullable|date', // Only required when pausing
            'stop_time' => 'nullable|date',  // Only required when stopping
            // 'elapsed_time' => 'required|integer|min:0', // Total tracked time in seconds
            'users_id' => 'required|exists:users,id', // Ensure user exists
            'tasks_id' => 'required|exists:tasks,id', // Ensure task exists
            'tasks_projects_id' => 'required', // Ensure project exists
            'tasks_projects_workspaces_id' => 'required|exists:workspaces,id', // Ensure workspace exists
            'isTrash' => 'boolean',
            // 'status' => 'required|in:running,paused,stopped' // Ensure valid status
        ];
    }
}
