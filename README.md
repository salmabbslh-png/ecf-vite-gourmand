# Saveurs de Bordeaux

J'ai réalisé ce projet pendant ma formation Développeur Web et Web Mobile chez Studi. Saveurs de Bordeaux est le site d'un traiteur bordelais : les visiteurs peuvent découvrir les menus et passer commande en ligne, et l'équipe gère les commandes, les menus et les avis depuis son espace.

Le site est en ligne ici : https://benbousselham.alwaysdata.net

## Ce que fait l'application

- Les visiteurs consultent les menus et peuvent les filtrer sans recharger la page (prix, thème, régime, nombre de personnes).
- Les clients connectés passent commande. Le prix est calculé automatiquement, avec la remise et les frais de livraison. Ils peuvent ensuite suivre leur commande et laisser un avis.
- Les employés gèrent les menus, les plats et les horaires, font avancer les commandes d'un statut à l'autre et valident les avis.
- L'administrateur crée ou désactive les comptes des employés et consulte les statistiques des commandes.

## Technologies utilisées

- HTML, CSS et Bootstrap pour l'interface
- JavaScript pour les filtres, les graphiques (Chart.js) et le chargement des avis (fetch)
- PHP avec PDO pour le côté serveur, et PHPMailer pour les emails
- MySQL pour les données de l'application, MongoDB pour les statistiques
- MAMP pour développer en local, AlwaysData pour la mise en ligne

## Installer le projet en local

1. Installer MAMP et placer le projet dans le dossier htdocs.
2. Dans phpMyAdmin, créer une base nommée saveurs_bordeaux, puis importer le fichier sql/script_creation_saveurs_bordeaux.sql. Ce script crée les 11 tables et ajoute des données de test.
3. Installer les dépendances avec la commande composer install.
4. Ouvrir http://localhost:8888/pages/index.php dans le navigateur.

## Comptes de test

- Administrateur : admin@saveurs-bordeaux.fr / Admin#Saveurs2026
- Employé : employe@saveurs-bordeaux.fr / Employe#Saveurs2026
- Client : client@saveurs-bordeaux.fr / Client#Saveurs2026

Ces comptes sont créés automatiquement quand on importe le script SQL dans une base vide. Ils ne fonctionnent pas sur le site en ligne, qui utilise sa propre base de données.

## Sécurité

Les mots de passe sont hachés, toutes les requêtes utilisent des requêtes préparées, les données affichées sont protégées contre les failles XSS, et chaque espace est réservé au bon rôle. Le lien de réinitialisation du mot de passe ne fonctionne qu'une fois et expire au bout d'une heure.

## Ce que j'aimerais améliorer

- Les emails partent pour l'instant vers une boîte de test (Mailtrap).
- Les statistiques MongoDB fonctionnent en local, mais pas sur l'hébergement gratuit.
- Le code serveur est écrit en PHP procédural. J'aimerais le réorganiser en MVC, avec de la programmation orientée objet.

## Mini-projet

J'ai aussi réalisé un mini-projet séparé, Avis clients, pour travailler avec MongoDB dans des conteneurs Docker.

Salma Benbousselham, novembre 2026