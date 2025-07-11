# Projet DevSecOps Docker - ESTIAM Paris

## Description

Ce projet démontre une architecture DevSecOps complète avec 4 applications conteneurisées :

1. **Application Statique** (HTML/CSS/JS) - Port 80 via Nginx
2. **Application Python Flask** avec intégration Stripe - Port 5000 via Nginx  
3. **Application Node.js Express** - Port 3000 via Nginx
4. **Application PHP** - Port 8000 (Accès direct pour pentests)

## Architecture

- **Reverse Proxy** : Nginx pour router le trafic vers 3 des 4 applications
- **Conteneurisation** : Docker avec images optimisées Alpine Linux
- **Orchestration** : Docker Compose (Infrastructure as Code)
- **Sécurité** : Une application exposée directement pour les tests de pénétration
- **Intégration** : Passerelle de paiement Stripe (simulation)

## Démarrage Rapide

```bash
# Cloner le projet
git clone <repository-url>
cd devsecops-project

# Construire et démarrer toute la stack
docker-compose up --build -d

# Vérifier le statut des conteneurs
docker-compose ps

# Voir les logs
docker-compose logs -f
```

## Accès aux Applications

- **Application Statique** : http://localhost
- **Application Python (Stripe)** : http://localhost (Host: python-app.local)
- **Application Node.js** : http://localhost (Host: nodejs-app.local)  
- **Application PHP (Direct)** : http://localhost:8000
- **Nginx Status** : http://localhost (Host: status.local)

## Tests des APIs

### Python Flask (Stripe)
```bash
# Configuration Stripe
curl http://localhost/api/stripe/config

# Créer un payment intent
curl -X POST http://localhost/api/stripe/create-payment-intent \
  -H "Content-Type: application/json" \
  -d '{"amount": 1000}'
```

### Node.js Express
```bash
# Health check
curl http://localhost/api/health

# Liste des utilisateurs
curl http://localhost/api/users

# Créer un utilisateur
curl -X POST http://localhost/api/users \
  -H "Content-Type: application/json" \
  -d '{"name": "Test User", "email": "test@example.com"}'
```

### PHP (Accès Direct)
```bash
# Health check
curl http://localhost:8000/api/health

# Test de sécurité
curl http://localhost:8000/api/security-test

# Liste des produits
curl http://localhost:8000/api/products
```

## Commandes Docker Utiles

```bash
# Construire les images
docker-compose build

# Démarrer en mode détaché
docker-compose up -d

# Arrêter tous les services
docker-compose down

# Voir les logs d'un service spécifique
docker-compose logs -f nginx

# Redémarrer un service
docker-compose restart python-app

# Exécuter une commande dans un conteneur
docker-compose exec python-app /bin/sh
```

## Publication sur Docker Hub

```bash
# Tag des images
docker tag devsecops-project_static-app username/devsecops-static:latest
docker tag devsecops-project_python-app username/devsecops-python:latest
docker tag devsecops-project_nodejs-app username/devsecops-nodejs:latest
docker tag devsecops-project_php-app username/devsecops-php:latest
docker tag devsecops-project_nginx username/devsecops-nginx:latest

# Push vers Docker Hub
docker push username/devsecops-static:latest
docker push username/devsecops-python:latest
docker push username/devsecops-nodejs:latest
docker push username/devsecops-php:latest
docker push username/devsecops-nginx:latest
```

## Sécurité et Tests de Pénétration

L'application PHP est volontairement exposée directement (port 8000) pour permettre des tests de pénétration en boîte blanche. Elle inclut :

- Endpoint `/api/security-test` avec informations système exposées
- Headers de requête visibles
- Variables serveur accessibles
- Pas de reverse proxy pour un accès direct

## Technologies Utilisées

- **Docker** : Conteneurisation
- **Docker Compose** : Orchestration
- **Nginx** : Reverse proxy et load balancer
- **Python Flask** : API backend avec CORS
- **Node.js Express** : API RESTful
- **PHP** : Application native avec routing
- **Stripe** : Simulation de passerelle de paiement
- **Alpine Linux** : Images de base légères

## Structure du Projet

```
devsecops-project/
├── docker-compose.yml          # Orchestration principale
├── .dockerignore              # Fichiers à ignorer
├── README.md                  # Documentation
├── static-app/                # Application statique
│   ├── Dockerfile
│   └── index.html
├── python-app/                # Application Flask
│   ├── Dockerfile
│   └── stripe-payment-app/    # Code source Flask
├── nodejs-app/                # Application Express
│   ├── Dockerfile
│   ├── package.json
│   ├── app.js
│   └── public/
├── php-app/                   # Application PHP
│   ├── Dockerfile
│   ├── composer.json
│   └── public/
└── nginx/                     # Configuration Nginx
    ├── Dockerfile
    └── nginx.conf
```

## Optimisations Docker

- Images basées sur Alpine Linux (légères)
- Multi-stage builds pour réduire la taille
- Utilisateurs non-root pour la sécurité
- Health checks pour tous les services
- Réseaux isolés avec Docker Compose
- Variables d'environnement pour la configuration

## Monitoring et Logs

```bash
# Voir l'utilisation des ressources
docker stats

# Logs en temps réel
docker-compose logs -f --tail=100

# Inspecter un conteneur
docker inspect devsecops-nginx

# Voir les processus dans un conteneur
docker-compose exec nginx ps aux
```

## Dépannage

### Problèmes de ports
```bash
# Vérifier les ports utilisés
netstat -tulpn | grep :80
netstat -tulpn | grep :8000
```

### Problèmes de réseau
```bash
# Inspecter le réseau Docker
docker network ls
docker network inspect devsecops-project_devsecops-network
```

### Problèmes de build
```bash
# Rebuild sans cache
docker-compose build --no-cache

# Nettoyer les images inutilisées
docker system prune -a
```

## Auteur

Projet réalisé dans le cadre du cours DevSecOps - ESTIAM Paris 2025

