<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapat;
use App\Models\User;
use App\Models\RapatUndangan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\RapatInvitationNotification;
use App\Notifications\RapatInvitationCancelledNotification;

class RapatController extends Controller
{
    public function index()
    {
        $rapat = Rapat::latest()->paginate(10);
        return view('admin.rapat.index', compact('rapat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'waktu_mulai'   => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'lokasi'        => 'nullable|string|max:255',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'radius'        => 'nullable|integer',
            'jumlah_tamu'   => 'nullable|integer',
        ]);

        Rapat::create([
            'judul'        => $request->judul,
            'waktu_mulai'  => $request->waktu_mulai,
            'waktu_selesai'=> $request->waktu_selesai,
            'lokasi'       => $request->lokasi,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'radius'       => $request->radius ?? 100,
            'jumlah_tamu'  => $request->jumlah_tamu,
            'created_id'   => Auth::id(),
        ]);

        return redirect()->route('admin.rapat.index')->with('success','Rapat berhasil dibuat');
    }

    public function show(Rapat $rapat)
    {
        $rapat->load(['undangan.user','undangan.instansi']);
        $users = User::orderBy('name')->get();

        return view('admin.rapat.show', compact('rapat','users'));
    }

    public function update(Request $request, Rapat $rapat)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'waktu_mulai'   => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'lokasi'        => 'nullable|string|max:255',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'radius'        => 'nullable|integer',
            'jumlah_tamu'   => 'nullable|integer',
        ]);

        $rapat->update([
            'judul'        => $request->judul,
            'waktu_mulai'  => $request->waktu_mulai,
            'waktu_selesai'=> $request->waktu_selesai,
            'lokasi'       => $request->lokasi,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'radius'       => $request->radius ?? 100,
            'jumlah_tamu'  => $request->jumlah_tamu,
            'updated_id'   => Auth::id(),
        ]);

        return redirect()->route('admin.rapat.index')->with('success','Rapat berhasil diperbarui');
    }

    public function destroy(Rapat $rapat)
    {
        $rapat->update(['deleted_id' => Auth::id()]);
        $rapat->delete();

        return redirect()->route('admin.rapat.index')->with('success','Rapat berhasil dihapus');
    }

    public function storeInvitation(Request $request, Rapat $rapat)
    {
        $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $existing = RapatUndangan::where('rapat_id', $rapat->id)
        ->where('user_id', $validated['user_id'])
        ->first();

    if ($existing) {
        return redirect()->route('admin.rapat.show', $rapat->id)
            ->with('warning','User sudah diundang ke rapat ini.');
    }

    $token = (string) Str::uuid();

    $undangan = RapatUndangan::create([
        'rapat_id'           => $rapat->id,
        'user_id'            => $validated['user_id'],
        'checkin_token'      => $token,
        'checkin_token_hash' => hash('sha256', $token),
        'status_kehadiran'   => 'pending',
        'created_id'         => Auth::id(),
    ]);

    // 🚨 Kirim notifikasi ke user yang diundang
    $user = User::find($validated['user_id']);
    if ($user) {
        $user->notify(new RapatInvitationNotification($rapat));
    }

    return redirect()->route('admin.rapat.show', $rapat->id)
        ->with('success','Undangan berhasil ditambahkan & notifikasi terkirim.');
    }

    public function destroyInvitation(Rapat $rapat, RapatUndangan $invitation)
    {
        if ($invitation->rapat_id !== $rapat->id) {
        abort(404);
    }

    // simpan user sebelum delete
    $user = $invitation->user;

    $invitation->update(['deleted_id' => Auth::id()]);
    $invitation->delete();

    // Kirim notifikasi pembatalan
    if ($user) {
        $user->notify(new RapatInvitationCancelledNotification($rapat));

        // Hapus notifikasi "Undangan Rapat Baru" untuk rapat_id ini supaya tidak bikin bingung
        DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->where('data->event', 'rapat_undangan')
            ->where('data->rapat_id', $rapat->id)
            ->delete();
    }

    return redirect()->route('admin.rapat.show', $rapat->id)
        ->with('success','Undangan berhasil dihapus & notifikasi dibersihkan.');
    }

    public function exportKehadiran(Rapat $rapat)
    {
        $rapat->load(['undangan.user','undangan.instansi']);

        $filename = 'kehadiran_rapat_' . $rapat->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($rapat) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'Instansi Asal', 'Status', 'Checked In At', 'Lat', 'Lon', 'QR Scanned At']);

            foreach ($rapat->undangan as $u) {
                fputcsv($handle, [
                    $u->user->name ?? '-',
                    $u->instansi->nama_instansi ?? '-',
                    $u->status_kehadiran,
                    optional($u->checked_in_at)->format('Y-m-d H:i:s'),
                    $u->checkin_latitude,
                    $u->checkin_longitude,
                    optional($u->qr_scanned_at)->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportKehadiranPdf(Rapat $rapat)
    {
        $rapat->load(['undangan.user','undangan.instansi']);

        // Data yang akan dilempar ke view
        $data = [
            'rapat' => $rapat,
            'undangan' => $rapat->undangan,
        ];

        // Render view ke PDF
        $pdf = Pdf::loadView('admin.rapat.kehadiran_pdf', $data);

        $filename = 'kehadiran_rapat_' . $rapat->id . '.pdf';
        return $pdf->download($filename);
    }

    public function endRapat(Rapat $rapat)
    {
        if ($rapat->status === 'selesai') {
            return back()->with('info', 'Rapat ini sudah selesai.');
        }

        // Update status rapat
        $rapat->update([
            'status' => 'selesai',
            'waktu_selesai' => now(), // update waktu selesai aktual
        ]);

        // Mass update undangan: semua yang hadir → selesai + isi checked_out_at
        RapatUndangan::where('rapat_id', $rapat->id)
            ->where('status_kehadiran', 'hadir')
            ->update([
                'status_kehadiran' => 'selesai',
                'checked_out_at'   => now(),
            ]);

        return redirect()->route('admin.rapat.index')
            ->with('success', 'Rapat berhasil diakhiri. Semua peserta hadir ditandai selesai.');
    }


    public function rekapRapat(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'nullable|in:berjalan,selesai,dibatalkan',
        ]);

        $query = Rapat::with('undangan')->orderByDesc('waktu_mulai');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('waktu_mulai', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rapat = $query->get();

        $rekap = $rapat->map(function($r) {
            return [
                'id'      => $r->id,
                'judul'   => $r->judul,
                'waktu'   => \Carbon\Carbon::parse($r->waktu_mulai)->format('d/m/Y H:i') .
                            ' s/d ' .
                            \Carbon\Carbon::parse($r->waktu_selesai)->format('d/m/Y H:i'),
                'lokasi'  => $r->lokasi,
                'status'  => ucfirst($r->status),
                'total'   => $r->undangan->count(),
                'hadir'   => $r->undangan->where('status_kehadiran','hadir')->count(),
                'tidak'   => $r->undangan->where('status_kehadiran','tidak_hadir')->count(),
                'pending' => $r->undangan->where('status_kehadiran','pending')->count(),
            ];
        });

        return view('admin.rapat.rekap_rapat', compact('rekap'))
            ->with([
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'status'     => $request->status,
            ]);
    }

    public function exportRekapRapatPdf(Request $request)
    {
        $query = Rapat::with('undangan')->orderByDesc('waktu_mulai');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('waktu_mulai', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rapat = $query->get();

        $rekap = $rapat->map(function($r) {
            return [
                'judul'   => $r->judul,
                'waktu'   => \Carbon\Carbon::parse($r->waktu_mulai)->format('d/m/Y H:i') .
                            ' s/d ' .
                            \Carbon\Carbon::parse($r->waktu_selesai)->format('d/m/Y H:i'),
                'lokasi'  => $r->lokasi,
                'status'  => ucfirst($r->status),
                'total'   => $r->undangan->count(),
                'hadir'   => $r->undangan->where('status_kehadiran','hadir')->count(),
                'tidak'   => $r->undangan->where('status_kehadiran','tidak_hadir')->count(),
                'pending' => $r->undangan->where('status_kehadiran','pending')->count(),
            ];
        });

        $pdf = Pdf::loadView('admin.rapat.rekap_rapat_pdf', compact('rekap'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('rekap_rapat.pdf');
    }

}
