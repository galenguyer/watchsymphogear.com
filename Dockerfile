# Multi-Stage Build File the Universal Animal API

# set up the final container
FROM alpine:latest
EXPOSE 80
WORKDIR /var/www/html

# install php and supervisor
RUN apk add nginx supervisor php7 php7-fpm curl

# configure php and php-fpm
COPY configs/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY configs/php.ini /etc/php7/conf.d/custom.ini

# setup nginx folders and files
RUN adduser www-data -D -H
RUN mkdir -p /tmp/nginx/{client,proxy} && chown -R www-data:www-data /tmp/nginx/
RUN mkdir -p /var/log/nginx && chown -R www-data:www-data /var/log/nginx
RUN mkdir -p /usr/share/nginx/fastcgi_temp && chown -R www-data:www-data /usr/share/nginx/fastcgi_temp
RUN chown -R www-data:www-data /var/www/html
RUN touch /run/nginx.pid && chown www-data:www-data /run/nginx.pid
RUN mkdir -p /etc/nginx 

# add nginx binaries and confs
COPY configs/nginx.conf /etc/nginx/nginx.conf
COPY configs/mime.types /etc/nginx/mime.types
COPY configs/fastcgi.conf /etc/nginx/fastcgi.conf
COPY configs/fastcgi-php.conf /etc/nginx/fastcgi-php.conf

# homepage
COPY *.php ./

# add supervisord conf
COPY configs/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
