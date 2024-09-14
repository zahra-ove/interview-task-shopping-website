# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage/ /var/www/html/bootstrap/cache/

# Start Apache
apache2-foreground