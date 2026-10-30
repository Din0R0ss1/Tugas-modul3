CREATE DATABASE IF NOT EXISTS stockwise CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stockwise;
CREATE TABLE IF NOT EXISTS products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 sku VARCHAR(40) NOT NULL UNIQUE,
 category VARCHAR(80) NOT NULL,
 price DECIMAL(14,2) NOT NULL DEFAULT 0,
 stock INT UNSIGNED NOT NULL DEFAULT 0,
 description VARCHAR(1000) NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_products_name(name), INDEX idx_products_category(category)
);
INSERT INTO products(name,sku,category,price,stock,description) VALUES
('Wireless Headphones','AUD-001','Electronics',425000,18,'Over-ear wireless headphones.'),
('Mechanical Keyboard','ACC-014','Accessories',685000,4,'Compact mechanical keyboard.'),
('Desk Organizer','OFF-032','Office supplies',89000,32,'Minimal organizer for desk essentials.'),
('USB-C Hub 7-in-1','ELC-008','Electronics',279000,0,'Multiport USB-C hub.'),
('Ergonomic Mouse','ACC-021','Accessories',215000,11,'Ergonomic mouse for daily use.');
