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

$pdo = getDatabaseConnection();
$orders = [];
$flashMessage = null;
$flashType = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $orderId = (int) $_POST['cancel_order_id'];
    if ($orderId <= 0) {
        $flashMessage = 'Pesanan tidak valid.';
        $flashType = 'error';
    } else {
        $validationStmt = $pdo->prepare('SELECT status FROM orders WHERE id = ? AND user_id = ? LIMIT 1');
        $validationStmt->execute([$orderId, $_SESSION['user_id']]);
        $orderToCancel = $validationStmt->fetch();

        if (!$orderToCancel) {
            $flashMessage = 'Pesanan tidak ditemukan.';
            $flashType = 'error';
        } elseif ($orderToCancel['status'] !== 'pending') {
            $flashMessage = 'Hanya pesanan dengan status pending yang dapat dihapus.';
            $flashType = 'error';
        } else {
            try {
                $pdo->beginTransaction();
                $deleteItemsStmt = $pdo->prepare('DELETE FROM order_items WHERE order_id = ?');
                $deleteItemsStmt->execute([$orderId]);
                $deleteOrderStmt = $pdo->prepare('DELETE FROM orders WHERE id = ?');
                $deleteOrderStmt->execute([$orderId]);
                $pdo->commit();

                $flashMessage = 'Pesanan pending berhasil dihapus.';
                $flashType = 'success';
            } catch (Throwable $exception) {
                $pdo->rollBack();
                $flashMessage = 'Terjadi kesalahan saat menghapus pesanan.';
                $flashType = 'error';
            }
        }
    }
}

$flashClasses = '';
if ($flashMessage !== null) {
    $flashClasses = $flashType === 'success'
        ? 'bg-green-50 border border-green-200 text-green-800'
        : 'bg-red-50 border border-red-200 text-red-800';
}

$orderStmt = $pdo->prepare('SELECT id, order_date, status, total, shipping_address FROM orders WHERE user_id = ? ORDER BY order_date DESC');
$orderStmt->execute([$_SESSION['user_id']]);

$itemStmt = $pdo->prepare(
    'SELECT oi.order_id, oi.product_id, oi.quantity, oi.unit_price, oi.subtotal, p.name, p.image_url
     FROM order_items oi
     JOIN products p ON p.id = oi.product_id
     WHERE oi.order_id = ?'
);

while ($row = $orderStmt->fetch()) {
    $itemStmt->execute([$row['id']]);
    $items = $itemStmt->fetchAll();
    $orders[] = [
        'id' => (int) $row['id'],
        'order_date' => $row['order_date'],
        'status' => $row['status'],
        'total' => (float) $row['total'],
        'shipping_address' => $row['shipping_address'],
        'items' => $items,
    ];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya | Produk Siswa SMKS Kesehatan Yannas Husada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-800">Pesanan Saya</h1>
                <p class="text-gray-600 mt-2">Lihat riwayat pembelian serta status pengiriman pesanan Anda.</p>
            </div>

            <?php if ($flashMessage !== null): ?>
                <div class="mx-auto mb-8 max-w-3xl rounded-2xl px-6 py-4 text-sm font-semibold <?= $flashClasses ?>">
                    <?= htmlspecialchars($flashMessage, ENT_QUOTES) ?>
                </div>
            <?php endif; ?>

            <?php if (empty($orders)): ?>
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center text-gray-500">
                    Belum ada pesanan. Mulai dengan menambahkan produk ke keranjang lalu checkout.
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($orders as $order): ?>
                        <div class="bg-white rounded-2xl shadow-xl p-6" data-aos="fade-up">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-wide text-gray-500">Order #<?= str_pad((string) $order['id'], 6, '0', STR_PAD_LEFT) ?></p>
                                    <h2 class="text-2xl font-bold text-gray-800">
                                        <?= htmlspecialchars((new DateTime($order['order_date']))->format('d M Y H:i'), ENT_QUOTES) ?>
                                    </h2>
                                </div>
                                <div>
                                    <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-sm font-semibold <?= $order['status'] === 'completed' ? 'border-green-300 text-green-700' : 'border-yellow-300 text-yellow-700' ?>">
                                        <i class="fas fa-truck"></i>
                                        <?= htmlspecialchars(ucfirst($order['status']), ENT_QUOTES) ?>
                                    </span>
                                </div>
                            </div>

                            <?php if ($order['status'] === 'pending'): ?>
                                <div class="mt-4 text-right">
                                    <form method="post" class="inline-flex" onsubmit="return confirm('Yakin ingin menghapus pesanan pending ini?');">
                                        <input type="hidden" name="cancel_order_id" value="<?= (int) $order['id'] ?>">
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-full border border-red-300 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100 focus:outline-none focus:ring focus:ring-red-200">
                                            <i class="fas fa-trash-alt"></i>
                                            Hapus Pesanan Pending
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>

                            <div class="mt-6 grid gap-6 md:grid-cols-[2fr,1fr]">
                                <div>
                                    <div class="space-y-3">
                                        <?php foreach ($order['items'] as $item): ?>
                                            <div class="flex items-center gap-4 border-b border-gray-100 py-3">
                                                <div class="w-20 h-20 overflow-hidden rounded-xl border">
                                                    <img src="<?= htmlspecialchars($item['image_url'] ?: 'image/produk1.jpg', ENT_QUOTES) ?>" alt="<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>" class="w-full h-full object-cover">
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-800"><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></p>
                                                    <p class="text-sm text-gray-500">Qty <?= (int) $item['quantity'] ?></p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-semibold text-gray-800">Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?></p>
                                                    <p class="text-xs text-gray-500">Rp <?= number_format((float) $item['unit_price'], 0, ',', '.') ?>/pcs</p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="space-y-3 rounded-2xl border border-gray-100 p-4 bg-gray-50">
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Total Item</span>
                                        <span><?= array_sum(array_column($order['items'], 'quantity')) ?></span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>Biaya Pengiriman</span>
                                        <span>Rp 0 (menunggu konfirmasi)</span>
                                    </div>
                                    <div class="flex justify-between text-base font-semibold text-gray-900">
                                        <span>Total Bayar</span>
                                        <span>Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
                                    </div>
                                    <p class="text-xs text-gray-500">Alamat: <?= htmlspecialchars($order['shipping_address'], ENT_QUOTES) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
