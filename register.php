<?php
declare(strict_types=1);

require_once __DIR__ . '/config/db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $errors[] = 'Semua kolom harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid.';
    }

    if (empty($errors)) {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        try {
            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
            ]);
            $success = 'Registrasi berhasil. Silakan login.';
        } catch (PDOException $ex) {
            if ((int) $ex->getCode() === 23000) {
                $errors[] = 'Email ini sudah terdaftar.';
            } else {
                $errors[] = 'Terjadi kesalahan saat menyimpan data.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Produk Siswa SMKS Kesehatan Yannas Husada</title>
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
                <div class="text-center mb-10" data-aos="fade-up">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                        <i class="fas fa-user-plus text-primary mr-2"></i> Pendaftaran Pesanan Terverifikasi
                    </h2>
                    <div class="section-divider"></div>
                    <p class="text-gray-600 max-w-3xl mx-auto">
                        Isi formulir ini agar tim kami dapat mencatat data Anda ke dalam sistem. Pastikan data valid
                        dan kami akan mengonfirmasi melalui WhatsApp atau email.
                    </p>
                </div>

                <div class="max-w-3xl mx-auto">
                    <form method="post"
                        class="space-y-6 bg-secondary/10 border border-secondary rounded-xl p-8 shadow-lg"
                        data-aos="fade-up" data-aos-delay="100">
                        <?php foreach ($errors as $error): ?>
                            <div class="text-red-600 text-sm font-medium"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
                        <?php endforeach; ?>
                        <?php if ($success): ?>
                            <div class="text-green-600 text-sm font-medium"><?= htmlspecialchars($success, ENT_QUOTES) ?></div>
                        <?php endif; ?>
                        <div>
                            <label for="register-name" class="block text-sm font-semibold text-gray-700">Nama
                                Lengkap</label>
                            <input type="text" id="register-name" name="name"
                                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring focus:ring-primary/30"
                                placeholder="Contoh: Siti Aisyah" required>
                        </div>
                        <div>
                            <label for="register-email" class="block text-sm font-semibold text-gray-700">Email</label>
                            <input type="email" id="register-email" name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring focus:ring-primary/30"
                                placeholder="contoh@domain.com" required>
                        </div>
                        <div>
                            <label for="register-password" class="block text-sm font-semibold text-gray-700">Kata
                                Sandi</label>
                            <input type="password" id="register-password" name="password"
                                class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-primary focus:ring focus:ring-primary/30"
                                placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-lock text-primary"></i>
                            <p>Data yang dikirimkan akan disimpan untuk proses tindak lanjut.</p>
                        </div>
                        <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-semibold rounded-full px-6 py-3 transition focus:outline-none focus:ring focus:ring-primary/40">
                            Kirim Pendaftaran
                        </button>
                        <p class="text-center text-gray-600 text-sm">
                            Sudah punya akun?
                            <a href="login.php" class="text-primary font-semibold hover:underline">Login di sini</a>
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
