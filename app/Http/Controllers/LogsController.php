<?php

namespace App\Http\Controllers;

use App\Models\{Logs};
use App\Http\Requests\StoreLogsRequest;
use App\Http\Requests\UpdateLogsRequest;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class LogsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $audits = Audit::latest()->paginate(10);
        return view('logs.logs', [
            'audits' => $audits
        ]);
    }
}
