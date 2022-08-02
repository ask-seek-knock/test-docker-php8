#!/bin/sh

if [[ -z "$hisecret19891997" ]]; then
    >&2 echo "No password found..."
    exit 1
fi
tmp_dir=tam1989

mkdir ${tmp_dir} \
unzip -q -P ${hisecret19891997} -d ${tmp_dir} /src.zip \
mv -f /lighttpd.conf /etc/lighttpd/lighttpd.conf \
mkdir /var/www/html \
cp -R ${tmp_dir}/* /var/www/html/ \
chown -R lighttpd:lighttpd /var/www/html \
rm -rf /src.zip ${tmp_dir}


exec lighttpd-angel -D -f /etc/lighttpd/lighttpd.conf
