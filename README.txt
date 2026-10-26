ADMINISTRASI TEKNISI - PHP + MySQL (InfinityFree)

ISI PAKET
- index.php          Halaman utama dan proses login
- config.example.php Contoh konfigurasi koneksi MySQL
- install.php        Membuat tabel dan akun awal (jalankan sekali saja)
- .htaccess          Pengaturan dasar opsional

LANGKAH PEMASANGAN
1. Unggah file-file ini ke folder htdocs di InfinityFree.
2. Di File Manager, salin config.example.php menjadi config.php.
3. Edit config.php dan isi host, nama database, username, dan password MySQL persis seperti di panel InfinityFree.
4. Buka https://DOMAIN-ANDA/install.php satu kali.
5. Jika instalasi berhasil, segera hapus install.php dari htdocs.
6. Buka https://DOMAIN-ANDA/ lalu login.

LOGIN AWAL (ubah kata sandi sesegera mungkin)
Admin:   admin / admin123
Teknisi: teknisi / teknisi123

PENTING
- Jangan unggah config.php ke GitHub atau membagikan password database.
- Jika tabel sudah dibuat di phpMyAdmin, installer memakai CREATE TABLE IF NOT EXISTS dan akan membuat akun awal hanya bila tabel users masih kosong.
- Jangan biarkan install.php berada di hosting setelah instalasi.
