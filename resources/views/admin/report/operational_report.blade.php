@extends('template.dashboard')
@section('admin-dashboard')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Operational Report</h2>
                <p class="text-muted-foreground">Audit trails and system activities.</p>
            </div>
            <a href="{{ route('admin.reports.operational-report-export') }}"
                class="inline-flex items-center justify-center gap-2 rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors">
                <x-dynamic-component component="lucide-file-spreadsheet" class="h-4 w-4" /> Export Log
            </a>
        </div>

        <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 text-muted-foreground font-medium border-b">
                        <tr>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Action</th>
                            <th class="px-6 py-4">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($logs as $log)
                            <tr class="hover:bg-muted/10">
                                <td class="px-6 py-4 whitespace-nowrap text-muted-foreground">
                                    {{ $log->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-foreground">{{ $log->causer->name ?? 'System' }}</div>
                                    <div class="text-xs text-muted-foreground">{{ $log->causer->role ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium border capitalize
                                        {{ $log->event == 'created'
                                            ? 'bg-green-50 text-green-700 border-green-200'
                                            : ($log->event == 'deleted'
                                                ? 'bg-red-50 text-red-700 border-red-200'
                                                : 'bg-blue-50 text-blue-700 border-blue-200') }}">
                                        {{ $log->event ?? 'Log' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-foreground">{{ $log->description }}</p>
                                    @if ($log->properties && $log->properties->count() > 0)
                                        <code
                                            class="text-xs text-muted-foreground mt-1 block bg-muted p-1 rounded">{{ Str::limit(json_encode($log->properties), 50) }}</code>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-muted-foreground">No activities recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">{{ $logs->links() }}</div>
        </div>
    </div>
@endsection
