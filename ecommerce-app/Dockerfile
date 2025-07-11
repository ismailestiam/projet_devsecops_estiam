# Utilisation d'une image Python légère basée sur Alpine
FROM python:3.11-alpine

# Variables d'environnement
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1
ENV DJANGO_SETTINGS_MODULE=core.settings

# Installation des dépendances système nécessaires
RUN apk add --no-cache \
    gcc \
    musl-dev \
    postgresql-dev \
    jpeg-dev \
    zlib-dev \
    freetype-dev \
    lcms2-dev \
    openjpeg-dev \
    tiff-dev \
    tk-dev \
    tcl-dev \
    harfbuzz-dev \
    fribidi-dev \
    libimagequant-dev \
    libxcb-dev \
    libpng-dev \
    nodejs \
    npm \
    curl

# Définition du répertoire de travail
WORKDIR /app

# Copie et installation des dépendances Python
COPY requirements.txt .
RUN pip install --upgrade pip --no-cache-dir
RUN pip install --no-cache-dir -r requirements.txt

# Copie du code source
COPY . .

# Installation des dépendances Node.js et build des assets
RUN npm ci --only=production
RUN npm run build

# Collecte des fichiers statiques Django
RUN python manage.py collectstatic --no-input

# Préparation de la base de données
RUN python manage.py makemigrations
RUN python manage.py migrate

# Création de l'utilisateur non-root pour la sécurité
RUN adduser -D -s /bin/sh appuser
RUN chown -R appuser:appuser /app
USER appuser

# Exposition du port 8000
EXPOSE 8000

# Commande de démarrage avec Gunicorn
CMD ["gunicorn", "--bind", "0.0.0.0:8000", "--workers", "3", "core.wsgi:application"]

