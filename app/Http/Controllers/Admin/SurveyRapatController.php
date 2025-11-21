<?php

namespace App\Http\Controllers\Admin;

use App\Models\SurveyRapat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SurveyRapatController extends Controller
{
    public function index()
    {
        $surveys = SurveyRapat::latest()->paginate(10);
        return view('admin.survey-rapat.index', compact('surveys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:Internal,Eksternal',
            'deskripsi' => 'nullable|string',
        ]);

        // slug otomatis di model, cukup simpan field utama
        SurveyRapat::create($request->only(['judul','tipe','deskripsi']));

        return redirect()->route('admin.survey-rapat.index')
            ->with('success', 'Survey rapat berhasil dibuat.');
    }

    public function show(SurveyRapat $survey_rapat)
    {
        // load responden, nanti bisa ditambah questions
        $survey_rapat->load('respon','rapat');
        return view('admin.survey-rapat.show', compact('survey_rapat'));
    }

    public function update(Request $request, SurveyRapat $survey_rapat)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:Internal,Eksternal',
            'deskripsi' => 'nullable|string',
        ]);

        $survey_rapat->update([
            'judul' => $request->judul,
            'tipe' => $request->tipe,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Survey rapat berhasil diperbarui.');
    }

    public function destroy(SurveyRapat $survey_rapat)
    {
        $survey_rapat->delete();
        return back()->with('success', 'Survey rapat berhasil dihapus.');
    }

    public function getByTipe($tipe)
    {
        $surveys = SurveyRapat::where('tipe', $tipe)
            ->select('id','judul','tipe')
            ->orderBy('created_at','desc')
            ->get();

        return response()->json($surveys);
    }
}
