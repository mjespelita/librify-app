@extends('layouts.main')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center mb-4">Backup File</h1>
        </div>

        <!-- Inline CSS -->
        <style>
            #backup-status {
                margin-top: 10px;
                font-weight: bold;
                color: #2c7be5;
                display: flex;
                align-items: center;
                gap: 10px;
            }
        </style>

        <!-- Refresh Backup Button -->
        <a href="{{ url('backup-process') }}" onclick="showBackupStatus(this)" class="mb-3" id="backup-link">
            <button class="btn btn-success" id="backup-btn">Refresh Backup File</button>
        </a>

        <!-- Status Message -->
        <div id="backup-status" style="display: none;">
            <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            🔄 Running refresh backup... Please wait.
        </div>

        <!-- Inline JS -->
        <script>
            function showBackupStatus(el) {
                document.getElementById('backup-btn').style.display = 'none';
                document.getElementById('backup-status').style.display = 'flex';
            }
        </script>

    </div>

    @php
        function formatSizeUnits($bytes)
        {
            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                return $bytes . ' bytes';
            } elseif ($bytes == 1) {
                return '1 byte';
            } else {
                return '0 bytes';
            }
        }
    @endphp

    <div class="card">
        <div class="card-body">

            <h4>Welcome to Backup Page</h4>
            <small>
                When you click the <strong>Refresh Backup File</strong> button, the system will generate a complete backup of our project. This includes:
                <ul>
                    <li>All files and folders — such as uploaded comment files and profile photos</li>
                    <li>A full database backup saved as a <code>.sql</code> file to preserve our data</li>
                </ul>
            </small>


            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Current Backup File Name</th>
                            <th scope="col">File Size</th>
                            <th scope="col">Date Created</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $file)
                        <tr>
                            <td>{{ $file->getFilename() }}</td>
                            

                            <td>{{ formatSizeUnits($file->getSize()) }}</td>
                            <td>{{ \Carbon\Carbon::createFromTimestamp($file->getCTime())->format('M d, Y (D)') }}</td>
                            <td>
                                <a href="{{ url('backup/'.$file->getFilename()) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No backup files found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ url('assets/jquery/jquery.min.js') }}"></script>

@endsection
