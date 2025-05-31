<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

// end of import

use App\Http\Controllers\LogsController;
use App\Models\Logs;

// end of import

use App\Http\Controllers\TypesController;
use App\Models\Types;

// end of import

use App\Http\Controllers\ItemsController;
use App\Models\Items;

// end of import

use App\Http\Controllers\SitesController;
use App\Models\Sites;

// end of import

use App\Http\Controllers\TechniciansController;
use App\Models\Technicians;

// end of import

use App\Http\Controllers\ItemlogsController;
use App\Models\Itemlogs;

// end of import

use App\Http\Controllers\OnsitesController;
use App\Models\Onsites;
use App\Models\User;

// end of import

use App\Http\Controllers\DamagesController;
use App\Http\Controllers\RandomController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\TechnicianMiddleware;
use App\Models\Damages;

// end of import

use App\Http\Controllers\DeployedtechniciansController;
use App\Models\Deployedtechnicians;

// end of import

use App\Http\Controllers\WorkspacesController;
use App\Models\Workspaces;

// end of import

use App\Http\Controllers\ProjectsController;
use App\Models\Projects;

// end of import

use App\Http\Controllers\TasksController;
use App\Models\Tasks;

// end of import

use App\Http\Controllers\WorkspaceusersController;
use App\Models\Workspaceusers;

// end of import

use App\Http\Controllers\CommentsController;
use App\Models\Comments;

// end of import

use App\Http\Controllers\TaskassignmentsController;
use App\Models\Taskassignments;

// end of import

use App\Http\Controllers\TasktimelogsController;
use App\Mail\FromWebsiteMailer;
use App\Models\Tasktimelogs;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Smark\Smark\Dater;
use Smark\Smark\Excel;
use Smark\Smark\JSON;
use Smark\Smark\PDFer;

// end of import

Route::get('/', function () {
    return redirect('/dashboard');
});

// Send Email from website

Route::post('/send-email-from-website', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required',
        'subject' => 'required',
        'message' => 'required',
    ]);

    Mail::to('mark.medivadigital@gmail.com')
        ->send(new FromWebsiteMailer(
            $request->name,
            $request->email,
            $request->subject,
            $request->message,
        ));

    return "OK";
});

// public tasks api for task board

Route::get('/task-board', function () {
    $tasks = Tasks::with(['projects', 'workspaces'])->orderBy('id', 'desc')->get();

    $data = $tasks->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'priority' => $item->priority,
            'isScheduled' => $item->isScheduled,
            'isPrivate' => $item->isPrivate,
            'status' => $item->status,
            'created_at' => Dater::humanReadableDateWithDayAndTime($item->created_at),
            'project' => $item->projects?->name ?? 'No Project',
            'project_id' => $item->projects?->id ?? null,
            'workspace' => $item->workspaces?->name ?? 'No Workspace',
            'workspace_id' => $item->workspaces?->id ?? null,
            'assignees' => Taskassignments::where('tasks_id', $item->id)->get()->map(function ($ta) {
                return [
                    'id' => $ta->users_id,
                    'photo' => $ta->users?->profile_photo_path
                        ? url('/storage/' . $ta->users->profile_photo_path)
                        : url('/assets/profile_photo_placeholder.png'),
                ];
            }),
        ];
    });

    return response()->json($data);
});

// Experemental

Route::post('/experemental-ai', function (Request $request) {
    $body = [
        "messages" => [
            ["role" => "user", "content" => $request->message]
        ],
        "max_tokens" => 1000
    ];

    $response = Http::withOptions([
        'verify' => false,
    ])->post('http://localhost:1234/v1/chat/completions', $body);

    return response()->json($response->json());
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware(AuthMiddleware::class);
    
    Route::get('/admin-dashboard', function () {
        if (Auth::user()->role != 'office_admin') {
            return view('admin-dashboard');
        } else {
            return redirect('/workspaces');
        }
    })->middleware(AdminMiddleware::class);

    Route::get('/employee-dashboard', function () {
        return view('technician-dashboard');
    })->middleware(TechnicianMiddleware::class);

    // CUSTOMS

    Route::get('/create-add-item-quantity/{itemId}', [ItemsController::class, 'addItemQuantity'])->middleware(AdminMiddleware::class);
    Route::post('/add-item-quantity-process/{itemId}', [ItemsController::class, 'addItemQuantityProcess'])->middleware(AdminMiddleware::class);
    Route::get('/view-add-item-quantity-logs/{itemId}', [ItemsController::class, 'viewAddItemQuantityLogs'])->middleware(AdminMiddleware::class);
    Route::get('/view-technician-onsite-items/{userId}', [OnsitesController::class, 'viewTechnicianOnsiteItems'])->middleware(AdminMiddleware::class);
    Route::get('/view-technician-damage-items/{userId}', [DamagesController::class, 'viewTechnicianDamageItems'])->middleware(AdminMiddleware::class);

    // technicians

    Route::get('/my-onsite-items/{userId}', [RandomController::class, 'myOnsiteItems']);
    Route::get('/view-my-onsite-items-on-site/{siteId}', [RandomController::class, 'viewMyOnsiteItemsOnSite']);
    Route::get('/my-damaged-items/{userId}', [RandomController::class, 'myDamagedItems']);
    Route::get('/view-my-damaged-items-on-site/{siteId}', [RandomController::class, 'viewMyDamagedItemsOnSite']);
    Route::get('/my-sites', [RandomController::class, 'mySites']);
    Route::get('/my-tasks', [TasksController::class, 'myTasks']);
    Route::get('/notifications', function () {
        return view('notifications');
    });

    Route::post('/update-employee-profile/{userId}', [TechniciansController::class, 'updateEmployeeProfile']);

    Route::get('/my-tasks-filter', function (Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');
    
        $query = Taskassignments::query();
    
        // Convert input dates to Carbon instances (ensuring full day range)
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Filter tasks by date range
        if ($fromDate && $toDate) {
            $tasks = $query->where('users_id', Auth::user()->id)
                           ->whereBetween('created_at', [$fromDate, $toDate])
                           ->paginate(20);
        } else {
            $tasks = $query->where('users_id', Auth::user()->id)
                           ->groupBy('tasks_id')
                           ->paginate(10);
        }
    
        // ✅ Query all pending tasks within the selected range
        $unfinished_tasks = Taskassignments::where('users_id', Auth::user()->id)
            ->whereHas('tasks', function ($query) {
                $query->where('status', 'pending');
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->orderBy('id', 'desc')
            ->paginate(100);

            $public_tasks = Taskassignments::whereHas('tasks', function ($query) {
                    $query->where('isPrivate', false); // Only include public tasks
                })
                ->orderBy('id', 'desc')
                ->paginate(20);
    
        return view('technicians.my-tasks', compact('tasks', 'unfinished_tasks', 'public_tasks', 'from', 'to'));
    });

    Route::get('/admin-my-tasks-filter', function (Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');
        $userId = $request->input('user') ?? Auth::id(); // Use requested user or authenticated user
    
        // Convert input dates to Carbon instances
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Fetch user details
        $user = User::findOrFail($userId);
    
        // Query task assignments within the date range (or all if no range)
        $taskAssignmentsQuery = Taskassignments::where('users_id', $userId);
        if ($fromDate && $toDate) {
            $taskAssignmentsQuery->whereBetween('created_at', [$fromDate, $toDate]);
        } else {
            $taskAssignmentsQuery->whereDate('created_at', Carbon::today()); // Default to today
        }
    
        $taskAssignments = $taskAssignmentsQuery->orderBy('id', 'desc')->paginate(20);
    
        // Query unfinished task assignments (pending status)
        $unfinished_taskAssignmentsQuery = Taskassignments::where('users_id', $userId)
            ->whereHas('tasks', function ($query) {
                $query->where('status', 'pending'); 
            });
    
        if ($fromDate && $toDate) {
            $unfinished_taskAssignmentsQuery->whereBetween('created_at', [$fromDate, $toDate]);
        } else {
            $unfinished_taskAssignmentsQuery->whereDate('created_at', Carbon::today());
        }
    
        $unfinished_taskAssignments = $unfinished_taskAssignmentsQuery->orderBy('id', 'desc')->paginate(20);
    
        return view('technicians.show-technicians', [
            'item' => $user,
            'taskAssignments' => $taskAssignments,
            'unfinished_taskAssignments' => $unfinished_taskAssignments,
            'from' => $from,
            'to' => $to
        ]);
    });

    Route::get('/backups', function () {
        // Path to the backups folder
        $backupFolder = public_path('backup'); // Adjust the path as needed
        $files = File::allFiles($backupFolder);

        return view('backups', compact('files'));
    });
    
    Route::get('/backup-process', function () {
        // Call the backup artisan command
        Artisan::call('backup');

        // Optional: show the output in the browser
        $output = Artisan::output();

        // Return to a view or just show confirmation
        return redirect('/backups')->with('success', '✅ Backup completed.')->with('output', $output);
    });

    Route::get('/show-employee-performance-details/{userId}', function ($userId) {
        return view('technicians.show-employee-performance-details', [
            'item' => User::where('id', $userId)->first(),
            'taskAssignments' => Taskassignments::where('users_id', $userId)
                                      ->whereDate('created_at', Carbon::today())
                                      ->orderBy('id', 'desc')
                                      ->paginate(20),
            'unfinished_taskAssignments' => Taskassignments::where('users_id', $userId)
                ->whereHas('tasks', function ($query) {
                    $query->where('status', 'pending');
                })->orderBy('id', 'desc')->paginate(20),
        ]);
    });

    Route::get('/export-employee-performance-details/{userIds}', function ($userIds) {
        $user = User::where('id', $userIds)->first();
        return PDFer::exportEmployeePerformanceDetails([
            [
                'ID' => 1,
                'Name' => 'Mark Jason',
                'Email' => 'mark@gmail.com',
            ]
        ]);
    });

    // use Illuminate\Support\Carbon;

    Route::post('/bulk-export-employee-tasks', function (Request $request) {
        $userIds = $request->input('user_ids', []);
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
    
        if (empty($userIds)) {
            return back()->with('error', 'No users selected.');
        }
    
        $users = User::whereIn('id', $userIds)->get();
        $excelData = [];
    
        // Excel Header
        $header = [
            'Name',
            'Email',
            'Role',
            'Task Title',
            'Priority',
            'Scheduled',
            'Audience',
            'Created Date',
            'Due Date',
            'Relative Deadline',
            'Status',
        ];
        $excelData[] = $header;
    
        foreach ($users as $user) {
            $assignments = Taskassignments::where('users_id', $user->id)->get();
            $taskRows = [];
    
            foreach ($assignments as $assignment) {
                $task = Tasks::find($assignment->tasks_id);
    
                if ($task && $task->created_at) {
                    $taskDate = Carbon::parse($task->created_at);
                    $withinRange = true;
    
                    if ($fromDate && $toDate) {
                        $withinRange = $taskDate->between($fromDate, $toDate);
                    }
    
                    if ($withinRange) {
                        $taskRows[] = [
                            'created_at' => $task->created_at,
                            'data' => [
                                $user->name ?? '',
                                $user->email ?? '',
                                $user->role ? ucfirst(str_replace('_', ' ', $user->role)) : '',
                                $task->name ?? '',
                                $task->priority ? ucfirst($task->priority) : '',
                                isset($task->isScheduled) ? ($task->isScheduled ? 'Yes' : 'No') : '',
                                isset($task->isPrivate) ? ($task->isPrivate ? 'Private' : 'Public') : '',
                                Dater::humanReadableDateWithDayAndTime($task->created_at),
                                $task->deadline ? Dater::humanReadableDateWithDayAndTime($task->deadline) : '',
                                $task->deadline ? Carbon::parse($task->deadline)->diffForHumans() : '',
                                $task->status ?? '',
                            ]
                        ];
                    }
                }
            }
    
            // Sort task rows by creation date (descending)
            $sortedTasks = collect($taskRows)->sortByDesc('created_at');
    
            if ($sortedTasks->isEmpty()) {
                $excelData[] = [
                    $user->name ?? '',
                    $user->email ?? '',
                    $user->role ? ucfirst(str_replace('_', ' ', $user->role)) : '',
                    '', '', '', '', '', '', '', '',
                ];
            } else {
                foreach ($sortedTasks as $taskRow) {
                    $excelData[] = $taskRow['data'];
                }
            }
        }
    
        return Excel::_downloadEmployeeTasksInExcel(
            Dater::humanReadableDate($fromDate),
            Dater::humanReadableDate($toDate),
            $header,
            array_slice($excelData, 1) // Skip header, it's already added
        );
    });

    Route::post('/export-employee-tasks-details/{id}', function ($id, Request $request) {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
    
        $user = User::findOrFail($id);
    
        $excelData = [];
    
        $header = [
            'Name',
            'Email',
            'Role',
            'Task Title',
            'Priority',
            'Scheduled',
            'Audience',
            'Created Date',
            'Due Date',
            'Relative Deadline',
            'Status',
        ];
        $excelData[] = $header;
    
        $assignments = Taskassignments::where('users_id', $user->id)->get();
        $userHasData = false;
    
        foreach ($assignments as $assignment) {
            $task = Tasks::find($assignment->tasks_id);
    
            if ($task) {
                $taskDate = $task->created_at ? Carbon::parse($task->created_at) : null;
                $withinRange = true;
    
                if ($fromDate && $toDate && $taskDate) {
                    $withinRange = $taskDate->between($fromDate, $toDate);
                }
    
                if ($withinRange) {
                    $row = [
                        $user->name ?? '',
                        $user->email ?? '',
                        $user->role ? ucfirst(str_replace('_', ' ', $user->role)) : '',
                        $task->name ?? '',
                        $task->priority ? ucfirst($task->priority) : '',
                        isset($task->isScheduled) ? ($task->isScheduled ? 'Yes' : 'No') : '',
                        isset($task->isPrivate) ? ($task->isPrivate ? 'Private' : 'Public') : '',
                        $task->created_at ? Dater::humanReadableDateWithDayAndTime($task->created_at) : '',
                        $task->deadline ? Dater::humanReadableDateWithDayAndTime($task->deadline) : '',
                        $task->deadline ? Carbon::parse($task->deadline)->diffForHumans() : '',
                        $task->status ?? '',
                    ];
    
                    $excelData[] = $row;
                    $userHasData = true;
                }
            }
        }
    
        // If user has no task within range, add a blank row with user info
        if (!$userHasData) {
            $excelData[] = [
                $user->name ?? '',
                $user->email ?? '',
                $user->role ? ucfirst(str_replace('_', ' ', $user->role)) : '',
                '', '', '', '', '', '', '', '',
            ];
        }
    
        return Excel::_downloadEmployeeTasksInExcel(
            Dater::humanReadableDate($fromDate),
            Dater::humanReadableDate($toDate),
            $header,
            array_slice($excelData, 1) // Remove header to avoid duplication
        );
    });
    


    // API

    Route::get('/get-employee-task-count/{users_id}', function ($users_id) {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
    
        $taskCounts = [];
    
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $dateString = $date->toDateString(); // Format: YYYY-MM-DD
    
            // Count pending task assignments for the user on this date
            $pendingTasks = Taskassignments::where('users_id', $users_id)
                ->whereHas('tasks', function ($query) {
                    $query->where('status', 'pending');
                })
                ->whereDate('created_at', $dateString)
                ->count();
    
            // Count completed task assignments for the user on this date
            $completedTasks = Taskassignments::where('users_id', $users_id)
                ->whereHas('tasks', function ($query) {
                    $query->where('status', 'completed');
                })
                ->whereDate('created_at', $dateString)
                ->count();
    
            $taskCounts[] = [
                'date' => $dateString,
                'pending' => $pendingTasks ?: 0, // Ensure zero if no tasks
                'completed' => $completedTasks ?: 0,
            ];
        }
    
        return response()->json($taskCounts);
    });

    // With input

    Route::get('/get-employee-task-count-by-month/{users_id}/{month}/{year}', function ($users_id, $month, $year) {
        try {
            // Get start and end of the provided month and year
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();
    
            $taskCounts = [];
    
            for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                $dateString = $date->toDateString(); // Format: YYYY-MM-DD
    
                // Count pending task assignments for the user on this date
                $pendingTasks = Taskassignments::where('users_id', $users_id)
                    ->whereHas('tasks', function ($query) {
                        $query->where('status', 'pending');
                    })
                    ->whereDate('created_at', $dateString)
                    ->count();
    
                // Count completed task assignments for the user on this date
                $completedTasks = Taskassignments::where('users_id', $users_id)
                    ->whereHas('tasks', function ($query) {
                        $query->where('status', 'completed');
                    })
                    ->whereDate('created_at', $dateString)
                    ->count();
    
                $taskCounts[] = [
                    'date' => $dateString,
                    'pending' => $pendingTasks ?: 0,
                    'completed' => $completedTasks ?: 0,
                ];
            }
    
            return response()->json($taskCounts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid input or server error', 'message' => $e->getMessage()], 400);
        }
    });
    
    
    Route::get('/get-item-data-for-graph', function () {
        return response()->json([
            'inWarehousesCount' => Items::sum('quantity') - Onsites::sum('quantity') - Damages::sum('quantity'),
            'onSitesCount' => Onsites::sum('quantity'),
            'damagesCount' => Damages::sum('quantity'),
        ]);
    });

    Route::get('/get-item-data-for-stats', function () {
        $onsiteItems = Onsites::all();
        $damagedItems = Damages::all();
        $final = [];
        $onsiteArray = [];
        $damagesArray = [];

        foreach ($onsiteItems as $key => $onsite) {
            $itemId = $onsite->items->id ?? "";
            $itemName = $onsite->items->name ?? "";
            $count = $onsite['quantity'];

            array_push($onsiteArray, [
                'id' => $itemId,
                'name' => $itemName,
                'count' => $count,
            ]);
        }

        array_push($final, [
            'onsites' => $onsiteArray
        ]);

        foreach ($damagedItems as $key => $damaged) {
            $itemId = $onsite->items->id ?? "";
            $itemName = $damaged->items->name ?? "";
            $count = $damaged['quantity'];

            array_push($damagesArray, [
                'id' => $itemId,
                'name' => $itemName,
                'count' => $count,
            ]);
        }

        array_push($final, [
            'damages' => $damagesArray
        ]);

        return response()->json([
            'final' => $final
        ]);
    });

    Route::get('/get-item-serial-numbers', function () {
        // Fetch all items from the Items model
        $allItems = Items::all();
    
        // Fetch the serial numbers from onsites and damages tables
        $onsites = Onsites::pluck('serial_numbers')->toArray();
        $damages = Damages::pluck('serial_numbers')->toArray();
    
        // Initialize an array to hold all serial numbers from onsites and damages
        $excludedSerialNumbers = [];
    
        // Loop through each record in onsites and damages to extract the serial numbers
        foreach ($onsites as $onsite) {
            // Split the serial numbers string by commas and clean up the whitespace
            $excludedSerialNumbers = array_merge($excludedSerialNumbers, array_map('trim', explode(',', $onsite)));
        }
    
        foreach ($damages as $damage) {
            // Split the serial numbers string by commas and clean up the whitespace
            $excludedSerialNumbers = array_merge($excludedSerialNumbers, array_map('trim', explode(',', $damage)));
        }
    
        // Remove duplicate serial numbers (optional)
        $excludedSerialNumbers = array_unique($excludedSerialNumbers);
    
        // Initialize an array to store serial numbers from allItems that are not in excludedSerialNumbers
        $validSerialNumbers = [];
    
        // Loop through each item in allItems and check if its serial numbers are not in the excluded list
        foreach ($allItems as $item) {
            // Split the serial numbers string by commas and clean up the whitespace
            $itemSerialNumbers = explode(',', $item->serial_numbers);
    
            // Filter the serial numbers to include only those not in the excludedSerialNumbers array
            $validSerialNumbers = array_merge($validSerialNumbers, array_filter($itemSerialNumbers, function ($serialNumber) use ($excludedSerialNumbers) {
                return !in_array(trim($serialNumber), $excludedSerialNumbers);
            }));
        }
    
        // Remove duplicate serial numbers
        $validSerialNumbers = array_unique($validSerialNumbers);
    
        // Return the serial numbers as an array (not an object)
        return response()->json([
            'serial_numbers' => array_values($validSerialNumbers), // Ensure it's an indexed array
        ], 200); // Optionally specify the status code
    });

    Route::get('/comments/{taskId}', function ($taskId, Request $request) {
        $authId = Auth::user()->id; // Get authenticated user ID
    
        $comments = Comments::where('tasks_id', $taskId)
            ->with(['users', 'files'])
            ->latest()
            ->get()
            ->map(function ($comment) use ($authId) {
                return [
                    'id' => $comment->id,
                    'comment_position' => $authId === $comment->users_id ? 'left' : 'right',
                    'users_id'   => $comment->users_id,
                    'auth_id'    => $authId, // Include authenticated user ID
                    'user'       => [
                        'name'               => $comment->users?->name ?? 'No Name',
                        'profile_photo_path' => $comment->users?->profile_photo_path,
                    ],
                    'comment'    => $comment->comment,
                    'created_at' => $comment->created_at,
                    'hasImage'   => $comment->hasImage,
                    'files'      => $comment->files->map(fn ($file) => ['file' => $file->file]),
                ];
            });
    
        return response()->json($comments);
    });

    Route::get('/get-tasktimelogs/{taskId}', function ($tasksId) {
        return response()->json([
            'taskTimeLog' => Tasktimelogs::where('tasks_id', $tasksId)->first()
        ]);
    });

    // PROXY

    Route::get('/omada-customers', function () {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer PtUAhZwm1u0YSu7iXJhxUHZdAvhrJTk0fuTFjElTb717062d', // Replace with your API key
        ])->withOptions([
            'verify' => false,
        ])->get('https://omada.librifyits.com/api/customers');

        $omadaCustomers = json_decode(json_encode($response->json()));
    
        return view('omada.omada-customers', [
            'omadaCustomers' => $omadaCustomers
        ]);
    });

    Route::get('/omada-sites', function () {
        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer PtUAhZwm1u0YSu7iXJhxUHZdAvhrJTk0fuTFjElTb717062d', // Replace with your API key
        // ])->withOptions([
        //     'verify' => false,
        // ])->get('https://omada.librifyits.com/api/sites');

        $omadaSites = JSON::jsonRead('omada/sites.json');
    
        return view('omada.omada-sites', [
            'omadaSites' => $omadaSites
        ]);
    });

    Route::get('/omada-sites-statistics/{siteId}', function ($siteId) {
        $sites = JSON::jsonRead('omada/sites.json');
        $targetSite = null;
    
        foreach ($sites as $site) {
            if ($site['siteId'] === $siteId) {
                $targetSite = (object) $site; // Cast to object here
                break; // Optional: stop the loop once found
            }
        }
    
        return view('omada.omada-sites-statistics', [
            'item' => $targetSite
        ]);
    });

    Route::get('/omada-audit-logs', function () {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer PtUAhZwm1u0YSu7iXJhxUHZdAvhrJTk0fuTFjElTb717062d', // Replace with your API key
        ])->withOptions([
            'verify' => false,
        ])->get('https://omada.librifyits.com/api/audit-logs');
    
        $omadaAuditLogs = json_decode(json_encode($response->json()));
    
        return view('omada.omada-audit-logs', [
            'omadaAuditLogs' => $omadaAuditLogs
        ]);
    });

    // end...

    Route::get('/logs', [LogsController::class, 'index'])->name('logs.index')->middleware(AdminMiddleware::class);
    Route::get('/create-logs', [LogsController::class, 'create'])->name('logs.create')->middleware(AdminMiddleware::class);
    Route::get('/edit-logs/{logsId}', [LogsController::class, 'edit'])->name('logs.edit')->middleware(AdminMiddleware::class);
    Route::get('/show-logs/{logsId}', [LogsController::class, 'show'])->name('logs.show')->middleware(AdminMiddleware::class);
    Route::get('/delete-logs/{logsId}', [LogsController::class, 'delete'])->name('logs.delete')->middleware(AdminMiddleware::class);
    Route::get('/destroy-logs/{logsId}', [LogsController::class, 'destroy'])->name('logs.destroy')->middleware(AdminMiddleware::class);
    Route::post('/store-logs', [LogsController::class, 'store'])->name('logs.store')->middleware(AdminMiddleware::class);
    Route::post('/update-logs/{logsId}', [LogsController::class, 'update'])->name('logs.update')->middleware(AdminMiddleware::class);
    Route::post('/delete-all-bulk-data', [LogsController::class, 'bulkDelete'])->middleware(AdminMiddleware::class);

    // Logs Search
    Route::get('/logs-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $logs = Logs::when($search, function ($query) use ($search) {
            return $query->where('log', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('logs.logs', compact('logs', 'search'));
    });

    // Logs Paginate
    Route::get('/logs-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the logs based on the 'paginate' value
        $logs = Logs::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated logs
        return view('logs.logs', compact('logs'));
    });

    // Logs Filter
    Route::get('/logs-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for logs
        $query = Logs::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $logs = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all logs without filtering
            $logs = $query->paginate(10);
        }
    
        // Return the view with logs and the selected date range
        return view('logs.logs', compact('logs', 'from', 'to'));
    });

    // end...

    Route::get('/types', [TypesController::class, 'index'])->name('types.index')->middleware(AdminMiddleware::class);
    Route::get('/create-types', [TypesController::class, 'create'])->name('types.create')->middleware(AdminMiddleware::class);
    Route::get('/edit-types/{typesId}', [TypesController::class, 'edit'])->name('types.edit')->middleware(AdminMiddleware::class);
    Route::get('/show-types/{typesId}', [TypesController::class, 'show'])->name('types.show')->middleware(AdminMiddleware::class);
    Route::get('/delete-types/{typesId}', [TypesController::class, 'delete'])->name('types.delete')->middleware(AdminMiddleware::class);
    Route::get('/destroy-types/{typesId}', [TypesController::class, 'destroy'])->name('types.destroy')->middleware(AdminMiddleware::class);
    Route::post('/store-types', [TypesController::class, 'store'])->name('types.store')->middleware(AdminMiddleware::class);
    Route::post('/update-types/{typesId}', [TypesController::class, 'update'])->name('types.update')->middleware(AdminMiddleware::class);
    Route::post('/types-delete-all-bulk-data', [TypesController::class, 'bulkDelete'])->middleware(AdminMiddleware::class);
    Route::post('/types-move-to-trash-all-bulk-data', [TypesController::class, 'bulkMoveToTrash'])->middleware(AdminMiddleware::class);
    Route::post('/types-restore-all-bulk-data', [TypesController::class, 'bulkRestore'])->middleware(AdminMiddleware::class);
    Route::get('/trash-types', [TypesController::class, 'trash'])->middleware(AdminMiddleware::class);
    Route::get('/restore-types/{typesId}', [TypesController::class, 'restore'])->name('types.restore')->middleware(AdminMiddleware::class);

    // Types Search
    Route::get('/types-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $types = Types::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('types.types', compact('types', 'search'));
    });

    // Types Paginate
    Route::get('/types-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the types based on the 'paginate' value
        $types = Types::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated types
        return view('types.types', compact('types'));
    });

    // Types Filter
    Route::get('/types-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for types
        $query = Types::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $types = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all types without filtering
            $types = $query->paginate(10);
        }
    
        // Return the view with types and the selected date range
        return view('types.types', compact('types', 'from', 'to'));
    });

    // end...

    Route::get('/items', [ItemsController::class, 'index'])->name('items.index')->middleware(AdminMiddleware::class);
    Route::get('/create-items', [ItemsController::class, 'create'])->name('items.create')->middleware(AdminMiddleware::class);
    Route::get('/edit-items/{itemsId}', [ItemsController::class, 'edit'])->name('items.edit')->middleware(AdminMiddleware::class);
    Route::get('/show-items/{itemsId}', [ItemsController::class, 'show'])->name('items.show')->middleware(AdminMiddleware::class);
    Route::get('/delete-items/{itemsId}', [ItemsController::class, 'delete'])->name('items.delete')->middleware(AdminMiddleware::class);
    Route::get('/destroy-items/{itemsId}', [ItemsController::class, 'destroy'])->name('items.destroy')->middleware(AdminMiddleware::class);
    Route::post('/store-items', [ItemsController::class, 'store'])->name('items.store')->middleware(AdminMiddleware::class);
    Route::post('/update-items/{itemsId}', [ItemsController::class, 'update'])->name('items.update')->middleware(AdminMiddleware::class);
    Route::post('/items-delete-all-bulk-data', [ItemsController::class, 'bulkDelete'])->middleware(AdminMiddleware::class);
    Route::post('/items-move-to-trash-all-bulk-data', [ItemsController::class, 'bulkMoveToTrash'])->middleware(AdminMiddleware::class);
    Route::post('/items-restore-all-bulk-data', [ItemsController::class, 'bulkRestore'])->middleware(AdminMiddleware::class);
    Route::get('/trash-items', [ItemsController::class, 'trash'])->middleware(AdminMiddleware::class);
    Route::get('/restore-items/{itemsId}', [ItemsController::class, 'restore'])->name('items.restore')->middleware(AdminMiddleware::class);

    // Items Search
    Route::get('/items-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $items = Items::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%")
                ->orWhere('itemId', 'like', "%$search%")
                ->orWhere('model', 'like', "%$search%")
                ->orWhere('brand', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");

        })->paginate(10);

        return view('items.items', compact('items', 'search'));
    });

    // Items Paginate
    Route::get('/items-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the items based on the 'paginate' value
        $items = Items::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated items
        return view('items.items', compact('items'));
    });

    // Items Filter
    Route::get('/items-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for items
        $query = Items::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $items = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all items without filtering
            $items = $query->paginate(10);
        }
    
        // Return the view with items and the selected date range
        return view('items.items', compact('items', 'from', 'to'));
    });

    // end...

    Route::get('/sites', [SitesController::class, 'index'])->name('sites.index');
    Route::get('/create-sites', [SitesController::class, 'create'])->name('sites.create');
    Route::get('/edit-sites/{sitesId}', [SitesController::class, 'edit'])->name('sites.edit');
    Route::get('/show-sites/{sitesId}', [SitesController::class, 'show'])->name('sites.show');
    Route::get('/delete-sites/{sitesId}', [SitesController::class, 'delete'])->name('sites.delete');
    Route::get('/destroy-sites/{sitesId}', [SitesController::class, 'destroy'])->name('sites.destroy');
    Route::post('/store-sites', [SitesController::class, 'store'])->name('sites.store');
    Route::post('/update-sites/{sitesId}', [SitesController::class, 'update'])->name('sites.update');
    Route::post('/sites-delete-all-bulk-data', [SitesController::class, 'bulkDelete']);
    Route::post('/sites-move-to-trash-all-bulk-data', [SitesController::class, 'bulkMoveToTrash']);
    Route::post('/sites-restore-all-bulk-data', [SitesController::class, 'bulkRestore']);
    Route::get('/trash-sites', [SitesController::class, 'trash']);
    Route::get('/restore-sites/{sitesId}', [SitesController::class, 'restore'])->name('sites.restore');

    // Sites Search
    Route::get('/sites-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $sites = Sites::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%")
            ->orWhere('phonenumber', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('sites.sites', compact('sites', 'search'));
    });

    // Sites Paginate
    Route::get('/sites-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the sites based on the 'paginate' value
        $sites = Sites::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated sites
        return view('sites.sites', compact('sites'));
    });

    // Sites Filter
    Route::get('/sites-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for sites
        $query = Sites::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $sites = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all sites without filtering
            $sites = $query->paginate(10);
        }
    
        // Return the view with sites and the selected date range
        return view('sites.sites', compact('sites', 'from', 'to'));
    });

    // end...

    Route::get('/technicians', [TechniciansController::class, 'index'])->name('technicians.index')->middleware(AdminMiddleware::class);
    Route::get('/create-technicians', [TechniciansController::class, 'create'])->name('technicians.create')->middleware(AdminMiddleware::class);
    Route::get('/edit-technicians/{techniciansId}', [TechniciansController::class, 'edit'])->name('technicians.edit')->middleware(AdminMiddleware::class);
    Route::get('/show-technicians/{techniciansId}', [TechniciansController::class, 'show'])->name('technicians.show')->middleware(AdminMiddleware::class);
    Route::get('/delete-technicians/{techniciansId}', [TechniciansController::class, 'delete'])->name('technicians.delete')->middleware(AdminMiddleware::class);
    Route::get('/destroy-technicians/{techniciansId}', [TechniciansController::class, 'destroy'])->name('technicians.destroy')->middleware(AdminMiddleware::class);
    Route::post('/store-technicians', [TechniciansController::class, 'store'])->name('technicians.store')->middleware(AdminMiddleware::class);
    Route::post('/update-technicians/{techniciansId}', [TechniciansController::class, 'update'])->name('technicians.update')->middleware(AdminMiddleware::class);
    Route::post('/technicians-delete-all-bulk-data', [TechniciansController::class, 'bulkDelete'])->middleware(AdminMiddleware::class);
    Route::post('/technicians-move-to-trash-all-bulk-data', [TechniciansController::class, 'bulkMoveToTrash'])->middleware(AdminMiddleware::class);
    Route::post('/technicians-restore-all-bulk-data', [TechniciansController::class, 'bulkRestore'])->middleware(AdminMiddleware::class);
    Route::get('/trash-technicians', [TechniciansController::class, 'trash'])->middleware(AdminMiddleware::class);
    Route::get('/restore-technicians/{techniciansId}', [TechniciansController::class, 'restore'])->name('technicians.restore')->middleware(AdminMiddleware::class);

    // Technicians Search
    Route::get('/technicians-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $technicians = User::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('technicians.technicians', compact('technicians', 'search'));
    });

    // Technicians Paginate
    Route::get('/technicians-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the technicians based on the 'paginate' value
        $technicians = Technicians::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated technicians
        return view('technicians.technicians', compact('technicians'));
    });

    // Technicians Filter
    Route::get('/technicians-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for technicians
        $query = Technicians::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $technicians = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all technicians without filtering
            $technicians = $query->paginate(10);
        }
    
        // Return the view with technicians and the selected date range
        return view('technicians.technicians', compact('technicians', 'from', 'to'));
    });

    // end...

    Route::get('/itemlogs', [ItemlogsController::class, 'index'])->name('itemlogs.index')->middleware(AdminMiddleware::class);
    Route::get('/create-itemlogs', [ItemlogsController::class, 'create'])->name('itemlogs.create')->middleware(AdminMiddleware::class);
    Route::get('/edit-itemlogs/{itemlogsId}', [ItemlogsController::class, 'edit'])->name('itemlogs.edit')->middleware(AdminMiddleware::class);
    Route::get('/show-itemlogs/{itemlogsId}', [ItemlogsController::class, 'show'])->name('itemlogs.show')->middleware(AdminMiddleware::class);
    Route::get('/delete-itemlogs/{itemlogsId}', [ItemlogsController::class, 'delete'])->name('itemlogs.delete')->middleware(AdminMiddleware::class);
    Route::get('/destroy-itemlogs/{itemlogsId}', [ItemlogsController::class, 'destroy'])->name('itemlogs.destroy')->middleware(AdminMiddleware::class);
    Route::post('/store-itemlogs', [ItemlogsController::class, 'store'])->name('itemlogs.store')->middleware(AdminMiddleware::class);
    Route::post('/update-itemlogs/{itemlogsId}', [ItemlogsController::class, 'update'])->name('itemlogs.update')->middleware(AdminMiddleware::class);
    Route::post('/itemlogs-delete-all-bulk-data', [ItemlogsController::class, 'bulkDelete'])->middleware(AdminMiddleware::class);
    Route::post('/itemlogs-move-to-trash-all-bulk-data', [ItemlogsController::class, 'bulkMoveToTrash'])->middleware(AdminMiddleware::class);
    Route::post('/itemlogs-restore-all-bulk-data', [ItemlogsController::class, 'bulkRestore'])->middleware(AdminMiddleware::class);
    Route::get('/trash-itemlogs', [ItemlogsController::class, 'trash'])->middleware(AdminMiddleware::class);
    Route::get('/restore-itemlogs/{itemlogsId}', [ItemlogsController::class, 'restore'])->name('itemlogs.restore')->middleware(AdminMiddleware::class);

    // Itemlogs Search
    Route::get('/itemlogs-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $itemlogs = Itemlogs::when($search, function ($query) use ($search) {
            return $query->whereHas('items', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        })->paginate(10);

        return view('itemlogs.itemlogs', compact('itemlogs', 'search'));
    });

    // Itemlogs Paginate
    Route::get('/itemlogs-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the itemlogs based on the 'paginate' value
        $itemlogs = Itemlogs::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated itemlogs
        return view('itemlogs.itemlogs', compact('itemlogs'));
    });

    // Itemlogs Filter
    Route::get('/itemlogs-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for itemlogs
        $query = Itemlogs::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $itemlogs = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all itemlogs without filtering
            $itemlogs = $query->paginate(10);
        }
    
        // Return the view with itemlogs and the selected date range
        return view('itemlogs.itemlogs', compact('itemlogs', 'from', 'to'));
    });

    // end...

    Route::get('/onsites', [OnsitesController::class, 'index'])->name('onsites.index');
    Route::get('/create-onsites', [OnsitesController::class, 'create'])->name('onsites.create');
    Route::get('/edit-onsites/{onsitesId}', [OnsitesController::class, 'edit'])->name('onsites.edit');
    Route::get('/show-onsites/{onsitesId}', [OnsitesController::class, 'show'])->name('onsites.show');
    Route::get('/delete-onsites/{onsitesId}', [OnsitesController::class, 'delete'])->name('onsites.delete');
    Route::get('/destroy-onsites/{onsitesId}', [OnsitesController::class, 'destroy'])->name('onsites.destroy');
    Route::post('/store-onsites', [OnsitesController::class, 'store'])->name('onsites.store');
    Route::post('/update-onsites/{onsitesId}', [OnsitesController::class, 'update'])->name('onsites.update');
    Route::post('/onsites-delete-all-bulk-data', [OnsitesController::class, 'bulkDelete']);
    Route::post('/onsites-move-to-trash-all-bulk-data', [OnsitesController::class, 'bulkMoveToTrash']);
    Route::post('/onsites-restore-all-bulk-data', [OnsitesController::class, 'bulkRestore']);
    Route::get('/trash-onsites', [OnsitesController::class, 'trash']);
    Route::get('/restore-onsites/{onsitesId}', [OnsitesController::class, 'restore'])->name('onsites.restore');

    // Onsites Search
    Route::get('/onsites-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $onsites = Onsites::when($search, function ($query) use ($search) {
            return $query->whereHas('sites', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        })->paginate(10);

        return view('onsites.onsites', compact('onsites', 'search'));
    });

    // Onsites Paginate
    Route::get('/onsites-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the onsites based on the 'paginate' value
        $onsites = Onsites::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated onsites
        return view('onsites.onsites', compact('onsites'));
    });

    // Onsites Filter
    Route::get('/onsites-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for onsites
        $query = Onsites::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $onsites = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all onsites without filtering
            $onsites = $query->paginate(10);
        }
    
        // Return the view with onsites and the selected date range
        return view('onsites.onsites', compact('onsites', 'from', 'to'));
    });

    // end...

    Route::get('/damages', [DamagesController::class, 'index'])->name('damages.index');
    Route::get('/create-damages', [DamagesController::class, 'create'])->name('damages.create');
    Route::get('/edit-damages/{damagesId}', [DamagesController::class, 'edit'])->name('damages.edit');
    Route::get('/show-damages/{damagesId}', [DamagesController::class, 'show'])->name('damages.show');
    Route::get('/delete-damages/{damagesId}', [DamagesController::class, 'delete'])->name('damages.delete');
    Route::get('/destroy-damages/{damagesId}', [DamagesController::class, 'destroy'])->name('damages.destroy');
    Route::post('/store-damages', [DamagesController::class, 'store'])->name('damages.store');
    Route::post('/update-damages/{damagesId}', [DamagesController::class, 'update'])->name('damages.update');
    Route::post('/damages-delete-all-bulk-data', [DamagesController::class, 'bulkDelete']);
    Route::post('/damages-move-to-trash-all-bulk-data', [DamagesController::class, 'bulkMoveToTrash']);
    Route::post('/damages-restore-all-bulk-data', [DamagesController::class, 'bulkRestore']);
    Route::get('/trash-damages', [DamagesController::class, 'trash']);
    Route::get('/restore-damages/{damagesId}', [DamagesController::class, 'restore'])->name('damages.restore');

    // Damages Search
    Route::get('/damages-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $damages = Damages::when($search, function ($query) use ($search) {
            return $query->whereHas('sites', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        })->paginate(10);

        return view('damages.damages', compact('damages', 'search'));
    });

    // Damages Paginate
    Route::get('/damages-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the damages based on the 'paginate' value
        $damages = Damages::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated damages
        return view('damages.damages', compact('damages'));
    });

    // Damages Filter
    Route::get('/damages-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for damages
        $query = Damages::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $damages = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all damages without filtering
            $damages = $query->paginate(10);
        }
    
        // Return the view with damages and the selected date range
        return view('damages.damages', compact('damages', 'from', 'to'));
    });

    // end...

    Route::get('/deployedtechnicians', [DeployedtechniciansController::class, 'index'])->name('deployedtechnicians.index');
    Route::get('/create-deployedtechnicians', [DeployedtechniciansController::class, 'create'])->name('deployedtechnicians.create');
    Route::get('/edit-deployedtechnicians/{deployedtechniciansId}', [DeployedtechniciansController::class, 'edit'])->name('deployedtechnicians.edit');
    Route::get('/show-deployedtechnicians/{deployedtechniciansId}', [DeployedtechniciansController::class, 'show'])->name('deployedtechnicians.show');
    Route::get('/delete-deployedtechnicians/{deployedtechniciansId}', [DeployedtechniciansController::class, 'delete'])->name('deployedtechnicians.delete');
    Route::get('/destroy-deployedtechnicians/{deployedtechniciansId}', [DeployedtechniciansController::class, 'destroy'])->name('deployedtechnicians.destroy');
    Route::post('/store-deployedtechnicians', [DeployedtechniciansController::class, 'store'])->name('deployedtechnicians.store');
    Route::post('/update-deployedtechnicians/{deployedtechniciansId}', [DeployedtechniciansController::class, 'update'])->name('deployedtechnicians.update');
    Route::post('/deployedtechnicians-delete-all-bulk-data', [DeployedtechniciansController::class, 'bulkDelete']);
    Route::post('/deployedtechnicians-move-to-trash-all-bulk-data', [DeployedtechniciansController::class, 'bulkMoveToTrash']);
    Route::post('/deployedtechnicians-restore-all-bulk-data', [DeployedtechniciansController::class, 'bulkRestore']);
    Route::get('/trash-deployedtechnicians', [DeployedtechniciansController::class, 'trash']);
    Route::get('/restore-deployedtechnicians/{deployedtechniciansId}', [DeployedtechniciansController::class, 'restore'])->name('deployedtechnicians.restore');

    // Deployedtechnicians Search
    Route::get('/deployedtechnicians-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $deployedtechnicians = Deployedtechnicians::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('deployedtechnicians.deployedtechnicians', compact('deployedtechnicians', 'search'));
    });

    // Deployedtechnicians Paginate
    Route::get('/deployedtechnicians-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the deployedtechnicians based on the 'paginate' value
        $deployedtechnicians = Deployedtechnicians::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated deployedtechnicians
        return view('deployedtechnicians.deployedtechnicians', compact('deployedtechnicians'));
    });

    // Deployedtechnicians Filter
    Route::get('/deployedtechnicians-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for deployedtechnicians
        $query = Deployedtechnicians::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $deployedtechnicians = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all deployedtechnicians without filtering
            $deployedtechnicians = $query->paginate(10);
        }
    
        // Return the view with deployedtechnicians and the selected date range
        return view('deployedtechnicians.deployedtechnicians', compact('deployedtechnicians', 'from', 'to'));
    });

    // end...

    Route::get('/workspaces', [WorkspacesController::class, 'index'])->name('workspaces.index');
    Route::get('/create-workspaces', [WorkspacesController::class, 'create'])->name('workspaces.create');
    Route::get('/edit-workspaces/{workspacesId}', [WorkspacesController::class, 'edit'])->name('workspaces.edit');
    Route::get('/show-workspaces/{workspacesId}', [WorkspacesController::class, 'show'])->name('workspaces.show');
    Route::get('/delete-workspaces/{workspacesId}', [WorkspacesController::class, 'delete'])->name('workspaces.delete');
    Route::get('/destroy-workspaces/{workspacesId}', [WorkspacesController::class, 'destroy'])->name('workspaces.destroy');
    Route::post('/store-workspaces', [WorkspacesController::class, 'store'])->name('workspaces.store');
    Route::post('/update-workspaces/{workspacesId}', [WorkspacesController::class, 'update'])->name('workspaces.update');
    Route::post('/workspaces-delete-all-bulk-data', [WorkspacesController::class, 'bulkDelete']);
    Route::post('/workspaces-move-to-trash-all-bulk-data', [WorkspacesController::class, 'bulkMoveToTrash']);
    Route::post('/workspaces-restore-all-bulk-data', [WorkspacesController::class, 'bulkRestore']);
    Route::get('/trash-workspaces', [WorkspacesController::class, 'trash']);
    Route::get('/restore-workspaces/{workspacesId}', [WorkspacesController::class, 'restore'])->name('workspaces.restore');

    // Workspaces Search
    Route::get('/workspaces-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $workspaces = Workspaces::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('workspaces.workspaces', compact('workspaces', 'search'));
    });

    // Workspaces Paginate
    Route::get('/workspaces-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the workspaces based on the 'paginate' value
        $workspaces = Workspaces::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated workspaces
        return view('workspaces.workspaces', compact('workspaces'));
    });

    // Workspaces Filter
    Route::get('/workspaces-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for workspaces
        $query = Workspaces::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $workspaces = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all workspaces without filtering
            $workspaces = $query->paginate(10);
        }
    
        // Return the view with workspaces and the selected date range
        return view('workspaces.workspaces', compact('workspaces', 'from', 'to'));
    });
    

    // end...

    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/create-projects', [ProjectsController::class, 'create'])->name('projects.create');
    Route::get('/edit-projects/{projectsId}', [ProjectsController::class, 'edit'])->name('projects.edit');
    Route::get('/show-projects/{projectsId}', [ProjectsController::class, 'show'])->name('projects.show');
    Route::get('/delete-projects/{projectsId}', [ProjectsController::class, 'delete'])->name('projects.delete');
    Route::get('/destroy-projects/{projectsId}', [ProjectsController::class, 'destroy'])->name('projects.destroy');
    Route::post('/store-projects', [ProjectsController::class, 'store'])->name('projects.store');
    Route::post('/update-projects/{projectsId}', [ProjectsController::class, 'update'])->name('projects.update');
    Route::post('/projects-delete-all-bulk-data', [ProjectsController::class, 'bulkDelete']);
    Route::post('/projects-move-to-trash-all-bulk-data', [ProjectsController::class, 'bulkMoveToTrash']);
    Route::post('/projects-restore-all-bulk-data', [ProjectsController::class, 'bulkRestore']);
    Route::get('/trash-projects', [ProjectsController::class, 'trash']);
    Route::get('/restore-projects/{projectsId}', [ProjectsController::class, 'restore'])->name('projects.restore');

    // Projects Search
    Route::get('/projects-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $projects = Projects::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('projects.projects', compact('projects', 'search'));
    });

    // Projects Paginate
    Route::get('/projects-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the projects based on the 'paginate' value
        $projects = Projects::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated projects
        return view('projects.projects', compact('projects'));
    });

    // Projects Filter
    Route::get('/projects-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for projects
        $query = Projects::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $projects = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all projects without filtering
            $projects = $query->paginate(10);
        }
    
        // Return the view with projects and the selected date range
        return view('projects.projects', compact('projects', 'from', 'to'));
    });

    // end...

    Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.index');
    Route::get('/create-tasks', [TasksController::class, 'create'])->name('tasks.create');
    Route::get('/edit-tasks/{tasksId}', [TasksController::class, 'edit'])->name('tasks.edit');
    Route::get('/show-tasks/{tasksId}', [TasksController::class, 'show'])->name('tasks.show');
    Route::get('/delete-tasks/{tasksId}', [TasksController::class, 'delete'])->name('tasks.delete');
    Route::get('/destroy-tasks/{tasksId}', [TasksController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/store-tasks', [TasksController::class, 'store'])->name('tasks.store');
    Route::post('/update-tasks/{tasksId}', [TasksController::class, 'update'])->name('tasks.update');
    Route::post('/tasks-delete-all-bulk-data', [TasksController::class, 'bulkDelete']);
    Route::post('/tasks-move-to-trash-all-bulk-data', [TasksController::class, 'bulkMoveToTrash']);
    Route::post('/tasks-restore-all-bulk-data', [TasksController::class, 'bulkRestore']);
    Route::get('/trash-tasks', [TasksController::class, 'trash']);
    Route::get('/restore-tasks/{tasksId}', [TasksController::class, 'restore'])->name('tasks.restore');

    // Tasks Search
    Route::get('/tasks-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $tasks = Tasks::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%")
            ->orWhere('status', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('tasks.tasks', compact('tasks', 'search'));
    });

    // Tasks Paginate
    Route::get('/tasks-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the tasks based on the 'paginate' value
        $tasks = Tasks::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated tasks
        return view('tasks.tasks', compact('tasks'));
    });

    // Tasks Filter
    Route::get('/tasks-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for tasks
        $query = Tasks::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $tasks = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(100);
        } else {
            // If 'from' or 'to' are missing, show all tasks without filtering
            $tasks = $query->paginate(100);
        }
    
        // Return the view with tasks and the selected date range
        return view('tasks.tasks', compact('tasks', 'from', 'to'));
    });

    // end...

    Route::get('/workspaceusers', [WorkspaceusersController::class, 'index'])->name('workspaceusers.index');
    Route::get('/create-workspaceusers', [WorkspaceusersController::class, 'create'])->name('workspaceusers.create');
    Route::get('/edit-workspaceusers/{workspaceusersId}', [WorkspaceusersController::class, 'edit'])->name('workspaceusers.edit');
    Route::get('/show-workspaceusers/{workspaceusersId}', [WorkspaceusersController::class, 'show'])->name('workspaceusers.show');
    Route::get('/delete-workspaceusers/{workspaceusersId}', [WorkspaceusersController::class, 'delete'])->name('workspaceusers.delete');
    Route::get('/destroy-workspaceusers/{workspaceusersId}', [WorkspaceusersController::class, 'destroy'])->name('workspaceusers.destroy');
    Route::post('/store-workspaceusers', [WorkspaceusersController::class, 'store'])->name('workspaceusers.store');
    Route::post('/update-workspaceusers/{workspaceusersId}', [WorkspaceusersController::class, 'update'])->name('workspaceusers.update');
    Route::post('/workspaceusers-delete-all-bulk-data', [WorkspaceusersController::class, 'bulkDelete']);
    Route::post('/workspaceusers-move-to-trash-all-bulk-data', [WorkspaceusersController::class, 'bulkMoveToTrash']);
    Route::post('/workspaceusers-restore-all-bulk-data', [WorkspaceusersController::class, 'bulkRestore']);
    Route::get('/trash-workspaceusers', [WorkspaceusersController::class, 'trash']);
    Route::get('/restore-workspaceusers/{workspaceusersId}', [WorkspaceusersController::class, 'restore'])->name('workspaceusers.restore');

    // Workspaceusers Search
    Route::get('/workspaceusers-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $workspaceusers = Workspaceusers::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('workspaceusers.workspaceusers', compact('workspaceusers', 'search'));
    });

    // Workspaceusers Paginate
    Route::get('/workspaceusers-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the workspaceusers based on the 'paginate' value
        $workspaceusers = Workspaceusers::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated workspaceusers
        return view('workspaceusers.workspaceusers', compact('workspaceusers'));
    });

    // Workspaceusers Filter
    Route::get('/workspaceusers-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for workspaceusers
        $query = Workspaceusers::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $workspaceusers = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all workspaceusers without filtering
            $workspaceusers = $query->paginate(10);
        }
    
        // Return the view with workspaceusers and the selected date range
        return view('workspaceusers.workspaceusers', compact('workspaceusers', 'from', 'to'));
    });

    // end...

    Route::get('/comments', [CommentsController::class, 'index'])->name('comments.index');
    Route::get('/create-comments', [CommentsController::class, 'create'])->name('comments.create');
    Route::get('/edit-comments/{commentsId}', [CommentsController::class, 'edit'])->name('comments.edit');
    Route::get('/show-comments/{commentsId}', [CommentsController::class, 'show'])->name('comments.show');
    Route::get('/delete-comments/{commentsId}', [CommentsController::class, 'delete'])->name('comments.delete');
    Route::get('/destroy-comments/{commentsId}', [CommentsController::class, 'destroy'])->name('comments.destroy');
    Route::post('/store-comments', [CommentsController::class, 'store'])->name('comments.store');
    Route::post('/update-comments/{commentsId}', [CommentsController::class, 'update'])->name('comments.update');
    Route::post('/comments-delete-all-bulk-data', [CommentsController::class, 'bulkDelete']);
    Route::post('/comments-move-to-trash-all-bulk-data', [CommentsController::class, 'bulkMoveToTrash']);
    Route::post('/comments-restore-all-bulk-data', [CommentsController::class, 'bulkRestore']);
    Route::get('/trash-comments', [CommentsController::class, 'trash']);
    Route::get('/restore-comments/{commentsId}', [CommentsController::class, 'restore'])->name('comments.restore');

    // Comments Search
    Route::get('/comments-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $comments = Comments::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('comments.comments', compact('comments', 'search'));
    });

    // Comments Paginate
    Route::get('/comments-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the comments based on the 'paginate' value
        $comments = Comments::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated comments
        return view('comments.comments', compact('comments'));
    });

    // Comments Filter
    Route::get('/comments-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for comments
        $query = comments::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $comments = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all comments without filtering
            $comments = $query->paginate(10);
        }
    
        // Return the view with comments and the selected date range
        return view('comments.comments', compact('comments', 'from', 'to'));
    });

    // end...

    Route::get('/taskassignments', [TaskassignmentsController::class, 'index'])->name('taskassignments.index');
    Route::get('/create-taskassignments', [TaskassignmentsController::class, 'create'])->name('taskassignments.create');
    Route::get('/edit-taskassignments/{taskassignmentsId}', [TaskassignmentsController::class, 'edit'])->name('taskassignments.edit');
    Route::get('/show-taskassignments/{taskassignmentsId}', [TaskassignmentsController::class, 'show'])->name('taskassignments.show');
    Route::get('/delete-taskassignments/{taskassignmentsId}', [TaskassignmentsController::class, 'delete'])->name('taskassignments.delete');
    Route::get('/destroy-taskassignments/{taskassignmentsId}', [TaskassignmentsController::class, 'destroy'])->name('taskassignments.destroy')->middleware(AdminMiddleware::class);
    Route::post('/store-taskassignments', [TaskassignmentsController::class, 'store'])->name('taskassignments.store')->middleware(AdminMiddleware::class);
    Route::post('/update-taskassignments/{taskassignmentsId}', [TaskassignmentsController::class, 'update'])->name('taskassignments.update');
    Route::post('/taskassignments-delete-all-bulk-data', [TaskassignmentsController::class, 'bulkDelete']);
    Route::post('/taskassignments-move-to-trash-all-bulk-data', [TaskassignmentsController::class, 'bulkMoveToTrash']);
    Route::post('/taskassignments-restore-all-bulk-data', [TaskassignmentsController::class, 'bulkRestore']);
    Route::get('/trash-taskassignments', [TaskassignmentsController::class, 'trash']);
    Route::get('/restore-taskassignments/{taskassignmentsId}', [TaskassignmentsController::class, 'restore'])->name('taskassignments.restore');

    // Taskassignments Search
    Route::get('/taskassignments-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $taskassignments = Taskassignments::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('taskassignments.taskassignments', compact('taskassignments', 'search'));
    });

    // Taskassignments Paginate
    Route::get('/taskassignments-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the taskassignments based on the 'paginate' value
        $taskassignments = Taskassignments::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated taskassignments
        return view('taskassignments.taskassignments', compact('taskassignments'));
    });

    // Taskassignments Filter
    Route::get('/taskassignments-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for taskassignments
        $query = Taskassignments::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $taskassignments = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all taskassignments without filtering
            $taskassignments = $query->paginate(10);
        }
    
        // Return the view with taskassignments and the selected date range
        return view('taskassignments.taskassignments', compact('taskassignments', 'from', 'to'));
    });

    // end...

    Route::get('/tasktimelogs', [TasktimelogsController::class, 'index'])->name('tasktimelogs.index');
    Route::get('/create-tasktimelogs', [TasktimelogsController::class, 'create'])->name('tasktimelogs.create');
    Route::get('/edit-tasktimelogs/{tasktimelogsId}', [TasktimelogsController::class, 'edit'])->name('tasktimelogs.edit');
    Route::get('/show-tasktimelogs/{tasktimelogsId}', [TasktimelogsController::class, 'show'])->name('tasktimelogs.show');
    Route::get('/delete-tasktimelogs/{tasktimelogsId}', [TasktimelogsController::class, 'delete'])->name('tasktimelogs.delete');
    Route::get('/destroy-tasktimelogs/{tasktimelogsId}', [TasktimelogsController::class, 'destroy'])->name('tasktimelogs.destroy');
    Route::post('/store-tasktimelogs', [TasktimelogsController::class, 'store'])->name('tasktimelogs.store');
    Route::post('/update-tasktimelogs/{tasktimelogsId}', [TasktimelogsController::class, 'update'])->name('tasktimelogs.update');
    Route::post('/tasktimelogs-delete-all-bulk-data', [TasktimelogsController::class, 'bulkDelete']);
    Route::post('/tasktimelogs-move-to-trash-all-bulk-data', [TasktimelogsController::class, 'bulkMoveToTrash']);
    Route::post('/tasktimelogs-restore-all-bulk-data', [TasktimelogsController::class, 'bulkRestore']);
    Route::get('/trash-tasktimelogs', [TasktimelogsController::class, 'trash']);
    Route::get('/restore-tasktimelogs/{tasktimelogsId}', [TasktimelogsController::class, 'restore'])->name('tasktimelogs.restore');

    // Route::get('/resume-timer/{taskId}', [TasktimelogsController::class, 'resumeTimer']);

    Route::put('/tasktimelogs/{id}/pause', [TaskTimeLogsController::class, 'update'])->name('tasktimelogs.pause');
    Route::put('/tasktimelogs/{id}/resume', [TaskTimeLogsController::class, 'resume'])->name('tasktimelogs.resume');
    Route::put('/tasktimelogs/{id}/stop', [TaskTimeLogsController::class, 'stop'])->name('tasktimelogs.stop');

    // Tasktimelogs Search
    Route::get('/tasktimelogs-search', function (Request $request) {
        $search = $request->get('search');

        // Perform the search logic
        $tasktimelogs = Tasktimelogs::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        })->orderBy('id', 'desc')->paginate(10);

        return view('tasktimelogs.tasktimelogs', compact('tasktimelogs', 'search'));
    });

    // Tasktimelogs Paginate
    Route::get('/tasktimelogs-paginate', function (Request $request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        $paginate = $request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the tasktimelogs based on the 'paginate' value
        $tasktimelogs = Tasktimelogs::paginate($paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated tasktimelogs
        return view('tasktimelogs.tasktimelogs', compact('tasktimelogs'));
    });

    // Tasktimelogs Filter
    Route::get('/tasktimelogs-filter', function (Request $request) {
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Default query for tasktimelogs
        $query = Tasktimelogs::query();
    
        // Convert dates to Carbon instances for better comparison
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
        $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if ($fromDate && $toDate) {
            // Ensure correct date filtering with full day range
            $tasktimelogs = $query->whereBetween('created_at', [$fromDate, $toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all tasktimelogs without filtering
            $tasktimelogs = $query->paginate(10);
        }
    
        // Return the view with tasktimelogs and the selected date range
        return view('tasktimelogs.tasktimelogs', compact('tasktimelogs', 'from', 'to'));
    });

    // end...

});
