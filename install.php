<?php
// Jalankan sekali setelah config.php diisi, lalu HAPUS install.php dari hosting.
session_start();
$cfgFile = __DIR__ . '/config.php';
if (!file_exists($cfgFile)) { http_response_code(500); exit('Buat config.php dari config.example.php dan isi data MySQL InfinityFree terlebih dahulu.'); }
$cfg = require $cfgFile;
try {
  $pdo = new PDO("mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset=utf8mb4", $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
  $sql = [
"CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(80) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, full_name VARCHAR(150) NOT NULL, role ENUM('admin','teknisi') NOT NULL DEFAULT 'teknisi', active TINYINT(1) NOT NULL DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS parts (id INT AUTO_INCREMENT PRIMARY KEY, code VARCHAR(80) NOT NULL UNIQUE, name VARCHAR(180) NOT NULL, spec TEXT, unit VARCHAR(40) NOT NULL DEFAULT 'pcs', min_stock INT NOT NULL DEFAULT 0, active TINYINT(1) NOT NULL DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS repairs (id INT AUTO_INCREMENT PRIMARY KEY, req_no VARCHAR(40) NOT NULL UNIQUE, req_date DATE NOT NULL, asset VARCHAR(180) NOT NULL, location VARCHAR(180) NOT NULL, damage TEXT NOT NULL, `condition` VARCHAR(80) NOT NULL, steps TEXT, finish_date DATE NULL, status ENUM('Menunggu','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu', created_by VARCHAR(150) NOT NULL, tech_name VARCHAR(150) NOT NULL DEFAULT '', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX(status), INDEX(req_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS movements (id INT AUTO_INCREMENT PRIMARY KEY, part_id INT NOT NULL, move_date DATE NOT NULL, kind ENUM('MASUK','KELUAR') NOT NULL, qty INT NOT NULL, repair_id INT NULL, taken_by VARCHAR(150) NOT NULL, notes TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX(part_id), INDEX(repair_id), CONSTRAINT fk_mov_part FOREIGN KEY(part_id) REFERENCES parts(id), CONSTRAINT fk_mov_repair FOREIGN KEY(repair_id) REFERENCES repairs(id) ON DELETE SET NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
"CREATE TABLE IF NOT EXISTS repair_parts (id INT AUTO_INCREMENT PRIMARY KEY, repair_id INT NOT NULL, part_id INT NOT NULL, qty INT NOT NULL, movement_id INT NOT NULL UNIQUE, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_rp_repair FOREIGN KEY(repair_id) REFERENCES repairs(id), CONSTRAINT fk_rp_part FOREIGN KEY(part_id) REFERENCES parts(id), CONSTRAINT fk_rp_move FOREIGN KEY(movement_id) REFERENCES movements(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
  ];
  foreach ($sql as $q) $pdo->exec($q);
  $count=(int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
  if ($count===0) {
    $st=$pdo->prepare('INSERT INTO users(username,password,full_name,role) VALUES(?,?,?,?)');
    $st->execute(['admin',password_hash('admin123', PASSWORD_DEFAULT),'Administrator','admin']);
    $st->execute(['teknisi',password_hash('teknisi123', PASSWORD_DEFAULT),'Teknisi Demo','teknisi']);
  }
  echo '<meta charset="utf-8"><h2>Instalasi berhasil</h2><p>Tabel database sudah siap. Login awal: <b>admin</b> / <b>admin123</b> dan <b>teknisi</b> / <b>teknisi123</b>.</p><p><b>Segera hapus file install.php dari hosting</b>, lalu login dan buat akun dengan kata sandi yang kuat.</p><p><a href="index.php">Buka aplikasi</a></p>';
} catch (Throwable $e) { http_response_code(500); echo '<meta charset="utf-8"><h2>Koneksi/instalasi gagal</h2><p>Periksa host, nama database, username, password, dan pastikan database sudah dibuat di panel InfinityFree.</p><pre>'.htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8').'</pre>'; }
