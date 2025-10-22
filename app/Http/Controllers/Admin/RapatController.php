<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapat;
use App\Models\User;
use App\Models\RapatUndangan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        RapatUndangan::create([
            'rapat_id'           => $rapat->id,
            'user_id'            => $validated['user_id'],
            'checkin_token'      => $token,
            'checkin_token_hash' => hash('sha256', $token),
            'status_kehadiran'   => 'pending',
            'created_id'         => Auth::id(),
        ]);

        return redirect()->route('admin.rapat.show', $rapat->id)
            ->with('success','Undangan berhasil ditambahkan.');
    }

    public function destroyInvitation(Rapat $rapat, RapatUndangan $invitation)
    {
        if ($invitation->rapat_id !== $rapat->id) {
            abort(404);
        }

        $invitation->update(['deleted_id' => Auth::id()]);
        $invitation->delete();

        return redirect()->route('admin.rapat.index')->with('success','Undangan berhasil dihapus.');
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
}
