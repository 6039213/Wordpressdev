FROM wordpress:php8.2-apache

# Install additional PHP extensions and tools
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    less \
    curl \
    && docker-php-ext-install zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

# Enable Apache modules
RUN a2enmod rewrite expires headers

# Configure Apache DirectoryIndex
RUN echo '<Directory /var/www/html/>\n\
    DirectoryIndex index.php index.html\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/wordpress.conf \
    && a2enconf wordpress

# Set recommended PHP.ini settings
RUN { \
    echo 'upload_max_filesize = 64M'; \
    echo 'post_max_size = 64M'; \
    echo 'memory_limit = 512M'; \
    echo 'max_execution_time = 300'; \
    echo 'max_input_time = 300'; \
    } > /usr/local/etc/php/conf.d/uploads.ini

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
