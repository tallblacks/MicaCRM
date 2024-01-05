FROM php:8.3-apache

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]
