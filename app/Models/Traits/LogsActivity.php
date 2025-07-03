<?php

namespace App\Models\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Trait LogsActivity
 * @package App\Models\Traits
 */
trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function (Model $model) use ($event) {
                self::logActivity($model, strtoupper($event));
            });
        }
    }

    /**
     * @param Model $model
     * @param string $action
     */
    protected static function logActivity(Model $model, string $action)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Per your request, only log activities for admins.
        // This assumes you have a role system in place (e.g., hasRole() method).
        if (method_exists($user, 'hasRole') && !$user->hasRole('admin')) {
            return;
        }

        $details = [
            'model_type' => get_class($model),
            'model_id' => $model->id,
        ];

        if ($action === 'CREATED') {
            $details['attributes'] = $model->getAttributes();
        } elseif ($action === 'UPDATED') {
            $details['original'] = $model->getOriginal();
            $details['changes'] = $model->getDirty();
        } elseif ($action === 'DELETED') {
            $details['attributes'] = $model->getAttributes();
        }

        ActivityLog::create([
            'actor_id' => $user->id,
            'actor_type' => 'admin',
            'action_type' => self::getActionType($model, $action),
            'description' => self::getDescription($model, $action),
            'details' => $details,
            'ip_address' => request()->ip(),
            'timestamp' => now(),
        ]);
    }

    /**
     * @param Model $model
     * @param string $action
     * @return string
     */
    private static function getActionType(Model $model, string $action): string
    {
        $modelName = strtoupper(class_basename($model));
        return "{$modelName}_{$action}";
    }

    /**
     * @param Model $model
     * @param string $action
     * @return string
     */
    private static function getDescription(Model $model, string $action): string
    {
        $modelName = strtolower(class_basename($model));

        if ($action === 'CREATED') {
            return "Admin menambahkan data {$modelName}.";
        } elseif ($action === 'UPDATED') {
            return "Admin memperbarui data {$modelName}.";
        } elseif ($action === 'DELETED') {
            return "Admin menghapus data {$modelName}.";
        }
    }
}
