<?php

namespace App\Observers;

use App\Models\HistoryLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BaseObserver
{
    protected function log($model, $action, $old = null, $new = null, $reason = null)
    {
        // Tentukan user_id yang valid
        $userId = null;

        if (Auth::check()) {
            $authId = Auth::id();
            // pastikan user benar-benar ada di DB
            if (User::where('id', $authId)->exists()) {
                $userId = $authId;
            }
        }

        // Kalau tidak ada user login, bisa fallback ke system user id=1
        // $userId = $userId ?? 1;

        HistoryLog::create([
            'user_id'    => $userId,
            'action'     => $action,
            'table_name' => $model->getTable(),
            'record_id'  => $model->id,
            'old_values' => $old ? json_encode($old) : null,
            'new_values' => $new ? json_encode($new) : null,
            'reason'     => $reason ?? "Aksi {$action} pada tabel {$model->getTable()}",
        ]);
    }

    public function created($model)
    {
        // Skip log saat membuat user baru & belum ada Auth
        if ($model instanceof User && !Auth::check()) {
            return;
        }

        $this->log(
            $model,
            'created',
            null,
            $model->getAttributes(),
            request()->input('reason')
        );
    }

    public function updated($model)
    {
        $this->log(
            $model,
            'updated',
            $model->getOriginal(),
            $model->getChanges(),
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
