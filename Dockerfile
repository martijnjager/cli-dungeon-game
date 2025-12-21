FROM php:8.2-cli

WORKDIR /app

# Install deps for composer
RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

COPY composer.json composer.lock* /app/
## Install full dependency set (including dev) to run tests during build.
RUN composer install --no-interaction --prefer-dist

# Copy application source
COPY . /app

# Re-install without dev dependencies for a leaner runtime image.
RUN composer install --no-interaction --prefer-dist --ignore-platform-reqs
# RUN composer install --no-interaction --prefer-dist --no-dev --ignore-platform-reqs

RUN chmod +x /app/bin/game

ENTRYPOINT ["/app/bin/game"]
