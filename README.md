# Régis

Régis est un logiciel développé pour [Drama-Tea'c Circus](https://www.dramateaccircus.fr), pour le lancement de sons et de vidéos sur vidéoprojecteur pendant des représentations théâtrales.

Il est conçu pour être résilient et utilisable avec le matériel le plus rudimentaire : seul un navigateur Web moderne est requis. Ainsi, peu importe le lieu de représentation et l'appareil utilisé, le résultat sur scène sera le même.

## Déploiement

Deux méthodes s'offrent à vous :

### Docker

Construisez une image Docker à partir du Dockerfile fourni dans ce dépôt. Utile pour un lancement rapide de l'appli sur votre infrastructure.

### Déploiement avancé

Déployez un serveur Web avec les dépendances qui suivent :

- Apache ou nginx
- PHP 8.4 ou supérieur avec le module libxml

Clonez ce dépôt et copiez les fichiers à la racine.

Prenez garde à ce que l'utilisateur du serveur web ait le droit d'écrire dans le répertoire ./projects

## Sécurité

Dans son état actuel, Régis ne dispose d'aucune sécurité contre les intrusions. Je recommande de mettre en place une authentification basique sur le serveur Web afin de filtrer les usagers de l'outil.

## À propos de Drama-Tea'c Circus

Nous sommes une troupe de théâtre anglophone, située à Besançon, avec pour objectif de promouvoir la pratique du théâtre auprès du public étudiant. Chaque année, nous composons une création originale sous toutes ses formes : texte, costumes, mise en scène, son et lumière...
