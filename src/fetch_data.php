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

// Mendapatkan parameter pencarian
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$presensiAkhir = $_GET['presensiAkhir'] ?? '';

// Membangun query
$query = "
    SELECT u.*, p.izin_awal, p.izin_telat, p.izin_akhir, p.izin_tidak_menghadiri
    FROM user u
    LEFT JOIN perizinan p ON u.kode_asisten = p.kode_asisten
    WHERE 1=1
";
$params = [];

if (!empty($search)) {
    $query .= " AND u.kode_asisten LIKE :search";
    $params['search'] = "%$search%";
}

if ($status) {
    if ($status === 'tepat_waktu') {
        $query .= " AND u.absen_awal = 1 AND u.keterangan_awal = 'Tepat waktu'";
    } elseif ($status === 'lambat') {
        $query .= " AND u.absen_awal = 1 AND u.keterangan_awal = 'Lambat'";
    } elseif ($status === 'kosong') {
        $query .= " AND u.absen_awal = 0";
    }
}

if ($presensiAkhir) {
    if ($presensiAkhir === 'ada') {
        $query .= " AND u.absen_akhir = 1";
    } elseif ($presensiAkhir === 'kosong') {
        $query .= " AND u.absen_akhir = 0";
    }
}

$stmt = $pdo->prepare($query);

foreach ($params as $key => &$val) {
    $stmt->bindParam(":$key", $val);
}

$stmt->execute();
$daftar_hadir = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menampilkan data
foreach ($daftar_hadir as $index => $user): ?>
    <tr class="<?= $index % 2 == 0 ? 'bg-blue-even' : 'bg-blue-odd' ?>">
        <td class="border px-4 py-2"><?= htmlspecialchars($user['kode_asisten']) ?></td>
        <td class="border px-4 py-2 <?= ($user['absen_awal'] && $user['keterangan_awal'] === "Tepat waktu") ? 'tepat-waktu' : (($user['absen_awal'] && $user['keterangan_awal'] === "Lambat") ? 'lambat' : 'kosong') ?>">
            <?= $user['absen_awal'] ? htmlspecialchars($user['keterangan_awal']) : 'Kosong' ?>
        </td>
        <td class="border px-4 py-2 <?= (isset($user['izin_awal']) && ($user['izin_awal'] == 1 || $user['izin_telat'] == 1 || $user['izin_tidak_menghadiri'] == 1)) ? 'valid' : 'invalid' ?>">
            <?= (isset($user['izin_awal']) && ($user['izin_awal'] == 1 || $user['izin_telat'] == 1 || $user['izin_tidak_menghadiri'] == 1)) ? 'Ada' : 'Tidak Ada' ?>
        </td>
        <td class="border px-4 py-2 <?= $user['absen_awal'] ? 'tepat-waktu' : 'kosong' ?>">
            <?= $user['jam_absen_awal'] ?: 'Kosong' ?>
        </td>
        <td class="border px-4 py-2 <?= $user['absen_akhir'] ? 'tepat-waktu' : 'kosong' ?>">
            <?= $user['absen_akhir'] ? 'Ada' : 'Kosong' ?>
        </td>
        <td class="border px-4 py-2 <?= (isset($user['izin_akhir']) && $user['izin_akhir'] == 1) ? 'valid' : 'invalid' ?>">
            <?= isset($user['izin_akhir']) ? ($user['izin_akhir'] ? 'Ada' : 'Tidak Ada') : 'Tidak Ada' ?>
        </td>
    </tr>
<?php endforeach; ?>
