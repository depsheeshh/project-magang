<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Ambil semua notifikasi user login
    public function index(Request $request)
    {
        return response()->json([
            'items' => $request->user()->notifications()->latest()->get()
        ]);
    }

    // Tandai notifikasi sebagai dibaca
    public function markAsRead($id, Request $request)
    {
        $notif = $request->user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        return response()->json(['success' => true]);
    }

    // Hapus notifikasi
    public function destroy($id, Request $request)
    {
        $notif = $request->user()->notifications()->findOrFail($id);
        $notif->delete();

        return response()->json(['success' => true]);
    }
    public function clearAll(Request $request)
    {
        $user = $request->user();
        // Hapus semua notifikasi milik user
        $user->notifications()->delete();
        return response()->json(['success' => true]);
    }

}
