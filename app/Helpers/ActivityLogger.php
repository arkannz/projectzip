<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log aktivitas
     */
    public static function log(string $type, string $action, string $description, $model = null, ?array $oldValues = null, ?array $newValues = null): ActivityLog
    {
        $user = Auth::user();
        
        return ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'type' => $type,
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log untuk Inventory
     */
    public static function inventory(string $action, string $description, $model = null, ?array $oldValues = null, ?array $newValues = null): ActivityLog
    {
        return self::log('inventory', $action, $description, $model, $oldValues, $newValues);
    }

    /**
     * Log untuk RAB
     */
    public static function rab(string $action, string $description, $model = null, ?array $oldValues = null, ?array $newValues = null): ActivityLog
    {
        return self::log('rab', $action, $description, $model, $oldValues, $newValues);
    }

    /**
     * Log untuk Angkutan
     */
    public static function angkutan(string $action, string $description, $model = null, ?array $oldValues = null, ?array $newValues = null): ActivityLog
    {
        return self::log('angkutan', $action, $description, $model, $oldValues, $newValues);
    }

    /**
     * Log untuk Print/Export
     */
    public static function print(string $type, string $description): ActivityLog
    {
        return self::log('print', 'print', $description, null, null, ['print_type' => $type]);
    }

    /**
     * Log untuk Master Data (Location, Unit, Type)
     */
    public static function master(string $action, string $modelType, string $description, $model = null, ?array $oldValues = null, ?array $newValues = null): ActivityLog
    {
        return self::log('master', $action, $description, $model, $oldValues, $newValues);
    }
}

