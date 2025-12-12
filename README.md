# 🏋️ Système de Gestion de Salle de Sport

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-cyan?style=for-the-badge&logo=tailwindcss" alt="TailwindCSS">
</p>

Un système complet de gestion de salle de sport développé avec Laravel, offrant une interface moderne et intuitive pour gérer les membres, les paiements, les cours, l'équipement et bien plus encore.

## ✨ Fonctionnalités Principales

### 👥 Gestion des Membres
- ✅ Inscription et profils complets des membres
- ✅ Gestion des adhésions et statuts
- ✅ Système de QR codes pour l'accès
- ✅ Historique des présences
- ✅ Filtres et recherche avancée

### 💳 Système de Paiements
- ✅ Paiement rapide depuis la liste des membres
- ✅ Gestion complète des transactions
- ✅ Méthodes multiples (espèces, carte, en ligne)
- ✅ Statistiques et rapports financiers
- ✅ Historique détaillé par membre

### 🏃‍♂️ Gestion des Cours
- ✅ Planification et réservation de cours
- ✅ Gestion des coachs et programmes
- ✅ Système de réservation en ligne
- ✅ Suivi des participants

### 🏋️‍♀️ Équipement
- ✅ Inventaire complet de l'équipement
- ✅ Suivi de la maintenance
- ✅ Gestion des emplacements
- ✅ Historique des réparations

### 🏆 Défis et Challenges
- ✅ Création de défis fitness
- ✅ Suivi des participations
- ✅ Système de récompenses
- ✅ Classements et statistiques

### 🔐 Sécurité et Rôles
- ✅ Authentification sécurisée
- ✅ Gestion des rôles (Admin, Coach, Membre)
- ✅ Protection contre les injections SQL
- ✅ Middleware de sécurité avancé
- ✅ Rate limiting et logging

## 🚀 Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- Node.js et npm
- MySQL 8.0 ou supérieur

### Étapes d'installation

1. **Cloner le repository**
```bash
git clone https://github.com/Mosbah-KhalfAllah/GYM-_Management.git
cd gym-management
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances JavaScript**
```bash
npm install
npm run build
```

4. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configuration de la base de données**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gym_management
DB_USERNAME=votre_username
DB_PASSWORD=votre_password
```

6. **Migration et seeding**
```bash
php artisan migrate --seed
```

7. **Lancer le serveur**
```bash
php artisan serve
```

## 👤 Comptes par Défaut

### Administrateur
- **Email**: admin@gym.com
- **Mot de passe**: password

### Coach
- **Email**: coach@gym.com
- **Mot de passe**: password

### Membre
- **Email**: member@gym.com
- **Mot de passe**: password

## 📱 Interface Utilisateur

### Dashboard Admin
- Vue d'ensemble des statistiques
- Graphiques de revenus et fréquentation
- Alertes et notifications
- Accès rapide aux fonctionnalités

### Interface Coach
- Gestion des cours et programmes
- Suivi des membres assignés
- Planning et réservations
- Outils d'entraînement

### Espace Membre
- Profil personnel et adhésion
- Réservation de cours
- Suivi des progrès
- Participation aux défis

## 🛠️ Technologies Utilisées

### Backend
- **Laravel 11.x** - Framework PHP moderne
- **MySQL** - Base de données relationnelle
- **Eloquent ORM** - Mapping objet-relationnel
- **Laravel Sanctum** - Authentification API

### Frontend
- **Blade Templates** - Moteur de templates Laravel
- **TailwindCSS** - Framework CSS utilitaire
- **Alpine.js** - Framework JavaScript léger
- **Font Awesome** - Icônes

### Outils de Développement
- **Laravel Breeze** - Authentification
- **Laravel Cashier** - Gestion des paiements
- **Intervention Image** - Manipulation d'images
- **Simple QrCode** - Génération de QR codes

## 📊 Structure de la Base de Données

### Tables Principales
- `users` - Utilisateurs (membres, coachs, admins)
- `memberships` - Adhésions et abonnements
- `payments` - Transactions et paiements
- `classes` - Cours et sessions
- `equipment` - Équipement de la salle
- `challenges` - Défis et challenges
- `attendances` - Présences et check-ins
- `programs` - Programmes d'entraînement

## 🔧 Configuration Avancée

### Variables d'Environnement
```env
# Application
APP_NAME="Gym Management"
APP_ENV=production
APP_DEBUG=false

# Paiements (optionnel)
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret

# Email (optionnel)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
```

### Tâches Cron (optionnel)
```bash
# Ajouter au crontab pour les tâches automatiques
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 📈 Fonctionnalités Avancées

### Rapports et Analytics
- Revenus mensuels et annuels
- Taux de fréquentation
- Analyse des membres actifs
- Rapports d'équipement

### Notifications
- Alertes d'expiration d'adhésion
- Rappels de cours
- Notifications de maintenance
- Messages système

### API REST
- Endpoints pour applications mobiles
- Authentification par token
- Documentation Swagger (optionnel)

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 License

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 📞 Support

Pour toute question ou support :
- 📧 Email : support@gym-management.com
- 🐛 Issues : [GitHub Issues](https://github.com/votre-username/gym-management/issues)
- 📖 Documentation : [Wiki](https://github.com/votre-username/gym-management/wiki)

---

<p align="center">
  Développé avec ❤️ pour la communauté fitness
</p>