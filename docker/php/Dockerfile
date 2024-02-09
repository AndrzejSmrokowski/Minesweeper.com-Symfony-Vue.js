# Argumenty dla wersji i użytkownika (opcjonalnie)
ARG PHP_VERSION=8.3-fpm
ARG USER_ID=1000
ARG GROUP_ID=1000

# Używanie argumentu wersji PHP
FROM php:${PHP_VERSION}

# Instalacja zależności, rozszerzeń PHP i narzędzi pomocniczych
RUN apt-get update && \
    apt-get install -y libpq-dev libzip-dev zip git && \
    docker-php-ext-install zip pdo_mysql && \
    pecl install xdebug-3.3.1 && \
    docker-php-ext-enable xdebug && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Xdebug configuration
COPY xdebug.ini "${PHP_INI_DIR}/conf.d"

# Tworzenie nowego użytkownika i grupy z określonym UID/GID i zmiana użytkownika
RUN groupadd -g ${GROUP_ID} appuser && \
    useradd -u ${USER_ID} -g appuser -m appuser && \
    chown -R appuser:appuser /var/www

USER appuser

# Command to run
CMD ["php-fpm"]
