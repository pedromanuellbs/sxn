<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048', // 2MB max
        ]);

        $filename = time().'_'.$request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('pdfs', $filename, 'public');

        return back()->with('success', 'PDF uploaded successfully!');
    }
}
