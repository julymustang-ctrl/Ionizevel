#!/bin/sh
# =============================================================================
# Ionizevel Laravel Application Entrypoint
# =============================================================================

set -e

echo "🚀 Starting Ionizevel Application..."

# Create necessary directories
mkdir -p /var/log/supervisor
mkdir -p /var/log/nginx
mkdir -p /var/log/php

# Ensure storage directories exist with proper permissions
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for database if needed (for MySQL/PostgreSQL)
if [ -n "$DB_HOST" ] && [ "$DB_CONNECTION" != "sqlite" ]; then
    echo "⏳ Waiting for database connection..."
    
    MAX_RETRIES=30
    RETRY_INTERVAL=2
    
    for i in $(seq 1 $MAX_RETRIES); do
        if php artisan db:show > /dev/null 2>&1; then
            echo "✅ Database connection established!"
            break
        fi
        
        if [ $i -eq $MAX_RETRIES ]; then
            echo "❌ Could not connect to database after $MAX_RETRIES attempts"
            exit 1
        fi
        
        echo "   Attempt $i/$MAX_RETRIES - Retrying in ${RETRY_INTERVAL}s..."
        sleep $RETRY_INTERVAL
    done
fi

# Handle SQLite database
if [ "$DB_CONNECTION" = "sqlite" ]; then
    SQLITE_PATH=${DB_DATABASE:-/var/www/html/database/database.sqlite}
    if [ ! -f "$SQLITE_PATH" ]; then
        echo "📄 Creating SQLite database..."
        touch "$SQLITE_PATH"
        chown www-data:www-data "$SQLITE_PATH"
        chmod 664 "$SQLITE_PATH"
    fi
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "📦 Running database migrations..."
php artisan migrate --force --no-interaction

# Clear and optimize caches
echo "🧹 Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
if [ ! -L /var/www/html/public/storage ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

echo "✅ Application ready!"
echo "============================================"

# Execute the main command
exec "$@"
