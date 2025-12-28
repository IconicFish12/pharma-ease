<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityLogRequest;
use App\Http\Requests\UpdateActivityLogRequest;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function update(UpdateActivityLogRequest $request, )
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
