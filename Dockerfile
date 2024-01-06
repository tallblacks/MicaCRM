FROM php:7.2-apache

# 安装 MySQLi 扩展和 MariaDB 客户端工具
RUN docker-php-ext-install mysqli \
    && apt-get update \
    && apt-get install -y mariadb-client \
    && rm -rf /var/lib/apt/lists/*

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
COPY ports.conf /etc/apache2/ports.conf

WORKDIR /var/www/html/web/crm
EXPOSE 8080
CMD ["apache2-foreground"]
