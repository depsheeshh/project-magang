<?php

namespace App\Http\Controllers;

use App\Models\SurveyRapat;
use App\Models\SurveyRapatRespon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyRapatFormController extends Controller
{
    /**
     * Tampilkan form survey berdasarkan slug.
     */
    public function form($slug)
    {
        $survey = SurveyRapat::where('slug', $slug)->firstOrFail();

        return view('survey-rapat.form', compact('survey'));
    }

    /**
     * Submit respon survey.
     */
    public function submit(Request $request, $slug)
    {
        $survey = SurveyRapat::where('slug', $slug)->firstOrFail();

        // Validation rules
        $rules = [
            'nama' => 'required|string|max:255',
        ];

        if ($survey->tipe === 'eksternal') {
            $rules['instansi'] = 'required|string|max:255';
        }

        $request->validate($rules);

        // Prevent internal duplicate responses
        if ($survey->tipe === 'internal' && Auth::check()) {
            $exists = SurveyRapatRespon::where('survey_id', $survey->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($exists) {
                return redirect()->route('survey.rapat.form', $survey->slug)
                    ->with('warning', 'Anda sudah mengisi survey ini.');
            }
        }

        // 🔑 Simpan jawaban dengan label pertanyaan yang jelas
        $jawaban = [
            'Kualitas Rapat' => $request->input('kualitas_rapat'),
            'Opini/Pendapat' => $request->input('opini'),
            'Saran' => $request->input('saran'),
        ];

        SurveyRapatRespon::create([
            'survey_id' => $survey->id,
            'rapat_id'  => $survey->rapat->first()->id ?? null,
            'user_id'   => Auth::id(),
            'nama'      => $request->nama,
            'instansi'  => $survey->tipe === 'eksternal' ? $request->instansi : null,
            'jawaban'   => $jawaban,
        ]);

        return redirect()->route('survey.rapat.form', $survey->slug)
            ->with('success', 'Terima kasih, survey berhasil diisi.');
    }
}
