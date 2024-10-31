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

// Memuat pengaturan cutoff
$cutoff = json_decode(file_get_contents('cutoff.json'), true);
$presensi_dinaktifkan = !$cutoff['absen_awal_enabled'] && !$cutoff['absen_akhir_enabled'];

$alert_message = ''; // Variabel untuk menyimpan pesan alert
$error_message = ''; // Variabel untuk menyimpan pesan kesalahan

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($presensi_dinaktifkan) {
        $error_message = 'Presensi belum dinyalakan, silahkan hubungi penjaga presensi';
    } else {
        $kode_asisten = $_POST['kode_asisten'];
        $jam_absen = date('H:i'); // Mengambil jam saat ini

        // Validasi kode asisten
        $stmt = $pdo->prepare("SELECT * FROM user WHERE kode_asisten = :kode");
        $stmt->execute(['kode' => $kode_asisten]);
        $user = $stmt->fetch();

        if (!$user) {
            $error_message = 'Kode asisten tidak ada! Silahkan hubungi penjaga presensi';
        } else {
            // Cek status presensi
            $absen_awal_enabled = $cutoff['absen_awal_enabled'];
            $absen_akhir_enabled = $cutoff['absen_akhir_enabled'];

            // Logika untuk absen awal
            if ($absen_awal_enabled && $user['absen_awal'] == 0) {
                $keterangan_awal = null;
                $jam_mulai = new DateTime($cutoff['absen_awal_start']);
                $jam_akhir = new DateTime($cutoff['absen_awal_end']);
                $jam_absen_dt = new DateTime($jam_absen);

                if ($jam_absen_dt <= $jam_akhir) {
                    $keterangan_awal = "Tepat waktu";
                } else {
                    $keterangan_awal = "Lambat"; // Jika di luar waktu
                }

                // Menyimpan absen awal
                $stmt = $pdo->prepare("UPDATE user SET absen_awal = 1, jam_absen_awal = :jam, keterangan_awal = :keterangan WHERE kode_asisten = :kode");
                $stmt->execute(['jam' => $jam_absen, 'keterangan' => $keterangan_awal, 'kode' => $kode_asisten]);

                $alert_message = 'Anda telah berhasil melakukan presensi awal!';
                // Hapus cache
                header("Location: " . $_SERVER['PHP_SELF'] . "?alert=" . urlencode($alert_message));
                exit();
            } 
            // Logika untuk absen akhir
            elseif ($absen_akhir_enabled && $user['absen_akhir'] == 0) {
                // Menyimpan absen akhir (tidak ada batas waktu)
                $stmt = $pdo->prepare("UPDATE user SET absen_akhir = 1, jam_absen_akhir = :jam WHERE kode_asisten = :kode");
                $stmt->execute(['jam' => $jam_absen, 'kode' => $kode_asisten]);

                $alert_message = 'Anda telah berhasil melakukan presensi akhir!';
                // Hapus cache
                header("Location: " . $_SERVER['PHP_SELF'] . "?alert=" . urlencode($alert_message));
                exit();
            } else {
                // Menentukan jenis presensi yang sudah dilakukan
                $pesan = $user['absen_awal'] ? 'Anda sudah melakukan presensi awal!' : 'Anda sudah melakukan presensi akhir!';
                $alert_message = $pesan;
            }
        }
    }
}

// Mengambil jam saat ini
$jam_sekarang = date('H:i');
$warna = '';

// Menentukan warna berdasarkan jenis presensi yang aktif
if ($cutoff['absen_awal_enabled']) {
    $jam_mulai = new DateTime($cutoff['absen_awal_start']);
    $jam_akhir = new DateTime($cutoff['absen_awal_end']);
    $jam_absen_dt = new DateTime($jam_sekarang);

    if ($jam_absen_dt <= $jam_akhir) {
        $warna = 'text-green-500'; // Tepat waktu
    } else {
        $warna = 'text-red-500'; // Lambat
    }
} elseif ($cutoff['absen_akhir_enabled']) {
    $warna = 'text-green-500'; // Anggap selalu tepat waktu untuk absen akhir
}

// Mengambil pesan alert jika ada
if (isset($_GET['alert'])) {
    $alert_message = htmlspecialchars($_GET['alert']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/heroicons@1.0.6/outline/svg/gear.svg"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-8 shadow-lg relative">
        <h1 class="text-2xl font-bold mb-4">Presensi <?php echo $cutoff['absen_awal_enabled'] ? "Awal" : "Akhir"; ?></h1>
        <div class="mb-4">
            <p class="font-semibold">Jam Sekarang: <span class="<?php echo $warna; ?>"><?php echo $jam_sekarang; ?></span></p>
        </div>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block font-semibold">Kode Asisten</label>
                <input type="text" name="kode_asisten" class="border rounded p-2 w-full <?php echo $presensi_dinaktifkan ? 'cursor-not-allowed' : ''; ?>" <?php echo $presensi_dinaktifkan ? 'disabled' : ''; ?> required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Kirim presensi</button>
        </form>
        <a href="admin.php" class="absolute top-0 right-0 mr-4 mt-4 text-gray-500 hover:text-gray-700">
        <!-- Icon Gear Font Awesome -->
        <i class="fas fa-cog text-xl"></i>
    </a>
    </div>


    <script>
        <?php if (!empty($error_message)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?php echo $error_message; ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        <?php if (!empty($alert_message)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '<?php echo $alert_message; ?>',
                confirmButtonText: 'OK'
            }).then(() => {
                history.replaceState(null, '', 'index.php');
            });
        <?php endif; ?>
    </script>
</body>
</html>
