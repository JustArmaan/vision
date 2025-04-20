cat > docker-entrypoint.sh << 'EOF'
#!/bin/bash
set -e

# Generate application key if not already set
if [ -z "$(grep '^APP_KEY=' .env | grep -v '=$')" ]; then
    php artisan key:generate
fi

# Run migrations (optional, comment out if you don't want this to happen automatically)
php artisan migrate --force

# Cache configuration for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Change ownership of storage and cache directories if needed
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Start Apache in foreground
apache2-foreground
EOF
