<?php

# La ligne suivante est à modifier si 
# vous voulez utiliser le multisite
# Regardez le fichier modeles/connect-modele.inc.php pour information

$multisite = 'n';

# Les cinq lignes suivantes sont 
# à modifier selon votre configuration
# Pensez à renommer ce fichier 
# connect.cfg.php en connect.inc.php
#
# ligne suivante : le nom du serveur qui herberge votre base mysql.
# Si c'est le même que celui qui heberge les scripts, mettre "localhost"

$dbHost="d6vscs19jtah8iwb.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";

# Port mySQL sur le serveur; c'est généralement le 3306

$dbPort="3306";

# ligne suivante : le nom de votre base mysql

$dbDb="qt6f02qy1t5r8vz7";

# ligne suivante : le nom de l'utilisateur mysql qui a les droits sur la base

$dbUser="fy6xwb3t9xgc6byh";

# ligne suivante : le mot de passe de l'utilisateur mysql ci-dessus

$dbPass="qj7g8vfi3oc80tps";

# Chemin relatif vers GEPI

$gepiPath="https://gepi.herokuapp.com";
$db_nopersist=true;


$set_mode_mysql="MYSQL40";

# Authentification par CAS ?
# Si vous souhaitez intégrer Gepi dans un environnement SSO avec CAS,
# vous devrez renseigner le fichier /secure/config_cas.inc.php avec les
# informations nécessaires à l'identification du serveur CAS

$use_cas = false; // false|true

?>