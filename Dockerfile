FROM alpine

RUN apk update \
&& apk add --no-cache ca-certificates caddy php php-fpm php-session \
php-curl php-openssl \
&& rm -rf /var/cache/apk/*

COPY src/ /var/www/html/
COPY ./Caddyfile /etc/caddy/Caddyfile
COPY ./entrypoint.sh /
RUN chown -R caddy. /var/www/html

CMD /entrypoint.sh
