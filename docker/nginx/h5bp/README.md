Component-config files
----------------------

Each of these files is intended to be included in a server block. Not all of
the files here are used - they are available to be included as required. The
`basic.conf` file includes the rules which are recommended to always be
defined.

When change is done
composer install
php artisan migrate:fresh
php artisan key:generate
php artisan passport:install
php artisan config:clear
php artisan module:seed Teacher
php artisan module:seed CustomClass
php artisan module:seed Student
#deploy master
