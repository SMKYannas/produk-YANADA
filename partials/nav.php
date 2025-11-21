<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user_name'] ?? null;
$cartQuantity = 0;
foreach ($_SESSION['cart'] ?? [] as $cartItem) {
    $cartQuantity += (int) ($cartItem['quantity'] ?? 0);
}
?>
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center">
            <img src="image/logo.jpg" alt="Logo SMKS Kesehatan Yannas Husada" class="mr-3" width="50" height="50" />
            <div>
                <h1 class="text-lg font-bold text-gray-800">Yannas Husada | Produk Siswa</h1>
                <p class="text-xs text-gray-600">Inovasi Kesehatan & Herbal Bangkalan</p>
            </div>
        </div>
        <div class="hidden md:flex space-x-6 items-center">
            <a href="index.php" class="text-gray-800 hover:text-primary font-medium">Beranda</a>
            <a href="products.php" class="text-gray-800 hover:text-primary font-medium">Produk Herbal</a>
            <!-- <a href="orders.php" class="text-gray-800 hover:text-primary font-medium">Pesanan Saya</a> -->
            <a href="pelayanan_kesehatan.php" class="text-gray-800 hover:text-primary font-medium">Pelayanan
                Kesehatan</a>
            <a href="index.php#gallery" class="text-gray-800 hover:text-primary font-medium">Galeri</a>
            <a href="index.php#contact" class="text-gray-800 hover:text-primary font-medium">Pesan & Kontak</a>
            <button type="button"
                class="cart-preview-trigger text-gray-800 hover:text-primary font-medium flex items-center gap-2">
                <i class="fas fa-shopping-cart"></i>
                Keranjang
                <span class="cart-count text-sm text-primary font-semibold"><?= $cartQuantity ?></span>
            </button>
            <?php if ($currentUser): ?>
                <div class="relative">
                    <button id="user-dropdown-toggle" type="button"
                        class="flex items-center gap-2 text-gray-800 hover:text-primary font-medium focus:outline-none">
                        <i class="fas fa-user-circle text-lg"></i>
                        <span><?= htmlspecialchars($currentUser, ENT_QUOTES) ?></span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div id="user-dropdown-menu"
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-xl hidden">
                        <a href="orders.php"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm font-medium">Pesanan Saya</a>
                        <button id="logout-button"
                            class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm font-medium border-t">Logout</button>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="text-gray-800 hover:text-primary font-medium">Login</a>
                <a href="register.php" class="text-gray-800 hover:text-primary font-medium">Register</a>
            <?php endif; ?>
        </div>
        <button id="menu-toggle" class="md:hidden text-gray-800 focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>
    <div id="mobile-menu" class="md:hidden hidden px-4 pb-4">
        <a href="index.php" class="block py-2 text-gray-800 hover:text-primary font-medium">Beranda</a>
        <a href="products.php" class="block py-2 text-gray-800 hover:text-primary font-medium">Produk Herbal</a>
        <a href="pelayanan_kesehatan.php" class="block py-2 text-gray-800 hover:text-primary font-medium">Layanan
            Kesehatan</a>
        <a href="index.php#gallery" class="block py-2 text-gray-800 hover:text-primary font-medium">Galeri</a>
        <a href="index.php#contact" class="block py-2 text-gray-800 hover:text-primary font-medium">Pesan &
            Kontak</a>
        <button type="button"
            class="cart-preview-trigger block py-2 text-gray-800 hover:text-primary font-medium flex items-center gap-2">
            <i class="fas fa-shopping-cart"></i>
            Keranjang
            <span class="cart-count text-sm text-primary font-semibold"><?= $cartQuantity ?></span>
        </button>
        <?php if ($currentUser): ?>
            <div class="border-t border-gray-100 pt-2">
                <a href="orders.php"
                    class="block py-2 text-gray-800 hover:text-primary font-medium">Pesanan Saya</a>
                <button id="logout-button-mobile" type="button"
                    class="block w-full text-left py-2 text-gray-800 hover:text-primary font-medium">Logout</button>
            </div>
        <?php else: ?>
            <a href="login.php" class="block py-2 text-gray-800 hover:text-primary font-medium">Login</a>
            <a href="register.php" class="block py-2 text-gray-800 hover:text-primary font-medium">Register</a>
        <?php endif; ?>
    </div>
</nav>
<script>
    window.__userLoggedIn = <?= json_encode((bool) $currentUser) ?>;
    window.__userName = <?= json_encode($currentUser ?? '') ?>;

    const userDropdownToggle = document.getElementById('user-dropdown-toggle');
    const userDropdownMenu = document.getElementById('user-dropdown-menu');

    const closeUserDropdown = () => {
        userDropdownMenu?.classList.add('hidden');
    };

    if (userDropdownToggle && userDropdownMenu) {
        userDropdownToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            userDropdownMenu.classList.toggle('hidden');
        });

        userDropdownMenu.addEventListener('click', (event) => {
            event.stopPropagation();
        });

        document.addEventListener('click', () => {
            closeUserDropdown();
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeUserDropdown();
            }
        });
    }
</script>
