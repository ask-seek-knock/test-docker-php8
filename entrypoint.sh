#!/bin/sh
chown -R caddy. /var/www/html
/usr/sbin/php*fpm* -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
