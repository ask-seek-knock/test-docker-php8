#!/bin/sh
/usr/sbin/php*fpm* -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
