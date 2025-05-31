<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentsRequest extends FormRequest
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
            //
            'comment' => 'required',
            'tasks_id' => 'required',
            'tasks_projects_id' => 'required',
            'tasks_projects_workspaces_id' => 'required',
            'users_id' => 'required',
            'files.*' => 'mimes:jpg,jpeg,png,gif,webp,xlsx,xls,csv,pdf,doc,docx',
        ];
    }
}
