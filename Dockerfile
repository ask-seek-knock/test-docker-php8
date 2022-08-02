#
# Dockerfile for shadowsocks-libev
#

FROM alpine

RUN set -ex \
 # Build environment setup
 && apk update \
 && apk add --no-cache \
      php \
      php-cgi \
      php-session \
      php-curl \
      lighttpd \
      openssl \
      unzip \
 && rm -rf /var/cache/apk/*

EXPOSE 80 443

COPY ./entrypoint.sh ./src.zip /

CMD /entrypoint.sh
