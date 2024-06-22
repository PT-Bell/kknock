# 베이스 이미지 설정
FROM php:7.4-apache

# Apache 설정 파일 복사
COPY config/apache/httpd.conf /etc/apache2/sites-available/000-default.conf

# PHP 설정 파일 복사
COPY config/php/php.ini /usr/local/etc/php

# 필요한 패키지 설치 (예: MySQL 확장)
RUN docker-php-ext-install mysqli

# Apache 모듈 활성화 (필요 시)
RUN a2enmod rewrite

# 포트 설정
EXPOSE 80

