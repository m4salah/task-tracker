#!/bin/sh

set -e

php artisan migrate
php artisan serve --host=0.0.0.0
