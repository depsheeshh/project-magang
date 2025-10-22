<?php

namespace App\Observers;

use App\Models\HistoryLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BaseObserver
{
    protected function log($model, $action, $old = null, $new = null, $reason = null)
    {
        $userId = null;
        if (Auth::check() && User::where('id', Auth::id())->exists()) {
            $userId = Auth::id();
        }

        HistoryLog::create([
            'user_id'    => $userId,
            'action'     => $action,
            'table_name' => $model->getTable(),
            'record_id'  => $model->id,
            'old_values' => $old ?: null,
            'new_values' => $new ?: null,
            'reason'     => $reason ?? "Aksi {$action} pada tabel {$model->getTable()}",
            'created_id' => $userId,
        ]);
    }

    public function created($model)
    {
        if ($model instanceof User && !Auth::check()) {
            return;
        }

        $this->log(
            $model,
            'created',
            null,
            $model->getAttributes(), // semua field baru
            request()->input('reason')
        );
    }

    public function updated($model)
    {
        $old = $model->getOriginal();
        $new = $model->getAttributes();

        // ambil hanya field yang berubah
        $changes = [];
        foreach ($new as $key => $value) {
            if (array_key_exists($key, $old) && $old[$key] != $value) {
                $changes[$key] = $value;
            }
        }

        $this->log(
            $model,
            'updated',
            $old,
            $changes,
            request()->input('reason')
        );
    }

    public function deleted($model)
    {
        $this->log(
            $model,
            'deleted',
            $model->getOriginal(),
            null,
            request()->input('reason')
        );
    }

    public function restored($model)
    {
        $this->log(
            $model,
            'restored',
            null,
            $model->getAttributes(),
            request()->input('reason')
        );
    }

    public function forceDeleted($model)
    {
        $this->log(
            $model,
            'force_deleted',
            $model->getOriginal(),
            null,
            request()->input('reason')
        );
    }
}

