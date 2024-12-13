# Documentation du projet Symfony - Gestion des réservations

Ce projet est un Travail Pratique réalisé à l'université Gustave Eiffel à Meaux.  
Il a été conçu par **Ilyès Bouziane**, étudiant en BUT MMI, pour apprendre et démontrer les principes de gestion de réservations dans une salle d'événements. Le projet utilise Symfony et inclut des fonctionnalités comme l'authentification sécurisée, la gestion des rôles (administrateur et utilisateur) et une API pour les réservations.  

## Sommaire

1. [Introduction](#introduction)
2. [Comment démarrer le projet ?](#comment-démarrer-le-projet)
   - [Prérequis](#1-prérequis)
   - [Étapes pour cloner et lancer le projet](#2-étapes-pour-cloner-et-lancer-le-projet)
3. [Liste des routes disponibles](#3-liste-des-routes-disponibles)
4. [Exemples de requêtes API (JSON)](#4-exemples-de-requêtes-api-json)
5. [Tester le projet](#5-tester-le-projet)
6. [Auteur](#6-auteur)
---

## Introduction

Ce projet a pour objectif de permettre aux utilisateurs :
- De réserver des créneaux horaires dans une salle d'événements.
- De consulter leurs réservations.
- D'utiliser une API pour gérer les réservations via des requêtes JSON.

**Note** : Le projet n'est pas terminé.  Certaines fonctionnalités ne sont pas entièrement opérationnelles, mais cette documentation est rédigée comme si le projet était entièrement finalisé pour offrir une vue d'ensemble de son fonctionnement prévu.

---

## Comment démarrer le projet ?

### 1. Prérequis

Avant de commencer, assurez-vous que les outils suivants sont installés :
- **PHP** (>= 8.1)
- **Composer**
- **Symfony CLI**
- **MySQL** ou une base de données compatible

### 2. Étapes pour cloner et lancer le projet

1. **Cloner le projet** :
   ```bash
   git clone https://github.com/IlyesBo/tp_Reservation.git
   cd tp_Reservation

    Installer les dépendances :

composer install

Configurer la base de données :

    Créez un fichier .env.local :

cp .env .env.local

Ajoutez les informations de connexion :

    DATABASE_URL="mysql://username:password@127.0.0.1:3306/tpReserv"

Initialiser la base de données :

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

Lancer le serveur Symfony :

    symfony server:start

    L'application sera accessible à http://localhost:8000.

## 3. Liste des routes disponibles
Routes utilisateur

    Page de connexion
        URL : /login
        Méthode : GET
        Description : Permet aux utilisateurs de se connecter.

    Profil utilisateur
        URL : /profile
        Méthode : GET
        Description : Affiche le profil de l'utilisateur connecté.

    Faire une réservation
        URL : /reserve
        Méthode : GET, POST
        Description : Permet à l'utilisateur de créer une réservation via un formulaire.

    Historique des réservations
        URL : /user/reservations
        Méthode : GET
        Description : Liste toutes les réservations effectuées par l'utilisateur connecté.

    Créer une réservation via API
        URL : /reservation
        Méthode : POST
        Description : API pour créer une réservation avec des données en JSON.

## 4. Exemples de requêtes API (JSON)
1. Créer une réservation

URL : /reservation
Méthode : POST
Exemple de corps de la requête :

{
    "date": "2024-12-15",
    "timeSlot": "14:00",
    "eventName": "Conférence Symfony"
}

Réponse (succès) :

{
    "status": "success",
    "message": "Réservation effectuée avec succès"
}

Réponse (erreur) :

{
    "status": "error",
    "message": "Le créneau horaire est déjà pris"
}

2. Récupérer les réservations de l'utilisateur connecté

URL : /user/reservations
Méthode : GET

Réponse (succès) :

[
    {
        "eventName": "Conférence Symfony",
        "date": "2024-12-15",
        "timeSlot": "14:00"
    },
    {
        "eventName": "Atelier Symfony",
        "date": "2024-12-16",
        "timeSlot": "10:00"
    }
]

## 5. Tester le projet
Tester manuellement avec Postman

    Installez Postman.
    Créez une requête POST à l'URL http://localhost:8000/reservation avec le corps de la requête au format JSON.
    Envoyez des requêtes GET à http://localhost:8000/user/reservations pour voir les données.

Tester avec curl

Créer une réservation :

curl -X POST http://localhost:8000/reservation \
     -H "Content-Type: application/json" \
     -d '{"date": "2024-12-15", "timeSlot": "14:00", "eventName": "Conférence Symfony"}'

Récupérer les réservations :

curl http://localhost:8000/user/reservations

## 6. Auteur

Ce projet a été conçu et réalisé par Ilyès Bouziane.
