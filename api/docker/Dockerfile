FROM php:8.1-apache

RUN apt-get update

RUN apt-get install -y \
    pkg-config  \
    supervisor \
    cron \
    libzip-dev

RUN docker-php-ext-install \
    mysqli \
    pdo_mysql \
    zip

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY crontab /etc/cron.d/crontab

RUN chmod 0644 /etc/cron.d/crontab \
    && crontab /etc/cron.d/crontab

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf
RUN sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
