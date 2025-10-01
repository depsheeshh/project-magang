<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user)
    {
        // Jika user baru saja diberi role pegawai
        if ($user->hasRole('pegawai')) {
            // Cek apakah sudah ada record pegawai
            if (!$user->pegawai) {
                Pegawai::create([
                    'user_id'   => $user->id,
                    'bidang_id' => null, // bisa diisi nanti
                    'jabatan_id'=> null, // bisa diisi nanti
                    'nip'       => null,
                    'telepon'   => null,
                    'created_id'=> Auth::id() ?? $user->id,
                ]);
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
