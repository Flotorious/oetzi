FROM php:8.2-fpm

# 1. Install tzdata and set timezone to Europe/Vienna
RUN apt-get update \
 && apt-get install -y tzdata \
 && ln -snf /usr/share/zoneinfo/Europe/Vienna /etc/localtime \
 && echo 'Europe/Vienna' > /etc/timezone \
 && dpkg-reconfigure -f noninteractive tzdata \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN { \
      echo 'date.timezone=Europe/Vienna'; \
    } > /usr/local/etc/php/conf.d/99-timezone.ini

ARG PHP_MEMORY_LIMIT=512M
RUN echo "memory_limit = ${PHP_MEMORY_LIMIT}" > /usr/local/etc/php/conf.d/99-custom-memory-limit.ini

# 2. Install PHP extensions and required system packages
RUN apt-get update && apt-get install -y \
    git zip unzip curl libicu-dev libonig-dev libzip-dev libpng-dev

RUN docker-php-ext-install pdo pdo_mysql intl zip

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Install Node.js and npm (NodeSource method for latest LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# 5. Set work directory
WORKDIR /srv/app

# 6. Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy application code
COPY . .

# Run auto-scripts now that bin/console exists
# RUN composer run-script @auto-scripts

# 7. Copy package files and install JS dependencies, then build assets
COPY package.json package-lock.json ./
RUN npm ci

# 8. Copy the rest of your app source code
COPY . .

# 9. Build front-end assets (Webpack Encore, Vite, etc)
RUN npm run build

# 10. Set correct permissions for Symfony cache/logs (adjust for your user/needs)
RUN mkdir -p var && chmod -R 777 var

# 11. Set production environment
ENV APP_ENV=prod

# 12. Expose port for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]

