# StockWise — PHP Native Inventory CRUD

Responsive inventory management app using PHP Native, MySQL, PDO and custom CSS.

## Features
- Dashboard statistics: products, stock units, inventory value, low-stock alerts
- Create, read, update, delete products
- Separate Add Product and Edit Product pages
- Search by product name, SKU or category; filter by category
- CSRF protection (`random_bytes(32)` + `hash_equals`)
- PDO prepared statements, server-side validation, output escaping, unique SKU
- Responsive layout

## Structure
- `index.php` — dashboard and product listing
- `create.php` — create form
- `edit.php` — update form
- `delete.php` — POST-only delete action
- `config/database.php` — PDO connection
- `includes/` — bootstrap, shared header/footer
- `assets/css/style.css` — UI styles
- `database.sql` — schema and sample data

## Run locally
1. Import `database.sql` into MySQL.
2. Set DB credentials in `config/database.php` or environment variables `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.
3. From this folder run `php -S localhost:8000`.
4. Open `http://localhost:8000`.

Requires PHP 8.1+ with PDO MySQL and MySQL/MariaDB.

## Azure deployment
Azure App Service does not automatically provide MySQL. Provision a reachable MySQL server, import the SQL schema, then set `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` under App Service > Settings > Environment variables. Ensure database network/firewall permits App Service access. Deploy this entire folder with `index.php` at its root. Never commit database passwords to GitHub.
