<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    // 🔹 Ambil semua notifikasi user
    public function index(Request $request)
    {
        $user = $request->user();

        $items = $user->notifications()
            ->latest()
            ->get()
            ->map(function ($n) {
                return [
                    'id'        => $n->id,
                    'kunjungan' => $n->data['kunjungan_id'] ?? null,
                    'nama'      => $n->data['nama'] ?? null,
                    'instansi'  => $n->data['instansi'] ?? null,
                    'keperluan' => $n->data['keperluan'] ?? null,
                    'waktu'     => $n->created_at->format('d-m-Y H:i'),
                    'event'     => $n->data['event'] ?? null,
                    'alasan'    => $n->data['alasan'] ?? null,
                    'message'   => $n->data['message'] ?? null,
                ];
            });

        return response()->json(['items' => $items]);
    }

    // 🔹 Tandai sebagai dibaca
    public function markAsRead($id, Request $request)
    {
        $notif = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', $request->user()->id)
            ->first();

        if ($notif) {
            $notif->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    // 🔹 Hapus satu notifikasi
    public function destroy($id, Request $request)
    {
        $deleted = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $request->user()->id)
            ->where('notifiable_type', get_class($request->user()))
            ->delete();

        Log::info("🗑️ Hapus notifikasi {$id} => {$deleted} row terhapus");

        return response()->json(['success' => true]);
    }

    // 🔹 Hapus semua notifikasi (fix versi Laravel 12)
    // public function clearAll(Request $request)
    // {
    //     $user = $request->user();

    //     // Ambil semua notifiable_type unik di DB
    //     $types = DB::table('notifications')->distinct()->pluck('notifiable_type')->toArray();

    //     // Filter hanya tipe yang mengandung kata "User"
    //     $userTypes = array_filter($types, fn($t) => stripos($t, 'user') !== false);

    //     $deleted = DB::table('notifications')
    //         ->where('notifiable_id', $user->id)
    //         ->whereIn('notifiable_type', $userTypes)
    //         ->delete();

    //     Log::info("🧹 CLEAR ALL run", [
    //         'id' => $user->id,
    //         'types_used' => $userTypes,
    //         'deleted' => $deleted,
    //     ]);

    //     return response()->json(['success' => true, 'deleted' => $deleted]);
    // }


}
