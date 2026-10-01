#!/bin/bash
set -Eeuo pipefail
cd /var/www

if [[ -n "${MYSQL_ATTR_SSL_CA:-}" ]]; then
    if [[ ! -f "$MYSQL_ATTR_SSL_CA" || ! -r "$MYSQL_ATTR_SSL_CA" ]]; then
        echo "Cannot read MySQL CA file. Check Render Secret Files and MYSQL_ATTR_SSL_CA." >&2
        exit 1
    fi
    umask 077
    mkdir -p /run/app-certificates
    chown root:www-data /run/app-certificates
    chmod 750 /run/app-certificates
    cp "$MYSQL_ATTR_SSL_CA" /run/app-certificates/mysql-ca.pem
    chown www-data:www-data /run/app-certificates/mysql-ca.pem
    chmod 400 /run/app-certificates/mysql-ca.pem
    export MYSQL_ATTR_SSL_CA=/run/app-certificates/mysql-ca.pem
    su-exec www-data php docker/check-ca.php
fi

if (($# > 0)); then
    exec su-exec www-data "$@"
fi

: "${APP_KEY:?Set a persistent APP_KEY before starting the application}"
: "${APP_URL:?Set APP_URL to the public HTTPS address}"
export PORT="${PORT:-10000}"

envsubst '${PORT}' < /etc/nginx/templates/default.conf.template > /etc/nginx/http.d/default.conf
mkdir -p storage/framework/{cache/data,sessions,views} storage/logs storage/app/public bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

su-exec www-data php artisan config:cache

case "${RUN_MIGRATIONS:-true}" in
    true) su-exec www-data php artisan migrate --force --no-interaction ;;
    false) ;;
    *) echo "RUN_MIGRATIONS must be true or false" >&2; exit 1 ;;
esac

case "${RUN_SEEDERS:-false}" in
    true) su-exec www-data php artisan db:seed --force --no-interaction ;;
    false) ;;
    *) echo "RUN_SEEDERS must be true or false" >&2; exit 1 ;;
esac

su-exec www-data php artisan route:cache
su-exec www-data php artisan view:cache

nginx -t
php-fpm -t

server_pids=()
cleanup() {
    trap - EXIT TERM INT
    if ((${#server_pids[@]})); then
        kill -QUIT "${server_pids[@]}" 2>/dev/null || true
        wait "${server_pids[@]}" 2>/dev/null || true
    fi
}
trap cleanup EXIT
trap 'exit 0' TERM INT

php-fpm -F &
server_pids+=("$!")
nginx -g 'daemon off;' &
server_pids+=("$!")

status=0
wait -n "${server_pids[@]}" || status=$?
echo "A web server exited (status $status); stopping container" >&2
exit 1