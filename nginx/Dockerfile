# Utilisation d'une image Nginx légère basée sur Alpine
FROM nginx:alpine

# Copie de la configuration personnalisée
COPY nginx.conf /etc/nginx/nginx.conf

# Création des répertoires de logs
RUN mkdir -p /var/log/nginx

# Exposition du port 80
EXPOSE 80

# Nginx démarre automatiquement avec l'image de base

