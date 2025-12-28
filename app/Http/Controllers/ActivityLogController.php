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

        if ($request->filled('module')) {
            $query->where('log_name', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('description', $request->action);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('properties', 'like', "%$search%")
                    ->orWhereHas('causer', function ($user) use ($search) {
                        $user->where('name', 'like', "%$search%");
                    });
            });
        }

        // 5. Ambil Data (Pakai paginate biar enteng)
        $logs = $query->paginate(10)->withQueryString();
        return view('admin.audit_log.activity_management', compact('logs'));
    }

    public function export(Request $request)
    {
        // Validasi format yang diminta (xlsx, csv, atau pdf)
        $format = $request->input('format', 'xlsx');

        $fileName = 'audit_log_' . date('Y-m-d_H-i') . '.' . $format;

        // Tentukan Library Writer berdasarkan format
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
