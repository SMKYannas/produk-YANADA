<?php
declare(strict_types=1);
session_start();

// Blok akses jika belum login
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Anda harus login terlebih dahulu untuk melakukan booking!'); 
    window.location.href='login.php';</script>";
    exit;
}

require_once __DIR__ . "/config/db.php"; 
// Pastikan db.php sudah memakai database: yanada

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Ambil data form
    $layanan = trim($_POST["layanan"] ?? "");
    $nama    = trim($_POST["nama"] ?? "");
    $wa      = trim($_POST["wa"] ?? "");
    $tanggal = trim($_POST["tanggal"] ?? "");

    // Validasi wajib isi
    if ($layanan === "" || $nama === "" || $wa === "" || $tanggal === "") {
        echo "<script>alert('Semua kolom wajib diisi!'); history.back();</script>";
        exit;
    }

    // Simpan ke database yanada → tabel bookings
    try {
        $pdo = getDatabaseConnection();   // Pastikan ini mengarah ke database yanada

        $stmt = $pdo->prepare("
            INSERT INTO bookings (layanan, nama, wa, tanggal, user_id)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $layanan,
            $nama,
            $wa,
            $tanggal,
            $_SESSION['user_id']
        ]);

    } catch (Throwable $e) {
        die("Database error: " . $e->getMessage());
    }

    // Nomor WhatsApp admin
    $adminWa = "6281808001437";

    // Format pesan
    $pesan = urlencode(
        "📌 *Booking Layanan Kesehatan Yannas*\n\n".
        "Layanan : $layanan\n".
        "Nama : $nama\n".
        "WA : $wa\n".
        "Tanggal : $tanggal\n\n".
        "Harap segera diproses."
    );

    // Redirect otomatis ke WhatsApp admin
    $waUrl = "https://wa.me/$adminWa?text=$pesan";

    header("Location: $waUrl");
    exit;
}
?>
