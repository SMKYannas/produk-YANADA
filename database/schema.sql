-- Schema for produk-YANADA backend
CREATE DATABASE IF NOT EXISTS `yanada` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `yanada`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('customer','admin') NOT NULL DEFAULT 'customer',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `stock` INT UNSIGNED NOT NULL DEFAULT 0,
    `image_url` VARCHAR(512),
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders table
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `order_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('pending','processing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
    `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `shipping_address` TEXT,
    PRIMARY KEY (`id`),
    KEY `idx_orders_user_id` (`user_id`),
    CONSTRAINT `fk_orders_users` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order items table
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
    `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (`id`),
    KEY `idx_order_items_order_id` (`order_id`),
    KEY `idx_order_items_product_id` (`product_id`),
    CONSTRAINT `fk_order_items_orders` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_products` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed products based on existing catalog
INSERT INTO `products` (`name`, `description`, `price`, `stock`, `image_url`)
VALUES
    ('Jasernas', 'Jasenas (Jahe Serbuk Yannas) adalah minuman herbal praktis untuk stamina dan penghangat tubuh, hasil TIM ABMAS & KKN Forum Akbar Penggerak Halal Madura.', 12000.00, 120, 'image/produk1.jpg'),
    ('Inhaler', 'Perangkat medis untuk kirim obat langsung ke paru-paru, membantu meredakan asma, mengendalikan gejala PPOK, dan mendukung aktivitas tanpa hambatan napas.', 8000.00, 80, 'image/produk2.jpg'),
    ('Sinom Tumeric Tamarind Infusa', 'Minuman tradisional yang kaya kandungan kunyit dan asam jawa untuk meningkatkan vitalitas tubuh.', 6000.00, 90, 'image/produk3.jpg'),
    ('Deodorant', 'Deodoran tawas semprot berbasis larutan potassium alum dengan sentuhan bahan alami seperti lidah buaya atau aroma esensial.', 8000.00, 70, 'image/produk4.jpg'),
    ('Hand Soap (Peach)', 'Hand soap aroma persik untuk membersihkan tangan sekaligus memberikan sensasi wangi buah segar.', 15000.00, 60, 'image/produk5.jpg'),
    ('Yanada Bar Soap', 'Sabun batang susu yang diformulasikan untuk membersihkan dan melembapkan kulit sepanjang hari.', 5000.00, 75, 'image/produk6.jpg');
