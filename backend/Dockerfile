FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first (for caching)
COPY composer.json composer.lock ./

# Install dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application code
COPY . .

# Run post-install scripts
RUN composer run-script post-autoload-dump

# Cache config and routes
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Create storage directories
RUN mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Start Laravel
CMD php artisan migrate --force && php artisan db:seed --class=AdminSeeder --force && php artisan serve --host=0.0.0.0 --port=$PORT
