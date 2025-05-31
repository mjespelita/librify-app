<?php

namespace App\Http\Controllers;

use App\Models\{Logs};
use App\Http\Requests\StoreLogsRequest;
use App\Http\Requests\UpdateLogsRequest;
use Illuminate\Http\Request;

class LogsController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('logs.logs', [
            'logs' => Logs::orderBy('id', 'desc')->paginate(10)
        ]);
    }
}