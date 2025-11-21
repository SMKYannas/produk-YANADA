<?php
declare(strict_types=1);

session_start();
$currentUser = $_SESSION['user_name'] ?? null;

require_once __DIR__ . '/config/db.php';

try {
    $pdo = getDatabaseConnection();
} catch (Throwable $exception) {
    die("Database error.");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pelayanan Kesehatan | Produk Siswa SMKS Kesehatan Yannas Husada</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />

    <style>
        .service-card img {
            margin: 0 auto 12px auto;
            border-radius: 12px;
            max-width: 100%;
            height: auto;
        }

        .service-card {
            background: white;
            padding: 16px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .price-tag {
            font-size: 1rem;
            font-weight: 700;
            color: #00796b;
            margin: 6px 0 10px 0;
        }

        .btn-booking {
            display: inline-block;
            border: 2px solid #00796b;
            color: #00796b;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }

        .btn-booking:hover {
            background: #00796b;
            color: white;
        }

        .btn-login-warning {
            display: inline-block;
            border: 2px solid #ff5733;
            color: #ff5733;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }

        .btn-login-warning:hover {
            background: #ff5733;
            color: white;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <!-- SECTION: Pelayanan -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-primary">Daftar Pelayanan Kesehatan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Pelayanan Template -->
                <?php
                $pelayanan = [
                    ["Tekanan Darah", "image/pelayanan/tekanan_darah.jpg", "Pemeriksaan tekanan darah akurat oleh tenaga kesehatan siswa.", 25000],
                    ["Tes Darah Lengkap", "image/pelayanan/tes_darah.jpg", "Pengecekan darah lengkap untuk memantau kondisi tubuh.", 250000],
                    ["Bekam", "image/pelayanan/bekam.jpg", "Terapi bekam untuk melancarkan peredaran darah.", 75000],
                    ["Treatment Facial", "image/pelayanan/facial.jpg", "Perawatan wajah lengkap untuk kesehatan kulit.", 100000],
                    ["Totok Wajah", "image/pelayanan/totok.jpg", "Relaksasi wajah & melancarkan peredaran darah.", 50000],
                ];

                foreach ($pelayanan as $srv):
                ?>
                    <div class="service-card text-center" data-aos="fade-up">
                        <img src="<?= $srv[1] ?>" alt="<?= $srv[0] ?>">
                        <h3 class="service-title text-xl font-bold mt-2"><?= $srv[0] ?></h3>
                        <p class="service-desc"><?= $srv[2] ?></p>

                        <p class="price-tag">Rp <?= number_format($srv[3], 0, ',', '.') ?></p>

                        <?php if ($currentUser): ?>
                            <a href="?layanan=<?= urlencode($srv[0]) ?>#booking"
                               class="btn-booking">
                                <i class="fas fa-calendar-check mr-2"></i> Booking Layanan
                            </a>
                        <?php else: ?>
                            <button onclick="alert('Silakan login terlebih dahulu untuk melakukan booking!'); window.location='login.php';"
                                    class="btn-login-warning">
                                <i class="fas fa-lock mr-2"></i> Login untuk Booking
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- SECTION BOOKING -->
    <section id="booking" class="py-16 bg-white border-t mt-12">
        <div class="container mx-auto px-4 max-w-2xl">

            <h2 class="text-3xl font-bold text-center mb-6 text-primary">Form Booking Layanan</h2>

            <?php $layananDipilih = $_GET['layanan'] ?? ''; ?>

            <form action="booking_process.php" method="POST" class="space-y-4 bg-gray-50 p-6 rounded-xl shadow">

                <div>
                    <label class="block font-semibold mb-1">Layanan yang Dipilih</label>
                    <input type="text" name="layanan" readonly
                           value="<?= htmlspecialchars($layananDipilih) ?>"
                           class="w-full p-3 rounded-lg border bg-gray-100">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full p-3 rounded-lg border" placeholder="Nama Anda">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Nomor WhatsApp</label>
                    <input type="text" name="wa" required class="w-full p-3 rounded-lg border" placeholder="08xxxxxxxxx">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Tanggal Booking</label>
                    <input type="date" name="tanggal" required class="w-full p-3 rounded-lg border">
                </div>

        <button type="submit"
    class="w-full py-3 rounded-lg font-semibold text-black
           bg-[--primary-color]
           hover:bg-[--secondary-color]
           shadow-lg hover:shadow-xl
           ring-1 ring-green/40
           tracking-wide
           transition-all duration-200">
    Kirim Booking
</button>


            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-secondary text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-lg font-semibold mb-2">SMKS Kesehatan Yannas Husada Bangkalan</p>
            <p class="text-sm opacity-80">Inovasi Produk & Pelayanan Kesehatan — © 2025.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });
    </script>

</body>
</html>
