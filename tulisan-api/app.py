from flask import Flask, request, jsonify
from PIL import Image
import pytesseract
import os
import cv2
import numpy as np
import shutil

app = Flask(__name__)

# Bersihkan dan buat ulang folder debug_preprocessed saat server dimulai
DEBUG_DIR = 'debug_preprocessed'
if os.path.exists(DEBUG_DIR):
    shutil.rmtree(DEBUG_DIR)
os.makedirs(DEBUG_DIR, exist_ok=True)

def preprocess_image(image_path):
    image = cv2.imread(image_path)

    # Grayscale
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # Resize agar teks lebih jelas
    gray = cv2.resize(gray, None, fx=2, fy=2, interpolation=cv2.INTER_CUBIC)

    # Denoising (mengurangi noise acak)
    denoised = cv2.fastNlMeansDenoising(gray, h=30)

    # Adaptive Thresholding
    thresh = cv2.adaptiveThreshold(denoised, 255,
                                   cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
                                   cv2.THRESH_BINARY, 31, 15)

    # Morphological Opening (hilangkan noise kecil)
    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (2, 2))
    opened = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, kernel)

    # Crop margin putih
    contours, _ = cv2.findContours(opened, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    if contours:
        x, y, w, h = cv2.boundingRect(np.vstack(contours))
        cropped = opened[y:y+h, x:x+w]
    else:
        cropped = opened

    # Sharpening
    sharpen_kernel = np.array([[0, -1, 0],
                               [-1, 5, -1],
                               [0, -1, 0]])
    sharpened = cv2.filter2D(cropped, -1, sharpen_kernel)

    # Simpan hasil preprocessing
    base_name = os.path.basename(image_path)
    name_wo_ext = os.path.splitext(base_name)[0]
    processed_path = os.path.join(DEBUG_DIR, f"{name_wo_ext}_processed.png")
    cv2.imwrite(processed_path, sharpened)

    return processed_path

@app.route('/ocr', methods=['POST'])
def ocr_image():
    if 'image' not in request.files:
        return jsonify({'error': 'No image uploaded'}), 400

    image = request.files['image']
    save_dir = '../tulisan-laravel/storage/app/public/uploads/'
    os.makedirs(save_dir, exist_ok=True)

    temp_path = os.path.join(save_dir, image.filename)
    image.save(temp_path)

    try:
        # Preprocessing gambar
        processed_path = preprocess_image(temp_path)

        # Konfigurasi OCR
        config = '--oem 3 --psm 4'  # cocok untuk paragraf tulisan tangan
        text = pytesseract.image_to_string(Image.open(processed_path), lang='ind+eng', config=config).strip()

        # Jika kosong, fallback ke PSM 11
        if not text or text.isspace():
            config_fallback = '--oem 3 --psm 11'
            text = pytesseract.image_to_string(Image.open(processed_path), lang='custom+ind+eng', config=config_fallback).strip()

        if not text or text.isspace():
            text = '[Tidak ada teks yang terdeteksi]'

        return jsonify({'text': text})

    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
