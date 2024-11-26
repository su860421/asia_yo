# 使用 PHP 8.3 官方镜像
FROM php:8.3-fpm

# 设置工作目录
WORKDIR /var/www

# 安装系统依赖和 PHP 扩展
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install redis && docker-php-ext-enable redis

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 复制应用代码
COPY . /var/www

# 设置权限
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# 暴露应用运行端口
EXPOSE 9000

# 启动 PHP-FPM
CMD ["php-fpm"]
