FROM php:8.2-fpm

# install tzdata and set Europe/Vienna
RUN apt-get update \
 && apt-get install -y tzdata \
 && ln -snf /usr/share/zoneinfo/Europe/Bucharest /etc/localtime \
 && echo 'Europe/Vienna' > /etc/timezone \
 && dpkg-reconfigure -f noninteractive tzdata \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN { \
      echo 'date.timezone=Europe/Vienna'; \
    } > /usr/local/etc/php/conf.d/99-timezone.ini

RUN apt-get update && apt-get install -y \
    git zip unzip curl libicu-dev libonig-dev libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /srv/app
