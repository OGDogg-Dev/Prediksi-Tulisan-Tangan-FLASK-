<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OCRController extends Controller
{
    public function index(Request $request)
    {
        // GET request: tampilkan halaman form
        if ($request->isMethod('get')) {
            return view('ocr.form');
        }

        // POST request via AJAX: proses gambar dan kirim JSON response
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|max:5120' // Maks 5MB
            ]);

            $image = $request->file('image');

            // Simpan gambar ke folder storage
            $storedPath = $image->storePublicly('uploads', 'public');
            $imagePath = storage_path('app/public/' . $storedPath);

            try {
                $response = Http::attach(
                    'image',
                    file_get_contents($imagePath),
                    $image->getClientOriginalName()
                )->post('http://localhost:5000/ocr');

                if ($response->successful()) {
                    $text = trim($response->json('text') ?? '');
                    return response()->json([
                        'text' => $text,
                        'charCount' => strlen($text),
                        'wordCount' => str_word_count($text),
                        'imageUrl' => asset('storage/' . $storedPath)
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Gagal memproses OCR (respons gagal dari Flask API)'
                    ], 500);
                }

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Gagal menghubungi tulisan-api: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Gambar tidak ditemukan dalam request.'
        ], 400);
    }
}
