<?php

namespace App\Http\Controllers;

use App\Models\LibraryText;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class LibraryTextController extends Controller
{
    /**
     * Display a list of all dream texts.
     */
    public function index()
    {
        $texts = LibraryText::all(); // Or ->paginate(10) if you want pagination
        return view('dreams.library.index', compact('texts'));
    }

    /**
     * Display a specific dream text by ID.
     */
    public function show($id)
    {
        $text = LibraryText::findOrFail($id);
        return view('dreams.library.show', compact('text'));
    }

    /**
     * Download the selected dream text as a PDF.
     */
    public function download($id)
    {
        $text = LibraryText::findOrFail($id);
        $pdf = Pdf::loadView('dreams.library.pdf', compact('text'));
        return $pdf->download(Str::slug($text->title) . '.pdf');
    }
}
