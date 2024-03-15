FROM php:8.3-fpm

# Instalacja zależności, rozszerzeń PHP i narzędzi pomocniczych
RUN apt-get update -q && \
    apt-get install -yq --no-install-recommends libpq-dev libzip-dev zip git && \
    docker-php-ext-install zip pdo_mysql && \
    pecl install xdebug-3.3.1 && \
    docker-php-ext-enable xdebug && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    echo "xdebug.mode=debug" >> "${PHP_INI_DIR}/conf.d/docker-php-ext-xdebug.ini" && \
    echo "xdebug.client_host=host.docker.internal" >> "${PHP_INI_DIR}/conf.d/docker-php-ext-xdebug.ini"

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tworzenie nowego użytkownika i grupy z określonym UID/GID i zmiana użytkownika
RUN groupadd -g 1000 appuser && \
    useradd -u 1000 -g appuser -m appuser && \
    chown -R appuser:appuser /var/www

USER appuser

# Command to run
CMD ["php-fpm"]
