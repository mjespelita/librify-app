@extends('layouts.main')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; margin: 0;">📝 System Logs</h1>
        <button onclick="history.back()" style="text-decoration: none; background-color: #0d6efd; color: #fff; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
            ← Back
        </button>
    </div>

    <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 6px; padding: 24px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">

        @forelse ($audits as $audit)
            @php
                $bgColor = '#f8f9fa';
                $borderColor = '#ced4da';
                $titleColor = '#212529';

                if ($audit->event === 'created') {
                    $bgColor = '#e9f7ef';
                    $borderColor = '#28a745';
                    $titleColor = '#28a745';
                } elseif ($audit->event === 'updated') {
                    $bgColor = '#fff3cd';
                    $borderColor = '#ffc107';
                    $titleColor = '#856404';
                } elseif ($audit->event === 'deleted') {
                    $bgColor = '#f8d7da';
                    $borderColor = '#dc3545';
                    $titleColor = '#721c24';
                }
            @endphp

            <div style="background-color: {{ $bgColor }}; border: 1px solid {{ $borderColor }}; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                <p style="margin-bottom: 10px; font-size: 15px; color: {{ $titleColor }};">
                    <strong>{{ $audit->user ? $audit->user->name : 'System' }}</strong>
                    performed
                    <strong style="text-transform: uppercase;">{{ $audit->event }}</strong>
                    on
                    <strong>{{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}</strong>
                    at
                    <em>{{ Smark\Smark\Dater::humanReadableDateWithDayAndTime($audit->created_at) }}</em>
                </p>

                @php
                    $pairs = explode(',', $audit->tags);
                    $tagsArray = [];
                    foreach ($pairs as $pair) {
                        $parts = explode(':', $pair, 2);
                        if(count($parts) == 2) {
                            $tagsArray[$parts[0]] = $parts[1];
                        }
                    }
                @endphp

                @if (!empty($audit->tags))
                    <details style="margin-bottom: 12px;">
                        <summary style="font-weight: bold; color: #6c757d;">Tags</summary>
                        <pre style="background-color: #fff; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">{{ $audit->tags }}</pre>
                    </details>
                @endif

                @if ($audit->event === 'updated')
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                        <thead>
                            <tr style="background-color: #ffeeba;">
                                <th style="padding: 8px; border: 1px solid #dee2e6;">Field</th>
                                <th style="padding: 8px; border: 1px solid #dee2e6; color: #dc3545;">Old Value</th>
                                <th style="padding: 8px; border: 1px solid #dee2e6; color: #28a745;">New Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audit->new_values as $key => $value)
                                @php $old = $audit->old_values[$key] ?? 'N/A'; @endphp
                                <tr>
                                    <td style="padding: 8px; border: 1px solid #dee2e6;">{{ ucfirst($key) }}</td>
                                    <td style="padding: 8px; border: 1px solid #dee2e6; color: #dc3545;">{{ $old }}</td>
                                    <td style="padding: 8px; border: 1px solid #dee2e6; color: #28a745;">{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif ($audit->event === 'created')
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                        <thead>
                            <tr style="background-color: #d4edda;">
                                <th style="padding: 8px; border: 1px solid #dee2e6;">Field</th>
                                <th style="padding: 8px; border: 1px solid #dee2e6; color: #28a745;">New Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audit->new_values as $key => $value)
                                <tr>
                                    <td style="padding: 8px; border: 1px solid #dee2e6;">{{ ucfirst($key) }}</td>
                                    <td style="padding: 8px; border: 1px solid #dee2e6; color: #28a745;">{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif ($audit->event === 'deleted')
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                        <thead>
                            <tr style="background-color: #f5c6cb;">
                                <th style="padding: 8px; border: 1px solid #dee2e6;">Field</th>
                                <th style="padding: 8px; border: 1px solid #dee2e6; color: #dc3545;">Deleted Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audit->old_values as $key => $value)
                                <tr>
                                    <td style="padding: 8px; border: 1px solid #dee2e6;">{{ ucfirst($key) }}</td>
                                    <td style="padding: 8px; border: 1px solid #dee2e6; color: #dc3545;">{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <p style="font-size: 12px; color: #6c757d; margin-top: 10px;">
                    <strong>IP:</strong> {{ $audit->ip_address }} |
                    <strong>URL:</strong> {{ $audit->url }} |
                    <strong>Agent:</strong> {{ Str::limit($audit->user_agent, 60) }}
                </p>
            </div>

        @empty
            <div style="text-align: center; padding: 20px; background-color: #e2e3e5; color: #6c757d; border-radius: 6px;">
                No audit logs available.
            </div>
        @endforelse

        <div style="margin-top: 24px; text-align: center;">
            {{ $audits->links('pagination::bootstrap-5') }}
        </div>

    </div>

@endsection
