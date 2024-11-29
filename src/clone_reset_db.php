<?php
$message = '';  // Pesan notifikasi
$alertType = '';  // Jenis alert (success/error)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $username = 'admin';  // Sesuaikan dengan konfigurasi Anda
    $password = 'admin';
    $main_db = 'absentot';

    try {
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Buat nama database baru dengan format 'absentot-tanggal'
        $date = date("d-m-Y");
        $base_db_name = $main_db . '-' . $date;
        $new_db = $base_db_name;

        // Cek jika database sudah ada, tambahkan angka incrementing
        $counter = 1;
        while (databaseExists($pdo, $new_db)) {
            $new_db = $base_db_name . '-' . $counter;
            $counter++;
        }

        // Kloning database utama ke database baru
        $pdo->exec("CREATE DATABASE `$new_db`");
        
        $tables = $pdo->query("SHOW TABLES FROM `$main_db`")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $pdo->exec("CREATE TABLE `$new_db`.`$table` LIKE `$main_db`.`$table`");
            $pdo->exec("INSERT INTO `$new_db`.`$table` SELECT * FROM `$main_db`.`$table`");
        }

        // Reset database utama
        $pdo->exec("USE `$main_db`");
        $pdo->exec("DELETE FROM `user`");
        $pdo->exec("DELETE FROM `perizinan`");

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

        $message = "Database berhasil di reset dan dikloning menjadi $new_db";
        $alertType = 'success';
    } catch (PDOException $e) {
        $message = 'Error: ' . $e->getMessage();
        $alertType = 'error';
    }
}

function databaseExists($pdo, $dbName) {
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
    return $stmt->rowCount() > 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kloning dan Reset Database</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Kloning dan Reset Database</h2>
        <form method="post" class="text-center">
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Kloning dan Reset Database
            </button>
        </form>
    </div>

    <?php if ($message): ?>
        <script>
            Swal.fire({
                title: "<?php echo ($alertType == 'success') ? 'Berhasil!' : 'Gagal!'; ?>",
                html: "<?php echo $message; ?>".replace("absentot", "<br><span class='text-blue-500 font-bold'>absentot"),
                icon: "<?php echo $alertType; ?>",
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
</body>
</html>
