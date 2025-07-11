# Utilisation d'une image PHP légère basée sur Alpine
FROM php:8.1-cli-alpine

# Installation des extensions PHP nécessaires
RUN apk add --no-cache \
    curl \
    && docker-php-ext-install \
    opcache

# Définition du répertoire de travail
WORKDIR /app

# Copie des fichiers de l'application
COPY . .

# Installation des dépendances avec Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader \
    && rm /usr/local/bin/composer

# Création de l'utilisateur non-root pour la sécurité
RUN adduser -D -s /bin/sh appuser
RUN chown -R appuser:appuser /app
USER appuser

# Exposition du port 8000
EXPOSE 8000

# Variables d'environnement
ENV PHP_CLI_SERVER_WORKERS=4

# Commande de démarrage
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]

