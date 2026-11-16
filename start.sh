#!/usr/bin/env bash
set -e

# Default port to 10000 if not provided by Render
PORT="${PORT:-10000}"

echo "Starting Apache on port ${PORT}..."

# Configure Apache to listen on Render's dynamic port
sed -i "s/Listen [0-9]*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Render Reverse Proxy Port 10000 / Canonical fixes
grep -q "UseCanonicalName Off" /etc/apache2/apache2.conf || echo "UseCanonicalName Off" >> /etc/apache2/apache2.conf
grep -q "UseCanonicalPhysicalPort Off" /etc/apache2/apache2.conf || echo "UseCanonicalPhysicalPort Off" >> /etc/apache2/apache2.conf
grep -q "ServerName localhost" /etc/apache2/apache2.conf || echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable AllowOverride All for .htaccess
sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Ensure permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html
[ -d /var/www/html/uploads ] && chmod -R 777 /var/www/html/uploads

# Start Apache in foreground
exec apache2-foreground
