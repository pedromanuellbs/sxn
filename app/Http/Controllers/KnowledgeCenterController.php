<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KnowledgeCenter;
use Illuminate\Support\Facades\Storage;


class KnowledgeCenterController extends Controller
{
    public function index(){
        $knowledge = KnowledgeCenter::get();
        return view('knowledge_center', compact('knowledge'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:5120', // max 5MB
        ]);

        // Simpan file
        $file = $request->file('file');
        $path = $file->store('knowledge_files'); // simpan di storage/app/knowledge_files
        $size = $file->getSize(); // dalam byte

        // Simpan ke DB
        KnowledgeCenter::create([
            'title' => $request->title,
            'status' => 'Not Ready', // atau default awalnya "Not Ready"
            'file_path' => $path,
            'size' => $this->formatSize($size),
        ]);

        return redirect()->route('knowledge.index')->with('success', 'File berhasil diunggah!');
    }

    // Fungsi bantu: format size ke KB/MB
    private function formatSize($bytes)
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }

    public function toggle($id)
{
    $knowledge = KnowledgeCenter::findOrFail($id);

    $knowledge->status = $knowledge->status === 'Ready' ? 'Not Ready' : 'Ready';
    $knowledge->save();

    return redirect()->back()->with('success', 'Status updated successfully.');
}
public function stream($id)
{
    $knowledge = KnowledgeCenter::findOrFail($id);
    $filePath = storage_path('app/' . $knowledge->file_path);

    if (!file_exists($filePath)) {
        abort(404);
    }

    return response()->file($filePath);
}

public function destroy($id)
{
    $file = KnowledgeCenter::findOrFail($id);
    $file->delete();

    return redirect()->back()->with('success', 'Knowledge source deleted successfully.');
}


}
