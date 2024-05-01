FROM php:8.3-fpm

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем composer.lock и composer.json
#COPY composer.lock composer.json /var/www/

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    build-essential \
    locales \
    zip \
    vim \
    unzip \
    git \
    curl \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev libmemcached-dev

RUN pecl install memcached

RUN docker-php-ext-enable memcached

# Очищаем кэш
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Устанавливаем расширения PHP
RUN docker-php-ext-install pdo_mysql mbstring zip dom simplexml

# Загружаем актуальную версию Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создаём пользователя и группу www для приложения Laravel
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Копируем содержимое текущего каталога в рабочую директорию
#COPY . /var/www
#COPY --chown=www:www . /var/www

RUN install -d -m 0775 -o www -g www /var/www/storage /var/www/storage/logs /var/www/bootstrap/cache

# Меняем пользователя на www
USER www

# В контейнере открываем 9000 порт и запускаем сервер php-fpm
EXPOSE 9000
CMD ["php-fpm"]
