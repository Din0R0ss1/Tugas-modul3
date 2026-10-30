<?php
declare(strict_types=1);

// Deteksi lingkungan aplikasi
$isAzure = getenv('WEBSITE_SITE_NAME') !== false;

try {

    if ($isAzure) {

        // =====================================
        // DATABASE AZURE: SQLITE
        // =====================================

        $dbDir = '/home/data';

        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0775, true);
        }

        $dbFile = $dbDir . '/stockwise.sqlite';

        $pdo = new PDO('sqlite:' . $dbFile);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Membuat tabel products
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(120) NOT NULL,
                sku VARCHAR(40) NOT NULL UNIQUE,
                category VARCHAR(80) NOT NULL,
                price DECIMAL(14,2) NOT NULL DEFAULT 0,
                stock INTEGER NOT NULL DEFAULT 0,
                description VARCHAR(1000) NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Update timestamp otomatis
        $pdo->exec("
            CREATE TRIGGER IF NOT EXISTS update_products_timestamp
            AFTER UPDATE ON products
            FOR EACH ROW
            BEGIN
                UPDATE products
                SET updated_at = CURRENT_TIMESTAMP
                WHERE id = NEW.id;
            END;
        ");

        // Memasukkan data awal jika tabel kosong
        $count = (int) $pdo
            ->query("SELECT COUNT(*) FROM products")
            ->fetchColumn();

        if ($count === 0) {

            $pdo->exec("
                INSERT INTO products
                (name, sku, category, price, stock, description)
                VALUES
                ('Wireless Headphones', 'AUD-001', 'Electronics', 425000, 18, 'Over-ear wireless headphones.'),
                ('Mechanical Keyboard', 'ACC-014', 'Accessories', 685000, 4, 'Compact mechanical keyboard.'),
                ('Desk Organizer', 'OFF-032', 'Office supplies', 89000, 32, 'Minimal organizer for desk essentials.'),
                ('USB-C Hub 7-in-1', 'ELC-008', 'Electronics', 279000, 0, 'Multiport USB-C hub.'),
                ('Ergonomic Mouse', 'ACC-021', 'Accessories', 215000, 11, 'Ergonomic mouse for daily use.')
            ");
        }

    } else {

        // =====================================
        // DATABASE LOKAL: MYSQL XAMPP
        // =====================================

        $host = '127.0.0.1';
        $name = 'stockwise';
        $user = 'root';
        $pass = '';

        $dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

} catch (PDOException $e) {

    http_response_code(500);

    exit(
        'Database connection failed: ' .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
    );
}
