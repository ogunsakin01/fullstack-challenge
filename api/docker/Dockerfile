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
    zip \
    redis \

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY crontab /etc/cron.d/crontab
RUN chmod 0644 /etc/cron.d/crontab
RUN crontab /etc/cron.d/crontab
RUN service cron start

RUN a2enmod rewrite
RUN service apache2 restart

EXPOSE 80

CMD sh /var/www/html/docker/startup.sh
