<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityLogRequest;
use App\Http\Requests\UpdateActivityLogRequest;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Mulai Query Dasar
        $query = Activity::with('causer')->latest();

        // 2. Filter Module (Jika ada input 'module')
        if ($request->filled('module')) {
            $query->where('log_name', $request->module);
        }

        // 3. Filter Action (Jika ada input 'action')
        // Action di sini mengacu pada kolom 'description' (Created, Updated, Login)
        if ($request->filled('action')) {
            $query->where('description', $request->action);
        }

        // 4. Filter Search (Cari di properties/detail atau nama user)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('properties', 'like', "%$search%") // Cari di detail pesan/IP
                    ->orWhereHas('causer', function ($user) use ($search) {
                        $user->where('name', 'like', "%$search%"); // Cari nama user
                    });
            });
        }

        // 5. Ambil Data (Pakai paginate biar enteng)
        $logs = Activity::with('causer')->latest()->get();
        return view('admin.audit_log.activity_management', compact('logs'));
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
