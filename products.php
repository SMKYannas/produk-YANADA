<?php
declare(strict_types=1);

require_once __DIR__ . '/config/db.php';

try {
    $pdo = getDatabaseConnection();
    $products = $pdo->query('SELECT * FROM products ORDER BY id ASC')->fetchAll();
} catch (Throwable $exception) {
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Produk Herbal | Produk Siswa SMKS Kesehatan Yannas Husada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <header class="bg-primary text-white py-16">
        <div class="container mx-auto px-4 text-center" data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-3">Katalog Produk Unggulan</h1>
            <p class="text-xl opacity-90">Jelajahi inovasi terbaik dari siswa-siswi kompeten kami.</p>
        </div>
    </header>

    <section id="products" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                    <i class="fas fa-hand-holding-heart text-primary mr-2"></i> Karya Siswa Jurusan Farmasi & Kesehatan
                </h2>
                <div class="section-divider"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($products as $index => $product): ?>
                    <?php $delay = 100 + ($index * 100); ?>
                    <div class="product-card" data-aos="zoom-in" data-aos-delay="<?= $delay ?>">
                        <div class="h-56 overflow-hidden">
                            <img src="<?= htmlspecialchars($product['image_url'] ?: 'image/produk1.jpg', ENT_QUOTES) ?>"
                                alt="<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110" />
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-primary mb-2"><?= htmlspecialchars($product['name'], ENT_QUOTES) ?></h3>
                            <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($product['description'], ENT_QUOTES) ?></p>
                            <p class="text-2xl font-extrabold text-secondary mb-4">
                                Rp <?= number_format((float) $product['price'], 0, ',', '.') ?>
                            </p>
                            <button class="mt-3 w-full border border-primary text-primary rounded-lg py-2 font-semibold add-cart-btn"
                                data-product-id="<?= $product['id'] ?>"
                                data-product-name="<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>"
                                data-price="<?= (int) $product['price'] ?>">
                                <i class="fas fa-shopping-cart mr-2"></i> Tambahkan ke Keranjang
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($products)): ?>
                <p class="text-center text-gray-500 mt-8">Belum ada produk yang terdaftar.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-secondary text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-lg font-semibold mb-2">SMKS Kesehatan Yannas Husada Bangkalan</p>
            <p class="text-sm opacity-80">
                Inovasi Produk Kesehatan Siswa - &copy; 2025. All Rights Reserved.
            </p>
        </div>
    </footer>

    <div id="cart-panel" class="fixed bottom-4 right-4 w-80 max-w-sm bg-white border border-gray-200 shadow-2xl rounded-2xl p-4 hidden">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">Keranjang Saya</h3>
            <button id="cart-panel-close" type="button" aria-label="Tutup keranjang" class="text-gray-500 hover:text-gray-800">&times;</button>
        </div>
        <div id="cart-items" class="mt-3 space-y-3 max-h-56 overflow-y-auto text-sm text-gray-700">
            <p class="text-center text-gray-500">Keranjang kosong.</p>
        </div>
        <div class="mt-4 border-t pt-3">
            <div class="flex justify-between font-semibold text-gray-800">
                <span>Total</span>
                <span id="cart-total">Rp 0</span>
            </div>
            <button type="button" id="cart-checkout"
                class="mt-3 w-full bg-primary hover:bg-primary-dark text-white font-semibold rounded-full py-2">Lanjut ke Checkout</button>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="scripts.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
        });
    </script>
</body>

</html>
