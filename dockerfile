FROM wordpress:latest

RUN apt-get update && apt-get install -y less mariadb-client curl \
  && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
  && chmod +x wp-cli.phar \
  && mv wp-cli.phar /usr/local/bin/wp

# Ensure PHP and dependencies are installed
RUN curl -sS https://getcomposer.org/installer | php -- --2 \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh
