# 📝 Prediksi Tulisan Tangan

**Prediksi Tulisan Tangan** adalah aplikasi web untuk membaca teks dari gambar menggunakan **Tesseract OCR**, dengan frontend berbasis **Laravel** dan backend menggunakan **Flask**.  
Mendukung bahasa **Indonesia dan Inggris**, cocok untuk dokumen hasil scan, tulisan tangan, KTP, buku, dan lainnya.

---

## 📁 Struktur Direktori

```
tulisan-ocr-app/
├── tulisan-laravel/   # Frontend: Laravel + Tailwind CSS
└── tulisan-api/       # Backend: Flask + OpenCV + Tesseract OCR
```

---

## 🚀 Cara Menjalankan Proyek

### 🔧 1. Menjalankan Laravel (Frontend)

```bash
cd tulisan-laravel

# Salin konfigurasi default
cp .env.example .env

# Install dependensi Laravel
composer install

# Install Tailwind & Vite
npm install && npm run dev

# Generate APP_KEY
php artisan key:generate

# Jalankan server Laravel
php artisan serve
```

> Akses di browser: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

### 🧠 2. Menjalankan Flask (Backend OCR API)

```bash
cd tulisan-api

# Buat dan aktifkan virtual environment
python3 -m venv venv
source venv/bin/activate  # Windows: venv\Scripts\activate

# Install dependensi Python
pip install -r requirements.txt

# Jalankan server Flask
python app.py
```

> Akses API: [http://127.0.0.1:5000](http://127.0.0.1:5000)

---

## 🔄 Alur Kerja Sistem

1. Pengguna mengunggah gambar dari halaman Laravel.
2. Gambar dikirim ke backend Flask secara **AJAX (tanpa reload)**.
3. Flask melakukan **preprocessing gambar** menggunakan OpenCV:
   - Grayscale
   - Resize
   - Denoising
   - Thresholding
   - Morphological Open
   - Crop margin putih
   - Sharpening
4. Flask menjalankan **Tesseract OCR** (`--psm 4`, bahasa: `ind+eng`).
5. Hasil teks dikirim kembali ke frontend dan langsung ditampilkan.
6. Pengguna dapat langsung **menyalin teks** hasil OCR.

---

## ⚙️ Teknologi yang Digunakan

| Komponen     | Teknologi                         |
|--------------|------------------------------------|
| Frontend     | Laravel 12, Tailwind CSS v4        |
| Backend      | Flask, Python 3, OpenCV, Pillow    |
| OCR Engine   | Tesseract OCR (ind+eng)            |
| Komunikasi   | AJAX (tanpa reload halaman)        |

---

## 🎯 Fitur Aplikasi

✅ Upload gambar dari file atau kamera  
✅ Preprocessing otomatis sebelum OCR  
✅ Dukungan bahasa Indonesia & Inggris  
✅ Teks hasil OCR langsung tampil tanpa reload  
✅ Tombol salin teks 1 klik  

🚫 Tidak tersedia dark mode  
🚫 Tidak tersedia fitur ekspor PDF  

---

## 📦 Contoh `requirements.txt` (Flask)

```
Flask
pillow
opencv-python
numpy
pytesseract
```

---

## 💻 Persyaratan Sistem

Pastikan perangkat telah terinstal:

- Python ≥ 3.8  
- PHP ≥ 8  
- Node.js & NPM  
- Composer  
- Tesseract OCR ✅ (Wajib)

### 📌 Cara install Tesseract:

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install tesseract-ocr
```

**Windows:**

1. Unduh dari [https://github.com/tesseract-ocr/tesseract](https://github.com/tesseract-ocr/tesseract)  
2. Tambahkan direktori `tesseract.exe` ke Environment Variables

---

## 📍 Catatan Tambahan

- Backend Flask hanya menerima request dari frontend Laravel di `localhost`.
- Pastikan port Laravel (`8000`) dan Flask (`5000`) **tidak bentrok**.
- Folder `uploads` otomatis dibuat di Laravel saat pengguna mengunggah gambar.
