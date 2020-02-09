#!/usr/bin/env bash

echo "Running LOCAL run-app.sh"

set -e

role=${CONTAINER_ROLE:-app}
env=local

php /var/www/html/artisan config:clear

confd -onetime -backend env

# App
if [ "$role" = "app" ]; then

    ln -sf /etc/supervisor/conf.d-available/app.conf /etc/supervisor/conf.d/app.conf
    
# App with scheduler
elif [ "$role" = "app + scheduler" ]; then

    ln -sf /etc/supervisor/conf.d-available/app.conf /etc/supervisor/conf.d/app.conf
    ln -sf /etc/supervisor/conf.d-available/scheduler.conf /etc/supervisor/conf.d/scheduler.conf

# Queue
elif [ "$role" = "queue" ]; then

    ln -sf /etc/supervisor/conf.d-available/queue.conf /etc/supervisor/conf.d/queue.conf

# Scheduler
elif [ "$role" = "scheduler" ]; then

    ln -sf /etc/supervisor/conf.d-available/scheduler.conf /etc/supervisor/conf.d/scheduler.conf

else
    echo "Could not match the container role \"$role\""
    exit 1
fi

# Create the default users.txt if it does not exist yet.
if [ ! -f /var/www/html/docker/local/api/users.txt ]; then
    cp /var/www/html/docker/local/api/users.txt.example /var/www/html/docker/local/api/users.txt
fi

# Recreate the www-data user
deluser www-data
newusers /var/www/html/docker/local/api/users.txt
printf "Recreated www-data user\n"

chown www-data:www-data /var/www/html/docker/local/api/users.txt

# Hit it!
exec supervisord -c /etc/supervisor/supervisord.conf
