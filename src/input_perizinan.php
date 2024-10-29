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

// Variabel pesan untuk memberikan umpan balik kepada pengguna
$message = '';

// Memproses form input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Konversi kode_asisten ke huruf besar
    $kode_asisten = strtoupper(trim($_POST['kode_asisten']));
    $izin_awal = isset($_POST['izin_awal']) ? 1 : 0;
    $izin_akhir = isset($_POST['izin_akhir']) ? 1 : 0;
    $izin_telat = isset($_POST['izin_telat']) ? 1 : 0;
    $izin_tidak_menghadiri = isset($_POST['izin_tidak_menghadiri']) ? 1 : 0;

    // Cek apakah kode_asisten sudah ada di tabel perizinan
    $stmt = $pdo->prepare("SELECT * FROM perizinan WHERE kode_asisten = :kode_asisten");
    $stmt->execute(['kode_asisten' => $kode_asisten]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Update data yang sudah ada
        $stmt = $pdo->prepare("UPDATE perizinan SET 
            izin_awal = :izin_awal, 
            izin_akhir = :izin_akhir, 
            izin_telat = :izin_telat, 
            izin_tidak_menghadiri = :izin_tidak_menghadiri 
            WHERE kode_asisten = :kode_asisten");
        $stmt->execute([
            'izin_awal' => max($izin_awal, $existing['izin_awal']), 
            'izin_akhir' => max($izin_akhir, $existing['izin_akhir']), 
            'izin_telat' => max($izin_telat, $existing['izin_telat']), 
            'izin_tidak_menghadiri' => max($izin_tidak_menghadiri, $existing['izin_tidak_menghadiri']),
            'kode_asisten' => $kode_asisten
        ]);
        $message = "Data perizinan diperbarui untuk kode asisten: $kode_asisten.";
    } else {
        // Tambahkan data baru
        $stmt = $pdo->prepare("INSERT INTO perizinan (kode_asisten, izin_awal, izin_akhir, izin_telat, izin_tidak_menghadiri) 
            VALUES (:kode_asisten, :izin_awal, :izin_akhir, :izin_telat, :izin_tidak_menghadiri)");
        $stmt->execute([
            'kode_asisten' => $kode_asisten, 
            'izin_awal' => $izin_awal, 
            'izin_akhir' => $izin_akhir, 
            'izin_telat' => $izin_telat, 
            'izin_tidak_menghadiri' => $izin_tidak_menghadiri
        ]);
        $message = "Data perizinan ditambahkan untuk kode asisten: $kode_asisten.";
    }
}

// Proses reset data
if (isset($_POST['reset_perizinan'])) {
    $stmt = $pdo->prepare("DELETE FROM perizinan WHERE kode_asisten = :kode_asisten");
    $stmt->execute(['kode_asisten' => strtoupper(trim($_POST['kode_asisten']))]);
    $message = "Data perizinan dihapus untuk kode asisten: " . strtoupper(trim($_POST['kode_asisten'])) . ".";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Perizinan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
        <h1 class="text-xl font-bold mb-4">Input Perizinan</h1>

        <?php if ($message): ?>
            <div class="bg-blue-100 text-blue-700 p-2 mb-4 rounded">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="kode_asisten" class="block text-sm font-medium">Kode Asisten</label>
                <input type="text" name="kode_asisten" id="kode_asisten" required class="border rounded w-full p-2" placeholder="Masukkan Kode Asisten">
            </div>
            <div>
                <label class="block text-sm font-medium">Izin:</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="izin_awal" class="mr-2">
                        Izin Awal
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="izin_akhir" class="mr-2">
                        Izin Akhir
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="izin_telat" class="mr-2">
                        Izin Telat
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="izin_tidak_menghadiri" class="mr-2">
                        Izin Tidak Menghadiri
                    </label>
                </div>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
                <button type="submit" name="reset_perizinan" class="bg-red-500 text-white py-2 px-4 rounded">Reset Perizinan</button>
            </div>
        </form>
        <br>
        <div class="mb-4 flex justify-between">
            <a href="daftar_hadir.php" class="bg-gray-500 text-white py-2 px-4 rounded">Kembali Ke Daftar Hadir</a></div>
        </div>
</body>
</html>
