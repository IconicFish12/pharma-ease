<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\Activitylog\Models\Activity;

class OperationalExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Activity::with('causer')->latest()->get();
    }

    public function headings(): array
    {
        return ['Date', 'User', 'Action', 'Subject', 'Description'];
    }

    public function map($log): array
    {
        return [
            $log->created_at->format('Y-m-d H:i:s'),
            $log->causer->name ?? 'System',
            $log->event, // created, updated, deleted
            class_basename($log->subject_type), // ex: Medicine
            $log->description,
        ];
    }
}
