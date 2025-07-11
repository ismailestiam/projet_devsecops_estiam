# Utilisation d'une image Python légère basée sur Alpine
FROM python:3.11-alpine

# Variables d'environnement
ENV PYTHONDONTWRITEBYTECODE=1
ENV PYTHONUNBUFFERED=1

# Définition du répertoire de travail
WORKDIR /app

# Copie et installation des dépendances
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copie du code source
COPY . .

# Création de l'utilisateur non-root pour la sécurité
RUN adduser -D -s /bin/sh appuser
RUN chown -R appuser:appuser /app
USER appuser

# Exposition du port 5000
EXPOSE 5000

# Commande de démarrage
CMD ["python", "app.py"]

