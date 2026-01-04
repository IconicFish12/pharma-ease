<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityLogRequest;
use App\Http\Requests\UpdateActivityLogRequest;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Exports\ActivityExport;
use Maatwebsite\Excel\Facades\Excel;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        // Filter Module
        if ($request->filled('module') && $request->module !== 'all') {
            $query->where('log_name', $request->module);
        }

        // Filter Action
        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('description', $request->action);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('properties', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('log_name', 'like', "%$search%")
                    ->orWhereHas('causer', function ($user) use ($search) {
                        $user->where('name', 'like', "%$search%");
                    });
            });
        }

        $logs = $query->paginate(10)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully Fetching Activiity Logs data',
                'data' => $logs,
            ]);

        }
        return view('admin.audit_log.activity_management', compact('logs'));
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'xlsx');

        $fileName = 'audit_log_' . date('Y-m-d_H-i') . '.' . $format;

        $writerType = match ($format) {
            'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
            'csv' => \Maatwebsite\Excel\Excel::CSV,
            default => \Maatwebsite\Excel\Excel::XLSX,
        };

        return Excel::download(new ActivityExport($request), $fileName, $writerType);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityLogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityLogRequest $request,)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
