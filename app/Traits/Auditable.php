<?php

namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

trait Auditable
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        // Otomatis ambil nama Model sebagai nama Module (misal: User, Product, Supplier)
        $moduleName = class_basename($this); 
        
        // Kalau mau nama custom, bisa override di modelnya nanti
        if (method_exists($this, 'getCustomModuleName')) {
            $moduleName = $this->getCustomModuleName();
        }

        return LogOptions::defaults()
            ->logAll() // Rekam semua field
            ->logOnlyDirty() // Hanya yang berubah
            ->dontLogIfAttributesChangedOnly(['remember_token', 'password', 'updated_at'])
            ->useLogName($moduleName)
            ->setDescriptionForEvent(fn(string $eventName) => ucfirst($eventName)); // Created, Updated, Deleted
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        // 1. Ambil User yang sedang Login (Otomatis)
        $user = Auth::user();
        
        // 2. Tentukan Pesan Detail Otomatis
        // Mencari field nama yang umum (name, title, kode_obat, dll)
        $itemName = $this->name ?? $this->title ?? $this->code ?? '#' . $this->id;
        
        $detailPesan = match($eventName) {
            'created' => "Created new data: $itemName",
            'updated' => "Updated data: $itemName",
            'deleted' => "Deleted data: $itemName",
            default   => "$eventName data: $itemName",
        };

        // 3. Masukkan ke Properties (IP, User, Role, Details)
        $activity->properties = $activity->properties->merge([
            'ip'        => request()->ip(),
            'user_name' => $user ? $user->name : 'System/Unknown',
            'role'      => $user ? ($user->role ?? '-') : '-',
            'details'   => $detailPesan,
        ]);
    }
}