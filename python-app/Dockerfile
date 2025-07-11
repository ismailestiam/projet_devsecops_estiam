# Utilisation d'une image Python légère basée sur Alpine
FROM python:3.11-alpine

# Définition du répertoire de travail
WORKDIR /app

# Copie des fichiers de dépendances
COPY stripe-payment-app/requirements.txt .

# Installation des dépendances
RUN pip install --no-cache-dir -r requirements.txt

# Copie du code source
COPY stripe-payment-app/src ./src

# Création de l'utilisateur non-root pour la sécurité
RUN adduser -D -s /bin/sh appuser
RUN chown -R appuser:appuser /app
USER appuser

# Exposition du port 5000
EXPOSE 5000

# Variables d'environnement
ENV FLASK_APP=src/main.py
ENV FLASK_ENV=production

# Commande de démarrage
CMD ["python", "src/main.py"]

