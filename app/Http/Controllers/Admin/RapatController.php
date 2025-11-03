<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapat;
use App\Models\User;
use App\Models\RapatUndangan;
use Illuminate\Support\Str;
use App\Models\Kantor;
use App\Models\Instansi;
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
        $rapat  = Rapat::latest()->paginate(10);
        $kantor = Kantor::with('ruangan')->get();
        return view('admin.rapat.index', compact('rapat','kantor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'ruangan_id'    => 'required|exists:ruangan,id',
            'waktu_mulai'   => 'required|date|after_or_equal:now',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'jenis_rapat'   => 'required|string',
            'lokasi'        => 'required|exists:kantor,nama_kantor',
            'jumlah_tamu'   => 'nullable|integer',
        ], [
        'waktu_mulai.after_or_equal' => 'Waktu mulai rapat minimal sekarang.',
        'waktu_selesai.after'        => 'Waktu selesai harus setelah waktu mulai.',
    ]);

        $kantor = Kantor::where('nama_kantor',$request->lokasi)->first();

        Rapat::create([
            'judul'        => $request->judul,
            'ruangan_id'   => $request->ruangan_id,
            'jenis_rapat' => $request->jenis_rapat,
            'waktu_mulai'  => $request->waktu_mulai,
            'waktu_selesai'=> $request->waktu_selesai,
            'lokasi'       => $kantor->nama_kantor,
            'latitude'     => $kantor->latitude,
            'longitude'    => $kantor->longitude,
            'radius'       => 100,
            'jumlah_tamu'  => $request->jumlah_tamu,
            'created_id'   => Auth::id(),
        ]);

        return redirect()->route('admin.rapat.index')->with('success','Rapat berhasil dibuat');
    }

    public function show(Rapat $rapat)
    {
    // Generate QR token rapat jika belum ada
        if (!$rapat->qr_token_hash) {
            $token = (string) Str::uuid();
            $rapat->fill([
                'qr_token'      => $token,
                'qr_token_hash' => hash('sha256', $token),
            ])->save();
        }

        // Eager load relasi undangan + user + pegawai + instansi
        $rapat->load([
            'undangan.user.pegawai.instansi',
            'undangan.instansi'
        ]);

        // Semua user (untuk undangan internal)
        $users = User::with('pegawai.instansi')->orderBy('name')->get();

        // Semua instansi (untuk undangan eksternal)
        $instansi = Instansi::orderBy('nama_instansi')->get();

        return view('admin.rapat.show', compact('rapat','users','instansi'));
    }

    public function update(Request $request, Rapat $rapat)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'ruangan_id' => 'required|exists:ruangan,id',
            'waktu_mulai'   => 'required|date|after_or_equal:now',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'jenis_rapat'   => 'required|string',
            'lokasi'        => 'required|exists:kantor,nama_kantor',
            'jumlah_tamu'   => 'nullable|integer',
        ], [
        'waktu_mulai.after_or_equal' => 'Waktu mulai rapat minimal sekarang.',
        'waktu_selesai.after'        => 'Waktu selesai harus setelah waktu mulai.',
    ]);

        $kantor = Kantor::where('nama_kantor',$request->lokasi)->first();

        $rapat->update([
            'judul'        => $request->judul,
            'ruangan_id'   => $request->ruangan_id,
            'jenis_rapat' => $request->jenis_rapat,
            'waktu_mulai'  => $request->waktu_mulai,
            'waktu_selesai'=> $request->waktu_selesai,
            'lokasi'       => $kantor->nama_kantor,
            'latitude'     => $kantor->latitude,
            'longitude'    => $kantor->longitude,
            'radius'       => 100,
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

    public function storeInvitationInstansi(Request $request, Rapat $rapat)
    {
        $request->validate([
            'instansi_id' => 'required|exists:instansi,id',
        ]);

        // Ambil semua user dari instansi tsb
        $users = User::where('instansi_id', $request->instansi_id)->get();

        if ($users->isEmpty()) {
            return back()->with('warning', 'Tidak ada user pada instansi ini.');
        }

        foreach ($users as $user) {
            // skip jika sudah ada undangan
            if ($rapat->undangan()->where('user_id', $user->id)->exists()) {
                continue;
            }

            $token = (string) Str::uuid();

            $rapat->undangan()->create([
                'user_id'            => $user->id,
                'instansi_id'        => $request->instansi_id,
                'checkin_token'      => $token,
                'checkin_token_hash' => hash('sha256', $token),
                'status_kehadiran'   => 'pending',
                'created_id'         => Auth::id(),
            ]);

            // opsional: kirim notifikasi
            if (method_exists($user, 'notify')) {
                $user->notify(new RapatInvitationNotification($rapat));
            }
        }

        return back()->with('success', 'Undangan instansi berhasil ditambahkan.');
    }

    public function inviteAllInstansi(Rapat $rapat)
    {
        $instansiList = Instansi::all();

        foreach ($instansiList as $instansi) {
            $users = User::where('instansi_id', $instansi->id)->get();

            foreach ($users as $user) {
                if ($rapat->undangan()->where('user_id', $user->id)->exists()) {
                    continue;
                }

                $token = (string) Str::uuid();

                $rapat->undangan()->create([
                    'user_id'            => $user->id,
                    'instansi_id'        => $instansi->id,
                    'checkin_token'      => $token,
                    'checkin_token_hash' => hash('sha256', $token),
                    'status_kehadiran'   => 'pending',
                    'created_id'         => Auth::id(),
                ]);

                if (method_exists($user, 'notify')) {
                    $user->notify(new RapatInvitationNotification($rapat));
                }
            }
        }

        return back()->with('success', 'Semua instansi berhasil diundang.');
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
            // ✅ Tambahkan kolom Checked Out At
            fputcsv($handle, [
                'Nama', 'Instansi Asal', 'Status',
                'Checked In At', 'Checked Out At',
                'Lat', 'Lon', 'QR Scanned At'
            ]);

            foreach ($rapat->undangan as $u) {
                fputcsv($handle, [
                    $u->user->name ?? '-',
                    $u->instansi->nama_instansi ?? '-',
                    $u->status_kehadiran,
                    optional($u->checked_in_at)->format('Y-m-d H:i:s'),
                    optional($u->checked_out_at)->format('Y-m-d H:i:s'), // ✅ baru
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
        // Pastikan view `admin.rapat.kehadiran_pdf` menampilkan kolom Checked Out At juga
        $pdf = Pdf::loadView('admin.rapat.kehadiran_pdf', $data)
                ->setPaper('a4', 'landscape');

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
                'selesai' => $r->undangan->where('status_kehadiran','selesai')->count(),
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
                'selesai' => $r->undangan->where('status_kehadiran','selesai')->count(),
                'tidak'   => $r->undangan->where('status_kehadiran','tidak_hadir')->count(),
                'pending' => $r->undangan->where('status_kehadiran','pending')->count(),
            ];
        });

        $pdf = Pdf::loadView('admin.rapat.rekap_rapat_pdf', compact('rekap'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('rekap_rapat.pdf');
    }

    public function inviteAll(Request $request, Rapat $rapat)
    {
        $request->validate([
            'role' => 'nullable|string'
        ]);

        $query = User::query();

        if ($request->filled('role')) {
            $query->role($request->role); // pakai spatie/laravel-permission
        }

        $users = $query->get();

        foreach ($users as $user) {
            $exists = RapatUndangan::where('rapat_id', $rapat->id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$exists) {
                $token = (string) \Illuminate\Support\Str::uuid();
                RapatUndangan::create([
                    'rapat_id'           => $rapat->id,
                    'user_id'            => $user->id,
                    'checkin_token'      => $token,
                    'checkin_token_hash' => hash('sha256', $token),
                    'status_kehadiran'   => 'pending',
                    'created_id'         => Auth::id(),
                ]);

                // opsional: kirim notifikasi
                $user->notify(new RapatInvitationNotification($rapat));
            }
        }

        return back()->with('success', 'Undangan massal berhasil ditambahkan.');
    }

}
