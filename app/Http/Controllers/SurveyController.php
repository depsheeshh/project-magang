<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'rating'                => 'required|integer|min:1|max:5',
            'feedback'              => 'nullable|string|max:1000',
            'kemudahan_registrasi'  => 'required|integer|min:1|max:5',
            'keramahan_pelayanan'   => 'required|integer|min:1|max:5',
            'waktu_tunggu'          => 'required|integer|min:1|max:5',
            'saran'                 => 'nullable|string|max:1000',
        ]);

        $kunjungan = Kunjungan::findOrFail($kunjunganId);

        $survey = Survey::where('kunjungan_id', $kunjungan->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Survey tidak ditemukan.'
            ], 404);
        }

        if ($survey->rating !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Survey sudah pernah diisi.'
            ], 400);
        }

        $survey->update([
            'rating'                => $request->rating,
            'feedback'              => $request->feedback,
            'kemudahan_registrasi'  => $request->kemudahan_registrasi,
            'keramahan_pelayanan'   => $request->keramahan_pelayanan,
            'waktu_tunggu'          => $request->waktu_tunggu,
            'saran'                 => $request->saran,
            'link'                  => null,
        ]);

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
            'rating'                => 'required|integer|min:1|max:5',
            'feedback'              => 'nullable|string|max:1000',
            'kemudahan_registrasi'  => 'required|integer|min:1|max:5',
            'keramahan_pelayanan'   => 'required|integer|min:1|max:5',
            'waktu_tunggu'          => 'required|integer|min:1|max:5',
            'saran'                 => 'nullable|string|max:1000',
        ]);

        $survey->update([
            'rating'                => $request->rating,
            'feedback'              => $request->feedback,
            'kemudahan_registrasi'  => $request->kemudahan_registrasi,
            'keramahan_pelayanan'   => $request->keramahan_pelayanan,
            'waktu_tunggu'          => $request->waktu_tunggu,
            'saran'                 => $request->saran,
            'link'                  => null,
        ]);

        $kunjungan->update(['is_survey_done' => true]);

        return redirect()->route('survey.thanks');
    }

    public function fill(Survey $survey)
    {
        // hanya kalau survey belum diisi
        if ($survey->rating !== null) {
            return redirect()->route('admin.surveys.index')
                ->with('error','Survey sudah pernah diisi.');
        }

        return view('admin.surveys.fill', compact('survey'));
    }

    public function fillSubmit(Request $request, Survey $survey)
    {
        $request->validate([
            'rating'                => 'required|integer|min:1|max:5',
            'feedback'              => 'nullable|string|max:1000',
            'kemudahan_registrasi'  => 'required|integer|min:1|max:5',
            'keramahan_pelayanan'   => 'required|integer|min:1|max:5',
            'waktu_tunggu'          => 'required|integer|min:1|max:5',
            'saran'                 => 'nullable|string|max:1000',
        ]);

        $survey->update([
            'rating'                => $request->rating,
            'feedback'              => $request->feedback,
            'kemudahan_registrasi'  => $request->kemudahan_registrasi,
            'keramahan_pelayanan'   => $request->keramahan_pelayanan,
            'waktu_tunggu'          => $request->waktu_tunggu,
            'saran'                 => $request->saran,
            'link'                  => null,
        ]);

        $survey->kunjungan->update(['is_survey_done' => true]);

        return redirect()->route('admin.surveys.index')
            ->with('success','Survey berhasil diisi oleh sekretariat.');
    }
    public function rekap(Request $request)
    {
        $periode = $request->get('periode', 'harian'); // default harian

        $query = Survey::query()->whereNotNull('rating');

        if ($periode === 'bulanan') {
            $rekap = $query->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total, AVG(rating) as avg_rating')
                        ->groupBy('tahun','bulan')
                        ->orderBy('tahun','desc')
                        ->orderBy('bulan','desc')
                        ->get();
        } elseif ($periode === 'tahunan') {
            $rekap = $query->selectRaw('YEAR(created_at) as tahun, COUNT(*) as total, AVG(rating) as avg_rating')
                        ->groupBy('tahun')
                        ->orderBy('tahun','desc')
                        ->get();
        } else {
            $rekap = $query->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total, AVG(rating) as avg_rating')
                        ->groupBy('tanggal')
                        ->orderBy('tanggal','desc')
                        ->get();
        }

        // siapkan data untuk chart
        $labels = [];
        $totals = [];
        $avgs   = [];

        foreach ($rekap as $r) {
            if ($periode === 'harian') {
                $labels[] = \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y');
            } elseif ($periode === 'bulanan') {
                $labels[] = $r->bulan.'/'.$r->tahun;
            } else {
                $labels[] = $r->tahun;
            }
            $totals[] = $r->total;
            $avgs[]   = round($r->avg_rating,2);
        }

        return view('admin.surveys.rekap', compact('rekap','periode','labels','totals','avgs'));
    }
    public function exportPdf($periode)
    {
        $query = Survey::query()->whereNotNull('rating');

        if ($periode === 'bulanan') {
            $rekap = $query->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total, AVG(rating) as avg_rating')
                        ->groupBy('tahun','bulan')
                        ->orderBy('tahun','desc')
                        ->orderBy('bulan','desc')
                        ->get();
        } elseif ($periode === 'tahunan') {
            $rekap = $query->selectRaw('YEAR(created_at) as tahun, COUNT(*) as total, AVG(rating) as avg_rating')
                        ->groupBy('tahun')
                        ->orderBy('tahun','desc')
                        ->get();
        } else {
            $rekap = $query->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total, AVG(rating) as avg_rating')
                        ->groupBy('tanggal')
                        ->orderBy('tanggal','desc')
                        ->get();
        }

        $pdf = Pdf::loadView('admin.surveys.rekap_pdf', compact('rekap','periode'))
                ->setPaper('a4','portrait');

        return $pdf->download("rekap-survey-{$periode}.pdf");
    }


}
