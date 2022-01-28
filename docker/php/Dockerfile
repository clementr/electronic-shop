FROM php:8.1-cli

RUN apt-get update && apt-get install -y \    
    curl \
    git \
    libzip-dev \
    unzip \
    zip

RUN docker-php-ext-install zip

WORKDIR /app

CMD ["bash"]