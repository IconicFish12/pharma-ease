<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ActivityExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $request;

    // Kita terima filter dari controller (biar yang diexport sesuai hasil search)
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Copy logika filter dari Controller kamu kesini
        // Supaya data yang didownload SAMA dengan yang tampil di layar
        $query = Activity::with('causer')->latest();

        if ($this->request->filled('module')) {
            $query->where('log_name', $this->request->module);
        }

        if ($this->request->filled('action')) {
            $query->where('description', $this->request->action);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('properties', 'like', "%$search%")
                  ->orWhereHas('causer', function($user) use ($search) {
                      $user->where('name', 'like', "%$search%");
                  });
            });
        }

        return $query->get();
    }

    // Judul Kolom di Excel/PDF
    public function headings(): array
    {
        return [
            'Timestamp',
            'User Name',
            'Role',
            'Action',
            'Module',
            'Details',
            'IP Address',
        ];
    }

    // Isi Baris per Baris
    public function map($log): array
    {
        // Ambil data dari properties (sama seperti di Blade)
        $props = $log->properties;

        return [
            $log->created_at->format('Y-m-d H:i:s'),
            $props['user_name'] ?? $log->causer->name ?? 'System', // User
            $props['role'] ?? $log->causer->role ?? '-',           // Role
            $log->description,                                     // Action
            $log->log_name,                                        // Module
            $props['details'] ?? '-',                              // Details
            $props['ip'] ?? '-',                                   // IP Address
        ];
    }
}