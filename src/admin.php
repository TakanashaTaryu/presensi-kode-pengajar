<?php
session_start(); // Memulai session

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

// Fungsi untuk memuat pengaturan dari cutoff.json
function loadCutoffSettings() {
    if (file_exists('cutoff.json')) {
        return json_decode(file_get_contents('cutoff.json'), true);
    } else {
        // Set nilai default jika file tidak ditemukan
        return [
            'absen_awal_enabled' => true,
            'absen_awal_start' => '14:30:00',
            'absen_awal_end' => '15:00:00',
            'absen_akhir_enabled' => true,
            'absen_akhir' => '17:00:00'
        ];
    }
}

// Fungsi untuk menyimpan pengaturan ke cutoff.json
function saveCutoffSettings($data) {
    file_put_contents('cutoff.json', json_encode($data, JSON_PRETTY_PRINT));
}

// Fungsi untuk reset data asisten
function resetAssistantData($pdo) {
    // Hapus semua data asisten
    $pdo->exec("DELETE FROM user");

    // Daftar kode asisten yang akan dimasukkan ulang
    $kode_asisten = [
        "TGH", "SOH", "NFB", "DEY", "VIS", "TAN", "TNT", "ALL", "AKA", "RIZ",
        "IAN", "DAR", "CYN", "JIN", "FYN", "DHY", "FAZ", "JFT", "PER", "BRI",
        "EZL", "AAA", "MAS", "AMG", "RAP", "WGG", "NTR", "DPR", "GUS", "TIP",
        "CLA", "ARC", "LLY", "LEX", "THI", "ONE", "RAD", "ALY", "BIL", "OIL",
        "NST", "DNR", "NUE", "RAF", "SSS", "SNI", "MHZ", "DAZ", "UZY", "ZEN",
        "ACC", "FLO", "VAL", "TRA", "RAR", "MIT", "DAN", "UKI", "AKI", "NAI",
        "ARZ", "RZE", "RDJ", "ALD", "ION", "FAV", "EKA", "ZIN", "SYW", "DUN",
        "AUL", "WLN", "REL", "TIN", "SAM", "RYU", "ZAI", "BAY", "DYS", "RYN",
        "BUS", "AKK", "GND", "NOE", "CHZ", "GAN", "SHA", "ARG"
    ];

    // Memasukkan ulang data asisten
    foreach ($kode_asisten as $kode) {
        $stmt = $pdo->prepare("INSERT INTO user (kode_asisten) VALUES (:kode)");
        $stmt->execute(['kode' => $kode]);
    }
}

// Menangani request
$cutoff = loadCutoffSettings();
$message = ''; // Menyimpan pesan untuk alert

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Jika tombol "Simpan Pengaturan" yang ditekan
    if (isset($_POST['save_settings'])) {
        $cutoff['absen_awal_enabled'] = isset($_POST['absen_awal_enabled']);
        $cutoff['absen_awal_start'] = $_POST['absen_awal_start'];
        $cutoff['absen_awal_end'] = $_POST['absen_awal_end'];
        $cutoff['absen_akhir_enabled'] = isset($_POST['absen_akhir_enabled']);
        $cutoff['absen_akhir'] = $_POST['absen_akhir'];

        // Simpan pengaturan presensi
        saveCutoffSettings($cutoff);
        $_SESSION['message'] = "Pengaturan berhasil disimpan!"; // Simpan pesan untuk alert
    }
}

// Jika ada permintaan untuk mereset data
if (isset($_GET['reset']) && $_GET['reset'] == 'true') {
    resetAssistantData($pdo);
    $_SESSION['message'] = "Data asisten berhasil direset!"; // Simpan pesan untuk alert
}

// Cek apakah ada pesan untuk ditampilkan
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Hapus pesan dari session setelah ditampilkan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pengaturan Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tambahkan SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-8 shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Pengaturan Presensi</h1>
        
        <!-- Form untuk menyimpan pengaturan presensi -->
        <form method="POST" class="space-y-4">
            <input type="hidden" name="save_settings" value="1">
            <div>
                <label class="block font-semibold">Presensi Awal Diizinkan</label>
                <input type="checkbox" name="absen_awal_enabled" class="mr-2" <?= $cutoff['absen_awal_enabled'] ? 'checked' : '' ?>>
            </div>
            <div>
                <label class="block font-semibold">Waktu Mulai Presensi Awal</label>
                <input type="time" name="absen_awal_start" class="border rounded p-2 w-full" value="<?= $cutoff['absen_awal_start'] ?>">
            </div>
            <div>
                <label class="block font-semibold">Waktu Akhir Presensi Awal</label>
                <input type="time" name="absen_awal_end" class="border rounded p-2 w-full" value="<?= $cutoff['absen_awal_end'] ?>">
            </div>
            <div>
                <label class="block font-semibold">Presensi Akhir Diizinkan</label>
                <input type="checkbox" name="absen_akhir_enabled" class="mr-2" <?= $cutoff['absen_akhir_enabled'] ? 'checked' : '' ?>>
            </div>
            <div>
                <label class="block font-semibold">Waktu Batas Presensi Akhir</label>
                <input type="time" name="absen_akhir" class="border rounded p-2 w-full" value="<?= $cutoff['absen_akhir'] ?>">
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan Pengaturan</button>
        </form>

        <!-- Tombol untuk mereset data asisten -->
        <div class="mb-4">
            <button type="button" class="bg-red-500 text-white py-2 px-4 rounded mt-6" onclick="confirmReset()">Reset Data</button>
        </div>
        <br>
        <br>
        <div class="mb-4 flex justify-between">
            <a href="index.php" class="bg-gray-500 text-white py-2 px-4 rounded">Input Presensi</a>
            <a href="daftar_hadir.php" class="bg-gray-500 text-white py-2 px-4 rounded">Lihat Daftar Presensi</a>
        </div>

    </div>

    <script>
        function confirmReset() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Jangan lupa di eksport sebelum mereset data.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?reset=true'; // Redirect ke URL reset
                }
            });
        }

        // Menampilkan alert SweetAlert jika ada pesan
        <?php if ($message): ?>
            Swal.fire({
                title: 'Berhasil!',
                text: '<?= $message ?>',
                icon: 'success'
            }).then(() => {
                // Reset URL setelah alert ditutup
                history.replaceState(null, '', 'admin.php');
            });
        <?php endif; ?>
    </script>
</body>
</html>
