<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCR - Baca Teks dari Gambar</title>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <style>
        .progress-bar {
            display: none;
            width: 0%;
            height: 5px;
            background: #4caf50;
            transition: width 0.3s ease;
        }
        .progress-wrapper {
            width: 100%;
            background: #ddd;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 20px;
        }
        .output-area {
            margin-top: 30px;
        }
        .output-box {
            background: #f5f5f5;
            border: 1px solid #ccc;
            padding: 1rem;
            border-radius: 5px;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>READ TEXT FROM IMAGES</h1>
        <p>Upload gambar atau ambil foto untuk mengekstrak teks menggunakan OCR</p>

        <form id="ocrForm" enctype="multipart/form-data">
            @csrf
            <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                <input type="file" name="image" id="imageInput" accept="image/*" hidden required>
                <div class="upload-icon">📄</div>
                <p><strong>Pilih File atau Drag & Drop</strong></p>
                <p>Mendukung format JPG, PNG. Maks. ukuran 5MB</p>
            </div>

            <div class="upload-actions">
                <button type="button" onclick="ambilFoto()">📷 Ambil Foto</button>
                <button type="submit" class="submit-btn">SCAN</button>
            </div>

            {{-- Loading Bar --}}
            <div class="progress-wrapper" id="progressWrapper" style="display: none;">
                <div class="progress-bar" id="progressBar"></div>
            </div>
        </form>

        {{-- Preview dan Hasil --}}
        <div class="output-area" id="outputArea" style="display: none;">
            <h3>📄 Hasil Prediksi Tulisan Tangan</h3>
            <img id="previewImage" src="" alt="Preview Gambar" style="max-width: 100%; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 10px;">
            <div class="output-box" id="ocrText"></div>
            <div class="output-meta">
                <p><strong>Jumlah Karakter:</strong> <span id="charCount"></span></p>
                <p><strong>Jumlah Kata:</strong> <span id="wordCount"></span></p>
                <button onclick="copyText()">📋 Salin Teks</button>
                <a id="downloadLink" class="download-btn" href="#" download>⬇️ Unduh Gambar</a>
            </div>
        </div>

        <div id="errorBox" style="color: red; margin-top: 20px;"></div>
    </div>

    <script>
        function ambilFoto() {
            document.getElementById('imageInput').click();
        }

        function copyText() {
            const text = document.getElementById('ocrText').innerText;
            if (text) {
                navigator.clipboard.writeText(text).then(() => {
                    alert('Teks berhasil disalin!');
                });
            }
        }

        document.getElementById('ocrForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const progressWrapper = document.getElementById('progressWrapper');
            const progressBar = document.getElementById('progressBar');
            const outputArea = document.getElementById('outputArea');
            const errorBox = document.getElementById('errorBox');

            progressWrapper.style.display = 'block';
            progressBar.style.width = '0%';
            errorBox.innerText = '';
            outputArea.style.display = 'none';

            // Progress fake animation
            let width = 0;
            const interval = setInterval(() => {
                if (width >= 90) return;
                width += 2;
                progressBar.style.width = width + '%';
            }, 100);

            try {
                const response = await fetch("{{ route('ocr.index') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                clearInterval(interval);
                progressBar.style.width = '100%';

                if (!response.ok) {
                    const err = await response.json();
                    errorBox.innerText = err.error || 'Terjadi kesalahan';
                    return;
                }

                const data = await response.json();

                // Tampilkan hasil
                document.getElementById('ocrText').innerText = data.text;
                document.getElementById('charCount').innerText = data.charCount;
                document.getElementById('wordCount').innerText = data.wordCount;
                document.getElementById('previewImage').src = data.imageUrl;
                document.getElementById('downloadLink').href = data.imageUrl;
                outputArea.style.display = 'block';

            } catch (err) {
                clearInterval(interval);
                errorBox.innerText = 'Gagal mengirim data: ' + err.message;
            }
        });
    </script>
</body>
</html>
