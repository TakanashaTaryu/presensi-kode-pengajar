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

// Mengambil semua data awal
$stmt = $pdo->query("SELECT * FROM user");
$daftar_hadir = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background-color: #f7fafc; }
        .tepat-waktu { background-color: #c6f6d5; } /* Light green */
        .lambat { background-color: #fbd38d; } /* Light yellow */
        .valid { background-color: #c6f6d5; } /* Light green for "Ada" */
        .invalid { background-color: #fed7e2; } /* Light red for "Tidak Ada" */
        .kosong { background-color: #fed7e2; } /* Light red for "Kosong" */
        .bg-blue-odd { background-color: rgba(37, 99, 235, 0.1); }
        .bg-blue-even { background-color: rgba(37, 99, 235, 0.2); }
    </style>
</head>
<body class="p-4 md:p-6">
    <div class="max-w-full md:max-w-3xl mx-auto bg-white p-4 md:p-8 shadow-lg">
        <h1 class="text-xl md:text-2xl font-bold mb-4">Daftar Hadir</h1>

        <form id="searchForm" class="mb-4 flex flex-col md:flex-row gap-2">
            <input type="text" name="search" id="searchInput" placeholder="Cari Kode Asisten..." class="border rounded p-2 w-full md:w-auto flex-1 bg-gray-300">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded mt-2 md:mt-0">Cari</button>
        </form>

        <div class="mb-4 flex flex-col md:flex-row gap-2">
            <span class="mr-2">Filter Status Awal:</span>
            <button class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600" onclick="filterStatus('tepat_waktu')">Tepat Waktu</button>
            <button class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600" onclick="filterStatus('lambat')">Lambat</button>
            <button class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600" onclick="filterStatus('kosong')">Kosong</button>
        </div>
        
        <div class="mb-4 flex flex-col md:flex-row gap-2">
            <span class="mr-2">Filter Status Akhir:</span>
            <button class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600" onclick="filterAkhir('ada')">Ada</button>
            <button class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600" onclick="filterAkhir('kosong')">Kosong</button>
        </div>

        <div class="mb-4 flex flex-col md:flex-row gap-2">
            <button class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600" onclick="resetFilter()">Reset Filter</button>
        </div>
        
        <div class="mb-4">
            <a href="export.php" class="bg-green-500 text-white py-2 px-4 rounded">Ekspor ke CSV</a>
        </div>

        <div class="overflow-x-auto">
            <table id="resultTable" class="min-w-full border-collapse table-auto">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Kode Asisten</th>
                        <th class="border px-4 py-2">Presensi Awal</th>
                        <th class="border px-4 py-2">Izin Awal</th>
                        <th class="border px-4 py-2">Waktu Awal</th>
                        <th class="border px-4 py-2">Presensi Akhir</th>
                        <th class="border px-4 py-2">Izin Akhir</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($daftar_hadir as $index => $user): ?>
                    <tr class="<?= $index % 2 == 0 ? 'bg-blue-even' : 'bg-blue-odd' ?>">
                    <td class="border px-4 py-2"><?= htmlspecialchars($user['kode_asisten']) ?></td>
                    <td class="border px-4 py-2 <?= ($user['absen_awal'] && $user['keterangan_awal'] === "Tepat waktu") ? 'tepat-waktu' : (($user['absen_awal'] && $user['keterangan_awal'] === "Lambat") ? 'lambat' : 'kosong') ?>">
                        <?= $user['absen_awal'] ? htmlspecialchars($user['keterangan_awal']) : 'Kosong' ?>
                    </td>
                    <td class="border px-4 py-2 <?= (isset($user['izin_awal']) && ($user['izin_awal'] == 1 || $user['izin_telat'] == 1)) ? 'valid' : 'invalid' ?>">
                        <?= (isset($user['izin_awal']) ? ($user['izin_awal'] ? 'Ada' : 'Tidak Ada') : 'Tidak Ada') ?>
                    </td>
                    <td class="border px-4 py-2 <?= $user['absen_awal'] ? 'tepat-waktu' : 'kosong' ?>">
                        <?= $user['jam_absen_awal'] ?: 'Kosong' ?>
                    </td>
                    <td class="border px-4 py-2 <?= $user['absen_akhir'] ? 'tepat-waktu' : 'kosong' ?>">
                        <?= $user['absen_akhir'] ? 'Ada' : 'Kosong' ?>
                    </td>
                    <td class="border px-4 py-2 <?= (isset($user['izin_akhir']) && $user['izin_akhir'] == 1) ? 'valid' : 'invalid' ?>">
                        <?= (isset($user['izin_akhir']) ? ($user['izin_akhir'] ? 'Ada' : 'Tidak Ada') : 'Tidak Ada') ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

        </div>
        <br>
        <div class="mb-4 flex justify-between">
            <a href="admin.php" class="bg-gray-500 text-white py-2 px-4 rounded">Kembali Ke Admin</a>
            <a href="index.php" class="bg-gray-500 text-white py-2 px-4 rounded">Pergi Ke Input</a>
        </div>
    </div>

    <script>
        // Fungsi untuk mencari
        document.getElementById('searchForm').onsubmit = function(event) {
            event.preventDefault();
            let searchValue = document.getElementById('searchInput').value;
            fetchResults(searchValue);
        };

        // Fungsi untuk filter berdasarkan status awal
        function filterStatus(status) {
            let searchValue = document.getElementById('searchInput').value;
            fetchResults(searchValue, status);
        }

        // Fungsi untuk filter presensi akhir
        function filterAkhir(presensiAkhir) {
            let searchValue = document.getElementById('searchInput').value;
            fetchResults(searchValue, '', presensiAkhir);
        }

        // Fungsi untuk mereset filter
        function resetFilter() {
            document.getElementById('searchInput').value = '';
            fetchResults();
        }

        // Fungsi untuk mengambil data dengan filter tambahan
        function fetchResults(search = '', status = '', presensiAkhir = '') {
            const url = 'fetch_data.php'; // File PHP untuk mengambil data

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (status) params.append('status', status);
            if (presensiAkhir) params.append('presensiAkhir', presensiAkhir);

            fetch(url + '?' + params.toString())
                .then(response => response.text())
                .then(data => {
                    document.getElementById('resultTable').getElementsByTagName('tbody')[0].innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
