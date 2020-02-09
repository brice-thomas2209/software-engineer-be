#!/usr/bin/env bash

echo "Running run-app.sh"

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

IS_LEADER_INSTANCE=false
# If the environment type is not set then assume this is the leader instance.
if [ -z "${ENVIRONMENT_TYPE}" ]; then
    IS_LEADER_INSTANCE=true
    ENVIRONMENT_TYPE="WORKER"
elif [ -f "/var/www/html/leader-instance" ]; then
    IS_LEADER_INSTANCE=true
fi

if [ "$env" != "local" ]; then
#    echo "Caching configuration..."
#    (cd /var/www/html && php artisan config:cache && php artisan route:cache)

    echo "Removing Xdebug..."
    rm -rf /usr/local/etc/php/conf.d/{docker-php-ext-xdebug,xdebug}.ini
fi

if [ "$env" == "local" ] && [ ! -z "$DEV_UID" ]; then
    echo "Changing www-data UID to $DEV_UID"
    echo "The UID should only be changed in development environments."
    usermod -u $DEV_UID www-data
fi

if [ "$env" != "production" ]; then
    # Run migrations only on the worker leader instance. (@see: 02_customizing_instances.config).
    if [ "$IS_LEADER_INSTANCE" = true ] && [ "$ENVIRONMENT_TYPE" == "WORKER" ]; then
        echo "Running migration and seeder for system database (non-production env)"
        php /var/www/html/artisan migrate
    else
        echo "Not a worker leader instance. Skipping migrations..."
        echo "Environment Type: $ENVIRONMENT_TYPE"
    fi
else
    # !!!!!!!!
    # !!! Production migrations here !!!
    # Run migrations only on the worker leader instance. (@see: 02_customizing_instances.config).
    if [ "$IS_LEADER_INSTANCE" = true ] && [ "$ENVIRONMENT_TYPE" == "WORKER" ]; then
        echo "Running migration system database (production env)"
        php /var/www/html/artisan migrate --force
    else
        echo "Not a worker leader instance. Skipping migrations..."
        echo "Environment Type: $ENVIRONMENT_TYPE"
        php /var/www/html/artisan migrate --force
    fi
fi

php /var/www/html/artisan config:clear

confd -onetime -backend env

# App
if [ "$role" = "app" ]; then
    ln -sf /etc/supervisor/conf.d-available/app.conf /etc/supervisor/conf.d/app.conf
    ln -sf /etc/supervisor/conf.d-available/websockets.conf /etc/supervisor/conf.d/websockets.conf

# App with queue worker
elif [ "$role" = "app + queue" ]; then
    ln -sf /etc/supervisor/conf.d-available/app.conf /etc/supervisor/conf.d/app.conf
    ln -sf /etc/supervisor/conf.d-available/queue.conf /etc/supervisor/conf.d/queue.conf
    ln -sf /etc/supervisor/conf.d-available/websockets.conf /etc/supervisor/conf.d/websockets.conf

# App with scheduler
elif [ "$role" = "app + scheduler" ]; then

    ln -sf /etc/supervisor/conf.d-available/app.conf /etc/supervisor/conf.d/app.conf
    ln -sf /etc/supervisor/conf.d-available/scheduler.conf /etc/supervisor/conf.d/scheduler.conf

# App with queue worker, websocket and scheduler
elif [ "$role" = "app + queue + scheduler + websocket" ]; then
    ln -sf /etc/supervisor/conf.d-available/app.conf /etc/supervisor/conf.d/app.conf
    ln -sf /etc/supervisor/conf.d-available/queue.conf /etc/supervisor/conf.d/queue.conf
    ln -sf /etc/supervisor/conf.d-available/websockets.conf /etc/supervisor/conf.d/websockets.conf
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

# Mark the container as ready to receive HTTP connections
touch /var/www/html/app-ready

exec supervisord -c /etc/supervisor/supervisord.conf
