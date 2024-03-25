# follow-abs

<img width="732" alt="image" src="https://github.com/dev-tutu/follow-abs/assets/164091895/b3af3523-c79e-4baa-b78d-57765071f6a1">

suivis des absences avec visulisation des niveau

## Composition

  - date -> du -- au
  - date de crèation
  - dernière mise à jour
  - commentaire
  - barre circuit de validation

## Utilisation

  1. Serveur de BDD
     
  - vous devez avoir un serveur de bdd déjà installer
  - import le script sql spa.sql

  2. Serveur Apache & PHP

  - Vous devez avoir un serveur apache déjà installer avec PHP
  - Vous devez importer le back et front dans le apache

  3. Crèation de la communication

  pour permettre la communication entre le back et la BDD vous devez modifier les variables dans le fichier bdd.php

  ```

    $host = "localhost"; (nom dns / @IP)
    $dbname = "spa"; (nom de la database)
    $user = "root"; (nom de l'utilisateur)
    $password = ""; (password)

  ```

 4. Utilisation d'un utilisateur

  Vous devez initialiser la connexion d'un "pseudo utilisateur" dans l'index.php

  ```

    $_SESSION['role'] = "pax";
    $_SESSION['nid'] = 815;

  ```

## SQL

la BDD stocke :


  - les motif d'event
  - les fonctions
  - grades
  - unité
  - peloton
  - RTT
  - jours bloquer par l'utilisateur
  - demande d'absences
  - les personnes

## Ameliorations V2


  1.Mail Automatique
  2.Supression directement depuis l'interface


