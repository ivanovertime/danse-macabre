#!/bin/sh
set -e

php artisan config:cache
php artisan route:cache
php artisan migrate --force

exec frankenphp run --config /etc/frankenphp/Caddyfile
