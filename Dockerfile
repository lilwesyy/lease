FROM php:8.2-cli

# Installa estensioni necessarie
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installa Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installa Node.js e npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm

# Imposta la cartella di lavoro
WORKDIR /var/www

# Esponi le porte necessarie
EXPOSE 8000 5173

# Comandi predefiniti
CMD ["sh", "-c", "composer install && npm install && php artisan serve --host=0.0.0.0 --port=8000 & npm run dev"]
