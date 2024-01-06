FROM php:8.3-apache

# 安装 MySQLi 扩展
RUN docker-php-ext-install mysqli

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
COPY ports.conf /etc/apache2/ports.conf

WORKDIR /var/www/html/web/crm
EXPOSE 8080
CMD ["apache2-foreground"]
