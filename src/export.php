<?php
// Mengatur zona waktu
date_default_timezone_set('Asia/Jakarta');

// Menghubungkan ke database
$host = 'localhost';
$db   = 'absentot';
$user = 'admin';
$pass = 'admin';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit;
}

// Ambil data dari tabel user
$stmt = $pdo->query("SELECT * FROM user");
$daftar_hadir = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ekspor ke CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="daftar_hadir.csv"');

$output = fopen('php://output', 'w');

// Menulis header kolom
fputcsv($output, ['Kode Asisten', 'Presensi Awal', 'Waktu Awal', 'Presensi Akhir']);

// Menulis data ke CSV
foreach ($daftar_hadir as $user) {
    $presensi_awal = $user['absen_awal'] ? ($user['keterangan_awal'] ?? 'Kosong') : 'Kosong';
    $waktu_awal = $user['jam_absen_awal'] ?? 'Kosong';
    $presensi_akhir = $user['absen_akhir'] ? 'Ada' : 'Kosong';

    fputcsv($output, [
        $user['kode_asisten'],
        $presensi_awal,
        $waktu_awal,
        $presensi_akhir
    ]);
}

fclose($output);
exit();
