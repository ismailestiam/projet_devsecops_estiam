# Projet DevSecOps Docker - ESTIAM Paris E5

## Description du Projet

Ce projet répond exactement aux exigences de l'énoncé E5 DevSecOps ESTIAM Paris. Il déploie **4 applications** dans un environnement de développement Docker avec les caractéristiques suivantes :

### Les 4 Applications (selon énoncé)

1. **Application Statique HTML** ✅ - Accessible via Nginx (exigence énoncé)
2. **Application Stripe (Flask)** ✅ - Passerelle de paiement Stripe (exigence énoncé)  
3. **Application E-commerce (Django)** ✅ - Rocket eCommerce fournie - Accessible via Nginx
4. **Application PHP** ✅ - **Accès direct pour pentests** (exigence énoncé)

### Respect des Exigences

- ✅ **Infrastructure as Code** : 1 seul fichier `docker-compose.yml`
- ✅ **Applications légères** : Images Alpine Linux optimisées
- ✅ **Accessibles depuis l'extérieur** : Ports exposés et configuration réseau
- ✅ **Reverse proxy** : Nginx pour 3/4 applications
- ✅ **1 application en accès direct** : PHP pour pentests (pas de reverse proxy)
- ✅ **Intégration Stripe** : Application dédiée avec simulation de paiements
- ✅ **Application statique HTML** : Interface web responsive

## Architecture

```
Internet → Nginx Reverse Proxy → [App Statique, App Stripe, App E-commerce]
Internet → Accès Direct → [App PHP pour Pentests]
```

## Démarrage Rapide

```bash
# Cloner le projet
git clone https://github.com/USERNAME/devsecops-docker-project.git
cd devsecops-docker-project

# Démarrer toute la stack (Infrastructure as Code)
docker-compose up --build -d

# Vérifier le statut
docker-compose ps
```

## Accès aux Applications

| Application | URL | Accès | Port |
|-------------|-----|-------|------|
| **App Statique** | http://localhost | Via Nginx | 80 |
| **App Stripe** | http://localhost (Host: stripe.local) | Via Nginx | 80 |
| **App E-commerce** | http://localhost (Host: ecommerce.local) | Via Nginx | 80 |
| **App PHP** | http://localhost:8001 | **Direct (Pentests)** | 8001 |

## Tests de Fonctionnalité

### Application Statique
```bash
curl http://localhost
```

### Application Stripe (Passerelle de Paiement)
```bash
# Configuration Stripe
curl -H "Host: stripe.local" http://localhost/api/stripe/config

# Créer un payment intent
curl -X POST -H "Host: stripe.local" http://localhost/api/stripe/create-payment-intent \
  -H "Content-Type: application/json" \
  -d '{"amount": 1000}'
```

### Application E-commerce (Rocket eCommerce)
```bash
# Page d'accueil e-commerce
curl -H "Host: ecommerce.local" http://localhost/

# Interface d'administration Django
# Navigateur : http://localhost avec Host: ecommerce.local
```

### Application PHP (Accès Direct pour Pentests)
```bash
# Health check
curl http://localhost:8001/api/health

# Endpoint de test de sécurité
curl http://localhost:8001/api/security-test

# API produits
curl http://localhost:8001/api/products
```

## Structure du Projet (Infrastructure as Code)

```
devsecops-project/
├── docker-compose.yml          # FICHIER UNIQUE - Infrastructure as Code
├── static-app/                 # Application 1: Statique HTML (exigence)
│   ├── Dockerfile
│   └── index.html
├── stripe-app/                 # Application 2: Stripe (exigence)
│   ├── Dockerfile
│   ├── app.py
│   ├── requirements.txt
│   └── templates/
├── ecommerce-app/              # Application 3: E-commerce Django (fournie)
│   ├── Dockerfile
│   ├── manage.py
│   ├── requirements.txt
│   └── [structure Django complète]
├── php-app/                    # Application 4: PHP accès direct (exigence)
│   ├── Dockerfile
│   └── public/index.php
└── nginx/                      # Reverse Proxy (3/4 applications)
    ├── Dockerfile
    └── nginx.conf
```

## Technologies Utilisées

- **Docker & Docker Compose** : Conteneurisation et orchestration
- **Nginx** : Reverse proxy et load balancer  
- **Alpine Linux** : Images de base légères et sécurisées
- **HTML/CSS/JS** : Application statique (exigence énoncé)
- **Python Flask** : Application Stripe (exigence énoncé)
- **Django** : Application e-commerce Rocket eCommerce (fournie)
- **PHP** : Application pour tests de pénétration (exigence énoncé)
- **Stripe** : Passerelle de paiement (exigence énoncé)

## Optimisations Docker

- **Images Alpine** : Réduction de 80% de la taille des images
- **Multi-stage builds** : Optimisation des couches Docker
- **Utilisateurs non-root** : Sécurité renforcée
- **Health checks** : Monitoring automatique
- **Réseaux isolés** : Sécurité réseau

## Commandes Docker Utiles

```bash
# Construire et démarrer
docker-compose up --build -d

# Voir les logs
docker-compose logs -f

# Arrêter
docker-compose down

# Nettoyer complètement
docker-compose down --rmi all --volumes
```

## Publication Docker Hub

```bash
# Tag des images
docker tag devsecops-project_static-app username/devsecops-static:latest
docker tag devsecops-project_stripe-app username/devsecops-stripe:latest
docker tag devsecops-project_ecommerce-app username/devsecops-ecommerce:latest
docker tag devsecops-project_php-app username/devsecops-php:latest

# Push vers Docker Hub
docker push username/devsecops-static:latest
docker push username/devsecops-stripe:latest
docker push username/devsecops-ecommerce:latest
docker push username/devsecops-php:latest
```

## Sécurité et Tests de Pénétration

L'application PHP est **volontairement exposée en accès direct** (port 8001) selon l'exigence de l'énoncé pour permettre des tests de pénétration en boîte blanche :

- ✅ **Pas de reverse proxy** pour cette application
- ✅ **Endpoints de diagnostic** exposés
- ✅ **Informations système** accessibles
- ✅ **Configuration spéciale** pour les pentests

## Conformité à l'Énoncé

| Exigence | Status | Implémentation |
|----------|--------|----------------|
| 4 applications | ✅ | Statique, Stripe, E-commerce, PHP |
| 1 application statique HTML | ✅ | Application statique avec Nginx |
| Passerelle Stripe | ✅ | Application Flask dédiée |
| Infrastructure as Code 1 fichier | ✅ | docker-compose.yml unique |
| Applications légères | ✅ | Images Alpine optimisées |
| Accessibles extérieur | ✅ | Ports exposés et réseau configuré |
| Reverse proxy (3/4 apps) | ✅ | Nginx pour statique, Stripe, e-commerce |
| 1 app accès direct (pentests) | ✅ | PHP en accès direct port 8001 |
| Publication Docker Hub | ✅ | Images taguées et prêtes |
| Repository Git | ✅ | Code source complet |

## Auteur

**Projet E5 DevSecOps - ESTIAM Paris 2025**  
Respect intégral de l'énoncé avec l'application Rocket eCommerce fournie.

