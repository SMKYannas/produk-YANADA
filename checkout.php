<?php

declare(strict_types=1);

require_once __DIR__ . '/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart = &$_SESSION['cart'];
if (!is_array($cart)) {
    $cart = [];
}

$pdo = getDatabaseConnection();
$errors = [];
$success = null;
$orderSummary = [];
$totalAmount = 0.0;
$totalQty = 0;

foreach ($cart as $productId => $entry) {
    $stmt = $pdo->prepare('SELECT id, name, price FROM products WHERE id = ? LIMIT 1');
    $stmt->execute([(int) $productId]);
    $product = $stmt->fetch();

    if (!$product) {
        unset($cart[$productId]);
        continue;
    }

    $quantity = max(1, (int) ($entry['quantity'] ?? 0));
    $subtotal = $product['price'] * $quantity;
    $orderSummary[] = [
        'id' => (int) $product['id'],
        'name' => $product['name'],
        'price' => (float) $product['price'],
        'quantity' => $quantity,
        'subtotal' => $subtotal,
    ];

    $totalQty += $quantity;
    $totalAmount += $subtotal;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shippingAddress = trim($_POST['shipping_address'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($orderSummary)) {
        $errors[] = 'Keranjang kosong.';
    }
    if ($shippingAddress === '') {
        $errors[] = 'Alamat pengiriman wajib diisi.';
    }

    if (empty($errors)) {
        $customerName = $_SESSION['user_name'] ?? 'Pelanggan';
        $customerEmail = $_SESSION['user_email'] ?? '';

        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('INSERT INTO orders (user_id, status, total, shipping_address) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $_SESSION['user_id'],
                'pending',
                $totalAmount,
                $shippingAddress,
            ]);
            $orderId = (int) $pdo->lastInsertId();

            $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)');

            foreach ($orderSummary as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['id'],
                    $item['quantity'],
                    $item['price'],
                    $item['subtotal'],
                ]);
            }

            $pdo->commit();
            $lineHeader = "Hai Admin Yannas Husada, saya {$customerName} (Order #" . str_pad((string) $orderId, 6, '0', STR_PAD_LEFT) . ").";
            $lines = [$lineHeader, "Detail pesanan:"];
            foreach ($orderSummary as $item) {
                $lines[] = "- {$item['name']} x{$item['quantity']} = Rp " . number_format($item['subtotal'], 0, ',', '.');
            }
            $lines[] = '';
            $lines[] = "Total: Rp " . number_format($totalAmount, 0, ',', '.');
            $lines[] = "Alamat: {$shippingAddress}";
            $lines[] = "Catatan: " . ($notes ?: '-');
            if ($customerEmail !== '') {
                $lines[] = "Email: {$customerEmail}";
            }

            $waText = rawurlencode(implode("\n", $lines));
            $waTarget = '6281808001437';
            $cart = [];
            header('Location: https://wa.me/' . $waTarget . '?text=' . $waText);
            exit;
        } catch (Throwable $exception) {
            $pdo->rollBack();
            $errors[] = 'Terjadi kesalahan saat menyimpan pesanan.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Produk Siswa SMKS Kesehatan Yannas Husada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <section class="lg:col-span-2 bg-white rounded-2xl shadow-xl p-8 space-y-6" data-aos="fade-up">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">Ringkasan Keranjang</h2>
                        <p class="text-gray-600 mt-2">Periksa kembali item sebelum menyelesaikan pesanan.</p>
                    </div>
                    <?php if ($errors): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">
                            <?php foreach ($errors as $error): ?>
                                <p><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($success): ?>
                        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4">
                            <?= htmlspecialchars($success, ENT_QUOTES) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($orderSummary)): ?>
                        <p class="text-center text-gray-500">Keranjangmu kosong. Tambahkan produk terlebih dahulu.</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($orderSummary as $item): ?>
                                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                    <div>
                                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></p>
                                        <p class="text-sm text-gray-500">Qty <?= $item['quantity'] ?></p>
                                    </div>
                                    <div class="text-right text-gray-700">
                                        <p><?= number_format($item['subtotal'], 0, ',', '.') ?> </p>
                                        <p class="text-xs text-gray-500"><?= number_format($item['price'], 0, ',', '.') ?> / pcs</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-6 flex justify-between text-gray-800 font-semibold">
                            <span>Total</span>
                            <span>Rp <?= number_format($totalAmount, 0, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="bg-white rounded-2xl shadow-xl p-8 space-y-6" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="text-2xl font-bold text-gray-800">Lengkapi Data Pengiriman</h2>
                    <form method="post" class="space-y-5">
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="shipping_address">Alamat Pengiriman</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3"
                                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary focus:ring focus:ring-primary/30"
                                required><?= htmlspecialchars($_POST['shipping_address'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700" for="notes">Catatan Tambahan (opsional)</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-primary focus:ring focus:ring-primary/30"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-semibold rounded-full py-3 transition focus:outline-none focus:ring focus:ring-primary/40">
                            Simpan Pesanan
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </main>

    <footer class="bg-secondary text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <p class="text-lg font-semibold mb-2">SMKS Kesehatan Yannas Husada Bangkalan</p>
            <p class="text-sm opacity-80">
                Inovasi Produk Kesehatan Siswa - &copy; 2025. All Rights Reserved.
            </p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
        });
    </script>
</body>

</html>
