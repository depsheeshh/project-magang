<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    /**
     * Daftar hasil survey (untuk admin/frontliner).
     */
    public function index()
    {
        $surveys = Survey::with(['user','kunjungan.tamu'])
            ->latest()
            ->paginate(20);

        foreach ($surveys as $s) {
            // Jika survey sudah diisi, pastikan link dikosongkan
            if (!is_null($s->rating) || !is_null($s->feedback)) {
                if ($s->link) {
                    $s->link = null;
                    $s->save();
                }
            }
            // Jika survey kosong dan belum ada link, generate link
            elseif (is_null($s->rating) && is_null($s->feedback) && !$s->link) {
                $s->link = url('/survey/'.$s->kunjungan_id.'/'.Str::uuid());
                $s->save();
            }
        }

        return view('admin.surveys.index', compact('surveys'));
    }

    /**
     * Simpan hasil survey dari modal setelah checkout.
     */
    public function store(Request $request, $kunjunganId)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $kunjungan = Kunjungan::findOrFail($kunjunganId);

        // Ambil survey kosong yang dibuat saat checkout
        $survey = Survey::where('kunjungan_id', $kunjungan->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Survey tidak ditemukan.'
            ], 404);
        }

        // Kalau sudah pernah diisi, cegah double submit
        if ($survey->rating !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Survey sudah pernah diisi.'
            ], 400);
        }

        // Update survey dengan data dari modal
        $survey->update([
            'rating'   => $request->rating,
            'feedback' => $request->feedback,
            'link'     => null, // pastikan link tetap null kalau sudah isi survey
        ]);

        // update status kunjungan -> sudah survey
        $kunjungan->update(['is_survey_done' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih, survey berhasil disimpan.'
        ]);
    }

    /**
     * Detail hasil survey (untuk admin).
     */
    public function show(Survey $survey)
    {
        return view('admin.surveys.show', compact('survey'));
    }

    /**
     * Hapus survey (untuk admin).
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();
        return redirect()->route('admin.surveys.index')
            ->with('success', 'Survey berhasil dihapus.');
    }

    /**
     * Form survey publik via link.
     */
    public function form(Kunjungan $kunjungan, $token)
    {
        $survey = Survey::where('kunjungan_id', $kunjungan->id)
            ->where('link', url("/survey/{$kunjungan->id}/{$token}"))
            ->firstOrFail();

        return view('survey.form', compact('kunjungan','survey'));
    }

    /**
     * Submit survey publik via link.
     */
    public function submit(Request $request, Kunjungan $kunjungan, $token)
    {
        $survey = Survey::where('kunjungan_id', $kunjungan->id)
            ->where('link', url("/survey/{$kunjungan->id}/{$token}"))
            ->firstOrFail();

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $survey->update([
            'rating'   => $request->rating,
            'feedback' => $request->feedback,
            'link'     => null, // kosongkan link setelah survey diisi via link
        ]);

        $kunjungan->update(['is_survey_done' => true]);

        return redirect()->route('survey.thanks');
    }
}
