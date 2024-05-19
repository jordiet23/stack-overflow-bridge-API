FROM php:8.1-fpm as base
ENV APACHE_DOCUMENT_ROOT /home/developer/app/public
RUN apt-get update
RUN apt-get install -y libzip-dev libpq-dev
RUN docker-php-ext-install zip pdo pdo_pgsql pcntl sockets

FROM base as dev
ARG DEV_USER_ID
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN pecl install pcov xdebug-3.2.1 && docker-php-ext-enable pcov xdebug
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN apt-get install -y git vim

RUN useradd -u $DEV_USER_ID -m -U -s /bin/zsh developer
USER developer
WORKDIR /home/developer/app