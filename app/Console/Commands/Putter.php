<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class Putter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'putter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        echo "\n";
        echo "\n";
        echo "Welcome to Putter.\n";
        $this->info('by Mark Jason Espelita');
        echo "\n";
        $this->line("\e[33mType --help to display available commands.\e[0m");

        while (true) {
            $queries = [
                [
                    "command" => 'motivate',
                    "description" => 'Display motivational qoute',
                    "action" => function () {

                        $this->info(Inspiring::quote());
                    }
                ],
                [
                    "command" => 'clear',
                    "description" => 'Clear console.',
                    "action" => function () {
                        // Clear the console screen
                        $this->line("\e[H\e[2J");
                    }
                ],
                [
                    "command" => 'login',
                    "description" => 'Login Form',
                    "action" => function () {

                        $email = $this->ask('Email');
                        $password = $this->ask('Password');

                        $user = User::where('email', $email)->first();

                        if ($user && Hash::check($password, $user->password)) {

                            // APPLICATION

                            while (true) {
                                $queries = [
                                    [
                                        "command" => 'logout',
                                        "description" => 'Bye!',
                                        "action" => function () {

                                        }
                                    ],
                                    [
                                        "command" => 'motivate',
                                        "description" => 'Display motivational qoute',
                                        "action" => function () {
                                            $this->info(Inspiring::quote());
                                        }
                                    ],
                                    [
                                        "command" => 'clear',
                                        "description" => 'Clear console.',
                                        "action" => function () {
                                            // Clear the console screen
                                            $this->line("\e[H\e[2J");
                                        }
                                    ],
                                    [
                                        "command" => 'generate-template',
                                        "description" => 'Generates main.blade.php template',
                                        "action" => function () {

// creating notification

echo "Creating view notification component...\n";
shell_exec("php artisan make:component MainNotification"); // index

file_put_contents(resource_path("views/components/main-notification.blade.php"), "
<div style='text-align: right;'>
    @if (session()->has('success'))
    <p class='alert alert-success text-success mb-0'><i class='fas fa-check'></i> {{ session()->get('success') }}</p>
    @endif

    @if (session()->has('error'))
        <p class='alert alert-danger text-danger mb-0'><i class='fas fa-warning'></i> {{ session()->get('error') }}</p>
    @endif

    @if (\$errors->any())
        @foreach (\$errors->all() as \$err)
            <p class='alert alert-danger text-danger mb-0'><i class='fas fa-warning'></i> {{ \$err }}</p>
        @endforeach
    @endif
</div>
");
$this->info("SUCCESS: Notifications created successfully.\n");

// creating main template

echo "Creating view layouts/main.blade.php...\n";
shell_exec("php artisan make:view layouts/main"); // index

file_put_contents(resource_path("views/layouts/main.blade.php"), "
<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta name='csrf-token' content='{{ csrf_token() }}'>
        <meta name='author' content='Mark Jason Penote Espelita'>
        <meta name='keywords' content='keyword1, keyword2'>
        <meta name='description' content='Dolorem natus ab illum beatae error voluptatem incidunt quis. Cupiditate ullam doloremque delectus culpa. Autem harum dolorem praesentium dolorum necessitatibus iure quo. Et ea aut voluptatem expedita.'>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href='{{ url('assets/bootstrap/bootstrap.min.css') }}' rel='stylesheet'>
        <!-- FontAwesome for icons -->
        <link href='{{ url('assets/font-awesome/css/all.min.css') }}' rel='stylesheet'>
        <link rel='stylesheet' href='{{ url('assets/custom/style.css') }}'>
        <link rel='icon' href='{{ url('assets/logo.png') }}'>
    </head>
    <body class='font-sans antialiased'>

        <!-- Sidebar for Desktop View -->
        <div class='sidebar' id='mobileSidebar'>
            <div class='logo'>
                <img src='{{ url('assets/logo.png') }}' alt='' width='100%'>
            </div>
            <a href='{{ url('dashboard') }}' class='{{ request()->is('dashboard') ? 'active' : '' }}'><i class='fas fa-tachometer-alt'></i> Dashboard</a>
            <a href='{{ url('logs') }}' class='{{ request()->is('logs', 'create-logs', 'show-logs/*', 'edit-logs/*', 'delete-logs/*', 'logs-search*') ? 'active' : '' }}'><i class='fas fa-bars'></i> Logs</a>
            <a href='{{ url('user/profile') }}'><i class='fas fa-user'></i> {{ Auth::user()->name }}</a>
        </div>

        <!-- Top Navbar -->
        <nav class='navbar navbar-expand-lg navbar-dark'>
            <div class='container-fluid'>
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav'
                    aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation' onclick='toggleSidebar()'>
                    <i class='fas fa-bars'></i>
                </button>
            </div>
        </nav>

        <x-main-notification />

        <div class='content'>
            @yield('content')
        </div>

        <!-- Bootstrap JS and dependencies -->
        <script src='{{ url('assets/bootstrap/bootstrap.bundle.min.js') }}'></script>

        <!-- Custom JavaScript -->
        <script>
            function toggleSidebar() {
                document.getElementById('mobileSidebar').classList.toggle('active');
                document.getElementById('sidebar').classList.toggle('active');
            }
        </script>
    </body>
</html>
");
$this->info("SUCCESS: Template layouts/main.blade.php created.\n");
                                        }
                                    ],
                                    [
                                        "command" => 'generate-crud',
                                        "description" => 'Generate CRUD (Model, Migration, Request, Views, and Routes)',
                                        "action" => function () {

                                            function putter($path, $pattern, $data) {
                                                // Get the current content
                                                $content = file_get_contents($path);

                                                $pattern = $pattern; // Pattern to search for

                                                // Find the position of the pattern
                                                $position = strpos($content, $pattern);
                                                if ($position !== false) {
                                                    // Insert new text after the pattern
                                                    $position += strlen($pattern);
                                                    $newContent = substr($content, 0, $position) . $data . substr($content, $position);

                                                    // Write the modified content back to the file
                                                    file_put_contents($path, $newContent);
                                                } else {
                                                    echo "Pattern not found!";
                                                }
                                            }

                                            $modelName = $this->ask('Model Name');
                                            $attr = $this->ask('Table Attributes (in JSON format, e.g., [{"col": "column1", "validate": "required", "dataType": "string"}])');

                                            // Check if the JSON was valid
                                            if (json_last_error() !== JSON_ERROR_NONE) {
                                                $this->error('Invalid JSON format. Please provide a valid JSON string.');
                                                return;
                                            }

                                            echo "Initializing model...\n";
                                            // initialize model -a
                                            shell_exec("php artisan make:model $modelName -a");
                                            $this->info("SUCCESS: Model initialized.\n");

                                            // Decode the JSON input into an associative array
                                            $attributes = json_decode($attr, true);

                                            // EDIT MODEL FILE ===========================================================================================================

                                            echo "Modifying model...\n";

                                            $modelPath = 'app/Models/' . $modelName . '.php';

                                            $fillableColumns = [];

                                            // Loop through attributes and echo each 'col' value
                                            foreach ($attributes as $attribute) {
                                                array_push($fillableColumns, $attribute['col']);
                                            }

                                            // isTrash
                                            array_push($fillableColumns, 'isTrash');

                                            $insertable = json_encode($fillableColumns);

                                            $insertText = "\nprotected \$fillable = " . $insertable . ";";

                                            putter($modelPath, '*/', $insertText);

                                            $this->info("SUCCESS: Model modified.\n");

                                            // EDIT MIGRATION FILE =======================================================================================================

                                            echo "Modifying migration...\n";

                                            $migrationName = strtolower($modelName);

                                            $directory = 'database/migrations/*' . $migrationName . '*.php';

                                            $migrationPath = glob($directory);

                                            $migrations = "";

                                            // Loop through attributes and echo each 'col' value
                                            foreach ($attributes as $attribute) {
                                                $dataType = $attribute['dataType'];
                                                $column = $attribute['col'];
                                                $migrations .= "\n\$table->$dataType('$column');";
                                            }

                                            // isTrash
                                            $migrations .= "\n\$table->boolean('isTrash')->default(0);";

                                            putter($migrationPath[0], "\$table->id();", $migrations);

                                            $this->info("SUCCESS: Migration modified.\n");

                                            // EDIT REQUESTS =============================================================================================================

                                            echo "Modifying store request...\n";

                                            // Store

                                            $storeRequestPath = 'app/Http/Requests/Store' . $modelName . 'Request.php';

                                            $validation = "";

                                            // Loop through attributes and echo each 'col' value
                                            foreach ($attributes as $attribute) {
                                                $validate = $attribute['validate'];
                                                $column = $attribute['col'];
                                                $validation .= "'$column' => '$validate',";
                                            }

                                            // replace the 'false' string to 'true' in Store Request file

                                            $fileContents = file_get_contents($storeRequestPath);
                                            $fileContents = str_replace('false', 'true', $fileContents);

                                            file_put_contents($storeRequestPath, $fileContents);

                                            putter($storeRequestPath, "//", "\n".$validation);

                                            $this->info("SUCCESS: Store request modified.\n");

                                            // Update

                                            echo "Modifying update request...\n";

                                            $updateRequestPath = 'app/Http/Requests/Update' . $modelName . 'Request.php';

                                            $validation = "";

                                            // Loop through attributes and echo each 'col' value
                                            foreach ($attributes as $attribute) {
                                                $validate = $attribute['validate'];
                                                $column = $attribute['col'];
                                                $validation .= "'$column' => '$validate',";
                                            }

                                            // replace the 'false' string to 'true' in Store Request file

                                            $fileContents = file_get_contents($updateRequestPath);
                                            $fileContents = str_replace('false', 'true', $fileContents);

                                            file_put_contents($updateRequestPath, $fileContents);

                                            putter($updateRequestPath, "//", "\n".$validation);

                                            $this->info("SUCCESS: Update request modified.\n");

                                            // APPEND ROUTES TO web.php ====================================================================================================

                                            echo "Appending routes...\n";

                                            $filePath = 'routes/web.php';
                                            $modelNameLowerCase = strtolower($modelName);

                                            // Function to append after the last `// end of import`
                                            function appendAfterLastEndOfImport($filePath, $pattern, $data) {
                                                // Get the current content of the file
                                                $content = file_get_contents($filePath);

                                                // Find the position of the last occurrence of the pattern
                                                $position = strrpos($content, $pattern);

                                                if ($position !== false) {
                                                    // Move position to just after the pattern
                                                    $position += strlen($pattern);
                                                    // Append the data after the last occurrence of the pattern
                                                    $newContent = substr($content, 0, $position) . $data . substr($content, $position);

                                                    // Write the modified content back to the file
                                                    return file_put_contents($filePath, $newContent) !== false;
                                                } else {
                                                    return false;
                                                }
                                            }

$importedControllerClassesAndModels = "

use App\Http\Controllers\\".$modelName."Controller;
use App\Models\\".$modelName.";

// end of import
";

                                            if (appendAfterLastEndOfImport($filePath, '// end of import', $importedControllerClassesAndModels)) {
                                                $this->info("Classes imported\n");
                                            } else {
                                                echo "Failed to append routes to web.php.\n";
                                            }

                                            // The routes to be appended
$textToAppend = "

    Route::get('/{$modelNameLowerCase}', [{$modelName}Controller::class, 'index'])->name('{$modelNameLowerCase}.index');
    Route::get('/create-{$modelNameLowerCase}', [{$modelName}Controller::class, 'create'])->name('{$modelNameLowerCase}.create');
    Route::get('/edit-{$modelNameLowerCase}/{{$modelNameLowerCase}Id}', [{$modelName}Controller::class, 'edit'])->name('{$modelNameLowerCase}.edit');
    Route::get('/show-{$modelNameLowerCase}/{{$modelNameLowerCase}Id}', [{$modelName}Controller::class, 'show'])->name('{$modelNameLowerCase}.show');
    Route::get('/delete-{$modelNameLowerCase}/{{$modelNameLowerCase}Id}', [{$modelName}Controller::class, 'delete'])->name('{$modelNameLowerCase}.delete');
    Route::get('/destroy-{$modelNameLowerCase}/{{$modelNameLowerCase}Id}', [{$modelName}Controller::class, 'destroy'])->name('{$modelNameLowerCase}.destroy');
    Route::post('/store-{$modelNameLowerCase}', [{$modelName}Controller::class, 'store'])->name('{$modelNameLowerCase}.store');
    Route::post('/update-{$modelNameLowerCase}/{{$modelNameLowerCase}Id}', [{$modelName}Controller::class, 'update'])->name('{$modelNameLowerCase}.update');
    Route::post('/{$modelNameLowerCase}-delete-all-bulk-data', [{$modelName}Controller::class, 'bulkDelete']);
    Route::post('/{$modelNameLowerCase}-move-to-trash-all-bulk-data', [{$modelName}Controller::class, 'bulkMoveToTrash']);
    Route::post('/{$modelNameLowerCase}-restore-all-bulk-data', [{$modelName}Controller::class, 'bulkRestore']);
    Route::get('/trash-{$modelNameLowerCase}', [{$modelName}Controller::class, 'trash']);
    Route::get('/restore-{$modelNameLowerCase}/{{$modelNameLowerCase}Id}', [{$modelName}Controller::class, 'restore'])->name('{$modelNameLowerCase}.restore');

    // $modelName Search
    Route::get('/$modelNameLowerCase-search', function (Request \$request) {
        \$search = \$request->get('search');

        // Perform the search logic
        $$modelNameLowerCase = $modelName::when(\$search, function (\$query) use (\$search) {
            return \$query->where('name', 'like', \"%\$search%\");
        })->paginate(10);

        return view('$modelNameLowerCase.$modelNameLowerCase', compact('$modelNameLowerCase', 'search'));
    });

    // $modelName Paginate
    Route::get('/$modelNameLowerCase-paginate', function (Request \$request) {
        // Retrieve the 'paginate' parameter from the URL (e.g., ?paginate=10)
        \$paginate = \$request->input('paginate', 10); // Default to 10 if no paginate value is provided
    
        // Paginate the $modelNameLowerCase based on the 'paginate' value
        \$$modelNameLowerCase = $modelName::paginate(\$paginate); // Paginate with the specified number of items per page
    
        // Return the view with the paginated $modelNameLowerCase
        return view('$modelNameLowerCase.$modelNameLowerCase', compact('$modelNameLowerCase'));
    });

    // $modelName Filter
    Route::get('/$modelNameLowerCase-filter', function (Request \$request) {
        // Retrieve 'from' and 'to' dates from the URL
        \$from = \$request->input('from');
        \$to = \$request->input('to');
    
        // Retrieve 'from' and 'to' dates from the URL
        \$from = \$request->input('from');
        \$to = \$request->input('to');
    
        // Default query for $modelNameLowerCase
        \$query = $modelName::query();
    
        // Convert dates to Carbon instances for better comparison
        \$fromDate = \$from ? Carbon::parse(\$from)->startOfDay() : null;
        \$toDate = \$to ? Carbon::parse(\$to)->endOfDay() : null;
    
        // Check if both 'from' and 'to' dates are provided
        if (\$fromDate && \$toDate) {
            // Ensure correct date filtering with full day range
            \$$modelNameLowerCase = \$query->whereBetween('created_at', [\$fromDate, \$toDate])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        } else {
            // If 'from' or 'to' are missing, show all $modelNameLowerCase without filtering
            \$$modelNameLowerCase = \$query->paginate(10);
        }
    
        // Return the view with $modelNameLowerCase and the selected date range
        return view('$modelNameLowerCase.$modelNameLowerCase', compact('$modelNameLowerCase', 'from', 'to'));
    });

    // end...";
                                            // Function to append after the last `// end...`
                                            function appendAfterLastEnd($filePath, $pattern, $data) {
                                                // Get the current content of the file
                                                $content = file_get_contents($filePath);

                                                // Find the position of the last occurrence of the pattern
                                                $position = strrpos($content, $pattern);

                                                if ($position !== false) {
                                                    // Move position to just after the pattern
                                                    $position += strlen($pattern);
                                                    // Append the data after the last occurrence of the pattern
                                                    $newContent = substr($content, 0, $position) . $data . substr($content, $position);

                                                    // Write the modified content back to the file
                                                    return file_put_contents($filePath, $newContent) !== false;
                                                } else {
                                                    return false;
                                                }
                                            }

                                            // Append the routes after the last occurrence of `// end...` or at the end if not found
                                            if (appendAfterLastEnd($filePath, '// end...', $textToAppend)) {
                                                $this->info("SUCCESS: Routes successfully appended to web.php.\n");
                                            } else {
                                                echo "Failed to append routes to web.php.\n";
                                            }


                                            // GENERATE VIEWS

                                            // Create the index view (show a table/list)
echo "Creating view $modelNameLowerCase/$modelNameLowerCase.blade.php...\n";
shell_exec("php artisan make:view $modelNameLowerCase.$modelNameLowerCase"); // index

$headlinesStringHandler = '';
$bodyStringHandler = '';

// loop attributes
foreach ($attributes as $attribute) {
    $column = $attribute['col'];
    $headlines = ucfirst($column);
    $headlinesStringHandler .= '<th>'.$headlines.'</th>';
    $bodyStringHandler .= '<td>{{ $item->'.$column.' }}</td>';
}

file_put_contents(resource_path("views/$modelNameLowerCase/$modelNameLowerCase.blade.php"), "
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>All $modelName</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
            <a href='{{ url('trash-$modelNameLowerCase') }}'><button class='btn btn-danger'><i class='fas fa-trash'></i> Trash <span class='text-warning'>{{ App\Models\\$modelName::where('isTrash', '1')->count() }}</span></button></a>
            <a href='{{ route('$modelNameLowerCase.create') }}'><button class='btn btn-success'><i class='fas fa-plus'></i> Add $modelName</button></a>
        </div>
    </div>
    
    <div class='card'>
        <div class='card-body'>
            <div class='row'>
                <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                    <div class='row'>
                        <div class='col-4'>
                            <button type='button' class='btn btn-outline-secondary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                Action
                            </button>
                            <div class='dropdown-menu'>
                                <a class='dropdown-item bulk-move-to-trash' href='#'>
                                    <i class='fa fa-trash'></i> Move to Trash
                                </a>
                                <a class='dropdown-item bulk-delete' href='#'>
                                    <i class='fa fa-trash'></i> <span class='text-danger'>Delete Permanently</span> <br> <small>(this action cannot be undone)</small>
                                </a>
                            </div>
                        </div>
                        <div class='col-8'>
                            <form action='{{ url('/$modelNameLowerCase-paginate') }}' method='get'>
                                <div class='input-group'>
                                    <input type='number' name='paginate' class='form-control' placeholder='Paginate' value='{{ request()->get('paginate', 10) }}'>
                                    <div class='input-group-append'>
                                        <button class='btn btn-success' type='submit'><i class='fa fa-bars'></i></button>
                                    </div>
                                </div>
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                    <form action='{{ url('/$modelNameLowerCase-filter') }}' method='get'>
                        <div class='input-group'>
                            <input type='date' class='form-control' id='from' name='from' required> 
                            <b class='pt-2'>- to -</b>
                            <input type='date' class='form-control' id='to' name='to' required>
                            <div class='input-group-append'>
                                <button type='submit' class='btn btn-primary form-control'><i class='fas fa-filter'></i></button>
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>
                <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                    <!-- Search Form -->
                    <form action='{{ url('/$modelNameLowerCase-search') }}' method='GET'>
                        <div class='input-group'>
                            <input type='text' name='search' value='{{ request()->get('search') }}' class='form-control' placeholder='Search...'>
                            <div class='input-group-append'>
                                <button class='btn btn-success' type='submit'><i class='fa fa-search'></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class='table-responsive'>
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'>
                            <input type='checkbox' name='' id='' class='checkAll'>
                            </th>
                            <th>#</th>
                            $headlinesStringHandler
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($$modelNameLowerCase as \$item)
                            <tr>
                                <th scope='row'>
                                    <input type='checkbox' name='' id='' class='check' data-id='{{ \$item->id }}'>
                                </th>
                                <td>{{ \$item->id }}</td>
                                $bodyStringHandler
                                <td>
                                    <a href='{{ route('$modelNameLowerCase.show', \$item->id) }}'><i class='fas fa-eye text-success'></i></a>
                                    <a href='{{ route('$modelNameLowerCase.edit', \$item->id) }}'><i class='fas fa-edit text-info'></i></a>
                                    <a href='{{ route('$modelNameLowerCase.delete', \$item->id) }}'><i class='fas fa-trash text-danger'></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>No Record...</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $".$modelNameLowerCase."->links('pagination::bootstrap-5') }}

    <script src='{{ url('assets/jquery/jquery.min.js') }}'></script>
    <script>
        $(document).ready(function () {

            // checkbox

            var click = false;
            $('.checkAll').on('click', function() {
                $('.check').prop('checked', !click);
                click = !click;
                this.innerHTML = click ? 'Deselect' : 'Select';
            });

            $('.bulk-delete').click(function () {
                let array = [];
                $('.check:checked').each(function() {
                    array.push($(this).attr('data-id'));
                });

                $.post('/{$modelNameLowerCase}-delete-all-bulk-data', {
                    ids: array,
                    _token: $(\"meta[name='csrf-token']\").attr('content')
                }, function (res) {
                    window.location.reload();
                })
            })

            $('.bulk-move-to-trash').click(function () {
                let array = [];
                $('.check:checked').each(function() {
                    array.push($(this).attr('data-id'));
                });

                $.post('/{$modelNameLowerCase}-move-to-trash-all-bulk-data', {
                    ids: array,
                    _token: $(\"meta[name='csrf-token']\").attr('content')
                }, function (res) {
                    window.location.reload();
                })
            })
        });
    </script>
@endsection
");
$this->info("SUCCESS: View $modelNameLowerCase/$modelNameLowerCase.blade.php created.\n");

// Create the trash (show a table/list)
echo "Creating trash $modelNameLowerCase/trash-$modelNameLowerCase.blade.php...\n";
shell_exec("php artisan make:view $modelNameLowerCase.trash-$modelNameLowerCase"); // index

$headlinesStringHandler = '';
$bodyStringHandler = '';

// loop attributes
foreach ($attributes as $attribute) {
    $column = $attribute['col'];
    $headlines = ucfirst($column);
    $headlinesStringHandler .= '<th>'.$headlines.'</th>';
    $bodyStringHandler .= '<td>{{ $item->'.$column.' }}</td>';
}

file_put_contents(resource_path("views/$modelNameLowerCase/trash-$modelNameLowerCase.blade.php"), "
@extends('layouts.main')

@section('content')
    <div class='row'>
        <div class='col-lg-6 col-md-6 col-sm-12'>
            <h1>Trash $modelName</h1>
        </div>
        <div class='col-lg-6 col-md-6 col-sm-12' style='text-align: right;'>
        </div>
    </div>
    
    <div class='card'>
        <div class='card-body'>
            <div class='row'>
                <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                    <div class='row'>
                        <div class='col-4'>
                            <button type='button' class='btn btn-outline-secondary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                Action
                            </button>
                            <div class='dropdown-menu'>
                                <a class='dropdown-item bulk-restore' href='#'>
                                    <i class='fa fa-recycle'></i> Restore
                                </a>
                                <a class='dropdown-item bulk-delete' href='#'>
                                    <i class='fa fa-trash'></i> <span class='text-danger'>Delete Permanently</span> <br> <small>(this action cannot be undone)</small>
                                </a>
                            </div>
                        </div>
                        <div class='col-8'>
                            <form action='{{ url('/$modelNameLowerCase-paginate') }}' method='get'>
                                <div class='input-group'>
                                    <input type='number' name='paginate' class='form-control' placeholder='Paginate' value='{{ request()->get('paginate', 10) }}'>
                                    <div class='input-group-append'>
                                        <button class='btn btn-success' type='submit'><i class='fa fa-bars'></i></button>
                                    </div>
                                </div>
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                    <form action='{{ url('/$modelNameLowerCase-filter') }}' method='get'>
                        <div class='input-group'>
                            <input type='date' class='form-control' id='from' name='from' required> 
                            <b class='pt-2'>- to -</b>
                            <input type='date' class='form-control' id='to' name='to' required>
                            <div class='input-group-append'>
                                <button type='submit' class='btn btn-primary form-control'><i class='fas fa-filter'></i></button>
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>
                <div class='col-lg-4 col-md-4 col-sm-12 mt-2'>
                    <!-- Search Form -->
                    <form action='{{ url('/$modelNameLowerCase-search') }}' method='GET'>
                        <div class='input-group'>
                            <input type='text' name='search' value='{{ request()->get('search') }}' class='form-control' placeholder='Search...'>
                            <div class='input-group-append'>
                                <button class='btn btn-success' type='submit'><i class='fa fa-search'></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class='table-responsive'>
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'>
                            <input type='checkbox' name='' id='' class='checkAll'>
                            </th>
                            <th>#</th>
                            $headlinesStringHandler
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($$modelNameLowerCase as \$item)
                            <tr>
                                <th scope='row'>
                                    <input type='checkbox' name='' id='' class='check' data-id='{{ \$item->id }}'>
                                </th>
                                <td>{{ \$item->id }}</td>
                                $bodyStringHandler
                                <td>
                                    <a href='{{ route('$modelNameLowerCase.restore', \$item->id) }}'><i class='fas fa-recycle text-info'></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>No Record...</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{ $".$modelNameLowerCase."->links('pagination::bootstrap-5') }}

    <script src='{{ url('assets/jquery/jquery.min.js') }}'></script>
    <script>
        $(document).ready(function () {

            // checkbox

            var click = false;
            $('.checkAll').on('click', function() {
                $('.check').prop('checked', !click);
                click = !click;
                this.innerHTML = click ? 'Deselect' : 'Select';
            });

            $('.bulk-delete').click(function () {
                let array = [];
                $('.check:checked').each(function() {
                    array.push($(this).attr('data-id'));
                });

                $.post('/{$modelNameLowerCase}-delete-all-bulk-data', {
                    ids: array,
                    _token: $(\"meta[name='csrf-token']\").attr('content')
                }, function (res) {
                    window.location.reload();
                })
            })

            $('.bulk-restore').click(function () {
                let array = [];
                $('.check:checked').each(function() {
                    array.push($(this).attr('data-id'));
                });

                $.post('/{$modelNameLowerCase}-restore-all-bulk-data', {
                    ids: array,
                    _token: $(\"meta[name='csrf-token']\").attr('content')
                }, function (res) {
                    console.log(res)
                    window.location.reload();
                })
            })
        });
    </script>
@endsection
");
$this->info("SUCCESS: Trash $modelNameLowerCase/trash-$modelNameLowerCase.blade.php created.\n");

// Create the create view (form to create a new item)
echo "Creating view $modelNameLowerCase/create-$modelNameLowerCase.blade.php...\n";
shell_exec("php artisan make:view $modelNameLowerCase.create-$modelNameLowerCase"); // create

$formDataHandler = '';

// loop attributes
foreach ($attributes as $attribute) {
    $column = $attribute['col'];
    $headlines = ucfirst($column);
    $formDataHandler .= "
        <div class='form-group'>
            <label for='name'>$headlines</label>
            <input type='text' class='form-control' id='$column' name='$column' required>
        </div>
    ";
}

file_put_contents(resource_path("views/$modelNameLowerCase/create-$modelNameLowerCase.blade.php"), "
@extends('layouts.main')

@section('content')
    <h1>Create a new $modelNameLowerCase</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('$modelNameLowerCase.store') }}' method='POST'>
                @csrf
                $formDataHandler
                <button type='submit' class='btn btn-primary mt-3'>Create</button>
            </form>
        </div>
    </div>

@endsection
");
$this->info("SUCCESS: View $modelNameLowerCase/create-$modelNameLowerCase.blade.php created.\n");

// Create the edit view (form to edit an existing item)
echo "Creating view $modelNameLowerCase/edit-$modelNameLowerCase.blade.php...\n";
shell_exec("php artisan make:view $modelNameLowerCase.edit-$modelNameLowerCase"); // edit

$formDataHandler = '';

// loop attributes
foreach ($attributes as $attribute) {
    $column = $attribute['col'];
    $headlines = ucfirst($column);
    $formDataHandler .= "
        <div class='form-group'>
            <label for='name'>$headlines</label>
            <input type='text' class='form-control' id='$column' name='$column' value='{{ \$item->$column }}' required>
        </div>
    ";
}

file_put_contents(resource_path("views/$modelNameLowerCase/edit-$modelNameLowerCase.blade.php"), "
@extends('layouts.main')

@section('content')
    <h1>Edit $modelName</h1>

    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('$modelNameLowerCase.update', \$item->id) }}' method='POST'>
                @csrf
                $formDataHandler
                <button type='submit' class='btn btn-primary mt-3'>Update</button>
            </form>
        </div>
    </div>

@endsection
");
$this->info("SUCCESS: View $modelNameLowerCase/edit-$modelNameLowerCase.blade.php created.\n");

// Create the delete view (confirmation message)
echo "Creating view $modelNameLowerCase/delete-$modelNameLowerCase.blade.php...\n";
shell_exec("php artisan make:view $modelNameLowerCase.delete-$modelNameLowerCase"); // delete
file_put_contents(resource_path("views/$modelNameLowerCase/delete-$modelNameLowerCase.blade.php"), "
@extends('layouts.main')

@section('content')
    <h1>Are you sure you want to delete this $modelNameLowerCase?</h1>

    <form action='{{ route('$modelNameLowerCase.destroy', \$item->id) }}' method='GET'>
        @csrf
        @method('DELETE')
        <button type='submit' class='btn btn-danger'>Yes, Delete</button>
        <a href='{{ route('$modelNameLowerCase.index') }}' class='btn btn-secondary'>Cancel</a>
    </form>
@endsection
");
$this->info("SUCCESS: View $modelNameLowerCase/delete-$modelNameLowerCase.blade.php created.\n");

// Create the show view (show item details in a table)
echo "Creating view $modelNameLowerCase/show-$modelNameLowerCase.blade.php...\n";
shell_exec("php artisan make:view $modelNameLowerCase.show-$modelNameLowerCase"); // show

$showDataHandler = '';

// loop attributes
foreach ($attributes as $attribute) {
    $column = $attribute['col'];
    $headlines = ucfirst($column);
    $showDataHandler .= "
        <tr>
            <th>$headlines</th>
            <td>{{ \$item->$column }}</td>
        </tr>
    ";
}

file_put_contents(resource_path("views/$modelNameLowerCase/show-$modelNameLowerCase.blade.php"), "
@extends('layouts.main')

@section('content')
    <h1>$modelName Details</h1>

    <div class='card'>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table'>
                    <tr>
                        <th>ID</th>
                        <td>{{ \$item->id }}</td>
                    </tr>
                    $showDataHandler
                    <tr>
                        <th>Created At</th>
                        <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime(\$item->created_at) }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime(\$item->updated_at) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <a href='{{ route('$modelNameLowerCase.index') }}' class='btn btn-primary'>Back to List</a>
@endsection
");
$this->info("SUCCESS: View $modelNameLowerCase/show-$modelNameLowerCase.blade.php created.\n");

                                            // MIGRATING NEW TABLE

                                            echo "Migrating new table...\n";
                                            shell_exec("php artisan migrate"); // show
                                            $this->info("SUCCESS: New table migrated.\n");


                                            // EDIT CONTROLLER =============================================================================================================


                                            echo "Modifying controller...\n";
                                            /**
                                             * Updates the controller file with new code, completely replacing its content.
                                             *
                                             * @param string $controllerPath The path to the controller file.
                                             * @param string $newCode The new code to insert into the controller.
                                             * @return bool Returns true if the operation is successful, false otherwise.
                                             */
                                            function updateControllerMethods($controllerPath, $newCode) {
                                                // Check if the controller file exists
                                                if (!file_exists($controllerPath)) {
                                                    echo "Controller file does not exist.\n";
                                                    return false;
                                                }

                                                // Write the new code to the controller file, replacing the entire content
                                                $result = file_put_contents($controllerPath, $newCode);

                                                if ($result === false) {
                                                    echo "Failed to write to the controller file.\n";
                                                    return false;
                                                }
                                                return true;
                                            }

                                            $controllerDataHandler = [];

                                            // loop attributes
                                            foreach ($attributes as $attribute) {
                                                $column = $attribute['col'];
                                                array_push($controllerDataHandler, "'$column' => \$request->$column");
                                            }

                                            $stripped = json_encode($controllerDataHandler);
                                            $encodedControllerDataHandler = str_replace('"', '', $stripped);

                                            // Example usage:
                                            $controllerPath = 'app/Http/Controllers/'.$modelName.'Controller.php'; // Path to your controller file
                                            $newCode = <<<PHP
                                            <?php

                                            namespace App\Http\Controllers;

                                            use App\Models\{Logs, $modelName};
                                            use App\Http\Requests\Store{$modelName}Request;
                                            use App\Http\Requests\Update{$modelName}Request;
                                            use Illuminate\Http\Request;
                                            use Illuminate\Support\Facades\Auth;

                                            class {$modelName}Controller extends Controller {
                                                /**
                                                 * Display a listing of the resource.
                                                 */
                                                public function index()
                                                {
                                                    return view('{$modelNameLowerCase}.{$modelNameLowerCase}', [
                                                        '{$modelNameLowerCase}' => {$modelName}::where('isTrash', '0')->paginate(10)
                                                    ]);
                                                }

                                                public function trash()
                                                {
                                                    return view('{$modelNameLowerCase}.trash-{$modelNameLowerCase}', [
                                                        '{$modelNameLowerCase}' => {$modelName}::where('isTrash', '1')->paginate(10)
                                                    ]);
                                                }

                                                public function restore(\${$modelNameLowerCase}Id)
                                                {
                                                    /* Log ************************************************** */
                                                    \$oldName = {$modelName}::where('id', \${$modelNameLowerCase}Id)->value('name');
                                                    // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a {$modelName} "'.\$oldName.'".']);
                                                    /******************************************************** */

                                                    {$modelName}::where('id', \${$modelNameLowerCase}Id)->update(['isTrash' => '0']);

                                                    return redirect('/{$modelNameLowerCase}');
                                                }

                                                /**
                                                 * Show the form for creating a new resource.
                                                 */
                                                public function create()
                                                {
                                                    return view('{$modelNameLowerCase}.create-{$modelNameLowerCase}');
                                                }

                                                /**
                                                 * Store a newly created resource in storage.
                                                 */
                                                public function store(Store{$modelName}Request \$request)
                                                {
                                                    {$modelName}::create({$encodedControllerDataHandler});

                                                    /* Log ************************************************** */
                                                    // Logs::create(['log' => Auth::user()->name.' created a new {$modelName} '.'"'.\$request->name.'"']);
                                                    /******************************************************** */

                                                    return back()->with('success', '{$modelName} Added Successfully!');
                                                }

                                                /**
                                                 * Display the specified resource.
                                                 */
                                                public function show({$modelName} \${$modelNameLowerCase}, \${$modelNameLowerCase}Id)
                                                {
                                                    return view('{$modelNameLowerCase}.show-{$modelNameLowerCase}', [
                                                        'item' => {$modelName}::where('id', \${$modelNameLowerCase}Id)->first()
                                                    ]);
                                                }

                                                /**
                                                 * Show the form for editing the specified resource.
                                                 */
                                                public function edit({$modelName} \${$modelNameLowerCase}, \${$modelNameLowerCase}Id)
                                                {
                                                    return view('{$modelNameLowerCase}.edit-{$modelNameLowerCase}', [
                                                        'item' => {$modelName}::where('id', \${$modelNameLowerCase}Id)->first()
                                                    ]);
                                                }

                                                /**
                                                 * Update the specified resource in storage.
                                                 */
                                                public function update(Update{$modelName}Request \$request, {$modelName} \${$modelNameLowerCase}, \${$modelNameLowerCase}Id)
                                                {
                                                    /* Log ************************************************** */
                                                    \$oldName = {$modelName}::where('id', \${$modelNameLowerCase}Id)->value('name');
                                                    // Logs::create(['log' => Auth::user()->name.' updated a {$modelName} from "'.\$oldName.'" to "'.\$request->name.'".']);
                                                    /******************************************************** */

                                                    {$modelName}::where('id', \${$modelNameLowerCase}Id)->update({$encodedControllerDataHandler});

                                                    return back()->with('success', '{$modelName} Updated Successfully!');
                                                }

                                                /**
                                                 * Show the form for deleting the specified resource.
                                                 */
                                                public function delete({$modelName} \${$modelNameLowerCase}, \${$modelNameLowerCase}Id)
                                                {
                                                    return view('{$modelNameLowerCase}.delete-{$modelNameLowerCase}', [
                                                        'item' => {$modelName}::where('id', \${$modelNameLowerCase}Id)->first()
                                                    ]);
                                                }

                                                /**
                                                 * Remove the specified resource from storage.
                                                 */
                                                public function destroy({$modelName} \${$modelNameLowerCase}, \${$modelNameLowerCase}Id)
                                                {

                                                    /* Log ************************************************** */
                                                    \$oldName = {$modelName}::where('id', \${$modelNameLowerCase}Id)->value('name');
                                                    // Logs::create(['log' => Auth::user()->name.' deleted a {$modelName} "'.\$oldName.'".']);
                                                    /******************************************************** */

                                                    {$modelName}::where('id', \${$modelNameLowerCase}Id)->update(['isTrash' => '1']);

                                                    return redirect('/{$modelNameLowerCase}');
                                                }

                                                public function bulkDelete(Request \$request) {

                                                    foreach (\$request->ids as \$value) {

                                                        /* Log ************************************************** */
                                                        \$oldName = {$modelName}::where('id', \$value)->value('name');
                                                        // Logs::create(['log' => Auth::user()->name.' deleted a {$modelName} "'.\$oldName.'".']);
                                                        /******************************************************** */

                                                        \$deletable = {$modelName}::find(\$value);
                                                        \$deletable->delete();
                                                    }
                                                    return response()->json("Deleted");
                                                }

                                                public function bulkMoveToTrash(Request \$request) {

                                                    foreach (\$request->ids as \$value) {

                                                        /* Log ************************************************** */
                                                        \$oldName = {$modelName}::where('id', \$value)->value('name');
                                                        // Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') deleted a {$modelName} "'.\$oldName.'".']);
                                                        /******************************************************** */

                                                        \$deletable = {$modelName}::find(\$value);
                                                        \$deletable->update(['isTrash' => '1']);
                                                    }
                                                    return response()->json("Deleted");
                                                }

                                                public function bulkRestore(Request \$request)
                                                {
                                                    foreach (\$request->ids as \$value) {

                                                        /* Log ************************************************** */
                                                        \$oldName = {$modelName}::where('id', \$value)->value('name');
                                                        Logs::create(['log' => Auth::user()->name.' ('.Auth::user()->role.') restored a {$modelName} "'.\$oldName.'".']);
                                                        /******************************************************** */

                                                        \$restorable = {$modelName}::find(\$value);
                                                        \$restorable->update(['isTrash' => '0']);
                                                    }
                                                    return response()->json("Restored");
                                                }
                                            }
                                            PHP;

                                            // Call the function to update the controller
                                            updateControllerMethods($controllerPath, $newCode);

                                            $this->info("SUCCESS: Controller modified.\n");
                                        }
                                    ],
                                    [
                                        "command" => 'show-table',
                                        "description" => 'Display database table.',
                                        "action" => function () {

                                            // Display Available Models

                                            // Load all models in the Models directory
                                            $modelFiles = glob(app_path('Models') . '/*.php');
                                            $models = [];

                                            $this->info('Select Model');

                                            // Loop through the files and add each model to the list
                                            foreach ($modelFiles as $file) {
                                                $model = basename($file, '.php');
                                                $class = "App\\Models\\$model";

                                                if (class_exists($class)) {
                                                    $models[] = $model;
                                                }
                                            }

                                            // Display the list of models in a table format
                                            $this->table(['Models'], array_map(fn($model) => [$model], $models));

                                            // Ask for the model name
                                            $modelName = $this->ask('Model Name');
                                            $modelClass = 'App\\Models\\' . $modelName;

                                            if (class_exists($modelClass)) {
                                                // Instantiate the model and get the table name
                                                $instance = new $modelClass;
                                                $table = $instance->getTable();

                                                // Check if the table exists
                                                if (Schema::hasTable($table)) {
                                                    // Get the column names for the table
                                                    $availableColumns = Schema::getColumnListing($table);

                                                    // Display the available columns in a table format
                                                    $this->info("Available Columns for $modelName:");
                                                    $this->table(['Columns'], array_map(fn($column) => [$column], $availableColumns));

                                                    // Ask the user to select columns from the available columns
                                                    $selectedColumns = $this->ask('Columns to display (separated by commas)');

                                                    // Convert the selected columns into an array and filter by available columns
                                                    $columns = array_intersect(explode(',', $selectedColumns), $availableColumns);

                                                    // Fetch and display data for the selected columns
                                                    $data = $modelClass::select($columns)->get();

                                                    $this->info("$modelName Table");
                                                    $this->table($columns, $data->toArray());

                                                } else {
                                                    $this->error("Table for $modelName not found!");
                                                }
                                            } else {
                                                $this->error("Model $modelName not found!");
                                            }

                                        }
                                    ],
                                    [
                                        "command" => 'show-models',
                                        "description" => 'Display all models.',
                                        "action" => function () {

                                            $modelFiles = glob(app_path('Models') . '/*.php');
                                            $models = [];

                                            foreach ($modelFiles as $file) {
                                                // Get the model class name without the extension
                                                $model = basename($file, '.php');

                                                // Construct the fully qualified class name
                                                $class = "App\\Models\\$model";

                                                // Check if the class exists
                                                if (class_exists($class)) {
                                                    $models[] = $model;
                                                }
                                            }

                                            // Display the models in a single column
                                            $this->table(['Models'], array_map(fn($model) => [$model], $models));

                                        }
                                    ],
                                    [
                                        "command" => 'input-data',
                                        "description" => 'Input data for a selected model based on its columns, excluding auto-managed columns.',
                                        "action" => function () {
                                            // Step 1: Get list of all models
                                            $modelFiles = glob(app_path('Models') . '/*.php');
                                            $models = [];

                                            foreach ($modelFiles as $file) {
                                                $model = basename($file, '.php');
                                                $class = "App\\Models\\$model";

                                                if (class_exists($class)) {
                                                    $models[] = $model;
                                                }
                                            }

                                            // Display available models in a table format
                                            $this->table(['Models'], array_map(fn($model) => [$model], $models));

                                            // Step 2: Prompt the user to select a model
                                            $modelName = $this->ask('Model Name');
                                            $modelClass = "App\\Models\\$modelName";

                                            if (class_exists($modelClass)) {
                                                // Instantiate the model and get the table name
                                                $instance = new $modelClass;
                                                $table = $instance->getTable();

                                                // Step 3: Check if the table exists and get columns
                                                if (Schema::hasTable($table)) {
                                                    // Get the column names and data types, excluding 'id', 'created_at', and 'updated_at'
                                                    $columns = Schema::getColumnListing($table);
                                                    $columnDataTypes = collect($columns)
                                                        ->reject(fn($column) => in_array($column, ['id', 'created_at', 'updated_at']))
                                                        ->mapWithKeys(function ($column) use ($table) {
                                                            return [$column => Schema::getColumnType($table, $column)];
                                                        });

                                                    // Display columns with data types
                                                    $this->info("Available Columns for $modelName (excluding auto-managed columns):");
                                                    $this->table(['Column', 'Data Type'], $columnDataTypes->map(fn($type, $column) => [$column, $type])->toArray());

                                                    // Step 4: Gather input for each column
                                                    $inputData = [];
                                                    foreach ($columnDataTypes as $column => $type) {
                                                        $value = $this->ask("Enter value for $column ($type):");
                                                        $inputData[$column] = $value;
                                                    }

                                                    // Step 5: Insert data into the model's table
                                                    $instance->fill($inputData);
                                                    $instance->save();

                                                    $this->info("Data successfully inserted into $modelName.");

                                                } else {
                                                    $this->error("Table for $modelName not found!");
                                                }
                                            } else {
                                                $this->error("Model $modelName not found!");
                                            }
                                        }
                                    ],
                                    [
                                        "command" => 'update-data',
                                        "description" => 'Update data for a selected model based on its columns, excluding auto-managed columns.',
                                        "action" => function () {
                                            // Step 1: Get list of all models
                                            $modelFiles = glob(app_path('Models') . '/*.php');
                                            $models = [];

                                            foreach ($modelFiles as $file) {
                                                $model = basename($file, '.php');
                                                $class = "App\\Models\\$model";

                                                if (class_exists($class)) {
                                                    $models[] = $model;
                                                }
                                            }

                                            // Display available models in a table format
                                            $this->table(['Models'], array_map(fn($model) => [$model], $models));

                                            // Step 2: Prompt the user to select a model
                                            $modelName = $this->ask('Model Name');
                                            $modelClass = "App\\Models\\$modelName";

                                            if (class_exists($modelClass)) {
                                                // Instantiate the model and get the table name
                                                $instance = new $modelClass;
                                                $table = $instance->getTable();

                                                // Step 3: Check if the table exists and get columns
                                                if (Schema::hasTable($table)) {
                                                    // Get the column names and data types, excluding 'id', 'created_at', and 'updated_at'
                                                    $columns = Schema::getColumnListing($table);
                                                    $columnDataTypes = collect($columns)
                                                        ->reject(fn($column) => in_array($column, ['id', 'created_at', 'updated_at']))
                                                        ->mapWithKeys(function ($column) use ($table) {
                                                            return [$column => Schema::getColumnType($table, $column)];
                                                        });

                                                    // Display columns with data types
                                                    $this->info("Available Columns for $modelName (excluding auto-managed columns):");
                                                    $this->table(['Column', 'Data Type'], $columnDataTypes->map(fn($type, $column) => [$column, $type])->toArray());

                                                    // Step 4: Ask for the record ID to update
                                                    $recordId = $this->ask('Enter the ID of the record to update');
                                                    $record = $modelClass::find($recordId);

                                                    if ($record) {
                                                        $this->info("Current data for record ID $recordId:");
                                                        $this->table(array_keys($record->toArray()), [array_values($record->toArray())]);

                                                        // Step 5: Gather updated values for each column
                                                        $updatedData = [];
                                                        foreach ($columnDataTypes as $column => $type) {
                                                            $currentValue = $record->$column;
                                                            $newValue = $this->ask("Enter new value for $column ($type) [Current: $currentValue]");

                                                            // Only update if a new value is provided
                                                            if (!is_null($newValue) && $newValue !== '') {
                                                                $updatedData[$column] = $newValue;
                                                            }
                                                        }

                                                        // Update the record with new data
                                                        $record->update($updatedData);

                                                        $this->info("Record ID $recordId successfully updated in $modelName.");

                                                    } else {
                                                        $this->error("Record with ID $recordId not found in $modelName.");
                                                    }

                                                } else {
                                                    $this->error("Table for $modelName not found!");
                                                }
                                            } else {
                                                $this->error("Model $modelName not found!");
                                            }
                                        }
                                    ],
                                    [
                                        "command" => 'delete-data',
                                        "description" => 'Delete data for a selected model based on record ID.',
                                        "action" => function () {
                                            // Step 1: Get list of all models
                                            $modelFiles = glob(app_path('Models') . '/*.php');
                                            $models = [];

                                            foreach ($modelFiles as $file) {
                                                $model = basename($file, '.php');
                                                $class = "App\\Models\\$model";

                                                if (class_exists($class)) {
                                                    $models[] = $model;
                                                }
                                            }

                                            // Display available models in a table format
                                            $this->table(['Models'], array_map(fn($model) => [$model], $models));

                                            // Step 2: Prompt the user to select a model
                                            $modelName = $this->ask('Model Name');
                                            $modelClass = "App\\Models\\$modelName";

                                            if (class_exists($modelClass)) {
                                                // Step 3: Ask for the record ID to delete
                                                $recordId = $this->ask('Enter the ID of the record to delete');
                                                $record = $modelClass::find($recordId);

                                                if ($record) {
                                                    // Display the record details before deleting
                                                    $this->info("Details of record ID $recordId:");
                                                    $this->table(array_keys($record->toArray()), [array_values($record->toArray())]);

                                                    // Confirm deletion
                                                    if ($this->confirm("Are you sure you want to delete this record? This action cannot be undone.", false)) {
                                                        $record->delete();
                                                        $this->info("Record ID $recordId successfully deleted from $modelName.");
                                                    } else {
                                                        $this->info("Deletion canceled.");
                                                    }

                                                } else {
                                                    $this->error("Record with ID $recordId not found in $modelName.");
                                                }
                                            } else {
                                                $this->error("Model $modelName not found!");
                                            }
                                        }
                                    ],
                                    [
                                        "command" => 'list-routes',
                                        "description" => 'List all registered routes with URI, Method, and Action.',
                                        "action" => function () {
                                            $routes = collect(Route::getRoutes())->map(function ($route) {
                                                return [
                                                    'Method' => implode('|', $route->methods),
                                                    'URI' => $route->uri,
                                                    'Name' => $route->getName(),
                                                    'Action' => $route->getActionName()
                                                ];
                                            });

                                            $this->table(['Method', 'URI', 'Name', 'Action'], $routes->toArray());
                                        }
                                    ],
                                    [
                                        "command" => 'input-command',
                                        "description" => 'Execute a custom Eloquent query on a specified model.',
                                        "action" => function () {
                                            // Step 1: Get a list of all models
                                            $modelFiles = glob(app_path('Models') . '/*.php');
                                            $models = [];

                                            foreach ($modelFiles as $file) {
                                                $model = basename($file, '.php');
                                                $class = "App\\Models\\$model";

                                                if (class_exists($class)) {
                                                    $models[] = $model;
                                                }
                                            }

                                            // Display available models in a table format
                                            $this->table(['Models'], array_map(fn($model) => [$model], $models));

                                            // Step 2: Prompt the user to select a model
                                            $modelName = $this->ask('Model Name');
                                            $modelClass = "App\\Models\\$modelName";

                                            if (class_exists($modelClass)) {
                                                // Step 3: Prompt for Eloquent query input
                                                $this->info("Write a full Eloquent query for the $modelName model (e.g., where('column', 'value')->get()).");
                                                $queryInput = $this->ask('Eloquent Query');

                                                try {
                                                    // Step 4: Evaluate and execute the query dynamically
                                                    $query = eval("return $modelClass::$queryInput;");

                                                    // Step 5: Check if the query result is empty or not
                                                    if ($query->isEmpty()) {
                                                        $this->warn("No records found.");
                                                    } else {
                                                        // Display the query result in a table
                                                        $columns = array_keys($query->first()->toArray());
                                                        $this->table($columns, $query->toArray());
                                                    }
                                                } catch (\Throwable $e) {
                                                    $this->error("Invalid query or error executing the query: " . $e->getMessage());
                                                }
                                            } else {
                                                $this->error("Model $modelName not found!");
                                            }
                                        }
                                    ],
                                    [
                                        "command" => 'smark',
                                        "description" => 'Display Smark classes and functions.',
                                        "action" => function () {
                                            $smark = [
                                                '*** ARRAYER ***',
                                                '',
                                                'Arrayer::flattenArray($array)',
                                                'Arrayer::uniqueMultidimensionalArray($array, $key)',
                                                '',
                                                '*** STRINGER ***',
                                                '',
                                                'Stringer::toCamelCase($string)',
                                                'Stringer::truncateString($string, $length)',
                                                'Stringer::sanitizeInput($input)',
                                                'Stringer::generateSlug($string)',
                                                '',
                                                '*** DATER ***',
                                                '',
                                                'Dater::calculateAge($dob)',
                                                'Dater::humanReadableDateWithDayAndTime($date)   // Month day, Year (Day of the week) hour:minute am/pm',
                                                'Dater::humanReadableDateWithDay($date)          // Month day, Year (Day of the week)',
                                                'Dater::humanReadableDate($date)                 // Month day, Year',
                                                'Dater::humanReadableDay($date)                  // Day of the week',
                                                'Dater::humanReadableTime($date)                 // hour:minute am/pm',
                                                'Dater::humanReadableMonth($date)                // Month word',
                                                'Dater::getWeekdays($startDate, $endDate)',
                                                'Dater::getDays($startDate, $endDate)',
                                                '',
                                                '*** ENCRYPTER ***',
                                                '',
                                                'Encryption::encrypter($data, $key)',
                                                'Encryption::decrypter($data, $key)',
                                                '',
                                                '*** EXCEL ***',
                                                '',
                                                'Excel::downloadExcel($excelArray, $source)',
                                                'Excel::downloadExcelAs($filename, $excelArray, $source)',
                                                'Excel::_downloadExcel($excelArray, $source)',
                                                'Excel::_downloadExcelAs($filename, $excelArray, $source)',
                                                '',
                                                '*** FILE ***',
                                                '',
                                                'File::$filename',
                                                'File::upload($request, $path)',
                                                'File::removeFile($path)',
                                                'File::$_filename',
                                                'File::_upload($filename_input, $file_path, $filename_valid_extension)',
                                                '',
                                                '*** HTML ***',
                                                '',
                                                'HTML::renderHTML($code)',
                                                'HTML::withURL($string)',
                                                'HTML::generateQRCode($data)',
                                                'HTML::generateBarCode($data)',
                                                'HTML::filamentMonths()',
                                                'HTML::filamentYears($startYear)',
                                                'HTML::readMarkdown()',
                                                '',
                                                '*** JSON ***',
                                                '',
                                                'JSON::jsonRead($json_filename)',
                                                'JSON::jsonPush($json_filename, $data_to_be_inserted)',
                                                'JSON::jsonUnshift($json_filename, $data_to_be_inserted)',
                                                'JSON::jsonDelete($json_filename, $data_key_to_be_deleted, $data_value_to_be_deleted)',
                                                'JSON::jsonUpdate($json_filename, $data_key_to_be_updated, $data_value_to_be_updated, $key_to_insert_new_updated_data, $new_updated_data)',
                                                'JSON::handleError($message)',
                                                '',
                                                '*** MAIL ***',
                                                '',
                                                'Mail::send()',
                                                'Mail::sendFromForm()',
                                                '',
                                                '*** MATH ***',
                                                '',
                                                'Math::compute($method, $nums)',
                                                'Math::isEven($num)',
                                                'Math::linearRegression($xValues, $yValues, $result)',
                                                'Math::calculateTotalPrice($items, $discountThreshold, $discountRate, $taxRate)',
                                                'Math::calculateBMI($weight, $height)',
                                                'Math::generateReceiptNumber()',
                                                'Math::factorial($number)',
                                                'Math::fibonacci($n)',
                                                'Math::calculateQuadraticRoots($a, $b, $c)',
                                                'Math::gcd($a, $b)',
                                                'Math::matrixMultiply($matrixA, $matrixB)',
                                                'Math::gaussianElimination($matrix)',
                                                '',
                                                '*** PAYMENT ***',
                                                '',
                                                'Payment::paymongoCreatePaymentLink($paymentDetails)',
                                                '',
                                                '*** PDFER ***',
                                                '',
                                                'PDFer::export($data)',
                                                '',
                                                '*** QUEUE ***',
                                                '',
                                                'Queue::push($task)',
                                                'Queue::run()',
                                                '',
                                                '*** WEB ***',
                                                '',
                                                'Web::scrapeWithCssSelectors($url, $cssSelectors)',
                                                'Web::extractScriptsAndLinks($url)',
                                                'Web::extractEmails($url)',
                                                'Web::extractImages($url)',
                                            ];
                                            $this->info("Installation: composer require smark/smark");
                                            $this->line(""); // Add a blank line for spacing
                                            $this->info("Available Functions: " . count($smark));
                                            $this->line(""); // Add a blank line for spacing

                                            // Define a fixed width for the func column

                                            foreach ($smark as $key => $value) {
                                                $this->info($value);
                                            }

                                            $this->line(""); // Add a blank line at the end for spacing
                                        }
                                    ],
                                    [
                                        "command" => 'test-mail',
                                        "description" => 'Send a test email to verify email functionality.',
                                        "action" => function () {
                                            // Prompt the user for the email address
                                            $email = $this->ask('Please enter the email address to send the test email to');

                                            // Validate the email format
                                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                // Prompt the user for a custom message
                                                $messageContent = $this->ask('Please enter the message to include in the test email');

                                                // Send the test email with the user-provided message
                                                Mail::raw($messageContent, function ($message) use ($email) {
                                                    $message->to($email)->subject('Test Email');
                                                });

                                                // Inform the user that the email has been sent
                                                $this->info("Test email successfully sent to {$email}");
                                            } else {
                                                // If the email is not valid, show an error message
                                                $this->error("The email address '{$email}' is not valid.");
                                            }
                                        }
                                    ],
                                    [
                                        "command" => 'generate-lorem',
                                        "description" => 'Generate Lorem Ipsum text',
                                        "action" => function () {
                                            // Ask the user for the length of the Lorem Ipsum text
                                            $count = $this->ask('How many words of Lorem Ipsum would you like to generate?', 100); // Default to 100 if no input

                                            // Ensure the length is an integer
                                            $count = (int) $count;

                                            // Validate that the length is a positive integer
                                            if ($count <= 0) {
                                                $this->error('Please enter a valid positive number for the length.');
                                                return;
                                            }

                                            // Generate Lorem Ipsum text (use a package like "faker" or create your own generator)
                                            $lorem = \Faker\Factory::create()->text($count * 5); // Multiply by 5 because text() returns a string of roughly 5 words per "word length"

                                            // Display the generated Lorem Ipsum text
                                            $this->info("\"$lorem\"");
                                            echo "\n";
                                        }
                                    ]
                                ];

                                echo "\n";

                                $query = $this->ask("Putter - ".$user->name);

                                $found = false;

                                foreach ($queries as $value) {
                                    if ($query === $value['command']) {
                                        $this->info($value['description']);
                                        $value['action']();
                                        $found = true;
                                        break;
                                    }


                                }

                                if ($query === '--help') {
                                    $this->info("Available Commands: " . count($queries));
                                    $this->line(""); // Add a blank line for spacing

                                    // Define a fixed width for the command column
                                    $commandWidth = 20;

                                    // Sort the $queries array alphabetically by 'command' value
                                    usort($queries, function($a, $b) {
                                        return strcmp($a['command'], $b['command']);
                                    });

                                    foreach ($queries as $key => $value) {
                                        // Pad the command with dashes to ensure alignment
                                        $command = str_pad($value['command'], $commandWidth, ' ', STR_PAD_RIGHT);
                                        $description = $value['description'];

                                        // Output the command and description in aligned format
                                        $this->info($command . "-> " . $description);
                                    }

                                    $this->line(""); // Add a blank line at the end for spacing
                                }

                                if ($query === 'logout') {
                                    break;
                                }

                                if (!$found && $query != '--help' && $query != 'logout') {
                                    $this->error('Invalid Command  "' . $query . '"');
                                }

                                echo "\e[34mPress CTRL + C to exit.\e[0m\n";  // Blue text
                                echo "\e[33mType --help to display available commands.\e[0m\n";  // Yellow text
                            }

                            // END OF APPLICATION
                        } else {
                            echo "Invalid Username/Email or Password \n";
                        }
                    }
                ],
                [
                    "command" => 'register',
                    "description" => 'Register Form',
                    "action" => function () {

                        $name = $this->ask('Name');
                        $email = $this->ask('Email');
                        $password = $this->ask('Password');

                        // Create a new user
                        User::create([
                            'name' => $name,
                            'email' => $email,
                            'password' => Hash::make($password),
                        ]);

                        $this->info('Success!, You can now login.');
                    }
                ]
            ];

            echo "\n";

            $query = $this->ask('Putter');

            $found = false;

            foreach ($queries as $value) {
                if ($query === $value['command']) {
                    $this->info($value['description']);
                    $value['action']();
                    $found = true;
                    break;
                }


            }

            if ($query === '--help') {
                $this->info("Available Commands: " . count($queries));
                $this->line(""); // Add a blank line for spacing

                // Define a fixed width for the command column
                $commandWidth = 20;

                foreach ($queries as $key => $value) {
                    // Pad the command with dashes to ensure alignment
                    $command = str_pad($value['command'], $commandWidth, ' ', STR_PAD_RIGHT);
                    $description = $value['description'];

                    // Output the command and description in aligned format
                    $this->info($command . "-> " . $description);
                }

                $this->line(""); // Add a blank line at the end for spacing
            }

            if (!$found && $query != '--help') {
                $this->error('Invalid Command  "' . $query . '"');
            }

            echo "\e[34mPress CTRL + C to exit.\e[0m\n";  // Blue text
            echo "\e[33mType --help to display available commands.\e[0m\n";  // Yellow text
        }
    }
}
