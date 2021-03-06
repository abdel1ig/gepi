##############################################################
# we are looking for translators for the README,             #
# contact vincent at artichow dot org if you are interested! #
##############################################################

+--------------------+
| README en français |
+--------------------+

  I. Installation
 II. Configuration
III. Utilisation
 IV. Divers


I. Installation
   ------------

*** Première installation ***

L'installation de Artichow se résume à décompresser l'archive dans le dossier
de votre choix sur votre serveur. Veillez simplement à télécharger l'archive
dont vous avez vraiment besoin (PHP 5 ou PHP 4 & 5).
Notez que Artichow requiert GD 2 et PHP 4.3.0 au minimum pour fonctionner.

*** Mise à jour ***

Lorsque vous souhaitez mettre à jour Artichow avec la dernière version,
essayez de suivre pas à pas ces étapes :
   1) Décompressez la dernière version de Artichow dans un dossier
   2) Ecrasez le fichier Artichow.cfg.php avec votre ancien fichier
   3) Copiez vos patterns dans le dossier patterns/ de la nouvelle version
	4) Supprimez l'ancienne version de Artichow de votre disque
	5) Copiez la nouvelle version là où était l'ancienne
Une fois ces cinq étapes effectuées, vous n'aurez plus qu'à mettre
éventuellement à jour vos graphiques, en fonction des dernières évolutions de
l'API de Artichow. Pour cela, voyez le titre "Migrer d'une version à l'autre"
sur la page :
http://www.artichow.org/documentation

II. Configuration
    -------------

Même si une utilisation normale de Artichow ne nécessite pas de configuration
particulière, il existe un fichier Artichow.cfg.php qui permet de modifier
quelques paramètres de la librairie.
Vous pouvez notamment configurer le répertoire vers les polices de caractère
en modifiant la constante ARTICHOW_FONT (par exemple en choisissant
'c:\Windows\font' si vous êtes sous Windows).
Vous pouvez également redéfinir la variable $fonts. Cette variable contient une
liste de polices TTF (sans l'extension) présentes dans votre répertoire
ARTICHOW_FONT. Pour toutes les polices de cette liste, une classe du même nom
est créée. Les polices ainsi définies peuvent ensuite être utilisées de cette
manière :
<?php
$font = new Verdana(12); // 12 représente la taille en points
?>
Il existe également une constante ARTICHOW_DEPRECATED. Si cette constante vaut
TRUE, alors un message d'erreur sera affiché lorsque vous utiliserez une
fonctionnalité dépréciée de Artichow. A l'inverse, avec la valeur FALSE,
vous pourrez continuer à utiliser les fonctions dépréciées sans soucis.
Cependant, dans un souci de compatibilité, il est préférable de mettre à
jour vos graphiques dès lors qu'un message de ce type apparaît (et donc de
laisser la constante à TRUE). Les fonctionnalités dépréciées sont toujours
potentiellement susceptibles de disparaître d'une version à l'autre de la
librairie.
La constante ARTICHOW_PREFIX est vide par défaut et correspond à un préfixe qui
est ajouté au nom de chaque classe utilisée sur Artichow. Certains noms de
classe (Graph, Image, Text, Font, etc.) sont utilisés par d'autres librairies
et cela peut aboutir à des conflits. Pour résoudre ce problème, choisissez par
exemple 'xyz' comme préfixe et toutes les classes de Artichow s'appèleront
désormais xyz[Nom normal]. Exemple d'utilisation de Artichow avec
ARTICHOW_PREFIX à 'xyz' :
<?php
require_once "Artichow/LinePlot.class.php";

$plot = new xyzLinePlot(array(1, 2, 3));
$plot->title->set('Mon graphique');
$plot->title->setFont(new xyzFont4);

$graph = new xyzGraph(400, 300);
$graph->add($plot);
$graph->draw();
?>


III. Utilisation
     -----------

Si vous utilisez la version conçue exclusivement pour PHP 5, vous pouvez vous
référer aux exemples et aux tutoriels afin de bien prendre en main la
librairie.
Si vous utilisez la version pour PHP 4 & 5, référez vous également aux exemples
et tutoriels mais faîtes attention lors de l'inclusion des fichiers de
Artichow. N'incluez pas les fichiers de cette manière :
<?php
// Ceci ne fonctionnera pas
require_once "Artichow/php5/LinePlot.class.php";
// Cela non plus
require_once "Artichow/php4/LinePlot.class.php";
?>
Préférez plutôt :
<?php
// Fonctionnera correctement
require_once "Artichow/LinePlot.class.php";
?>
C'est la librairie qui se charge de sélectionner les bons fichiers en fonction
de la version de PHP dont vous disposez.

IV. Divers
    ------

La documentation de Artichow est disponible sur :
http://www.artichow.org/documentation

Des tutoriels sont accessibles sur :
http://www.artichow.org/tutorial

Un forum de support peut être trouvé sur :
http://www.artichow.org/forum/

N'oubliez pas que Artichow est dans le domaine public. Vous pouvez donc faire
CE QUE VOUS SOUHAITEZ avec cette librairie, y compris ajouter votre nom dans
chaque fichier, et la redistribuer ainsi.

Si vous souhaitez aider et participer au développement de Artichow, n'hésitez
pas à consulter cette page :
http://www.artichow.org/help

