<?php
# La ligne suivante est à modifier si vous voulez utiliser le multisite
# Regardez le fichier modeles/connect-modele.inc.php pour information
$multisite = 'n';
# Les cinq lignes suivantes sont à modifier selon votre configuration
# Pensez à renommer ce fichier connect.cfg.php en connect.inc.php
#
# ligne suivante : le nom du serveur qui herberge votre base mysql.
# Si c'est le même que celui qui heberge les scripts, mettre "localhost"
$dbHost="localhost";
# Port mySQL sur le serveur; c'est généralement le 3306
$dbPort="3306";
# ligne suivante : le nom de votre base mysql
$dbDb="gepi";
# ligne suivante : le nom de l'utilisateur mysql qui a les droits sur la base
$dbUser="root";
# ligne suivante : le mot de passe de l'utilisateur mysql ci-dessus
$dbPass="root";
# Chemin relatif vers GEPI
$gepiPath="/gepi";
#
$db_nopersist=true;
#
# Authentification par CAS ?
# Si vous souhaitez intégrer Gepi dans un environnement SSO avec CAS,
# vous devrez renseigner le fichier /secure/config_cas.inc.php avec les
# informations nécessaires à l'identification du serveur CAS
$use_cas = false; // false|true
?>