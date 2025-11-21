<?php
declare(strict_types=1);

require_once __DIR__ . '/config/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email dan password harus diisi.';
    } else {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Produk Siswa SMKS Kesehatan Yannas Husada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-gray-50">
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="py-16">
        <section class="bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto">
                    <div class="text-center mb-10" data-aos="fade-up">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                            <i class="fas fa-sign-in-alt text-primary mr-2"></i> Masuk ke Akun Anda
                        </h2>
                        <div class="section-divider"></div>
                        <p class="text-gray-600 max-w-3xl mx-auto">
                            Gunakan email dan password yang sudah terdaftar agar bisa melihat produk dan melakukan
                            pemesanan.
                        </p>
                    </div>
                    <form method="post"
                        class="space-y-6 bg-secondary/10 border border-secondary rounded-xl p-8 shadow-lg"
                        data-aos="fade-up" data-aos-delay="100">
                        <?php foreach ($errors as $error): ?>
                            <div class="text-red-600 text-sm font-medium"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
                        <?php endforeach; ?>
                        <div>
                            <label for="login-email" class="block text-sm font-semibold text-gray-700">Email</label>
                            <input type="email" id="login-email" name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring focus:ring-primary/30"
                                placeholder="contoh@domain.com" required>
                        </div>
                        <div>
                            <label for="login-password" class="block text-sm font-semibold text-gray-700">Kata
                                Sandi</label>
                            <input type="password" id="login-password" name="password"
                                class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring focus:ring-primary/30"
                                placeholder="Minimal 8 karakter" required>
                        </div>
                        <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-semibold rounded-full px-6 py-3 transition focus:outline-none focus:ring focus:ring-primary/40">
                            Login Sekarang
                        </button>
                        <p class="text-center text-gray-600 text-sm">
                            Belum punya akun?
                            <a href="register.php" class="text-primary font-semibold hover:underline">Daftar di sini</a>
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-secondary text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-lg font-semibold mb-2">SMKS Kesehatan Yannas Husada Bangkalan</p>
            <p class="text-sm opacity-80">
                Inovasi Produk Kesehatan Siswa - &copy; 2025. All Rights Reserved.
            </p>
        </div>
    </footer>

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
