# Guide des Commandes Git - Projet DevSecOps

## Pour l'Étudiant : Publier le Projet

### 1. Configuration Git (si pas déjà fait)
```bash
git config --global user.name "Votre Nom"
git config --global user.email "votre.email@example.com"
```

### 2. Initialisation et Premier Commit (déjà fait)
```bash
# Déjà exécuté
git init

# Ajouter tous les fichiers
git add .

# Premier commit
git commit -m "Initial commit: Projet DevSecOps Docker avec 4 applications"
```

### 3. Créer un Repository sur GitHub
1. Aller sur https://github.com
2. Cliquer sur "New repository"
3. Nommer le repository : `devsecops-docker-project`
4. Laisser en Public pour que le prof puisse y accéder
5. Ne pas initialiser avec README (on a déjà nos fichiers)
6. Cliquer "Create repository"

### 4. Lier le Repository Local à GitHub
```bash
# Remplacer USERNAME par votre nom d'utilisateur GitHub
git remote add origin https://github.com/USERNAME/devsecops-docker-project.git

# Renommer la branche principale en main (optionnel mais recommandé)
git branch -M main

# Pousser le code vers GitHub
git push -u origin main
```

### 5. Vérifications Finales
```bash
# Vérifier le statut
git status

# Voir l'historique des commits
git log --oneline

# Vérifier les remotes
git remote -v
```

---

## Pour le Professeur : Cloner et Tester le Projet

### 1. Cloner le Repository
```bash
# Cloner le projet
git clone https://github.com/USERNAME/devsecops-docker-project.git

# Entrer dans le répertoire
cd devsecops-docker-project
```

### 2. Vérifier les Prérequis
```bash
# Vérifier Docker
docker --version
docker-compose --version

# Vérifier que Docker est démarré
docker ps
```

### 3. Démarrer l'Infrastructure Complète
```bash
# Construire et démarrer tous les services
docker-compose up --build -d

# Vérifier que tous les conteneurs sont démarrés
docker-compose ps
```

### 4. Tester les Applications

#### Application Statique (via Nginx)
```bash
# Test avec curl
curl http://localhost

# Ou ouvrir dans le navigateur : http://localhost
```

#### Application Python Flask (Stripe)
```bash
# Test de l'API Stripe
curl http://localhost/api/stripe/config

# Test de création de payment intent
curl -X POST http://localhost/api/stripe/create-payment-intent \
  -H "Content-Type: application/json" \
  -d '{"amount": 1000}'

# Ou avec Host header pour accès direct
curl -H "Host: python-app.local" http://localhost
```

#### Application Node.js Express
```bash
# Test health check
curl -H "Host: nodejs-app.local" http://localhost/api/health

# Test API utilisateurs
curl -H "Host: nodejs-app.local" http://localhost/api/users

# Créer un utilisateur
curl -X POST -H "Host: nodejs-app.local" http://localhost/api/users \
  -H "Content-Type: application/json" \
  -d '{"name": "Test User", "email": "test@example.com"}'
```

#### Application PHP (Accès Direct - pour Pentests)
```bash
# Test health check
curl http://localhost:8000/api/health

# Test endpoint de sécurité (spécial pentests)
curl http://localhost:8000/api/security-test

# Test API produits
curl http://localhost:8000/api/products

# Ou ouvrir dans le navigateur : http://localhost:8000
```

#### Nginx Status
```bash
# Statut Nginx
curl -H "Host: status.local" http://localhost
```

### 5. Voir les Logs
```bash
# Logs de tous les services
docker-compose logs

# Logs d'un service spécifique
docker-compose logs nginx
docker-compose logs python-app
docker-compose logs nodejs-app
docker-compose logs php-app

# Logs en temps réel
docker-compose logs -f
```

### 6. Monitoring et Debug
```bash
# Voir l'utilisation des ressources
docker stats

# Inspecter un conteneur
docker inspect devsecops-nginx

# Exécuter des commandes dans un conteneur
docker-compose exec nginx /bin/sh
docker-compose exec python-app /bin/sh
```

### 7. Arrêter l'Infrastructure
```bash
# Arrêter tous les services
docker-compose down

# Arrêter et supprimer les volumes
docker-compose down -v

# Nettoyer complètement (images, conteneurs, réseaux)
docker-compose down --rmi all --volumes --remove-orphans
```

---

## Tests de Fonctionnalité Recommandés

### 1. Test de l'Infrastructure as Code
- Vérifier que `docker-compose up` démarre tous les services
- Confirmer que tous les health checks passent
- Valider la communication inter-services

### 2. Test du Reverse Proxy
- Vérifier que Nginx route correctement vers chaque application
- Tester les headers de sécurité
- Valider l'isolation des services

### 3. Test de l'Intégration Stripe
- Tester la configuration Stripe
- Simuler une transaction de paiement
- Vérifier la gestion des erreurs

### 4. Test de Sécurité (Application PHP)
- Accéder directement à l'application PHP (port 8000)
- Tester l'endpoint `/api/security-test`
- Vérifier l'exposition des informations système

### 5. Test de Performance
- Vérifier la légèreté des images Docker
- Tester la réactivité des applications
- Valider l'utilisation des ressources

---

## Informations Importantes

- **URL du Repository** : https://github.com/USERNAME/devsecops-docker-project
- **Ports utilisés** : 
  - 80 (Nginx + Applications proxifiées)
  - 8000 (Application PHP directe)
- **Temps de démarrage estimé** : 2-3 minutes
- **Prérequis** : Docker et Docker Compose installés

## Support

En cas de problème :
1. Vérifier que Docker est démarré
2. Vérifier que les ports 80 et 8000 sont libres
3. Consulter les logs avec `docker-compose logs`
4. Redémarrer avec `docker-compose down && docker-compose up --build`

