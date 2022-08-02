FROM alpine

RUN apk update \
&& apk add --no-cache ca-certificates caddy php php-fpm php-session \
php-curl \
&& rm -rf /var/cache/apk/*

COPY src/ /var/www/html/
COPY ./Caddyfile /etc/caddy/Caddyfile
COPY ./entrypoint.sh /

CMD /entrypoint.sh
