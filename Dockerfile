FROM alpine

COPY src/ /var/www/html/
RUN apk update \
&& apk add --no-cache ca-certificates caddy php php-fpm php-session \
php-curl \
&& chown -R caddy. /var/www/html
&& rm -rf /var/cache/apk/* 

COPY ./Caddyfile /etc/caddy/Caddyfile
COPY ./entrypoint.sh /


CMD /entrypoint.sh
