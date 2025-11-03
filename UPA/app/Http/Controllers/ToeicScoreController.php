<?php
namespace App\Http\Controllers;

use App\Models\ToeicScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ToeicScoreController extends Controller
{
public function index()
{
    $scores = ToeicScore::all();
    return view('toeic_scores.index', compact('scores'));
}


    public function create()
    {
        return view('toeic_scores.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'pdf' => 'required|mimes:pdf|max:2048',
        // 'score' => 'nullable|integer|min:0|max:990',
    ]);

    $pdfPath = $request->file('pdf')->store('toeic_pdfs', 'public');

    ToeicScore::create([
        'pdf' => $pdfPath,
        'user_id' => auth()->id(),
    ]);

    return redirect()->route('toeic-scores.index')->with('success', 'PDF berhasil diunggah.');
}

    public function show($id)
    {
        $toeicScore = ToeicScore::findOrFail($id);
        return view('toeic_scores.show', compact('toeicScore'));
    }

    public function edit($id)
    {
        $toeicScore = ToeicScore::findOrFail($id);
        return view('toeic_scores.edit', compact('toeicScore'));
    }

    public function update(Request $request, $id)
    {
        $toeicScore = ToeicScore::findOrFail($id);

        $request->validate([
            'pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('pdf')) {
            Storage::disk('public')->delete($toeicScore->pdf);
            $path = $request->file('pdf')->store('toeic_pdfs', 'public');
            $toeicScore->update(['pdf' => $path]);
        }

        return redirect()->route('toeic-scores.index')->with('success', 'PDF berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $toeicScore = ToeicScore::findOrFail($id);
        Storage::disk('public')->delete($toeicScore->pdf);
        $toeicScore->delete();

        return redirect()->route('toeic-scores.index')->with('success', 'PDF berhasil dihapus.');
    }

// public function studentScores()
// {
//     $scores = ToeicScore::where('user_id', auth()->id())->get();
//     return view('toeic_scores.index', compact('scores'));
// }

public function studentScores()
{
    $scores = ToeicScore::whereHas('user', function ($query) {
        $query->where('role_name', 'admin');
    })->get();

    return view('toeic_scores.index', compact('scores'));
}


}
