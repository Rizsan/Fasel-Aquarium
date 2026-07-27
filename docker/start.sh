#!/bin/sh

echo "===================================="
echo "Starting Fasel Aquarium..."
echo "===================================="

php artisan package:discover --ansi

php artisan storage:link || true

echo "Laravel Ready"

exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}