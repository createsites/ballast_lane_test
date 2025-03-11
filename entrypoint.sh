#!/bin/sh

# waiting for the DB is completely started
dockerize -wait tcp://mariadb:3306 -timeout 30s

# migration
php artisan migrate

# create testing DB
mysql -u root -h $LARAVEL_DATABASE_HOST -p$LARAVEL_DATABASE_PASSWORD -e "CREATE DATABASE testing; GRANT ALL PRIVILEGES ON testing.* TO '$LARAVEL_DATABASE_USER'@'%'; FLUSH PRIVILEGES;"

# start the app
php artisan serve --host=0.0.0.0 --port=8000
