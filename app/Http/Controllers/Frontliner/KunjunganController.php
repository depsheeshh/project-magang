<?php

namespace App\Http\Controllers\Frontliner;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\User;
use App\Models\HistoryLog;
use Illuminate\Http\Request;
use App\Notifications\KunjunganDitolakNotification;
use App\Notifications\KunjunganDisetujuiNotification;
use App\Notifications\KunjunganSelesaiNotification;
use App\Models\Survey;
use Illuminate\Support\Str;

class KunjunganController extends Controller
{
    // Halaman semua kunjungan (dengan filter status opsional)
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:menunggu,sedang_bertamu,selesai,ditolak',
        ]);

        $query = Kunjungan::with(['tamu.user','pegawai.user','pegawai.bidang'])
            ->orderBy('waktu_masuk','desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kunjungan = $query->get();

        return view('frontliner.kunjungan.index', compact('kunjungan'));
    }

    // Halaman khusus tamu menunggu
    public function menunggu()
    {
        $kunjungan = Kunjungan::with(['tamu','pegawai.user'])
            ->where('status','menunggu')
            ->orderBy('waktu_masuk','desc')
            ->get();

        return view('frontliner.index', compact('kunjungan'));
    }

    // Setujui kunjungan
    public function approve(Kunjungan $kunjungan)
    {
        if ($kunjungan->status === 'menunggu') {
            $kunjungan->update(['status' => 'sedang_bertamu']);

            $this->clearNotifications($kunjungan);

            // Kirim notifikasi ke tamu
            $kunjungan->tamu?->user?->notify(new KunjunganDisetujuiNotification($kunjungan));

            // Catat log
            HistoryLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'update',
                'table_name' => 'kunjungan',
                'record_id'  => $kunjungan->id,
                'reason'     => 'Frontliner menyetujui kunjungan',
                'new_values' => ['status' => 'sedang_bertamu'],
            ]);
        }

        return back()->with('success','Kunjungan disetujui, tamu dipersilakan masuk.');
    }

    // Tolak kunjungan
    public function reject(Request $request, Kunjungan $kunjungan)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $reason = strip_tags($validated['reason']);

        if ($kunjungan->status === 'menunggu') {
            $kunjungan->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $reason,
            ]);

            $this->clearNotifications($kunjungan);

            $kunjungan->tamu?->user?->notify(new KunjunganDitolakNotification($kunjungan, $reason));

            HistoryLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'update',
                'table_name' => 'kunjungan',
                'record_id'  => $kunjungan->id,
                'reason'     => 'Menolak tamu dengan alasan: '.$reason,
                'new_values' => [
                    'status' => 'ditolak',
                    'alasan_penolakan' => $reason,
                ],
            ]);
        }

        return back()->with('success','Kunjungan berhasil ditolak dengan alasan.');
    }

    // Checkout kunjungan
    public function checkout(Kunjungan $kunjungan)
    {
        if ($kunjungan->status === 'sedang_bertamu') {
            $kunjungan->update([
                'status'       => 'selesai',
                'waktu_keluar' => now(),
            ]);

            // Buat survey kosong + link
            $survey = Survey::firstOrCreate(
                ['kunjungan_id' => $kunjungan->id, 'user_id' => $kunjungan->tamu->user->id],
                ['rating' => null, 'feedback' => null, 'link' => null]
            );

            if (!$survey->link) {
                $survey->link = url('/survey/'.$kunjungan->id.'/'.Str::uuid());
                $survey->save();
            }

            $this->clearNotifications($kunjungan);

            $kunjungan->tamu->user?->notify(new KunjunganSelesaiNotification($kunjungan));

            HistoryLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'update',
                'table_name' => 'kunjungan',
                'record_id'  => $kunjungan->id,
                'reason'     => 'Frontliner melakukan checkout kunjungan',
                'new_values' => [
                    'status'       => 'selesai',
                    'waktu_keluar' => now()->toDateTimeString(),
                ],
            ]);

            // kirim link ke view via flash session
            return back()->with([
                'success' => 'Kunjungan tamu berhasil di-checkout.',
                'survey_link' => $survey->link
            ]);
        }

        return back()->with('error','Kunjungan tidak valid untuk checkout.');
    }

    /**
     * Helper untuk hapus notifikasi terkait kunjungan
     */
    private function clearNotifications(Kunjungan $kunjungan)
    {
        $kunjungan->pegawai?->user?->notifications()
            ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
            ->delete();

        User::role('frontliner')->each(function($f) use ($kunjungan) {
            $f->notifications()
                ->whereJsonContains('data->kunjungan_id', $kunjungan->id)
                ->delete();
        });
    }
}
