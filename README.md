# Birama Photobooth

Photobooth sederhana menggunakan PHP native, HTML, CSS, JavaScript, PHP GD, dan PHPMailer.

## Struktur

- `index.php`: landing page
- `email.php`: validasi dan penyimpanan email di session
- `camera.php`: live camera preview dan countdown
- `save_photos.php`: menerima hasil canvas, menggabungkan foto, dan template PNG
- `result.php`: preview hasil akhir
- `send_email.php`: pengiriman hasil melalui SMTP
- `assets/background/`: gambar background landing page
- `assets/templates/`: template PNG transparan
- `uploads/`: foto sementara dan hasil akhir

## Menjalankan di XAMPP

1. Salin folder `photobooth_birama` ke `C:\xampp\htdocs\`.
2. Buka XAMPP Control Panel dan nyalakan **Apache**.
3. Pastikan ekstensi GD aktif di `C:\xampp\php\php.ini` dengan menghapus tanda `;` pada `extension=gd`, lalu restart Apache.
4. Buka terminal di folder project dan jalankan `composer install`. PHPMailer akan dipasang ke folder `vendor/`.
5. Tambahkan gambar background JPG/PNG/WEBP ke `assets/background/`.
6. Tambahkan template frame PNG transparan ke `assets/templates/`. Template pertama yang ditemukan akan dipakai.
7. Buka `http://localhost/photobooth_birama/`.

## Konfigurasi SMTP Gmail

1. Aktifkan verifikasi dua langkah di akun Google.
2. Buat **App Password** dari pengaturan keamanan akun Google.
3. Buka `send_email.php`, lalu ubah `Username`, `Password`, dan alamat `setFrom`.
4. Untuk Gmail, gunakan host `smtp.gmail.com`, enkripsi `PHPMailer::ENCRYPTION_STARTTLS`, dan port `587`.

Untuk produksi, jangan menyimpan kredensial SMTP langsung di repository. Pindahkan ke environment variable atau file konfigurasi di luar web root.

## Catatan kamera

Kamera browser membutuhkan HTTPS atau `localhost`. Saat menguji di XAMPP, gunakan alamat `localhost`, bukan alamat IP biasa.
