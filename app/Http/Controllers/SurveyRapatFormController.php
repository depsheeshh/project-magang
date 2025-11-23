<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\SurveyRapat;
use App\Models\SurveyRapatRespon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyRapatFormController extends Controller
{
    /**
     * Form survey internal.
     */
    public function formInternal($slug)
    {
        $survey = SurveyRapat::where('slug', $slug)->firstOrFail();

        if ($survey->tipe !== 'Internal') {
            return redirect()->back()->with('warning', 'QR tidak valid untuk survey internal.');
        }

        $rapat = $survey->rapat;
        $user  = Auth::user();
        $undangan = $rapat->undangan()->where('user_id',$user->id)->first();

        // ✅ Validasi status kehadiran
        if (!$undangan) {
            return redirect()->route('pegawai.agenda.rapat')
                ->with('error','Anda tidak terdaftar dalam rapat ini.');
        }
        if ($undangan->status_kehadiran === 'hadir') {
            // Auto-checkout sebelum survey
            $undangan->update([
                'status_kehadiran' => 'selesai',
                'checked_out_at'   => now(),
                'updated_id'       => $user->id,
            ]);
        } elseif ($undangan->status_kehadiran === 'tidak_hadir') {
            return redirect()->route('pegawai.agenda.rapat')
                ->with('error','Anda tidak tercatat hadir, tidak bisa isi survey.');
        }

        // ✅ Batas waktu survey 7 hari
        if (now()->diffInDays($rapat->waktu_selesai) > 7) {
            return redirect()->route('pegawai.agenda.rapat')
                ->with('error','Waktu pengisian survey telah berakhir.');
        }

        if ($undangan->status_survey === 'sudah_isi') {
            return redirect()->route('pegawai.agenda.rapat')
                ->with('warning','Anda sudah mengisi survey rapat ini.');
        }

        return view('survey-rapat.form', [
            'survey'   => $survey,
            'nama'   => $user->name,
            'instansi' => 'DINAS KOMUNIKASI, INFORMATIKA DAN STATISTIK' // internal tidak butuh instansi
        ]);
    }

    /**
     * Form survey eksternal.
     */
    public function formEksternal($slug)
    {
        $survey = SurveyRapat::where('slug', $slug)->firstOrFail();

        if ($survey->tipe !== 'Eksternal') {
            return redirect()->back()->with('warning', 'QR tidak valid untuk survey eksternal.');
        }

        $rapat = $survey->rapat;
        $user  = Auth::user();
        $undangan = $rapat->undangan()->where('user_id',$user->id)->first();

        $instansi = Instansi::orderBy('nama_instansi')->get();

        if (!$undangan) {
            return redirect()->route('tamu.rapat.index')
                ->with('error','Anda tidak terdaftar dalam rapat ini.');
        }

        return view('survey-rapat.form', [
            'survey'   => $survey,
            'nama'     => $undangan->nama ?? $user->name,          // ✅ ambil dari undangan
            'instansi' => $undangan->instansi->nama_instansi ?? '-', // ✅ ambil dari undangan
        ]);
    }

    /**
     * Submit respon survey internal.
     */
    public function submitInternal(Request $request, $slug)
    {
        $survey = SurveyRapat::where('slug', $slug)->firstOrFail();
        if ($survey->tipe !== 'Internal') {
            return redirect()->back()->with('warning', 'Survey ini bukan tipe internal.');
        }

        $user = Auth::user();

        // ✅ Cegah duplikat
        if (Auth::check()) {
            $exists = SurveyRapatRespon::where('survey_id',$survey->id)
                ->where('user_id',Auth::id())->first();
            if ($exists) {
                return redirect()->route('pegawai.survey.rapat.form.internal',$survey->slug)
                    ->with('warning','Anda sudah mengisi survey ini.');
            }
        }

        $jawaban = [
            'Kualitas Rapat' => $request->input('kualitas_rapat'),
            'Opini/Pendapat' => $request->input('opini'),
            'Saran'          => $request->input('saran'),
        ];

        SurveyRapatRespon::create([
            'survey_id' => $survey->id,
            'rapat_id'  => $survey->rapat?->id,
            'user_id'   => Auth::id(),
            'nama'      => $request->nama,
            'instansi'  => 'DINAS KOMUNIKASI, INFORMATIKA DAN STATISTIK',
            'jawaban'   => $jawaban,
        ]);

        $undangan = $survey->rapat->undangan()
            ->where('user_id', Auth::id())
            ->first();
        if ($undangan) {
            $undangan->update(['status_survey' => 'sudah_isi']);
        }

        return redirect()->route('survey.rapat.thanks',$survey->slug);
    }

    /**
     * Submit respon survey eksternal.
     */
    public function submitEksternal(Request $request, $slug)
    {
        $survey = SurveyRapat::where('slug', $slug)->firstOrFail();

        if ($survey->tipe !== 'Eksternal') {
            return redirect()->back()->with('warning', 'Survey ini bukan tipe eksternal.');
        }

        $user = Auth::user();
        $undangan = $survey->rapat->undangan()->where('user_id',$user->id)->first();

        if (!$undangan) {
            return redirect()->route('tamu.rapat.index')
                ->with('error','Anda tidak terdaftar dalam rapat ini.');
        }

        $nama = $undangan->nama ?? $user->name;
        $instansiFinal = $undangan->instansi->nama_instansi ?? '-';

        $jawaban = [
            'Kualitas Rapat' => $request->input('kualitas_rapat'),
            'Opini/Pendapat' => $request->input('opini'),
            'Saran'          => $request->input('saran'),
        ];

        SurveyRapatRespon::create([
            'survey_id' => $survey->id,
            'rapat_id'  => $survey->rapat?->id, // ✅ pakai relasi langsung
            'user_id'   => Auth::id(),
            'nama'      => $nama,          // ✅ ambil dari undangan
            'instansi'  => $instansiFinal,
            'jawaban'   => $jawaban,
        ]);

        // ✅ update status_survey untuk undangan user
        $undangan = $survey->rapat->undangan()
            ->where('user_id', Auth::id())
            ->first();
        if ($undangan) {
            $undangan->update(['status_survey' => 'sudah_isi']);
        }

        // ✅ update status_survey untuk instansi (opsional, jika mau tandai di level instansi)
        $undanganInstansi = $survey->rapat->undanganInstansi()
            ->where('instansi_id', $undangan->instansi_id ?? null)
            ->first();
        if ($undanganInstansi) {
            $undanganInstansi->update(['status_survey' => 'sudah_isi']);
        }

        return redirect()->route('survey.rapat.thanks', $survey->slug);
    }

}
