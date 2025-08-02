<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediksi Tulisan Tangan App | Read Text From Images</title>
    <link rel="stylesheet" href="{{ asset('assets/landing.css') }}">
</head>
<body>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Prediksi Tulisan Tangan</h1>
        <p>Prediksi Tulisan Tangan berbasis web untuk membaca teks dari gambar secara otomatis — mudah, cepat, dan gratis digunakan langsung di browser Anda.</p>
        <a href="{{ route('ocr.index') }}" class="cta-button">🔍 Coba Sekarang</a>
        <div class="floating-background"></div>
    </div>

    <!-- Features -->
    <div class="features">
        <div class="feature-box">
            <h3>📤 Upload Gambar</h3>
            <p>Dukungan drag & drop atau ambil langsung dari kamera Anda untuk mulai membaca teks.</p>
        </div>
        <div class="feature-box">
            <h3>🧠 Didukung OCR Engine</h3>
            <p>Menggunakan Tesseract OCR yang canggih untuk ekstraksi teks dari gambar dengan akurasi tinggi.</p>
        </div>
        <div class="feature-box">
            <h3>📋 Salin & Unduh</h3>
            <p>Hasil teks dapat langsung disalin atau diunduh untuk digunakan kembali dengan mudah.</p>
        </div>
    </div>

    <!-- Penjelasan Tambahan -->
    <div class="explanation">
        <h2>Bagaimana Cara Kerjanya?</h2>
        <p>
            Anda cukup mengunggah gambar yang berisi teks — sistem kami akan memproses gambar tersebut menggunakan teknologi Optical Character Recognition (OCR) untuk mengekstrak teks di dalamnya. Dalam hitungan detik, teks akan muncul dan siap Anda salin atau unduh.
        </p>
        <p>
            Cocok digunakan untuk membaca dokumen cetak, hasil scan, nota, atau konten visual lainnya yang mengandung teks.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; {{ date('Y') }} Prediksi Tulisan Tangan App. Dibuat dengan ❤️ oleh Kelompok 1.
    </div>

</body>
</html>
