<?php

use App\Models\Taskassignments;
use App\Models\Tasks;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Smark\Smark\JSON;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

Artisan::command('mailtest', function () {
    Mail::raw('This is the email body. Sample 2', function ($message) {
        $message->to('mark.medivadigital@gmail.com')
                ->subject('No view email');
    });
    $this->info('Mail Test');
})->purpose('Mail Test');

Artisan::command('backup', function () {
    $this->info('📦 Starting backup...');

    // Backup directory
    $backupPath = public_path('backup');

    // Create backup folder if it doesn't exist
    if (!File::exists($backupPath)) {
        File::makeDirectory($backupPath, 0755, true);
    } else {
        // Delete all files in the backup folder
        $files = File::files($backupPath);
        foreach ($files as $file) {
            File::delete($file);
        }
        $this->info('🧹 Old backup files deleted.');
    }

    // Create SQL file
    $sqlFile = $backupPath . '/database.sql';
    $tables = DB::select('SHOW TABLES');
    $dbName = env('DB_DATABASE');
    $key = "Tables_in_$dbName";

    $sqlDump = '';

    foreach ($tables as $table) {
        $tableName = $table->$key;
        $create = DB::select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'};
        $sqlDump .= "\n\n-- Table structure for `$tableName`\n\n$create;\n";

        $rows = DB::table($tableName)->get();
        foreach ($rows as $row) {
            $columns = array_map(fn($v) => "`$v`", array_keys((array)$row));
            $values = array_map(fn($v) => is_null($v) ? 'NULL' : DB::getPdo()->quote($v), array_values((array)$row));
            $sqlDump .= "INSERT INTO `$tableName` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
        }
    }

    File::put($sqlFile, $sqlDump);

    // Create zip
    $timestamp = now()->format('Y-m-d_H-i-s');
    $zipPath = $backupPath . "/backup-{$timestamp}.zip";

    $zip = new \ZipArchive;
    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
        $this->info("📁 Zipping files...");

        $allFilesAndDirs = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(base_path(), RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($allFilesAndDirs as $file) {
            if (strpos($file->getRealPath(), base_path('vendor')) !== false) {
                continue;
            }

            $relativePath = ltrim(str_replace(base_path(), '', $file->getRealPath()), '/\\');
            $zip->addFile($file->getRealPath(), $relativePath);
        }

        // Include the generated SQL
        $zip->addFile($sqlFile, 'database.sql');

        $zip->close();
        $this->info("✅ Backup complete: {$zipPath}");
    } else {
        $this->error('❌ Failed to create zip file.');
    }

    File::delete($sqlFile);
    return 0;
});

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('sched', function () {

    // Get the current time
    $currentTime = Carbon::now();

    // If today is Sunday, do not proceed
    if ($currentTime->isSunday()) {
        $this->info('Today is Sunday. Scheduled tasks will not run.');
        return;
    }

    // Check if the time is exactly 8:00 AM
    if ($currentTime->format('H:i') == '08:00') {
        $scheduledTasks = Tasks::where('isScheduled', 1)->get();

        foreach ($scheduledTasks as $scheduledTask) {
            $newScheduledTask = Tasks::create([
                'name' => $scheduledTask['name'],
                'status' => 'pending',
                'projects_id' => $scheduledTask['projects_id'],
                'projects_workspaces_id' => $scheduledTask['projects_workspaces_id'],
                'deadline' => $scheduledTask['deadline'],
                'priority' => $scheduledTask['priority'],
                'isScheduled' => 0,
            ]);

            $assigneesOfScheduledTasks = Taskassignments::where('tasks_id', $scheduledTask['id'])->get();

            foreach ($assigneesOfScheduledTasks as $assignee) {
                Taskassignments::create([
                    'tasks_id' => $newScheduledTask->id,
                    'tasks_projects_id' => $assignee['tasks_projects_id'],
                    'tasks_projects_workspaces_id' => $assignee['tasks_projects_workspaces_id'],
                    'users_id' => $assignee['users_id'],
                    'role' => $assignee['role'],
                    'isLeadAssignee' => $assignee['isLeadAssignee']
                ]);
            }
        }

        $this->info('Running scheduled tasks at 8:00 AM...');
    } else {
        $this->info('It is not the scheduled time to run the task.');
    }

})->purpose('Scheduled Tasks')->dailyAt('08:00');

// OMADA

Artisan::command('sync', function () {

    // GENERATE NEW API KEY  ========================================================================================================

    function generateNewAPIAccessToken()
    {
        // $postRequestForNewApiKey = Http::withHeaders([
        //     'Content-Type' => 'application/json',  // Optional, can be inferred from the `json` method
        // ])->withOptions([
        //     'verify' => false,
        // ])->post(env('OMADAC_SERVER').'/openapi/authorize/token?grant_type=client_credentials', [
        //     'omadacId' => env('OMADAC_ID'),
        //     'client_id' => env('CLIENT_ID'),
        //     'client_secret' => env('CLIENT_SECRET'),
        // ]);
        $postRequestForNewApiKey = Http::withHeaders([
            'Content-Type' => 'application/json',  // Optional, can be inferred from the `json` method
        ])->withOptions([
            'verify' => false,
        ])->post('https://aps1-omada-northbound.tplinkcloud.com/openapi/authorize/token?grant_type=client_credentials', [
            'omadacId' => 'cce89a5bc86ca8d4a0325406f500fc96',
            'client_id' => '5c4d4a9c18fb4b6cbee4b3a87f50c8b5',
            'client_secret' => '089d10233e9048beadd5f5e3d21fbfac'
        ]);

        if (isset($responseBody['errorCode'])) {
            // Handle error condition
            dd('API error: ' . $responseBody['errorCode']);
        }

        // Decode the response body from JSON to an array
        $responseBody = json_decode($postRequestForNewApiKey->body(), true);  // Decode into an associative array

        // Logs::create(['log' => 'A new Access Token has been successfully generated on '.Dater::humanReadableDateWithDayAndTime(date('F j, Y g:i:s'))]);

        return JSON::jsonUnshift('public/accessTokenStorage/accessTokens.json', $responseBody['result']);
    }

    // CUSTOMERS ======================================================================================================================

    // function queryCustomersDataFromTheDatabase($latestAccessTokenParam)
    // {
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer AccessToken='.$latestAccessTokenParam, // Replace with your API key
    //     ])->withOptions([
    //         'verify' => false,
    //     ])->get('https://aps1-omada-northbound.tplinkcloud.com/openapi/v1/cce89a5bc86ca8d4a0325406f500fc96/customers?page=1&pageSize=1000');

    //     unlink('public/omada/customers.json');
    //     file_put_contents('public/omada/customers.json', json_encode([]));

    //     foreach ($response['result']['data'] as $key => $value) {
    //         JSON::jsonPush('public/omada/customers.json', [
    //             'customerId' => isset($value['customerId']) ? $value['customerId'] : null,
    //             'name' => isset($value['customerName']) ? $value['customerName'] : null,
    //             'description' => isset($value['description']) ? $value['description'] : null, // Check if description exists
    //             'users_id' => 1, // changed
    //         ]);
    //     }
    // }

    // END CUSTOMERS

    // SITES ======================================================================================================================

    function querySitesDataFromTheDatabase($latestAccessTokenParam)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer AccessToken='.$latestAccessTokenParam, // Replace with your API key
        ])->withOptions([
            'verify' => false,
        ])->get('https://aps1-omada-northbound.tplinkcloud.com/openapi/v1/cce89a5bc86ca8d4a0325406f500fc96/sites?page=1&pageSize=1000');

        unlink('public/omada/sites.json');
        file_put_contents('public/omada/sites.json', json_encode([]));

        foreach ($response['result']['data'] as $key => $value) {

            JSON::jsonPush('public/omada/sites.json', [
                'name' => isset($value['name']) ? $value['name'] : null,
                'siteId' => isset($value['siteId']) ? $value['siteId'] : null,
                'customerId' => isset($value['customerId']) ? $value['customerId'] : null,
                'customerName' => isset($value['customerName']) ? $value['customerName'] : null,
                'region' => isset($value['region']) ? $value['region'] : null,
                'timezone' => isset($value['timeZone']) ? $value['timeZone'] : null,
                'scenario' => isset($value['scenario']) ? $value['scenario'] : null,
                'wan' => isset($value['wan']) ? $value['wan'] : null,
                'connectedApNum' => isset($value['connectedApNum']) ? $value['connectedApNum'] : null,
                'disconnectedApNum' => isset($value['disconnectedApNum']) ? $value['disconnectedApNum'] : null,
                'isolatedApNum' => isset($value['isolatedApNum']) ? $value['isolatedApNum'] : null,
                'connectedSwitchNum' => isset($value['connectedSwitchNum']) ? $value['connectedSwitchNum'] : null,
                'disconnectedSwitchNum' => isset($value['disconnectedSwitchNum']) ? $value['disconnectedSwitchNum'] : null,
                'type' => isset($value['type']) ? $value['type'] : null,
            ]);
        }

        // Return a success response
        return response()->json([
            'message' => 'Sites data updated successfully!',
        ]);
    }

    // END SITES

    // AUDIT LOGS ======================================================================================================================

    function queryAuditLogsDataFromTheDatabase($latestAccessTokenParam)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer AccessToken='.$latestAccessTokenParam, // Replace with your API key
        ])->withOptions([
            'verify' => false,
        ])->get('https://aps1-omada-northbound.tplinkcloud.com/openapi/v1/cce89a5bc86ca8d4a0325406f500fc96/audit-logs?page=1&pageSize=1000');

        unlink('public/omada/audit_logs.json');
        file_put_contents('public/omada/audit_logs.json', json_encode([]));

        foreach ($response['result']['data'] as $key => $value) {
            JSON::jsonPush('public/omada/audit_logs.json', [
                'time' => isset($value['time']) ? $value['time'] : null,
                'operator' => isset($value['operator']) ? $value['operator'] : null,
                'resource' => isset($value['resource']) ? $value['resource'] : null,
                'ip' => isset($value['ip']) ? $value['ip'] : null,
                'auditType' => isset($value['auditType']) ? $value['auditType'] : null,
                'level' => isset($value['level']) ? $value['level'] : null,
                'result' => isset($value['result']) ? $value['result'] : null,
                'content' => isset($value['content']) ? $value['content'] : null,
                'content' => isset($value['content']) ? $value['content'] : null,
                'label' => isset($value['label']) ? $value['label'] : null,
                'oldValue' => isset($value['oldValue']) ? $value['oldValue'] : null,
                'newValue' => isset($value['newValue']) ? $value['newValue'] : null,
            ]);
        }

        // Return a success response
        return response()->json([
            'message' => 'Audit Type data updated successfully!',
        ]);
    }

    // END AUDIT LOGS

    // get the stored latest access token

    $latestAccessToken = JSON::jsonRead('public/accessTokenStorage/accessTokens.json')[0]['accessToken'];

    // CHECKING API ACCESS TOKEN IF EXPIRED ===========================================================================================

    $response = Http::withHeaders([
        'Authorization' => 'Bearer AccessToken='.$latestAccessToken, // Replace with your API key
    ])->withOptions([
        'verify' => false,
    ])->get('https://aps1-omada-northbound.tplinkcloud.com/openapi/v1/cce89a5bc86ca8d4a0325406f500fc96/system/setting/controller-status');

    $expirationBasisThroughErrorCode = $response['errorCode'];

    // EXECUTE ========================================================================================================================

    if ($expirationBasisThroughErrorCode == 0) {

        // queryCustomersDataFromTheDatabase($latestAccessToken);
        querySitesDataFromTheDatabase($latestAccessToken);
        queryAuditLogsDataFromTheDatabase($latestAccessToken);

        // Logs::create(['log' => 'The database has been successfully synchronized on '.Dater::humanReadableDateWithDayAndTime(date('F j, Y g:i:s'))]);
        
    } else {

        // if expired

        generateNewAPIAccessToken(); // generate new access token

        $latestAccessToken = JSON::jsonRead('public/accessTokenStorage/accessTokens.json')[0]['accessToken'];

        // queryCustomersDataFromTheDatabase($latestAccessToken);
        querySitesDataFromTheDatabase($latestAccessToken);
        queryAuditLogsDataFromTheDatabase($latestAccessToken);

        // Logs::create(['log' => 'The database has been successfully synchronized on '.Dater::humanReadableDateWithDayAndTime(date('F j, Y g:i:s')).' with new generated Access Token.']);
    }

})->purpose('Sync data from the API.')->everyFiveMinutes();