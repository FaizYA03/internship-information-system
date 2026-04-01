<?php

namespace App\Observers;

use App\Models\Lab\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LabActivityObserver
{
    public function created(Model $model)
    {
        $this->logActivity($model, 'created', 'Created ' . class_basename($model));
    }

    public function updated(Model $model)
    {
        // Ignore if only updated_at changed
        if ($model->wasChanged('updated_at') && count($model->getChanges()) == 1) {
            return;
        }
        
        $this->logActivity($model, 'updated', 'Updated ' . class_basename($model));
    }

    public function deleted(Model $model)
    {
        $this->logActivity($model, 'deleted', 'Deleted ' . class_basename($model));
    }

    protected function logActivity(Model $model, $action, $description)
    {
        if (!Auth::check()) return;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'properties' => $action === 'updated' ? [
                'old' => $model->getOriginal(),
                'new' => $model->getChanges()
            ] : null,
            'ip_address' => Request::ip()
        ]);
    }
}
