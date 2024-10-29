<?php
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

// Daftar kode asisten
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

// Memasukkan data ke dalam tabel user
foreach ($kode_asisten as $kode) {
    $stmt = $pdo->prepare("INSERT INTO user (kode_asisten) VALUES (:kode)");
    $stmt->execute(['kode' => $kode]);
}

echo "Data kode asisten berhasil dimasukkan!";
?>
