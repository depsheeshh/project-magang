<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarSurvey;

class SurveyLinkController extends Controller
{
    /**
     * Tampilkan daftar link survey SKM
     */
    public function index()
    {
        $surveys = DaftarSurvey::orderBy('id')->get();
        return view('admin.survey_links.index', compact('surveys'));
    }

    /**
     * Tambah link survey baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'link_survey' => 'required|url|unique:daftar_survey,link_survey',
        ]);

        DaftarSurvey::create([
            'link_survey' => $request->link_survey,
            'is_active'   => false,
        ]);

        return redirect()->route('admin.survey_links.index')
            ->with('success','Link survey baru berhasil ditambahkan.');
    }

    /**
     * Aktifkan link survey tertentu (nonaktifkan yang lain)
     */
    public function activate($id)
    {
        // Nonaktifkan semua survey
        DaftarSurvey::query()->update(['is_active' => false]);

        // Aktifkan survey terpilih
        $survey = DaftarSurvey::findOrFail($id);
        $survey->update(['is_active' => true]);

        return redirect()->route('admin.survey_links.index')
            ->with('success','Survey berhasil diaktifkan.');
    }

    /**
     * Nonaktifkan link survey tertentu
     */
    public function deactivate($id)
    {
        $survey = DaftarSurvey::findOrFail($id);
        $survey->update(['is_active' => false]);

        return redirect()->route('admin.survey_links.index')
            ->with('success','Survey berhasil dinonaktifkan.');
    }

    /**
     * Hapus link survey
     */
    public function destroy($id)
    {
        $survey = DaftarSurvey::findOrFail($id);
        $survey->delete();

        return redirect()->route('admin.survey_links.index')
            ->with('success','Survey berhasil dihapus.');
    }
}
