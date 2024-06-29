# Use the official PHP image as base
FROM php:7.4-apache

# Install necessary extensions and tools
RUN docker-php-ext-install mysqli pdo pdo_mysql && \
    apt-get update && \
    apt-get install -y libpng-dev libjpeg-dev && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd

# Create the directory and copy project files to the container
RUN mkdir -p /github/kknock
COPY . /github/kknock/

# Update the Apache configuration
RUN sed -i 's|/var/www/html|/github/kknock|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/github/kknock|g' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /github/kknock

# Expose port 80
EXPOSE 80

