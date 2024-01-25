FROM php:apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

ENV http_proxy=http://nip:senha@proxy-gastao.mb:6060
ENV https_proxy=http://nip:senha@proxy-gastao.mb:6060

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


###### Estação de trabalho MB Ubuntu ############

# Copiar o arquivo de configuração de proxy para o APT para dentro da imagem
COPY apt.conf /etc/apt/apt.conf

###### Fim -- Estação de trabalho MB Ubuntu ############


# Copie os arquivos do seu projeto para o diretório de trabalho no contêiner
#COPY ./app/. /var/www/html

# Set the ownership and permissions for the /var/www/html directory
#RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install postgres and PDO
RUN apt-get -y update && apt-get install -y libpq-dev zip
RUN docker-php-ext-install pgsql pdo_pgsql
RUN a2enmod rewrite

# Install Composer (Não consegui baixar pelo proxy, baixei o arquivo do composer e coloquei na pasta app)
RUN curl --sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer