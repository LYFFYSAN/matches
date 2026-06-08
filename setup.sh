#!/usr/bin/env bash
# ============================================================
# World Cup 2026 — Quick Setup Script
# ============================================================
set -e

echo ""
echo "⚽  World Cup 2026 — Laravel Setup"
echo "======================================"

# 1. Copy env
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅  .env created from .env.example"
else
    echo "ℹ️   .env already exists — skipping"
fi

# 2. Install PHP deps
echo ""
echo "📦  Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Generate app key
php artisan key:generate --ansi

# 4. Create SQLite DB if not using MySQL
if grep -q "DB_CONNECTION=sqlite" .env; then
    touch database/database.sqlite
    echo "✅  SQLite database file created"
fi

# 5. Run migrations
echo ""
echo "🗄️   Running migrations..."
php artisan migrate --force

# 6. Seed sample data
echo ""
echo "🌱  Seeding sample matches..."
php artisan db:seed --force

# 7. Copy assets to public (if not using npm)
echo ""
echo "🎨  Copying assets to public/..."
mkdir -p public/css public/js
cp resources/css/app.css public/css/app.css
cp resources/css/admin.css public/css/admin.css
cp resources/js/app.js public/js/app.js

echo ""
echo "======================================"
echo "✅  Setup complete!"
echo ""
echo "  Start server:  php artisan serve"
echo "  Site:          http://localhost:8000"
echo "  Admin:         http://localhost:8000/admin/login"
echo "  Admin secret:  $(grep ADMIN_SECRET .env | cut -d= -f2)"
echo ""
