#!/bin/sh
set -e

echo "⏳ Waiting for database to be ready..."

until php bin/console doctrine:query:sql "SELECT 1" --env=prod >/dev/null 2>&1; do
    echo "⏳ Attente de la base de données..."
    sleep 2
done

echo "✅ Database is up"

echo "📦 Installing database schema..."
php bin/console doctrine:database:create --if-not-exists --no-interaction
php bin/console doctrine:migrations:diff --no-interaction --allow-empty-diff
php bin/console doctrine:migrations:migrate --no-interaction

echo "📦 Clear Cache..."
php bin/console cache:clear --no-warmup
php bin/console cache:warmup

echo "🌱 Loading fixtures..."
php bin/console doctrine:fixtures:load --no-interaction

echo "🚀 Starting PHP-FPM..."
exec php-fpm
