#!/usr/bin/env bash

mkdir /var/www/html/var/cache /var/www/html/var/logs /var/www/html/var/sessions
chmod -R 0700 /var/www/html/var/cache /var/www/html/var/logs /var/www/html/var/sessions
chown -R www-data:www-data /var/www/html/var/cache /var/www/html/var/logs /var/www/html/var/sessions

exec php-fpm
