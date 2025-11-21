<?php
declare(strict_types=1);

require_once __DIR__ . '/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$pdo = getDatabaseConnection();
$cart = &$_SESSION['cart'];

if (!is_array($cart)) {
    $cart = [];
}

/**
 * @param int $productId
 * @return array|null
 */
function loadProduct(PDO $pdo, int $productId): ?array
{
    $stmt = $pdo->prepare('SELECT id, name, price, image_url FROM products WHERE id = ? LIMIT 1');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    return $product === false ? null : $product;
}

/**
 * @return array
 */
function buildCartData(): array
{
    global $cart, $pdo;

    $items = [];
    $totalQty = 0;
    $totalAmount = 0.0;

    foreach ($cart as $productId => $entry) {
        $product = loadProduct($pdo, (int) $productId);
        if ($product === null) {
            unset($cart[$productId]);
            continue;
        }

        $quantity = max(1, (int) ($entry['quantity'] ?? 0));
        $subtotal = $product['price'] * $quantity;
        $items[] = [
            'product_id' => (int) $product['id'],
            'name' => $product['name'],
            'price' => (float) $product['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'image_url' => $product['image_url'],
        ];
        $totalQty += $quantity;
        $totalAmount += $subtotal;
    }

    return [
        'items' => $items,
        'totalQty' => $totalQty,
        'totalAmount' => $totalAmount,
    ];
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $payload = json_decode((string) file_get_contents('php://input'), true);
    $action = $payload['action'] ?? 'add';
    $productId = (int) ($payload['product_id'] ?? 0);
    $quantity = max(0, (int) ($payload['quantity'] ?? 1));

    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Product ID tidak valid']);
        exit;
    }

    $product = loadProduct($pdo, $productId);
    if ($product === null) {
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
        exit;
    }

    switch ($action) {
        case 'set':
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId] = ['quantity' => $quantity];
            }
            break;
        case 'remove':
            unset($cart[$productId]);
            break;
        case 'clear':
            $cart = [];
            break;
        case 'add':
        default:
            $existing = $cart[$productId]['quantity'] ?? 0;
            $cart[$productId] = ['quantity' => $existing + max(1, $quantity)];
            break;
    }

    echo json_encode(array_merge(['success' => true], buildCartData()));
    exit;
}

echo json_encode(array_merge(['success' => true], buildCartData()));
