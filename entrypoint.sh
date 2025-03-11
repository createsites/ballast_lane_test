#!/bin/sh

# waiting for the DB is completely started
dockerize -wait tcp://mariadb:3306 -timeout 30s

# migration
php artisan migrate

# start the app
php artisan serve --host=0.0.0.0 --port=8000
