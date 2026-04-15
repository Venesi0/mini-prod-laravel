FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
	git \
	curl \
	unzip \
	libpq-dev \
	libzip-dev \
	libicu-dev \
	libonig-dev \
	&& docker-php-ext-install pdo pdo_pgsql intl zip \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

CMD ["php-fpm"]
