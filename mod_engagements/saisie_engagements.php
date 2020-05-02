<?php
/*
 *
 *
 * Copyright 2001, 2018 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Julien Jocal, Stephane Boireau
 *
 * This file is part of GEPI.
 *
 * GEPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GEPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GEPI; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$niveau_arbo = 1;

// Initialisations files
require_once("../lib/initialisations.inc.php");

// fonctions complémentaires et/ou librairies utiles

// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == "c") {
   header("Location:utilisateurs/mon_compte.php?change_mdp=yes&retour=accueil#changemdp");
   die();
} else if ($resultat_session == "0") {
    header("Location: ../logout.php?auto=1");
    die();
}

$sql="SELECT 1=1 FROM droits WHERE id='/mod_engagements/saisie_engagements.php';";
$test=mysqli_query($GLOBALS["mysqli"], $sql);
if(mysqli_num_rows($test)==0) {
$sql="INSERT INTO droits SET id='/mod_engagements/saisie_engagements.php',
administrateur='V',
professeur='V',
cpe='V',
scolarite='V',
eleve='F',
responsable='F',
secours='F',
autre='F',
description='Saisie des engagements',
statut='';";
$insert=mysqli_query($GLOBALS["mysqli"], $sql);
}

if (!checkAccess()) {
	header("Location: ../logout.php?auto=2");
	die();
}

$id_classe=isset($_POST['id_classe']) ? $_POST['id_classe'] : (isset($_GET['id_classe']) ? $_GET['id_classe'] : NULL);

$engagement_statut=isset($_POST['engagement_statut']) ? $_POST['engagement_statut'] : (isset($_GET['engagement_statut']) ? $_GET['engagement_statut'] : "");
if(($engagement_statut!="eleve")&&($engagement_statut!="responsable")) {
	$engagement_statut="";
}

if($engagement_statut=="") {
	$tab_tous_engagements=get_tab_engagements("");
	if(count($tab_tous_engagements['indice'])==0) {
		header("Location: ../accueil.php?msg=Aucun type d engagement n est actuellement défini.");
		die();
	}
}
else {
	//echo "\$engagement_statut=$engagement_statut<br />";
	$tab_tous_engagements=get_tab_engagements($engagement_statut);
	/*
	if($_SESSION['statut']=='administrateur') {
		$tab_engagements=$tab_tous_engagements;
	}
	elseif($_SESSION['statut']=='cpe') {
		$tab_engagements=get_tab_engagements($engagement_statut, "cpe");
	}
	elseif($_SESSION['statut']=='scolarite') {
		$tab_engagements=get_tab_engagements($engagement_statut, "scolarite");
	}
	*/
	$tab_engagements=$tab_tous_engagements;

	if(count($tab_tous_engagements['indice'])==0) {
		header("Location: ../accueil.php?msg=Aucun type d engagement n est actuellement défini.");
		die();
	}
}

$nb_tous_engagements=count($tab_tous_engagements['indice']);
//$nb_engagements=count($tab_engagements['indice']);

/*
echo "<pre>";
print_r($tab_tous_engagements);
echo "</pre>";
*/

//debug_var();

$tab_engagements_avec_droit_saisie=get_tab_engagements_droit_saisie_tel_user($_SESSION['login']);
if(count($tab_engagements_avec_droit_saisie['indice'])==0) {
	header("Location: ../accueil.php?msg=Vous ne pouvez saisir aucun type d engagement.");
	die();
}
/*
echo "<pre>";
print_r($tab_engagements_avec_droit_saisie);
echo "</pre>";
*/
/*
// 20170122
if($_SESSION['statut']=='professeur') {

	// Si l'id_engagement est précisé, on peut tester array_key_exists($id_engagement, $tab_engagements_avec_droit_saisie["id_engagement"])
	// Sinon, il faut être PP

	if(!is_pp($_SESSION['login'])) {
		header("Location: ../accueil.php?msg=Vous n êtes PP d'aucune classe.");
		die();
	}

	$tab_classes_pp=get_tab_prof_suivi("", $_SESSION['login']);
	if(isset($id_classe)) {
		for($loop=0;$loop<count($id_classe);$loop++) {
			if(!in_array($id_classe[$loop], $tab_classes_pp)) {
				header("Location: ../accueil.php?msg=Vous n êtes pas PP de la classe choisie (n°".$id_classe[$loop].").");
				die();
			}
		}
	}
}
*/

if((isset($id_classe))&&(isset($_POST['is_posted']))&&($engagement_statut=='eleve')) {
	check_token();

	$msg="";
	/*
	$_POST['id_classe']=	Array (*)
	$_POST[id_classe]['0']=	33
	$_POST['engagement']=	Array (*)
	$_POST[engagement]['0']=	33|beaaaa|1
	$_POST[engagement]['1']=	33|beraaaa|3
	$_POST[engagement]['2']=	33|daaaaa|1
	$_POST[engagement]['3']=	33|gosaaa|3
	*/

	$nb_inscriptions=0;
	$tab_engagements_classe=array();
	$engagement=isset($_POST['engagement']) ? $_POST['engagement'] : array();
	for($loop=0;$loop<count($engagement);$loop++) {
		$tab=explode("|", $engagement[$loop]);
		$current_id_classe=$tab[0];
		$current_login=$tab[1];
		$current_id_engagement=$tab[2];

		//echo "<br /><p>\$current_id_classe=$current_id_classe<br />";
		//echo "\$current_login=$current_login<br />";
		//echo "\$current_id_engagement=$current_id_engagement<br />";

		$acces_saisie="n";
		if($_SESSION['statut']=="administrateur") {
			$acces_saisie="y";
		}
		elseif($_SESSION['statut']=="professeur") {
			if(isset($tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement])) {
				$indice_tab=$tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement];
				//echo "\$indice_tab=$indice_tab<br />";
				if(isset($tab_engagements_avec_droit_saisie["indice"][$indice_tab]["droit_special"])) {
					// Accès indépendamment de la classe pour ce type d'engagement
					$acces_saisie="y";
					//echo "\$acces_saisie=$acces_saisie<br />";
				}
				else {
					if(is_pp($_SESSION['login'], $current_id_classe)) {
						$acces_saisie="y";
					}
					else {
						$acces_saisie="n";
					}
				}
			}
			/*
			else {
				if(is_pp($_SESSION['login'], $current_id_classe)) {
					$acces_saisie="y";
				}
				else {
					$acces_saisie="n";
				}
			}
			*/
		}
		else {
			// Limite-t-on à certaines classes seulement pour un autre profil?
			if(isset($tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement])) {
				$acces_saisie="y";
			}
		}

		if($acces_saisie=="y") {
			if(!array_key_exists($current_id_classe, $tab_engagements_classe)) {
				$tab_engagements_classe[$current_id_classe]=get_tab_engagements_user("", $current_id_classe, "eleve");
			}
			if(array_key_exists($current_id_engagement, $tab_engagements['id_engagement'])) {
				// L'utilisateur a accès à la saisie de ce type d'engagement
				if((!isset($tab_engagements_classe[$current_id_classe]['id_engagement_user'][$current_id_engagement]))||(!in_array($current_login, $tab_engagements_classe[$current_id_classe]['id_engagement_user'][$current_id_engagement]))) {
					/*
					echo "$current_login n'est pas dans \$tab_engagements_classe[$current_id_classe]['id_engagement_user'][$current_id_engagement]<pre>";
					print_r($tab_engagements_classe[$current_id_classe]['id_engagement_user'][$current_id_engagement]);
					echo "</pre>";
					*/
					$sql="INSERT INTO engagements_user SET login='$current_login', id_type='id_classe', valeur='$current_id_classe', id_engagement='$current_id_engagement';";
					//echo "$sql<br />";
					$insert=mysqli_query($GLOBALS["mysqli"], $sql);
					if(!$insert) {
						$msg.="Erreur lors de l'inscription de l'engagement n°$current_id_engagement en classe ".get_nom_classe($current_id_classe)." pour ".civ_nom_prenom($current_login)."<br />";
					}
					else {
						$nb_inscriptions++;
					}
				}

			}
		}
	}
	$msg.=$nb_inscriptions." inscription(s) d'engagements.<br />";

	// Désinscriptions
	$nb_desinscriptions=0;
	for($loop=0;$loop<count($id_classe);$loop++) {
		if(!array_key_exists($id_classe[$loop], $tab_engagements_classe)) {
			$tab_engagements_classe[$id_classe[$loop]]=get_tab_engagements_user("", $id_classe[$loop], "eleve");
		}

		foreach($tab_engagements_classe[$id_classe[$loop]]['id_engagement_user'] as $current_id_engagement => $current_login) {
			/*
			echo "<pre>";
			print_r($current_login);
			echo "</pre>";
			*/

			$acces_saisie="n";
			if($_SESSION['statut']=="administrateur") {
				$acces_saisie="y";
			}
			elseif($_SESSION['statut']=="professeur") {
				if(isset($tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement])) {
					$indice_tab=$tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement];
					if(isset($tab_engagements_avec_droit_saisie["indice"][$indice_tab]["droit_special"])) {
						$acces_saisie="y";
					}
					else {
						if(is_pp($_SESSION['login'], $id_classe[$loop])) {
							$acces_saisie="y";
						}
						else {
							$acces_saisie="n";
						}
					}
				}
				/*
				else {
					if(is_pp($_SESSION['login'], $id_classe[$loop])) {
						$acces_saisie="y";
					}
					else {
						$acces_saisie="n";
					}
				}
				*/
			}
			else {
				// Limite-t-on à certaines classes seulement pour un autre profil?
				if(isset($tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement])) {
					$acces_saisie="y";
				}
			}

			if($acces_saisie=="y") {

				for($loop2=0;$loop2<count($current_login);$loop2++) {
					// On parcourt les élèves et les parents, mais avec get_info_eleve(), on ne récupère ensuite que les élèves.

					$chaine=$id_classe[$loop]."|".$current_login[$loop2]."|".$current_id_engagement;
					//echo "$chaine<br />";
					//if(!in_array($chaine, $engagement)) {
					//$tmp_info_user=get_info_user($current_login[$loop2]);
					$tmp_info_user=get_info_eleve($current_login[$loop2]);
					/*
					echo "<pre>";
					print_r($tmp_info_user);
					echo "</pre>";
					*/
					if((!in_array($chaine, $engagement))&&(isset($tmp_info_user['statut']))&&($tmp_info_user['statut']=='eleve')) {
						$sql="DELETE FROM engagements_user WHERE login='".$current_login[$loop2]."' AND id_type='id_classe' AND valeur='".$id_classe[$loop]."' AND id_engagement='$current_id_engagement';";
						//echo "$sql<br />";
						$del=mysqli_query($GLOBALS["mysqli"], $sql);
						if(!$del) {
							$msg.="Erreur lors de la suppression de l'engagement n°$current_id_engagement en classe ".get_nom_classe($id_classe[$loop])." pour ".civ_nom_prenom($current_login[$loop2])."<br />";
						}
						else {
							$nb_desinscriptions++;
							$msg.="Désinscription de ".civ_nom_prenom($current_login[$loop2])."<br />";
						}
					}
				}
			}
		}
	}

	$msg.=$nb_desinscriptions." suppression(s) d'engagements.<br />";
}

if((isset($id_classe))&&(isset($_POST['is_posted']))&&($engagement_statut=='responsable')) {
	check_token();

	$msg="";
	/*
	$_POST['id_classe']=	Array (*)
	$_POST[id_classe]['0']=	32
	$_POST[id_classe]['1']=	33
	$_POST['engagement']=	Array (*)
	$_POST[engagement]['0']=	32|philippe.bxxxxxxx|6
	$_POST[engagement]['1']=	32|christian.cccccccccc2|6
	$_POST[engagement]['2']=	33|philippe.bxxxxxxxxxxxxx|6
	$_POST[engagement]['3']=	33|cecile.bssssss|6
	*/

	$nb_inscriptions=0;
	$tab_engagements_classe=array();
	$engagement=isset($_POST['engagement']) ? $_POST['engagement'] : array();
	for($loop=0;$loop<count($engagement);$loop++) {
		$tab=explode("|", $engagement[$loop]);
		$current_id_classe=$tab[0];
		$current_login=$tab[1];
		$current_id_engagement=$tab[2];

		$acces_saisie="n";
		if($_SESSION['statut']=="administrateur") {
			$acces_saisie="y";
		}
		elseif($_SESSION['statut']=="professeur") {
			if(isset($tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement])) {
				$indice_tab=$tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement];
				//echo "\$indice_tab=$indice_tab<br />";
				if(isset($tab_engagements_avec_droit_saisie["indice"][$indice_tab]["droit_special"])) {
					$acces_saisie="y";
					//echo "\$acces_saisie=$acces_saisie<br />";
				}
				else {
					if(is_pp($_SESSION['login'], $current_id_classe)) {
						$acces_saisie="y";
					}
					else {
						$acces_saisie="n";
					}
				}
			}
		}
		else {
			// Limite-t-on à certaines classes seulement pour un autre profil?
			if(isset($tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement])) {
				$acces_saisie="y";
			}
		}

		if($acces_saisie=="y") {

			if(!array_key_exists($current_id_classe, $tab_engagements_classe)) {
				$tab_engagements_classe[$current_id_classe]=get_tab_engagements_user("", $current_id_classe, "responsable");
			}
			if(array_key_exists($current_id_engagement, $tab_engagements['id_engagement'])) {
				// L'utilisateur a accès à la saisie de ce type d'engagement
				if((!isset($tab_engagements_classe[$current_id_classe]['id_engagement_user'][$current_id_engagement]))||(!in_array($current_login, $tab_engagements_classe[$current_id_classe]['id_engagement_user'][$current_id_engagement]))) {
					/*
					echo "$current_login n'est pas dans \$tab_engagements_classe[$current_id_classe]['id_engagement_user'][$current_id_engagement]<pre>";
					print_r($tab_engagements_classe[$current_id_classe]['id_engagement_user'][$current_id_engagement]);
					echo "</pre>";
					*/
					$sql="INSERT INTO engagements_user SET login='$current_login', id_type='id_classe', valeur='$current_id_classe', id_engagement='$current_id_engagement';";
					//echo "$sql<br />";
					$insert=mysqli_query($GLOBALS["mysqli"], $sql);
					if(!$insert) {
						$msg.="Erreur lors de l'inscription de l'engagement n°$current_id_engagement en classe ".get_nom_classe($current_id_classe)." pour ".civ_nom_prenom($current_login)."<br />";
					}
					else {
						$nb_inscriptions++;
					}
				}

			}
		}
	}
	$msg.=$nb_inscriptions." inscription(s) d'engagements.<br />";

	// Désinscriptions
	$nb_desinscriptions=0;
	for($loop=0;$loop<count($id_classe);$loop++) {
		if(!array_key_exists($id_classe[$loop], $tab_engagements_classe)) {
			$tab_engagements_classe[$id_classe[$loop]]=get_tab_engagements_user("", $id_classe[$loop], "responsable");
		}

		/*
		echo get_nom_classe($id_classe[$loop]).":<pre>";
		print_r($tab_engagements_classe[$id_classe[$loop]]);
		echo "</pre>";
		*/

		foreach($tab_engagements_classe[$id_classe[$loop]]['id_engagement_user'] as $current_id_engagement => $current_login) {
			/*
			echo "<pre>";
			print_r($current_login);
			echo "</pre>";
			*/


			$acces_saisie="n";
			if($_SESSION['statut']=="administrateur") {
				$acces_saisie="y";
			}
			elseif($_SESSION['statut']=="professeur") {
				if(isset($tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement])) {
					$indice_tab=$tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement];
					if(isset($tab_engagements_avec_droit_saisie["indice"][$indice_tab]["droit_special"])) {
						$acces_saisie="y";
					}
					else {
						if(is_pp($_SESSION['login'], $id_classe[$loop])) {
							$acces_saisie="y";
						}
						else {
							$acces_saisie="n";
						}
					}
				}
			}
			else {
				// Limite-t-on à certaines classes seulement pour un autre profil?
				if(isset($tab_engagements_avec_droit_saisie["id_engagement"][$current_id_engagement])) {
					$acces_saisie="y";
				}
			}

			if($acces_saisie=="y") {
				for($loop2=0;$loop2<count($current_login);$loop2++) {
					// On parcourt les élèves et les parents, mais avec get_info_responsable(), on ne récupère ensuite que les parents.

					$chaine=$id_classe[$loop]."|".$current_login[$loop2]."|".$current_id_engagement;

					//echo "$chaine<br />";
					// Il ne faut pas désinscrire les élèves ici
					//$tmp_info_user=get_info_user($current_login[$loop2]);
					$tmp_info_user=get_info_responsable($current_login[$loop2]);
					/*
					echo "<pre>";
					print_r($tmp_info_user);
					echo "</pre>";
					*/
					if((!in_array($chaine, $engagement))&&(isset($tmp_info_user['statut']))&&($tmp_info_user['statut']=='responsable')) {
						$sql="DELETE FROM engagements_user WHERE login='".$current_login[$loop2]."' AND id_type='id_classe' AND valeur='".$id_classe[$loop]."' AND id_engagement='$current_id_engagement';";
						//echo "$sql<br />";
						$del=mysqli_query($GLOBALS["mysqli"], $sql);
						if(!$del) {
							$msg.="Erreur lors de la suppression de l'engagement n°$current_id_engagement en classe ".get_nom_classe($id_classe[$loop])." pour ".civ_nom_prenom($current_login[$loop2])."<br />";
						}
						else {
							$nb_desinscriptions++;
							$msg.="Désinscription de ".civ_nom_prenom($current_login[$loop2])."<br />";
						}
					}
				}
			}
		}
	}

	$msg.=$nb_desinscriptions." suppression(s) d'engagements.<br />";
}

// ======================== CSS et js particuliers ========================
//$utilisation_win = "non";
//$utilisation_jsdivdrag = "non";
//$javascript_specifique = ".js";
//$style_specifique = ".css";

$themessage  = 'Des informations ont été modifiées. Voulez-vous vraiment quitter sans enregistrer ?';
// ===================== entete Gepi ======================================//
$titre_page = "Saisie engagements";
require_once("../lib/header.inc.php");
// ===================== fin entete =======================================//

//debug_var();

if($_SESSION['statut']=='administrateur') {
	if(isset($id_classe[0])) {
		echo "<p class='bold'><a href='../classes/classes_const.php";
		echo "?id_classe=".$id_classe[0];
	}
	else {
		echo "<p class='bold'><a href='../classes/index.php";
	}
	echo "'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>";
}
else {
	echo "<p class='bold'><a href='../accueil.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>";
}

if(acces("/mod_engagements/index_admin.php", $_SESSION['statut'])) {
	echo " | <a href='index_admin.php'>Définir les types d'engagements</a>";
}

if(acces("/mod_engagements/extraction_engagements.php", $_SESSION['statut'])) {
	echo " | <a href='extraction_engagements.php'>Extraire des engagements</a>";
}

if(acces("/mod_engagements/imprimer_documents.php", $_SESSION['statut'])) {
	echo " | <a href='imprimer_documents.php'>Imprimer les documents liés aux engagements</a>";
}

if((!isset($id_classe))||($engagement_statut=="")) {
	echo "</p>\n";

	echo "<p class='bold'>Choix des classes&nbsp;:</p>\n";

	if($_SESSION['statut']=='professeur') {
		$sql="SELECT * FROM engagements_droit_saisie WHERE login='".$_SESSION['login']."';";
		$test=mysqli_query($GLOBALS["mysqli"], $sql);
		if(mysqli_num_rows($test)>0) {
			// Liste des classes avec élève:
			$sql="SELECT DISTINCT c.* FROM j_eleves_classes jec, classes c WHERE (c.id=jec.id_classe) ORDER BY c.classe;";
		}
		else {
			// Liste des classes dont le prof est PP:
			$sql="SELECT DISTINCT c.* FROM j_eleves_professeurs jep, j_eleves_classes jec, classes c WHERE (c.id=jec.id_classe AND jep.login=jec.login AND jep.professeur='".$_SESSION['login']."' AND jep.id_classe=jec.id_classe) ORDER BY c.classe;";
		}
	}
	else {
		// Liste des classes avec élève:
		$sql="SELECT DISTINCT c.* FROM j_eleves_classes jec, classes c WHERE (c.id=jec.id_classe) ORDER BY c.classe;";
	}
	//echo "$sql<br />";
	$call_classes=mysqli_query($GLOBALS["mysqli"], $sql);

	$nb_classes=mysqli_num_rows($call_classes);
	if($nb_classes==0){
		echo "<p>Aucune classe avec élève affecté n'a été trouvée.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}

	$gepi_prof_suivi=ucfirst(getSettingValue('gepi_prof_suivi'));
	$tab_liste_pp=array();
	$tab_pp=get_tab_prof_suivi();
	foreach($tab_pp as $current_id_classe => $tab_pp_classe) {
		$tab_liste_pp[$current_id_classe]="";
		for($loop=0;$loop<count($tab_pp_classe);$loop++) {
			if($loop>0) {
				$tab_liste_pp[$current_id_classe].=", ";
			}
			$tab_liste_pp[$current_id_classe].=affiche_utilisateur($tab_pp_classe[$loop] ,$current_id_classe);
		}
	}

	echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post' name='formulaire'>\n";
	// Affichage sur 3 colonnes
	$nb_classes_par_colonne=round($nb_classes/3);

	echo "<table width='100%' summary='Choix des classes'>\n";
	echo "<tr valign='top' align='center'>\n";

	$cpt = 0;

	echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
	echo "<td align='left'>\n";

	while($lig_clas=mysqli_fetch_object($call_classes)) {

		//affichage 2 colonnes
		if(($cpt>0)&&(round($cpt/$nb_classes_par_colonne)==$cpt/$nb_classes_par_colonne)){
			echo "</td>\n";
			echo "<td align='left'>\n";
		}

		echo "<label id='label_tab_id_classe_$cpt' for='tab_id_classe_$cpt' style='cursor: pointer;'><input type='checkbox' name='id_classe[]' id='tab_id_classe_$cpt' value='$lig_clas->id' onchange='change_style_classe($cpt)' /> $lig_clas->classe";
		if((isset($tab_liste_pp[$lig_clas->id]))&&($tab_liste_pp[$lig_clas->id]!="")) {
			echo "<span style='font-size:xx-small' title=\"$gepi_prof_suivi\"> &nbsp;&nbsp;&nbsp;(".$tab_liste_pp[$lig_clas->id].")</span>";
		}
		echo "</label>";
		echo "<br />\n";
		$cpt++;
	}

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<p><a href='#' onClick='ModifCase(true);return false;'>Tout cocher</a> / <a href='#' onClick='ModifCase(false);return false;'>Tout décocher</a></p>\n";

	echo "
	<p>
		<input type='radio' name='engagement_statut' id='engagement_statut_eleve' value='eleve' checked onchange=\"checkbox_change('engagement_statut_responsable');checkbox_change('engagement_statut_eleve')\" /><label for='engagement_statut_eleve' id='texte_engagement_statut_eleve' style='font-weight:bold'>Saisir les engagements élèves</label><br />
		<input type='radio' name='engagement_statut' id='engagement_statut_responsable' value='responsable' onchange=\"checkbox_change('engagement_statut_responsable');checkbox_change('engagement_statut_eleve')\" /><label for='engagement_statut_responsable' id='texte_engagement_statut_responsable'>Saisir les engagements responsables</label>
	</p>
	<p><input type='submit' value='Valider' /></p>\n";
	echo "</form>\n";

	echo "<p><br /></p>

<p style='text-indent:-4em;margin-left:4em;'><em>NOTE&nbsp;:</em> Cette page est destinée saisir les engagements élèves pour telle ou telle classe (<em>délégué de classe,...</em>).</p>

<script type='text/javascript'>
	function ModifCase(mode) {
		for (var k=0;k<$cpt;k++) {
			if(document.getElementById('tab_id_classe_'+k)){
				document.getElementById('tab_id_classe_'+k).checked = mode;
				change_style_classe(k);
			}
		}
	}

	function change_style_classe(num) {
		if(document.getElementById('tab_id_classe_'+num)) {
			if(document.getElementById('tab_id_classe_'+num).checked) {
				document.getElementById('label_tab_id_classe_'+num).style.fontWeight='bold';
			}
			else {
				document.getElementById('label_tab_id_classe_'+num).style.fontWeight='normal';
			}
		}
	}

	".js_checkbox_change_style('checkbox_change', 'texte_', "n", 0.5)."
</script>\n";
	require("../lib/footer.inc.php");
	die();
}

echo " | <a href='".$_SERVER['PHP_SELF']."'>Saisir les engagements pour d'autres classes</a></p>\n";

if($engagement_statut=="eleve") {
	echo "<p class='bold'>Choix des élèves&nbsp;:</p>\n";

	echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post' name='formulaire'>\n";
	echo "<input type='hidden' name='is_posted' value='1' />\n";
	echo add_token_field();

	/*
	echo "<pre>";
	print_r($tab_engagements);
	echo "</pre>";
	*/


// Récupérer les droits en fonction de la classe et de l'id_engagement.
//acces_saisie_engagement($id_engagement, $id_classe) à modifier

	$tab_pp=get_tab_prof_suivi();

	$cpt=0;
	for($i=0;$i<count($id_classe);$i++) {
		$sql="SELECT DISTINCT e.login, e.nom, e.prenom, e.sexe, e.naissance, e.elenoet FROM eleves e, j_eleves_classes jec WHERE (e.login=jec.login AND jec.id_classe='".$id_classe[$i]."') ORDER BY e.nom, e.prenom;";
		//echo "$sql<br />";
		$call_eleves=mysqli_query($GLOBALS["mysqli"], $sql);
		$nombre_ligne=mysqli_num_rows($call_eleves);
		if($nombre_ligne==0) {
			echo "<p style='color:red;'>Aucun élève n'est inscrit dans la classe de ".get_class_from_id($id_classe[$i]).".</p>\n";
		}
		else {
			$tab_engagements_classe=get_tab_engagements_user("", $id_classe[$i], "eleve");

			echo "<input type='hidden' name='id_classe[]' value='$id_classe[$i]' />\n";

			$first_ele[$id_classe[$i]]=$cpt;
			echo "<table class='boireaus' summary='Classe n°$id_classe[$i]'/>\n";
			echo "<tr>\n";
			echo "<th>\n";
			echo "Classe de ".get_class_from_id($id_classe[$i])."\n";
			echo "</th>\n";
			echo "<th colspan='$nb_tous_engagements'>
					<div style='float:right; width:16px;'>
						<a href='javascript:affichage_des_photos_ou_non()' id='temoin_photo'><img src='../images/icons/camera-photo.png' class='icone16' alt='Photo affichée' title='Photo affichée.\nCliquer pour masquer les photos.' /></a>
					</div>

				Engagements
			</th>\n";
			echo "</tr>\n";

			echo "<tr>\n";

			/*
			echo "<th>\n";
			//echo "Cocher/décocher\n";
			echo "<p><a href='#' onClick='ModifCase(".$id_classe[$i].",true);return false;'><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a> / <a href='#' onClick='ModifCase(".$id_classe[$i].",false);return false;'><img src='../images/disabled.png' width='15' height='15' alt='Tout décocher' /></a></p>\n";
			echo "</th>\n";
			*/

			echo "<th>Elève</th>\n";
			for($loop=0;$loop<$nb_tous_engagements;$loop++) {
				echo "<th>".$tab_tous_engagements['indice'][$loop]['nom']."</th>\n";
			}
			echo "</tr>\n";

			$alt=1;
			while($lig_ele=mysqli_fetch_object($call_eleves)) {
				$alt=$alt*(-1);
				echo "<tr class='lig$alt white_hover'";
				if(isset($lig_ele->elenoet)) {
					echo " onmouseover=\"affiche_photo_courante('".nom_photo($lig_ele->elenoet)."')\" onmouseout=\"vide_photo_courante();\"";
				}
				echo ">\n";
				/*
				echo "<td>\n";
				echo "<input type='checkbox' name='login_eleve_".$id_classe[$i]."[]' id='login_eleve_$cpt' value='$lig_ele->login' onchange='change_style_eleve($cpt)' checked />\n";
				echo "</td>\n";
				*/

				echo "<td style='text-align:left;'><span id='eleve_$cpt'><a href='../eleves/visu_eleve.php?ele_login=".$lig_ele->login."' title=\"Voir les fiches/onglets élève.\" onclick=\"return confirm_abandon (this, change, '$themessage')\">$lig_ele->nom $lig_ele->prenom</a></span></td>\n";

				for($loop=0;$loop<$nb_tous_engagements;$loop++) {
					echo "<td>\n";
					if(($_SESSION['statut']=='administrateur')||
					((isset($tab_engagements['indice'][$loop]['droit_special']))&&(in_array($_SESSION['login'], $tab_engagements['indice'][$loop]['droit_special'])))||
					(($_SESSION['statut']=='cpe')&&(isset($tab_engagements['indice'][$loop]['SaisieCpe']))&&($tab_engagements['indice'][$loop]['SaisieCpe']=='yes'))||
					(($_SESSION['statut']=='scolarite')&&(isset($tab_engagements['indice'][$loop]['SaisieScol']))&&($tab_engagements['indice'][$loop]['SaisieScol']=='yes'))||
					(($_SESSION['statut']=='professeur')&&
						((isset($tab_engagements['indice'][$loop]['SaisiePP']))&&(in_array($_SESSION['login'], $tab_pp[$id_classe[$i]]))&&($tab_engagements['indice'][$loop]['SaisiePP']=='yes')))
					) {
						$checked="";
						if((isset($tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))&&(in_array($lig_ele->login, $tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))) {
							$checked=" checked";
						}
						echo "<input type='checkbox' name='engagement[]' id='engagement_".$loop."_".$cpt."' value=\"".$id_classe[$i]."|".$lig_ele->login."|".$tab_engagements['indice'][$loop]['id']."\"$checked onchange='changement()' title=\"".$tab_engagements['indice'][$loop]['nom']." -> $lig_ele->nom $lig_ele->prenom\" />";
					}
					else {
						if((isset($tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))&&(in_array($lig_ele->login, $tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))) {
							echo "<img src='../images/enabled.png' class='icone20' title=\"".$tab_engagements['indice'][$loop]['nom']." : $lig_ele->nom $lig_ele->prenom\" />";
						}
					}
					echo "</td>\n";
				}

				echo "</tr>\n";
				$cpt++;
			}
			echo "</table>\n";
			$last_ele[$id_classe[$i]]=$cpt;

		}
	}

	echo "
	<input type='hidden' name='engagement_statut' value='eleve' />
	<p><input type='submit' value='Valider' /></p>
	<div id='fixe' style='text-align:center;'>
		<div id='photo_fixe' title=\"Il est possible de désactiver l'affichage des photos à l'aide de l'icone en haut à droite dans l'entête du tableau.\">
		</div>
		<input type='submit' value='Valider' />
	</div>
	</form>

	<script type='text/javascript'>
		function affiche_photo_courante(photo) {
			document.getElementById('photo_fixe').innerHTML=\"<img src='\"+photo+\"' width='150' alt='Photo' />\";
		}

		function vide_photo_courante() {
			document.getElementById('photo_fixe').innerHTML='';
		}

		function affichage_des_photos_ou_non() {
			if(document.getElementById('photo_fixe').style.display=='') {
				document.getElementById('photo_fixe').style.display='none';
				document.getElementById('temoin_photo').innerHTML=\"<img src='../images/icons/camera-photo-barre.png' class='icone16' alt='Photo masquée' title='Photo masquée.\\nCliquer pour afficher les photos.' />\";
			}
			else {
				document.getElementById('photo_fixe').style.display='';
				document.getElementById('temoin_photo').innerHTML=\"<img src='../images/icons/camera-photo.png' class='icone16' alt='Photo affichée' title='Photo affichée.\\nCliquer pour masquer les photos.' />\";
			}
		}
	</script>";
}
else {
	echo "<p class='bold'>Choix des responsables&nbsp;:</p>\n";

	echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post' name='formulaire'>\n";
	echo "<input type='hidden' name='is_posted' value='1' />\n";
	echo add_token_field();

	$tab_pp=get_tab_prof_suivi();

	$cpt=0;
	for($i=0;$i<count($id_classe);$i++) {
		$sql="SELECT DISTINCT rp.* FROM resp_pers rp, 
							responsables2 r, 
							eleves e, 
							j_eleves_classes jec 
						WHERE (e.login=jec.login AND 
							jec.id_classe='".$id_classe[$i]."' AND 
							r.ele_id=e.ele_id AND 
							r.pers_id=rp.pers_id AND 
							(r.resp_legal='1' OR r.resp_legal='2')) 
							ORDER BY e.nom, e.prenom, r.resp_legal;";
		//echo "$sql<br />";
		$res=mysqli_query($GLOBALS["mysqli"], $sql);
		$nombre_ligne=mysqli_num_rows($res);
		if($nombre_ligne==0) {
			echo "<p style='color:red;'>Aucun responsable n'est associé à un élève de la classe de ".get_class_from_id($id_classe[$i]).".</p>\n";
		}
		else {
			// On récupère les engagements concernant les responsables, mais l'indice $tab_engagements_classe['login_user'] contient les engagements élèves et responsables
			$tab_engagements_classe=get_tab_engagements_user("", $id_classe[$i],"responsable");
			/*
			echo "<pre>";
			print_r($tab_engagements_classe);
			echo "</pre>";
			*/
			$nom_classe=get_class_from_id($id_classe[$i]);
			echo "<input type='hidden' name='id_classe[]' value='$id_classe[$i]' />\n";

			//$first_ele[$id_classe[$i]]=$cpt;
			echo "
	<table class='boireaus boireaus_alt' summary='Classe n°$id_classe[$i]'/>
		<tr>
			<th colspan='2'>Classe de ".$nom_classe."</th>
			<th colspan='$nb_tous_engagements'>Engagements</th>
		</tr>
		<tr>
			<th>Responsable</th>
			<th>Elève</th>";
			for($loop=0;$loop<$nb_tous_engagements;$loop++) {
				echo "
			<th>".$tab_tous_engagements['indice'][$loop]['nom']."</th>";
			}
			echo "
		</tr>";

			$tab_resp=array();
			while($lig_resp=mysqli_fetch_object($res)) {
				echo "
		<tr class='white_hover'>
			<td style='text-align:left;'>";
				if($lig_resp->login=='') {
					echo "
					<div style='float:right; width:16px'><img src='../images/icons/buddy_no.png' class='icone16' title=\"Ce responsable n'a pas de compte utilisateur.\nUn compte utilisateur est requis pour les engagements responsables.\" /></div>
					".$lig_resp->civilite.' '.$lig_resp->nom.' '.$lig_resp->prenom;
				}
				else {
					echo "
					<span id='resp_$cpt'><a href='saisie_engagements_user.php?login_user=".$lig_resp->login."' title=\"Saisir les engagements de cet utilisateur, même hors de la classe.\" onclick=\"return confirm_abandon (this, change, '$themessage')\">$lig_resp->civilite $lig_resp->nom $lig_resp->prenom</a></span>";
				}
				echo "
			</td>
			<td style='text-align:left;'>";

				if($lig_resp->login!='') {
					$tab_resp[]=$lig_resp->login;
				}

				$sql="SELECT DISTINCT e.* FROM eleves e, 
								j_eleves_classes jec,
								responsables2 r
							WHERE e.login=jec.login AND 
								jec.id_classe='".$id_classe[$i]."' AND 
								r.ele_id=e.ele_id AND 
								r.pers_id='".$lig_resp->pers_id."' AND 
								(r.resp_legal='1' OR r.resp_legal='2');";
				$res_ele=mysqli_query($GLOBALS["mysqli"], $sql);
				$cpt_ele=0;
				while($lig_ele=mysqli_fetch_object($res_ele)) {
					if($cpt_ele>0) {
						echo "<br />";
					}
					echo "
				<a href='../eleves/visu_eleve.php?ele_login=".$lig_ele->login."' title=\"Voir les fiches/onglets élève.\" onclick=\"return confirm_abandon (this, change, '$themessage')\">$lig_ele->nom $lig_ele->prenom</a>";
					$cpt_ele++;
				}
				echo "
			</td>";

				for($loop=0;$loop<$nb_tous_engagements;$loop++) {
					if($lig_resp->login=='') {
						echo "
			<td title=\"Ce responsable n'a pas de compte utilisateur.\nUn compte utilisateur est requis pour les engagements responsables.\">-</td>\n";
					}
					else {
						echo "<td>\n";
						if(($_SESSION['statut']=='administrateur')||
						((isset($tab_engagements['indice'][$loop]['droit_special']))&&(in_array($_SESSION['login'], $tab_engagements['indice'][$loop]['droit_special'])))||
						(($_SESSION['statut']=='cpe')&&($tab_engagements['indice'][$loop]['SaisieCpe']=='yes'))||
						(($_SESSION['statut']=='scolarite')&&($tab_engagements['indice'][$loop]['SaisieScol']=='yes'))||
						(($_SESSION['statut']=='professeur')&&
							((isset($tab_engagements['indice'][$loop]['SaisiePP']))&&(in_array($_SESSION['login'], $tab_pp[$id_classe[$i]]))&&($tab_engagements['indice'][$loop]['SaisiePP']=='yes')))
						) {
							$checked="";
							if((isset($tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))&&(in_array($lig_resp->login, $tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))) {
								$checked=" checked";
							}
							echo "<input type='checkbox' name='engagement[]' id='engagement_".$loop."_".$cpt."' value=\"".$id_classe[$i]."|".$lig_resp->login."|".$tab_engagements['indice'][$loop]['id']."\"$checked onchange='changement()' />";
						}
						else {
							if((isset($tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))&&(in_array($lig_resp->login, $tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))) {
								echo "<img src='../images/enabled.png' class='icone20' />";
							}
						}
						echo "</td>\n";
					}
				}

				echo "</tr>\n";
				$cpt++;
			}
			echo "</table>\n";
			//$last_ele[$id_classe[$i]]=$cpt;
		}

		$chaine_engagements_hors_classe="";
		if(isset($tab_engagements_classe['login_user'])) {
			foreach($tab_engagements_classe['login_user'] as $current_login => $tab_id_engagement) {
				$tmp_info_user=get_info_user($current_login);
				if((!in_array($current_login, $tab_resp))&&(isset($tmp_info_user['statut']))&&($tmp_info_user['statut']=='responsable')) {
					$chaine_engagements_hors_classe.="
			<tr>
				<td><a href='saisie_engagements_user.php?login_user=".$current_login."' title=\"Saisir les engagements de cet utilisateur, même hors de la classe.\" onclick=\"return confirm_abandon (this, change, '$themessage')\">".civ_nom_prenom($current_login)."</a></td>";

					for($loop=0;$loop<$nb_tous_engagements;$loop++) {
						$chaine_engagements_hors_classe.="
				<td>\n";
						if(($_SESSION['statut']=='administrateur')||
						(($_SESSION['statut']=='cpe')&&($tab_engagements['indice'][$loop]['SaisieCpe']=='yes'))||
						(($_SESSION['statut']=='scolarite')&&($tab_engagements['indice'][$loop]['SaisieScol']=='yes'))||
						(($_SESSION['statut']=='professeur')&&(isset($tab_engagements['indice'][$loop]['SaisiePP']))&&($tab_engagements['indice'][$loop]['SaisiePP']=='yes'))
						) {
							$checked="";
							if((isset($tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))&&(in_array($current_login, $tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))) {
								$checked=" checked";
							}
							$chaine_engagements_hors_classe.="<input type='checkbox' name='engagement[]' id='engagement_".$loop."_".$cpt."' value=\"".$id_classe[$i]."|".$current_login."|".$tab_engagements['indice'][$loop]['id']."\"$checked onchange='changement()' />";
						}
						else {
							if((isset($tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))&&(in_array($current_login, $tab_engagements_classe['id_engagement_user'][$tab_engagements['indice'][$loop]['id']]))) {
								$chaine_engagements_hors_classe.="<img src='../images/enabled.png' class='icone20' />";
							}
						}
						$chaine_engagements_hors_classe.="</td>\n";
					}

					$chaine_engagements_hors_classe.="
			</tr>";
					$cpt++;
				}
			}
		}

		if($chaine_engagements_hors_classe!="") {
			echo "<br /><p class='bold'>Engagements hors classe pour la classe de ".$nom_classe."</p>
	<table class='boireaus boireaus_alt'>
		<tr>
			<th>Identité</th>";
			for($loop=0;$loop<$nb_tous_engagements;$loop++) {
				echo "
			<th>".$tab_engagements['indice'][$loop]['nom']."</th>";
			}
			echo "
		</tr>$chaine_engagements_hors_classe
	</table>";
		}
	}

	echo "
	<input type='hidden' name='engagement_statut' value='responsable' />
	<p><input type='submit' value='Valider' /></p>
	<div id='fixe'><input type='submit' value='Valider' /></div>
	</form>
	
	<p style='margin-top:1em;'><em>NOTES&nbsp;:</em></p>
	<ul>
		<li><p>Pour saisir des engagements de responsables qui ne sont pas responsables/parents d'élèves dans la classe, effectuer la saisie des engagements depuis une recherche sur le nom du parent dans la page de ";
	if(acces('/responsables/index.php', $_SESSION['statut'])) {
		echo "<a href='../responsables/index.php'>Gestion des responsables</a>";
	}
	elseif(acces('../eleves/recherche.php', $_SESSION['statut'])) {
		echo "<a href='../responsables/index.php'>Recherche</a>";
	}
	else {
		echo "Gestion des responsables (<em>en administrateur</em>)";
	}
	echo "
		</p></li>
		<li><p>Les responsables doivent disposer d'un compte utilisateur pour pouvoir être cochés dans les Engagements.</p></li>
	</ul>\n";
}

require_once("../lib/footer.inc.php");
?>
