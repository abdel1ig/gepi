<?php
/*
* Copyright 2001, 2018 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Stephane Boireau
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

// On indique qu'il faut creer des variables non protégées (voir fonction cree_variables_non_protegees())
$variables_non_protegees = 'yes';

// Initialisations files
require_once("../lib/initialisations.inc.php");


// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
	header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
	die();
} else if ($resultat_session == '0') {
	header("Location: ../logout.php?auto=1");
	die();
}

//======================================================================================

$sql="SELECT 1=1 FROM droits WHERE id='/mod_genese_classes/affiche_listes.php';";
$test=mysqli_query($GLOBALS["mysqli"], $sql);
if(mysqli_num_rows($test)==0) {
$sql="INSERT INTO droits SET id='/mod_genese_classes/affiche_listes.php',
administrateur='V',
professeur='F',
cpe='F',
scolarite='F',
eleve='F',
responsable='F',
secours='F',
autre='F',
description='Genèse des classes: Affichage de listes',
statut='';";
$insert=mysqli_query($GLOBALS["mysqli"], $sql);
}

//======================================================================================
// Section checkAccess() à décommenter en prenant soin d'ajouter le droit correspondant:
if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}
//======================================================================================

$projet=isset($_POST['projet']) ? $_POST['projet'] : (isset($_GET['projet']) ? $_GET['projet'] : NULL);

$user_temp_directory=get_user_temp_directory();

if((isset($_POST['nommer_requete']))&&(isset($_POST['nom_requete']))&&(isset($_POST['projet']))&&(isset($_POST['id_aff']))&&(isset($_POST['id_req']))) {
	check_token();

	$nom_requete=remplace_accents($_POST['nom_requete'], "all");
	if($nom_requete!="") {
		$sql="UPDATE gc_affichages SET nom_requete='".mysqli_real_escape_string($GLOBALS["mysqli"], $nom_requete)."' WHERE projet='$projet' AND id_aff='".$_POST['id_aff']."' AND id_req='".$_POST['id_req']."';";
		//echo "$sql<br />";
		$del=mysqli_query($GLOBALS["mysqli"], $sql);
		if($del) {
			$msg="<span class='color:green'>Requête n°".$_POST['id_req']." de l'affichage n°".$_POST['id_aff']." renommée&nbsp;: $nom_requete</span><br />";
		}
		else {
			$msg="<span class='color:green'>ERREUR lors du renommage de la requête n°".$_POST['id_req']." de l'affichage n°".$_POST['id_aff']." en&nbsp;: $nom_requete</span><br />";
		}
	}
	else {
		$msg="<span class='color:red'>ERREUR: Nom de requête ($nom_requete) invalide.</span><br />";
	}
}

if((isset($_GET['id_aff']))&&(isset($_GET['projet']))&&(isset($_GET['id_aff']))&&(isset($_GET['suppr_req']))) {
	check_token();

	$sql="DELETE FROM gc_affichages WHERE projet='$projet' AND id_aff='".$_GET['id_aff']."' AND id_req='".$_GET['suppr_req']."';";
	//echo "$sql<br />";
	$del=mysqli_query($GLOBALS["mysqli"], $sql);
	if($del) {
		$msg="<span class='color:green'>Requête n°".$_GET['suppr_req']." de l'affichage n°".$_GET['id_aff']." supprimée.</span><br />";
	}
	else {
		$msg="<span class='color:red'>ERREUR lors de la suppression de la requête n°".$_GET['suppr_req']." de l'affichage n°".$_GET['id_aff'].".</span><br />";
	}
}

if((isset($projet))&&(isset($_POST['chgt_classe']))&&(isset($_POST['login_ele']))&&(isset($_POST['classe_fut']))) {
	$temoin="y";
	if(($_POST['classe_fut']!='')&&($_POST['classe_fut']!='Red')&&($_POST['classe_fut']!='Dep')) {
		$sql="SELECT 1=1 FROM gc_divisions WHERE statut='future' AND classe='".$_POST['classe_fut']."' AND projet='$projet';";
		$test=mysqli_query($GLOBALS["mysqli"], $sql);
		if(mysqli_num_rows($test)==0) {
			$msg="La classe <b>".$_POST['classe_fut']."</b> n'existe pas.<br />";
			$temoin="n";
		}
	}

	if($temoin=="y") {
		$sql="UPDATE gc_eleves_options SET classe_future='".$_POST['classe_fut']."' WHERE login='".$_POST['login_ele']."' AND projet='$projet';";
		//$msg=$sql;
		$res=mysqli_query($GLOBALS["mysqli"], $sql);
		if(!$res) {
			$msg="ERREUR lors du changement de classe de ".get_nom_prenom_eleve($_POST['login_ele'])."<br />\n";
		}
		else {
			$msg="Changement de classe de <b>".get_nom_prenom_eleve($_POST['login_ele'])."</b> vers <b>".$_POST['classe_fut']."</b> effectué.<br />\n";
		}
	}
}

if((isset($projet))&&(isset($_GET['set_profil']))&&(isset($_GET['profil']))&&(isset($_GET['login']))) {
	check_token();

	include_once("lib_gc.php");

	//if(in_array($_GET['profil'], array('GC', 'C', 'RAS', 'B', 'TB'))) {
	if(in_array($_GET['profil'], $tab_profil)) {
		$sql="UPDATE gc_eleves_options SET profil='".$_GET['profil']."' WHERE projet='".$projet."' AND login='".$_GET['login']."';";
		$res=mysqli_query($GLOBALS["mysqli"], $sql);
		if(!$res) {
			echo "ERREUR";
		}
		else {
			echo $_GET['profil'];
		}
	}
	else {
	}

	die();
}

if((isset($projet))&&(isset($_POST['valider_enregistrement_nom_aff']))&&(isset($_POST['id_aff']))) {
	check_token();

	$nom_aff=isset($_POST['nom_aff']) ? $_POST['nom_aff'] : NULL;

	$description_aff=isset($_POST['description_aff']) ? $_POST['description_aff'] : NULL;
	$description_aff=isset($NON_PROTECT['description_aff']) ? traitement_magic_quotes(corriger_caracteres($NON_PROTECT['description_aff'])) : NULL;

	if((!isset($nom_aff))||(!isset($description_aff))) {
		$msg="ERREUR : Aucun nom d'affichage ou aucune description proposée.<br />";
	}
	else {
		$sql="SELECT 1=1 FROM gc_noms_affichages WHERE id_aff='".$_POST['id_aff']."' AND projet='$projet';";
		$res=mysqli_query($GLOBALS["mysqli"], $sql);
		if(mysqli_num_rows($res)==0) {
			$sql="INSERT INTO gc_noms_affichages SET projet='$projet', id_aff='".$_POST['id_aff']."', nom='".remplace_accents($nom_aff, "all")."', description='".$description_aff."';";
			$insert=mysqli_query($GLOBALS["mysqli"], $sql);
			if(!$insert) {
				$msg="Erreur lors du nommage de l'affichage.<br />";
			}
			else {
				$msg="Nommage de l'affichage effectué.<br />";
			}
		}
		else {
			$sql="UPDATE gc_noms_affichages SET nom='".remplace_accents($nom_aff, "all")."', description='".$description_aff."' WHERE id_aff='".$_POST['id_aff']."' AND projet='$projet';";
			$update=mysqli_query($GLOBALS["mysqli"], $sql);
			if(!$update) {
				$msg="Erreur lors du nommage de l'affichage.<br />";
			}
			else {
				$msg="Nommage de l'affichage effectué.<br />";
			}
		}
	}
}
/*
function get_infos_gc_affichage($id_aff) {
	global $projet;
	$tab=array();

	$sql="SELECT * FROM gc_noms_affichages WHERE id_aff='$id_aff' AND projet='$projet';";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res)>0) {
		$lig=mysqli_fetch_object($res);
		//$tab["id"]=$lig->id;
		$tab["id_aff"]=$lig->id_aff;
		$tab["nom"]=$lig->nom;
		$tab["description"]=$lig->description;
		$tab["nomme"]=true;
	}
	else {
		$tab["id_aff"]=$id_aff;
		$tab["nom"]="Affichage n°".$id_aff;
		$tab["description"]="";
		$tab["nomme"]=false;
	}

	return $tab;
}
*/


$javascript_specifique[] = "lib/tablekit";
$utilisation_tablekit="ok";

$charger_js_dragresize="y";

//**************** EN-TETE *****************
$titre_page = "Genèse classe: affichage de listes";
//echo "<div class='noprint'>\n";
require_once("../lib/header.inc.php");
//echo "</div>\n";
//**************** FIN EN-TETE *****************

//debug_var();

function mediane($tab_notes) {
	if(count($tab_notes)>0) {
		$milieu=floor(count($tab_notes)/2);
		if((count($tab_notes))%2==0){
			return ($tab_notes[$milieu]+$tab_notes[$milieu-1])/2;
		}
		else{
			return $tab_notes[$milieu];
		}
	}
	else {
		return "X";
	}
}

function moyenne($tab_notes) {
	if(count($tab_notes)>0) {
		return round(100*(array_sum($tab_notes)/count($tab_notes)))/100;
	}
	else {
		return "X";
	}
}


if((!isset($projet))||($projet=="")) {
	echo "<p style='color:red'>ERREUR: Le projet n'est pas choisi.</p>\n";
	require("../lib/footer.inc.php");
	die();
}

//echo "<div class='noprint'>\n";
echo "<p class='bold'><a href='index.php?projet=$projet'>Retour</a> | 
<a href='select_eleves_options.php?projet=$projet'>Saisie des options</a> | 
<a href='affect_eleves_classes.php?projet=$projet'>Affecter les élèves</a>";
//echo "</div>\n";

$id_aff=isset($_POST['id_aff']) ? $_POST['id_aff'] : (isset($_GET['id_aff']) ? $_GET['id_aff'] : NULL);

if(isset($id_aff)) {
	echo " | <a href='".$_SERVER['PHP_SELF']."?projet=$projet'>Créer un nouvel affichage</a>\n";
}

if((isset($id_aff))&&(isset($_GET['mode']))&&($_GET['mode']=='nommer_aff')) {
	echo " | <a href='".$_SERVER['PHP_SELF']."?projet=$projet&amp;id_aff=$id_aff'>Affichage n°$id_aff</a></p>\n";

	echo "<h2>Projet $projet : Affichage</h2>\n";

	//=============================
	include("lib_gc.php");
	//=============================

	$tab_aff_courant=get_infos_gc_affichage($id_aff);

	echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">
	<fieldset class='fieldset_opacite50'>
		<p>Nommer l'affichage n°$id_aff&nbsp;:</p>
		".add_token_field()."
		<input type='hidden' name='projet' value='$projet' />
		<input type='hidden' name='id_aff' value='$id_aff' />
		<input type='hidden' name='valider_enregistrement_nom_aff' value='y' />
		<table class='boireaus boireaus_alt'>
			<tr>
				<th>
					Nom&nbsp;:
				</th>
				<td>
					<input type='text' name='nom_aff' id='nom_aff' value=\"".$tab_aff_courant['nom']."\" onfocus=\"javascript:this.select()\" />
				</td>
			</tr>
			<tr>
				<th>
					Description&nbsp;:
				</th>
				<td>
					<textarea name='no_anti_inject_description_aff'>".$tab_aff_courant['description']."</textarea>
				</td>
			</tr>
		</table>
		<input type='submit' name='valider_nommage_affichage' value='Valider' />
	</fieldset>
</form>

<script type='text/javascript'>
	document.getElementById('nom_aff').focus();
</script>\n";


	require("../lib/footer.inc.php");
	die();
}




$afficher_listes=isset($_POST['afficher_listes']) ? $_POST['afficher_listes'] : (isset($_GET['afficher_listes']) ? $_GET['afficher_listes'] : NULL);

// Choix des élèves à afficher:
if(!isset($afficher_listes)) {
	echo "</p>\n";

	echo "<h2>Projet $projet : Affichage</h2>\n";

	// Affichage déjà choisi ou non
	$id_aff=isset($_POST['id_aff']) ? $_POST['id_aff'] : (isset($_GET['id_aff']) ? $_GET['id_aff'] : NULL);
	if((my_ereg_replace("[0-9]","",$id_aff)!="")||($id_aff=="")) {unset($id_aff);}



	if(isset($id_aff)) {
		$suppr=isset($_POST['suppr']) ? $_POST['suppr'] : NULL;
		if(isset($suppr)) {
			for($i=0;$i<count($suppr);$i++) {
				$sql="DELETE FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff' AND id_req='$suppr[$i]';";
				//echo "$sql<br />";
				$del=mysqli_query($GLOBALS["mysqli"], $sql);
			}

			// Si plus aucune requête ne reste, il faudrait peut-être supprimer le $id_aff... non... il sera réutilisé si on rajoute une requête et il sera perdu et absent de la base si on quitte la page sans ajouter de requête
			// Mais alors une nouvelle requête va être placée avec le même id_aff et le même nom_aff
		}
	}



	// Ajout d'une requête pour l'affichage en cours
	if(isset($_POST['ajouter'])) {
	//if((isset($_POST['ajouter']))||
	//((isset($_POST['modifier_requete']))&&($_POST['modifier_requete']=='y'))) {

		$id_clas_act=isset($_POST['id_clas_act']) ? $_POST['id_clas_act'] : array();
		$clas_fut=isset($_POST['clas_fut']) ? $_POST['clas_fut'] : array();
		$avec_lv1=isset($_POST['avec_lv1']) ? $_POST['avec_lv1'] : array();
		$sans_lv1=isset($_POST['sans_lv1']) ? $_POST['sans_lv1'] : array();
		$avec_lv2=isset($_POST['avec_lv2']) ? $_POST['avec_lv2'] : array();
		$sans_lv2=isset($_POST['sans_lv2']) ? $_POST['sans_lv2'] : array();
		$avec_lv3=isset($_POST['avec_lv3']) ? $_POST['avec_lv3'] : array();
		$sans_lv3=isset($_POST['sans_lv3']) ? $_POST['sans_lv3'] : array();
		$avec_autre=isset($_POST['avec_autre']) ? $_POST['avec_autre'] : array();
		$sans_autre=isset($_POST['sans_autre']) ? $_POST['sans_autre'] : array();

		$avec_profil=isset($_POST['avec_profil']) ? $_POST['avec_profil'] : array();
		$sans_profil=isset($_POST['sans_profil']) ? $_POST['sans_profil'] : array();

		$nom_requete=isset($_POST['nom_requete']) ? remplace_accents($_POST['nom_requete'], "all") : "";

		//$id_aff=isset($_POST['id_aff']) ? $_POST['id_aff'] : (isset($_GET['id_aff']) ? $_GET['id_aff'] : NULL);
		//if((my_ereg_replace("[0-9]","",$id_aff)!="")||($id_aff=="")) {unset($id_aff);}
		if(!isset($id_aff)) {
			$sql="SELECT MAX(id_aff) AS max_id_aff FROM gc_affichages;";
			//echo "$sql<br />";
			$res=mysqli_query($GLOBALS["mysqli"], $sql);
			if(mysqli_num_rows($res)==0) {
				$id_aff=1;
			}
			else {
				$lig_tmp=mysqli_fetch_object($res);
				$id_aff=$lig_tmp->max_id_aff+1;
			}
		}

		if((isset($_POST['modifier_requete']))&&($_POST['modifier_requete']=='y')) {
			$id_req=$_POST['id_req'];
			$sql="SELECT 1=1 FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff' AND id_req='$id_req';";
			//echo "$sql<br />";
			$res=mysqli_query($GLOBALS["mysqli"], $sql);
			if(mysqli_num_rows($res)==0) {
				echo "<p style='color:red'>La requête n°$id_req n'est pas associée à l'affichage n°$id_aff sur le projet $projet.</p>\n";

				require("../lib/footer.inc.php");
				die();
			}

			$sql="DELETE FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff' AND id_req='$id_req';";
			$menage=mysqli_query($GLOBALS["mysqli"], $sql);
			if(!$menage) {
				echo "<p style='color:red'>ERREUR lors du ménage préalable de la requête n°$id_req de l'affichage n°$id_aff sur le projet $projet.</p>\n";

				require("../lib/footer.inc.php");
				die();
			}
		}
		else {
			$sql="SELECT MAX(id_req) AS max_id_req FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff';";
			//echo "$sql<br />";
			$res=mysqli_query($GLOBALS["mysqli"], $sql);
			if(mysqli_num_rows($res)==0) {
				$id_req=1;
			}
			else {
				$lig_tmp=mysqli_fetch_object($res);
				$id_req=$lig_tmp->max_id_req+1;
			}
		}
		//echo "id_req=$id_req<br />";

		$nb_conditions_req=0;
		$tmp_nom_req="";
		if(count($id_clas_act)>0) {
			for($i=0;$i<count($id_clas_act);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='id_clas_act', valeur='$id_clas_act[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req=$id_clas_act[$i];
			}
		}
		if(count($clas_fut)>0) {
			for($i=0;$i<count($clas_fut);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='clas_fut', valeur='$clas_fut[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req=$clas_fut[$i];
			}
		}

		if(count($avec_lv1)>0) {
			for($i=0;$i<count($avec_lv1);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='avec_lv1', valeur='$avec_lv1[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req=$avec_lv1[$i];
				//echo "\$nb_conditions_req=$nb_conditions_req<br />";
			}
		}
		if(count($avec_lv2)>0) {
			for($i=0;$i<count($avec_lv2);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='avec_lv2', valeur='$avec_lv2[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req=$avec_lv2[$i];
			}
		}
		if(count($avec_lv3)>0) {
			for($i=0;$i<count($avec_lv3);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='avec_lv3', valeur='$avec_lv3[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req=$avec_lv3[$i];
			}
		}
		if(count($avec_autre)>0) {
			for($i=0;$i<count($avec_autre);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='avec_autre', valeur='$avec_autre[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req=$avec_autre[$i];
			}
		}
		if(count($avec_profil)>0) {
			for($i=0;$i<count($avec_profil);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='avec_profil', valeur='$avec_profil[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req=$avec_profil[$i];
			}
		}

		if(count($sans_lv1)>0) {
			for($i=0;$i<count($sans_lv1);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='sans_lv1', valeur='$sans_lv1[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req="Non_".$sans_lv1[$i];
			}
		}
		if(count($sans_lv2)>0) {
			for($i=0;$i<count($sans_lv2);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='sans_lv2', valeur='$sans_lv2[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req="Non_".$sans_lv2[$i];
			}
		}
		if(count($sans_lv3)>0) {
			for($i=0;$i<count($sans_lv3);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='sans_lv3', valeur='$sans_lv3[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req="Non_".$sans_lv3[$i];
			}
		}
		if(count($sans_autre)>0) {
			for($i=0;$i<count($sans_autre);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='sans_autre', valeur='$sans_autre[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req="Non_".$sans_autre[$i];
			}
		}
		if(count($sans_profil)>0) {
			for($i=0;$i<count($sans_profil);$i++) {
				$sql="INSERT INTO gc_affichages SET projet='$projet', id_aff='$id_aff', id_req='$id_req', type='sans_profil', valeur='$sans_profil[$i]', nom_requete='$nom_requete';";
				//echo "$sql<br />";
				$insert=mysqli_query($GLOBALS["mysqli"], $sql);
				$nb_conditions_req++;
				$tmp_nom_req="Non_".$sans_profil[$i];
			}
		}

		//echo "\$nb_conditions_req=$nb_conditions_req<br />";
		if(($nom_requete=="")&&($nb_conditions_req==1)&&($tmp_nom_req!="")) {
			// On nomme automatiquement la requête d'après l'unique condition:
			$nom_requete=$tmp_nom_req;
			$sql="UPDATE gc_affichages SET nom_requete='$nom_requete' WHERE projet='$projet' AND id_aff='$id_aff' AND id_req='$id_req';";
			//echo "$sql<br />";
			$update=mysqli_query($GLOBALS["mysqli"], $sql);
		}

		// Si aucune requête n'existe pour $id_aff, on supprime le nom aussi:
		$sql="SELECT 1=1 FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff';";
		$test_aff=mysqli_query($GLOBALS["mysqli"], $sql);
		if(mysqli_num_rows($test_aff)==0) {
			$sql="DELETE FROM gc_noms_affichages WHERE id_aff='$id_aff' AND projet='$projet';";
			$del=mysqli_query($GLOBALS["mysqli"], $sql);
			unset($id_aff);
		}

	} // FIN DE L'AJOUT D'UNE REQUETE (ajout ou modif)


	//========================================================================
	// Récupération de la liste des classes et options pour le projet en cours
	$sql="SELECT DISTINCT id_classe, classe FROM gc_divisions WHERE projet='$projet' AND statut='actuelle' ORDER BY classe;";
	$res_clas_act=mysqli_query($GLOBALS["mysqli"], $sql);
	$nb_clas_act=mysqli_num_rows($res_clas_act);
	if($nb_clas_act==0) {
		echo "<p>Aucune classe actuelle n'est encore choisie pour ce projet.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}
	
	$sql="SELECT DISTINCT classe FROM gc_divisions WHERE projet='$projet' AND statut='future' ORDER BY classe;";
	$res_clas_fut=mysqli_query($GLOBALS["mysqli"], $sql);
	$nb_clas_fut=mysqli_num_rows($res_clas_fut);
	if($nb_clas_fut==0) {
		echo "<p>Aucune classe future n'est encore définie pour ce projet.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}

	$classe_fut=array();
	while($lig=mysqli_fetch_object($res_clas_fut)) {
		$classe_fut[]=$lig->classe;
	}
	$classe_fut_0=$classe_fut;

	$sql="SELECT DISTINCT opt FROM gc_options WHERE projet='$projet' AND type='lv1' ORDER BY opt;";
	$res_lv1=mysqli_query($GLOBALS["mysqli"], $sql);
	$nb_lv1=mysqli_num_rows($res_lv1);
	
	$sql="SELECT DISTINCT opt FROM gc_options WHERE projet='$projet' AND type='lv2' ORDER BY opt;";
	$res_lv2=mysqli_query($GLOBALS["mysqli"], $sql);
	$nb_lv2=mysqli_num_rows($res_lv2);
	
	$sql="SELECT DISTINCT opt FROM gc_options WHERE projet='$projet' AND type='lv3' ORDER BY opt;";
	$res_lv3=mysqli_query($GLOBALS["mysqli"], $sql);
	$nb_lv3=mysqli_num_rows($res_lv3);
	
	$sql="SELECT DISTINCT opt FROM gc_options WHERE projet='$projet' AND type='autre' ORDER BY opt;";
	$res_autre=mysqli_query($GLOBALS["mysqli"], $sql);
	$nb_autre=mysqli_num_rows($res_autre);
	//========================================================================


	//=========================================================
	/*
	$tab_affichages=array();
	// Liste des affichages précédemment programmés pour ce projet:
	$sql="SELECT DISTINCT id_aff,projet FROM gc_affichages WHERE projet='$projet' ORDER BY id_aff;";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res)>0) {
		echo "<div id='div_autres_aff' style='width:10em; float: right; text-align:center;'>\n";
		//echo "Autres affichages&nbsp;: ";
		echo "<p>Liste des affichages définis&nbsp;: ";
		$cpt=0;
		while($lig_tmp=mysqli_fetch_object($res)) {
			if($cpt>0) {echo ", ";}
			$tab_affichages[$lig_tmp->id_aff]=get_infos_gc_affichage($lig_tmp->id_aff);
			if($tab_affichages[$lig_tmp->id_aff]['nomme']) {
				echo "<a href='?projet=$lig_tmp->projet&amp;id_aff=$lig_tmp->id_aff' title=\"Affichage n°".$lig_tmp->id_aff.":\n".$tab_affichages[$lig_tmp->id_aff]['description']."\">".$tab_affichages[$lig_tmp->id_aff]['nom']."</a>\n";
			}
			else {
				echo "<a href='?projet=$lig_tmp->projet&amp;id_aff=$lig_tmp->id_aff'>$lig_tmp->id_aff</a>\n";
			}
			$cpt++;
		}
		echo "</p>\n";

		if(isset($id_aff)) {
			echo "<hr />\n";
			echo "<div id='div_affich_listes' style='text-align:center;'>\n";
			echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
			echo "<input type='hidden' name='projet' value='$projet' />\n";
			echo "<input type='hidden' name='id_aff' value='$id_aff' />\n";
			echo "<p align='center'><b>Affichage n°$id_aff&nbsp;:</b> <input type='submit' name='afficher_listes' value='Afficher les listes' /></p>\n";
			echo "</form>\n";
			echo "</div>\n";
		}

		echo "</div>\n";
	}
	*/

	//=============================
	$classe_fut[]="Red";
	$classe_fut[]="Dep";
	$classe_fut[]=""; // Vide pour les Non Affectés

	// Remplir $classe_fut avant pour remplir les tableaux de couleurs,...
	include("lib_gc.php");
	// On y initialise le tableau des profils
	//=============================

	//=========================================
	// 20170707
	$tab_affichages=array();
	// Pouvoir utiliser des requêtes déjà définies dans l'affichage des listes:
	$sql="SELECT DISTINCT id_aff FROM gc_affichages WHERE projet='$projet' ORDER BY id_aff;";
	$res_req_aff=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res_req_aff)>0) {
		echo "<script type='text/javascript'>
function change_display(id) {
	if(document.getElementById(id)) {
		if(document.getElementById(id).style.display=='none') {document.getElementById(id).style.display='block'} else {document.getElementById(id).style.display='none'}
	}
}
</script>\n";

		//echo "<div style='float:right; width:20em;' class='fieldset_opacite50'>\n";
		echo "<div style='float:right; width:20em; background-color:white; padding:2px; border:1px solid black;'>\n";
		echo "<p class='bold'>Listes des affichages définis</p>\n";
		while($lig_req_aff=mysqli_fetch_object($res_req_aff)) {
			// 20140624
			$tab_aff_courant=get_infos_gc_affichage($lig_req_aff->id_aff);
			$tab_affichages[$lig_req_aff->id_aff]=$tab_aff_courant;
			//echo "<p><a href='#' onclick=\"change_display('id_aff_$lig_req_aff->id_aff')\">Affichage n°$lig_req_aff->id_aff</a>";
			echo "<p><a href='#' onclick=\"change_display('id_aff_".$lig_req_aff->id_aff."')\" title=\"Voir la liste des requêtes pour choisir laquelle:
- afficher pour répartition
- juste afficher la liste des élèves concernés.\">".$tab_aff_courant['nom']."</a>";
			echo " <a href='affiche_listes.php?projet=".$projet."&id_aff=".$lig_req_aff->id_aff."' title=\"Ajouter une requête à cet affichage.\" onclick=\"return confirm_abandon (this, change, '$themessage')\"><img src='../images/icons/add.png' class='icone16' alt='Ajouter' /></a>";

			echo " <a href='affiche_listes.php?id_aff=$lig_req_aff->id_aff&amp;projet=$projet&amp;afficher_listes=y' title=\"Ajouter une requête à cet affichage.\" onclick=\"return confirm_abandon (this, change, '$themessage')\"><img src='../images/icons/chercher.png' class='icone16' alt='Voir les listes et exports CSV/ODS' /></a>";

			echo "</p>\n";

			echo "<div id='id_aff_$lig_req_aff->id_aff' style='display:none;'>\n";
			//++++++++++++++++++++++++++++++++++++++++++++++
			//$sql="SELECT DISTINCT id_req FROM gc_affichages WHERE projet='$projet'AND id_aff='$lig_req_aff->id_aff' ORDER BY id_req;";
			$sql="SELECT DISTINCT id_req, nom_requete FROM gc_affichages WHERE projet='$projet'AND id_aff='$lig_req_aff->id_aff' ORDER BY id_req;";
			$res=mysqli_query($GLOBALS["mysqli"], $sql);
			if(mysqli_num_rows($res)>0) {
				$txt_requete="<ul>\n";
				while($lig=mysqli_fetch_object($res)) {
					$txt_requete.="<li>\n";
					//$txt_requete.="<b><a href='".$_SERVER['PHP_SELF']."?choix_affich=y&amp;requete_definie=y&amp;id_aff=$lig_req_aff->id_aff&amp;id_req=$lig->id_req&amp;projet=$projet' title=\"Affecter les élèves dans des classes.\">";
					$txt_requete.="<b><a href='".$_SERVER['PHP_SELF']."?id_aff=$lig_req_aff->id_aff&amp;projet=$projet&amp;afficher_listes=y#requete_$lig->id_req' title=\"Afficher la requête.\">";
					//https://127.0.0.1/steph/gepi_git_trunk/mod_genese_classes/affiche_listes.php?projet=futures_3emes_ALL1_2_classes&id_aff=3
					//https://127.0.0.1/steph/gepi_git_trunk/mod_genese_classes/affiche_listes.php?id_aff=3&projet=futures_3emes_ALL1_2_classes&afficher_listes=y#requete_1
					if($lig->nom_requete!="") {
						$txt_requete.="$lig->nom_requete (<em>Req.n°$lig->id_req</em>)";
					}
					else {
						$txt_requete.="Requête n°$lig->id_req";
					}
					$txt_requete.="</a></b>";
	
					//===========================================
					$id_req=$lig->id_req;
	
					$sql_ele="SELECT DISTINCT login FROM gc_eleves_options WHERE projet='$projet' AND classe_future!='Dep' AND classe_future!='Red'";
					$sql_ele_id_classe_act="";
					$sql_ele_classe_fut="";
	
					$sql="SELECT * FROM gc_affichages WHERE projet='$projet' AND id_aff='$lig_req_aff->id_aff' AND id_req='$id_req' ORDER BY type;";
					$res_tmp=mysqli_query($GLOBALS["mysqli"], $sql);
					while($lig_tmp=mysqli_fetch_object($res_tmp)) {
						switch($lig_tmp->type) {
							case 'id_clas_act':
								if($sql_ele_id_classe_act!='') {$sql_ele_id_classe_act.=" OR ";}
								$sql_ele_id_classe_act.="id_classe_actuelle='$lig_tmp->valeur'";
								break;
			
							case 'clas_fut':
								if($sql_ele_classe_fut!='') {$sql_ele_classe_fut.=" OR ";}
								$sql_ele_classe_fut.="classe_future='$lig_tmp->valeur'";
								break;
			
							case 'avec_lv1':
								$sql_ele.=" AND liste_opt LIKE '%|$lig_tmp->valeur|%'";
								break;
							case 'avec_lv2':
								$sql_ele.=" AND liste_opt LIKE '%|$lig_tmp->valeur|%'";
								break;
							case 'avec_lv3':
								$sql_ele.=" AND liste_opt LIKE '%|$lig_tmp->valeur|%'";
								break;
			
							case 'avec_autre':
								$sql_ele.=" AND liste_opt LIKE '%|$lig_tmp->valeur|%'";
								break;
			
							case 'sans_lv1':
								$sql_ele.=" AND liste_opt NOT LIKE '%|$lig_tmp->valeur|%'";
								break;
							case 'sans_lv2':
								$sql_ele.=" AND liste_opt NOT LIKE '%|$lig_tmp->valeur|%'";
								break;
							case 'sans_lv3':
								$sql_ele.=" AND liste_opt NOT LIKE '%|$lig_tmp->valeur|%'";
								break;
							case 'sans_autre':
								$sql_ele.=" AND liste_opt NOT LIKE '%|$lig_tmp->valeur|%'";
								break;
						}
					}
			
					//$tab_ele=array();
			
					if($sql_ele_id_classe_act!='') {$sql_ele.=" AND ($sql_ele_id_classe_act)";}
					if($sql_ele_classe_fut!='') {$sql_ele.=" AND ($sql_ele_classe_fut)";}
			
					$sql_ele.=";";
					//echo "$sql_ele<br />\n";
					$res_ele=mysqli_query($GLOBALS["mysqli"], $sql_ele);
	
					$txt_requete.=" <span style='font-size:small;font-style:italic;'>(".mysqli_num_rows($res_ele).")</span>";
					//tableau_eleves_req($id_aff, $id_req)
					//$txt_requete.=" - <a href='#' onclick=\"afficher_div('div_id_aff_".$lig_req_aff->id_aff."_id_req_".$lig->id_req."','y',100,100); return false;\"><img src='../images/vert.png' width='16' height='16' title='Afficher les élèves de la requête n°$id_req en infobulle' /></a>";
					//$txt_requete.=" - <a href='#' onmouseover=\"afficher_div('div_id_aff_".$lig_req_aff->id_aff."_id_req_".$lig->id_req."','y',100,100);\" onmouseout=\"cacher_div('div_id_aff_".$lig_req_aff->id_aff."_id_req_".$lig->id_req."')\"><img src='../images/vert.png' width='16' height='16' title='Afficher les élèves de la requête n°$id_req en infobulle' /></a>";

					$txt_requete.=" - <a href='#' onclick=\"afficher_nommer_req(".$lig_req_aff->id_aff.", ".$id_req."); return false;\"' title=\"Nommer la requête.\"><img src ='../images/icons/configure.png'
width='16' height='16' alt='Nommer' /></a>";

					$txt_requete.=" <a href='#' onclick=\"afficher_div('div_id_aff_".$lig_req_aff->id_aff."_id_req_".$lig->id_req."','y',100,100);\"><img src='../images/vert.png' width='16' height='16' title='Afficher les élèves de la requête n°$id_req en infobulle' /></a>";

					$txt_requete.=" <a href='affiche_listes.php?id_aff=$lig_req_aff->id_aff&amp;projet=$projet&amp;afficher_listes=y#requete_".$lig->id_req."' title=\"Afficher les élèves des requêtes de cet affichage, en pointant en particulier sur cette requête.\"><img src ='../images/icons/chercher.png'
width='16' height='16' alt='Afficher' /></a>";

					//https://127.0.0.1/steph/gepi_git_trunk/mod_genese_classes/affect_eleves_classes.php?choix_affich=y&requete_definie=y&id_aff=3&id_req=1&projet=futures_3emes_ALL1_2_classes
					$txt_requete.=" <a href='affect_eleves_classes.php?choix_affich=y&amp;requete_definie=y&amp;id_aff=$lig_req_aff->id_aff&amp;id_req=$lig->id_req&amp;projet=$projet' title='Affecter les élèves dans des classes'><img src ='../images/icons/tableau_couleur.png'
width='16' height='16' alt='Affecter' /></a>";

					$txt_requete.=" <a href='".$_SERVER['PHP_SELF']."?id_aff=$lig_req_aff->id_aff&amp;projet=$projet&amp;suppr_req=".$lig->id_req.add_token_in_url()."' title=\"Supprimer la requête.\" onclick=\"return confirm('Etes-vous sûr de vouloir supprimer cette requête?')\"><img src ='../images/delete16.png'
width='16' height='16' alt='Supprimer' /></a>";

					$txt_requete.="<br />";

					$titre_i="Affichage n°$lig_req_aff->id_aff - Requête n°$lig->id_req";
					$texte_i=tableau_eleves_req($lig_req_aff->id_aff, $lig->id_req);
					//$tabdiv_infobulle[]=creer_div_infobulle("div_id_aff_".$lig_req_aff->id_aff."_id_req_".$lig->id_req,$titre_i,"",$texte_i,"",18,0,'y','y','n','n');
					$tabdiv_infobulle[]=creer_div_infobulle("div_id_aff_".$lig_req_aff->id_aff."_id_req_".$lig->id_req,$titre_i,"",$texte_i,"",18,0,'y','y','n','n');

					//===========================================
	
	
					$sql="SELECT * FROM gc_affichages WHERE projet='$projet'AND id_aff='$lig_req_aff->id_aff' AND type='id_clas_act' AND id_req='$lig->id_req';";
					$res2=mysqli_query($GLOBALS["mysqli"], $sql);
					if(mysqli_num_rows($res2)>0) {
						$txt_requete.="Classe actuelle (";
						$cpt=0;
						while($lig2=mysqli_fetch_object($res2)) {
							if($cpt>0) {$txt_requete.=", ";}
							if(($lig2->valeur=='Red')||($lig2->valeur=='Arriv')) {
								$txt_requete.=$lig2->valeur;
							}
							else {
								$txt_requete.=get_class_from_id($lig2->valeur);
							}
							$cpt++;
						}
						$txt_requete.=")<br />";
					}
	
					$sql="SELECT * FROM gc_affichages WHERE projet='$projet'AND id_aff='$lig_req_aff->id_aff' AND type='clas_fut' AND id_req='$lig->id_req';";
					$res2=mysqli_query($GLOBALS["mysqli"], $sql);
					if(mysqli_num_rows($res2)>0) {
						$txt_requete.="Classe future (";
						$cpt=0;
						while($lig2=mysqli_fetch_object($res2)) {
							if($cpt>0) {$txt_requete.=", ";}
							$txt_requete.=$lig2->valeur;
							$cpt++;
						}
						$txt_requete.=")<br />";
					}
	
					$sql="SELECT * FROM gc_affichages WHERE projet='$projet'AND id_aff='$lig_req_aff->id_aff' AND type LIKE 'avec_%' AND id_req='$lig->id_req';";
					$res2=mysqli_query($GLOBALS["mysqli"], $sql);
					if(mysqli_num_rows($res2)>0) {
						$txt_requete.="Avec les options (<span style='color:green;'>";
						$cpt=0;
						while($lig2=mysqli_fetch_object($res2)) {
							if($cpt>0) {$txt_requete.=", ";}
							$txt_requete.=$lig2->valeur;
							$cpt++;
						}
						$txt_requete.="</span>)<br />";
					}
	
					$sql="SELECT * FROM gc_affichages WHERE projet='$projet'AND id_aff='$lig_req_aff->id_aff' AND type LIKE 'sans_%' AND id_req='$lig->id_req';";
					$res2=mysqli_query($GLOBALS["mysqli"], $sql);
					if(mysqli_num_rows($res2)>0) {
						$txt_requete.="Sans les options (<span style='color:red;'>";
						$cpt=0;
						while($lig2=mysqli_fetch_object($res2)) {
							if($cpt>0) {$txt_requete.=", ";}
							$txt_requete.=$lig2->valeur;
							$cpt++;
						}
						$txt_requete.="</span>)<br />";
					}
	
					$txt_requete.="</li>\n";
				}
				$txt_requete.="</ul>\n";
				echo $txt_requete;

			}
			//++++++++++++++++++++++++++++++++++++++++++++++
			echo "</div>\n";




		}
		echo "</div>\n";
	}
	//=========================================

	$titre_infobulle="Nommer la requête\n";
	$texte_infobulle="<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\" name=\"form_autre_requete\">
	".add_token_field()."
	<input type='hidden' name='nommer_requete' value=\"y\" />
	<p>Nommer la requête n°<span id='id_req_actuelle'></span>&nbsp;:<br /><input type='text' name='nom_requete' id='nommer_requete_nom_requete' value=\"\" /></p>
	<input type='hidden' name='projet' value=\"".$projet."\" />
	<input type='hidden' name='id_aff' id='id_aff_nommage' value=\"\" />
	<input type='hidden' name='id_req' id='id_req_nommage' value=\"\" />
	<p><input type='submit' value='Renommer' /></p>
</form>\n";
	$tabdiv_infobulle[]=creer_div_infobulle('div_set_nom_requete',$titre_infobulle,"",$texte_infobulle,"",14,0,'y','y','n','n');

	echo "<script type='text/javascript'>
	function afficher_nommer_req(id_aff, id_req) {
		document.getElementById('id_req_actuelle').innerHTML=id_req;
		document.getElementById('id_req_nommage').value=id_req;
		document.getElementById('id_aff_nommage').value=id_aff;
		afficher_div('div_set_nom_requete', 'y', 10, 10);

		document.getElementById('nommer_requete_nom_requete').focus();

		//new Ajax.Updater($('div_profil_'+cpt),'affiche_listes.php?set_profil=y&login='+current_login_ele+'&projet=$projet&profil='+profil+'".add_token_in_url(false)."',{method: 'get'});
	}
</script>";

	//=========================================================

	if(isset($id_aff)) {
		//echo "<p class='bold'>Liste de requêtes pour l'affichage n°$id_aff</p>\n";
		echo "<p class='bold'>Liste de requêtes pour l'affichage ".casse_mot($tab_affichages[$id_aff]['nom'], "min")." <a href='".$_SERVER['PHP_SELF']."?projet=$projet&amp;id_aff=$id_aff&amp;mode=nommer_aff' title=\"Nommer/décrire l'affichage.\"><img src='../images/icons/wizard.png' class='icone16' alt='Modifier' /></a></p>\n";
	}

	//================================
	// Formulaire d'ajout de requêtes:
	echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";

	$nom_requete="";
	if((isset($_GET['editer_requete']))&&(isset($_GET['id_req'])&&($_GET['id_req']!="")&&(mb_strlen(my_ereg_replace("[0-9]","",$_GET['id_req']))==0))) {
		$id_req=$_GET['id_req'];
		echo "<p class='bold'>Modification de la requête n°$id_req";

		$tab_ed_req=array();
		$sql="SELECT * FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff' AND id_req='$id_req';";
		//echo "$sql<br />\n";
		$res_edit_req=mysqli_query($GLOBALS["mysqli"], $sql);
		if(mysqli_num_rows($res_edit_req)>0) {
			$tmp_tab_nom_requete=array();
			while($lig_edit_req=mysqli_fetch_object($res_edit_req)) {
				$tab_ed_req[$lig_edit_req->type][]=$lig_edit_req->valeur;
				//echo "\$tab_ed_req[$lig_edit_req->type][]=$lig_edit_req->valeur<br />";

				if($lig_edit_req->nom_requete!="") {
					if(!in_array($lig_edit_req->nom_requete, $tmp_tab_nom_requete)) {
						echo " (<em>".$lig_edit_req->nom_requete."</em>)";
						$tmp_tab_nom_requete[]=$lig_edit_req->nom_requete;
					}
					$nom_requete=$lig_edit_req->nom_requete;
				}
			}
		}
		echo "</p>\n";
		echo "<input type='hidden' name='modifier_requete' value='y' />\n";
	}

	echo "<p>Nommer la requête&nbsp;: <input type='text' name='nom_requete' id='nom_requete' value=\"$nom_requete\" />";
	if($nom_requete=="") {echo " (<em title=\"Le nommage permet d'identifier plus rapidement une requête.\">optionnel</em>)";}
	echo "</p>\n";

	if(isset($id_aff)) {
		echo "<input type='hidden' name='id_aff' value='$id_aff' />\n";
	}
	if(isset($id_req)) {
		echo "<input type='hidden' name='id_req' value='$id_req' />\n";
	}

	echo "<table class='boireaus' border='1' summary='Choix des paramètres'>\n";
	echo "<tr>\n";
	echo "<th>Classe actuelle</th>\n";
	echo "<th>Classe future</th>\n";
	if($nb_lv1>0) {echo "<th>LV1</th>\n";}
	if($nb_lv2>0) {echo "<th>LV2</th>\n";}
	if($nb_lv3>0) {echo "<th>LV3</th>\n";}
	if($nb_autre>0) {echo "<th>Autre option</th>\n";}
	echo "<th>Profil</th>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td style='vertical-align:top; padding:2px;' class='lig-1'>\n";
	$cpt=0;
	while($lig=mysqli_fetch_object($res_clas_act)) {
		echo "<input type='checkbox' name='id_clas_act[]' id='id_clas_act_$cpt' value='$lig->id_classe' ";
		if((isset($tab_ed_req['id_clas_act']))&&(in_array($lig->id_classe,$tab_ed_req['id_clas_act']))) {
			echo "checked ";
		}
		echo "/><label for='id_clas_act_$cpt'>$lig->classe</label><br />\n";
		$cpt++;
	}
	echo "<input type='checkbox' name='id_clas_act[]' id='id_clas_act_$cpt' value='Red' ";
	if((isset($tab_ed_req['id_clas_act']))&&(in_array('Red',$tab_ed_req['id_clas_act']))) {
		echo "checked ";
	}
	echo "/><label for='id_clas_act_$cpt'>Redoublants</label><br />\n";
	$cpt++;
	//echo "<input type='checkbox' name='id_clas_act[]' id='id_clas_act_$cpt' value='Arr' /><label for='id_clas_act_$cpt'>Arrivants</label><br />\n";
	echo "<input type='checkbox' name='id_clas_act[]' id='id_clas_act_$cpt' value='Arriv' ";
	if((isset($tab_ed_req['id_clas_act']))&&(in_array('Arriv',$tab_ed_req['id_clas_act']))) {
		echo "checked ";
	}
	echo "/><label for='id_clas_act_$cpt'>Arrivants</label><br />\n";
	$cpt++;
	echo "</td>\n";

	//$classe_fut=array();
	$classe_fut=$classe_fut_0;
	echo "<td style='vertical-align:top; padding:2px;' class='lig-1'>\n";
	$cpt=0;
	//while($lig=mysqli_fetch_object($res_clas_fut)) {
	for($loop=0;$loop<count($classe_fut);$loop++) {
		//echo "<input type='checkbox' name='clas_fut[]' id='clas_fut_$cpt' value='$lig->classe' /><label for='clas_fut_$cpt'>$lig->classe</label><br />\n";

		//$sql="SELECT 1=1 FROM gc_eleve_fut_classe WHERE projet='$projet' AND classe='$lig->classe';";
		//$sql="SELECT 1=1 FROM gc_eleves_options WHERE projet='$projet' AND classe_future='$lig->classe';";
		$sql="SELECT 1=1 FROM gc_eleves_options WHERE projet='$projet' AND classe_future='".$classe_fut[$loop]."';";
		$res_test=mysqli_query($GLOBALS["mysqli"], $sql);
		if(mysqli_num_rows($res_test)>0) {
			//echo "<input type='checkbox' name='clas_fut[]' id='clas_fut_$cpt' value='$lig->classe' ";
			echo "<input type='checkbox' name='clas_fut[]' id='clas_fut_$cpt' value='".$classe_fut[$loop]."' ";
			//if((isset($tab_ed_req['clas_fut']))&&(in_array($lig->classe,$tab_ed_req['clas_fut']))) {
			if((isset($tab_ed_req['clas_fut']))&&(in_array($classe_fut[$loop],$tab_ed_req['clas_fut']))) {
				echo "checked ";
			}
			//echo "/><label for='clas_fut_$cpt'>$lig->classe</label><br />\n";
			echo "/><label for='clas_fut_$cpt'>".$classe_fut[$loop]." <span style='font-size:x-small'>(".mysqli_num_rows($res_test).")</span></label><br />\n";
		}
		else {
			//echo "_ $lig->classe<br />\n";
			echo "_ ".$classe_fut[$loop]."<br />\n";
		}

		//$classe_fut[]=$lig->classe;

		$cpt++;
	}
	echo "<input type='checkbox' name='clas_fut[]' id='clas_fut_$cpt' value='' ";
	if((isset($tab_ed_req['clas_fut']))&&(in_array("",$tab_ed_req['clas_fut']))) {
		echo "checked ";
	}
	echo "/><label for='clas_fut_$cpt'>Non encore affecté</label><br />\n";
	$cpt++;

	$classe_fut[]="Red";
	$classe_fut[]="Dep";
	$classe_fut[]=""; // Vide pour les Non Affectés

	/*
	echo "<input type='checkbox' name='clas_fut[]' id='clas_fut_$cpt' value='Red' /><label for='clas_fut_$cpt'>Red</label><br />\n";
	$cpt++;
	echo "<input type='checkbox' name='clas_fut[]' id='clas_fut_$cpt' value='Dep' /><label for='clas_fut_$cpt'>Dep</label><br />\n";
	$cpt++;
	*/
	echo "</td>\n";

	if($nb_lv1>0) {
		echo "<td style='vertical-align:top; padding:2px;' class='lig1'>\n";
			echo "<table class='boireaus' border='1' summary='LV1'>\n";
			echo "<tr>\n";
			echo "<th>Avec</th>\n";
			echo "<th>Sans</th>\n";
			echo "<th>LV</th>\n";
			echo "</tr>\n";
			$cpt=0;
			while($lig=mysqli_fetch_object($res_lv1)) {
				echo "<tr>\n";
				echo "<td>\n";
				echo "<input type='checkbox' name='avec_lv1[]' id='avec_lv1_$cpt' value='$lig->opt' ";
				if((isset($tab_ed_req['avec_lv1']))&&(in_array($lig->opt,$tab_ed_req['avec_lv1']))) {
					echo "checked ";
				}
				echo "/>\n";
				echo "</td>\n";
				echo "<td>\n";
				echo "<input type='checkbox' name='sans_lv1[]' id='sans_lv1_$cpt' value='$lig->opt' ";
				if((isset($tab_ed_req['sans_lv1']))&&(in_array($lig->opt,$tab_ed_req['sans_lv1']))) {
					echo "checked ";
				}
				echo "/>\n";
				echo "</td>\n";
				echo "<td";
				echo " onclick=\"permute_coche('lv1_".$cpt."')\"";
				echo " title=\"cliquez pour permuter les coches\">\n";
				echo "$lig->opt\n";
				echo "</td>\n";
				echo "</tr>\n";
				$cpt++;
			}
			echo "</table>\n";
		echo "</td>\n";
	}

	if($nb_lv2>0) {
		echo "<td style='vertical-align:top; padding:2px;' class='lig1'>\n";
			echo "<table class='boireaus' border='2' summary='LV2'>\n";
			echo "<tr>\n";
			echo "<th>Avec</th>\n";
			echo "<th>Sans</th>\n";
			echo "<th>LV</th>\n";
			echo "</tr>\n";
			$cpt=0;
			while($lig=mysqli_fetch_object($res_lv2)) {
				echo "<tr>\n";
				echo "<td>\n";
				echo "<input type='checkbox' name='avec_lv2[]' id='avec_lv2_$cpt' value='$lig->opt' ";
				if((isset($tab_ed_req['avec_lv2']))&&(in_array($lig->opt,$tab_ed_req['avec_lv2']))) {
					echo "checked ";
				}
				echo "/>\n";
				echo "</td>\n";
				echo "<td>\n";
				echo "<input type='checkbox' name='sans_lv2[]' id='sans_lv2_$cpt' value='$lig->opt' ";
				if((isset($tab_ed_req['sans_lv2']))&&(in_array($lig->opt,$tab_ed_req['sans_lv2']))) {
					echo "checked ";
				}
				echo "/>\n";
				echo "</td>\n";
				echo "<td";
				echo " onclick=\"permute_coche('lv2_".$cpt."')\"";
				echo " title=\"cliquez pour permuter les coches\">\n";
				echo "$lig->opt\n";
				echo "</td>\n";
				echo "</tr>\n";
				$cpt++;
			}
			echo "</table>\n";
		echo "</td>\n";
	}

	if($nb_lv3>0) {
		echo "<td style='vertical-align:top; padding:2px;' class='lig1'>\n";
			echo "<table class='boireaus' border='3' summary='LV3'>\n";
			echo "<tr>\n";
			echo "<th>Avec</th>\n";
			echo "<th>Sans</th>\n";
			echo "<th>LV</th>\n";
			echo "</tr>\n";
			$cpt=0;
			while($lig=mysqli_fetch_object($res_lv3)) {
				echo "<tr>\n";
				echo "<td>\n";
				echo "<input type='checkbox' name='avec_lv3[]' id='avec_lv3_$cpt' value='$lig->opt' ";
				if((isset($tab_ed_req['avec_lv3']))&&(in_array($lig->opt,$tab_ed_req['avec_lv3']))) {
					echo "checked ";
				}
				echo "/>\n";
				echo "</td>\n";
				echo "<td>\n";
				echo "<input type='checkbox' name='sans_lv3[]' id='sans_lv3_$cpt' value='$lig->opt' ";
				if((isset($tab_ed_req['sans_lv3']))&&(in_array($lig->opt,$tab_ed_req['sans_lv3']))) {
					echo "checked ";
				}
				echo "/>\n";
				echo "</td>\n";
				echo "<td";
				echo " onclick=\"permute_coche('lv3_".$cpt."')\"";
				echo " title=\"cliquez pour permuter les coches\">\n";
				echo "$lig->opt\n";
				echo "</td>\n";
				echo "</tr>\n";
				$cpt++;
			}
			echo "</table>\n";
		echo "</td>\n";
	}

	if($nb_autre>0) {
		echo "<td style='vertical-align:top; padding:2px;' class='lig1'>\n";
			echo "<table class='boireaus' border='1' summary='Option'>\n";
			echo "<tr>\n";
			echo "<th>Avec</th>\n";
			echo "<th>Sans</th>\n";
			echo "<th>Option</th>\n";
			echo "</tr>\n";
			$cpt=0;
			while($lig=mysqli_fetch_object($res_autre)) {
				echo "<tr>\n";
				echo "<td>\n";
				echo "<input type='checkbox' name='avec_autre[]' id='avec_autre_$cpt' value='$lig->opt' ";
				if((isset($tab_ed_req['avec_autre']))&&(in_array($lig->opt,$tab_ed_req['avec_autre']))) {
					echo "checked ";
				}
				echo "/>\n";
				echo "</td>\n";
				echo "<td>\n";
				echo "<input type='checkbox' name='sans_autre[]'  id='sans_autre_$cpt'value='$lig->opt' ";
				if((isset($tab_ed_req['sans_autre']))&&(in_array($lig->opt,$tab_ed_req['sans_autre']))) {
					echo "checked ";
				}
				echo "/>\n";
				echo "</td>\n";
				echo "<td";
				echo " onclick=\"permute_coche('autre_".$cpt."')\"";
				echo " title=\"cliquez pour permuter les coches\">\n";
				echo "$lig->opt\n";
				echo "</td>\n";
				echo "</tr>\n";
				$cpt++;
			}
			echo "</table>\n";
		echo "</td>\n";
	}

	echo "<td style='vertical-align:top; padding:2px;' class='lig-1'>\n";
		echo "<table class='boireaus' border='1' summary='Profil'>\n";
		echo "<tr>\n";
		echo "<th>Avec</th>\n";
		echo "<th>Sans</th>\n";
		echo "<th>Profil</th>\n";
		echo "</tr>\n";

		for($loop=0;$loop<count($tab_profil);$loop++) {
			echo "<tr>\n";
			echo "<td>\n";
			echo "<input type='checkbox' name='avec_profil[]' id='avec_profil_$loop' value='$tab_profil[$loop]' ";
			if((isset($tab_ed_req['avec_profil']))&&(in_array($tab_profil[$loop],$tab_ed_req['avec_profil']))) {echo "checked ";}
			echo "/>\n";
			echo "</td>\n";
			echo "<td>\n";
			echo "<input type='checkbox' name='sans_profil[]' id='sans_profil_$loop' value='$tab_profil[$loop]' ";
			if((isset($tab_ed_req['sans_profil']))&&(in_array($tab_profil[$loop],$tab_ed_req['sans_profil']))) {echo "checked ";}
			echo "/>\n";
			echo "</td>\n";
			echo "<td";
			if(isset($tab_profil_traduction_assoc[$tab_profil[$loop]])) {
				echo " title=\"".$tab_profil_traduction_assoc[$tab_profil[$loop]]."\"";
			}
			echo " onclick=\"permute_coche('profil_".$loop."')\"";
			echo " title=\"cliquez pour permuter les coches\">\n";
			if(isset($tab_couleur_profil_assoc[$tab_profil[$loop]])) {
				echo "<span style='color:".$tab_couleur_profil_assoc[$tab_profil[$loop]]."'>".$tab_profil[$loop]."</span>\n";
			}
			else {
				echo "$tab_profil[$loop]\n";
			}
			echo "</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	echo "</td>\n";

	// Pouvoir faire une recherche par niveau aussi?

	echo "</tr>\n";
	echo "</table>\n";

	echo "<script type='text/javascript'>
	function permute_coche(motif) {
		//alert(motif);
		if(document.getElementById('avec_'+motif)) {
			if(document.getElementById('avec_'+motif).checked==true) {
				document.getElementById('avec_'+motif).checked=false;
				document.getElementById('sans_'+motif).checked=true;
			}
			else {
				if(document.getElementById('sans_'+motif).checked==true) {
					document.getElementById('sans_'+motif).checked=false;
					document.getElementById('avec_'+motif).checked=false;
				}
				else {
					document.getElementById('avec_'+motif).checked=true;
				}
			}
		}
	}
</script>";

	echo "<input type='hidden' name='projet' value='$projet' />\n";
	//echo "<input type='hidden' name='is_posted' value='y' />\n";
	if(isset($_GET['editer_requete'])) {
		echo "<p align='center'><input type='submit' name='ajouter' value='Modifier la requête' /></p>\n";
	}
	else {
		echo "<p align='center'><input type='submit' name='ajouter' value='Ajouter/Valider' /></p>\n";
	}
	//================================

	//echo "<input type='checkbox' name='afficher_listes' value='y' /> Finaliser et afficher les listes\n";


	//================================
	// Suite du formulaire avec la liste des requêtes déjà effectuées:
	if(isset($id_aff)) {
		$chaine_id_requetes_existantes="";
		$sql="SELECT DISTINCT id_req FROM gc_affichages WHERE projet='$projet'AND id_aff='$id_aff' ORDER BY id_req;";
		$res=mysqli_query($GLOBALS["mysqli"], $sql);
		if(mysqli_num_rows($res)>0) {

			//20160629
			$tab_req_eff_tot_fut=array();
			for($loop_fut=0;$loop_fut<count($classe_fut);$loop_fut++) {
				$tab_req_eff_tot_fut[$loop_fut]=0;
			}

			echo "<p>Cochez les requêtes à supprimer, ou cliquez sur les images <img src='../images/edit16.png' class='icone16' alt='Modifier' /> pour modifier/corriger des requêtes.</p>";
			//echo "<table>\n";
			while($lig=mysqli_fetch_object($res)) {
				//$txt_requete="<div style='display:inline; width:20em; margin: 2px; border: 1px solid black;'>\n";
				$txt_requete="";
				$txt_requete.="<table summary='Requête n°$lig->id_req'>\n";
				$txt_requete.="<tr>\n";
				$txt_requete.="<td valign='top'>\n";
				$txt_requete.="<input type='checkbox' name='suppr[]' id='suppr_$lig->id_req' value='$lig->id_req' title=\"Cocher pour sélectionner à la suppression\net valider la suppression en cliquant sur le bouton Ajouter plus haut\n(quitte à ne rien cocher dans la requête à Ajouter).\" /> ";

				$chaine_id_requetes_existantes.=",'suppr_$lig->id_req'";

				$txt_requete.="</td>\n";
				$txt_requete.="<td>\n";
				//$txt_requete.="<b><label for='suppr_$lig->id_req'>Requête n°$lig->id_req</label></b>";
				$txt_requete.="<b><label for='suppr_$lig->id_req'>Requête n°$lig->id_req";
				$sql="SELECT DISTINCT nom_requete FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff' AND id_req='".$lig->id_req."' AND nom_requete!='';";
				//$txt_requete.="<br />".$sql."<br />";
				$res_tmp=mysqli_query($GLOBALS["mysqli"], $sql);
				while($lig_tmp=mysqli_fetch_object($res_tmp)) {
					$txt_requete.=" (<em>".$lig_tmp->nom_requete."</em>)";
				}
				$txt_requete.="</label> <a href='".$_SERVER['PHP_SELF']."?editer_requete=y&amp;id_aff=$id_aff&amp;id_req=$lig->id_req&amp;projet=$projet' title='Editer les paramètres de la requête'><img src ='../images/edit16.png'
width='16' height='16' alt='Editer' /></a></b>";

				$txt_requete.=" <a href='affect_eleves_classes.php?choix_affich=y&amp;requete_definie=y&amp;id_aff=$id_aff&amp;id_req=$lig->id_req&amp;projet=$projet' title='Affecter les élèves dans des classes'><img src ='../images/icons/tableau_couleur.png'
width='16' height='16' alt='Affecter' /></a></b>";

				//===========================================
				$id_req=$lig->id_req;

				$sql_ele="SELECT DISTINCT login FROM gc_eleves_options WHERE projet='$projet' AND classe_future!='Dep' AND classe_future!='Red'";
				$sql_ele_id_classe_act="";
				$sql_ele_classe_fut="";
				$sql_avec_profil="";
				$sql_sans_profil="";

				$sql="SELECT * FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff' AND id_req='$id_req' ORDER BY type;";
				$res_tmp=mysqli_query($GLOBALS["mysqli"], $sql);
				while($lig_tmp=mysqli_fetch_object($res_tmp)) {
					switch($lig_tmp->type) {
						case 'id_clas_act':
							if($sql_ele_id_classe_act!='') {$sql_ele_id_classe_act.=" OR ";}
							$sql_ele_id_classe_act.="id_classe_actuelle='$lig_tmp->valeur'";
							break;
		
						case 'clas_fut':
							if($sql_ele_classe_fut!='') {$sql_ele_classe_fut.=" OR ";}
							$sql_ele_classe_fut.="classe_future='$lig_tmp->valeur'";
							break;
		
						case 'avec_lv1':
							$sql_ele.=" AND liste_opt LIKE '%|$lig_tmp->valeur|%'";
							break;
						case 'avec_lv2':
							$sql_ele.=" AND liste_opt LIKE '%|$lig_tmp->valeur|%'";
							break;
						case 'avec_lv3':
							$sql_ele.=" AND liste_opt LIKE '%|$lig_tmp->valeur|%'";
							break;
		
						case 'avec_autre':
							$sql_ele.=" AND liste_opt LIKE '%|$lig_tmp->valeur|%'";
							break;
		
						case 'avec_profil':
							if($sql_avec_profil!='') {$sql_avec_profil.=" OR ";}
							$sql_avec_profil.="profil='$lig_tmp->valeur'";
							break;
		
						case 'sans_lv1':
							$sql_ele.=" AND liste_opt NOT LIKE '%|$lig_tmp->valeur|%'";
							break;
						case 'sans_lv2':
							$sql_ele.=" AND liste_opt NOT LIKE '%|$lig_tmp->valeur|%'";
							break;
						case 'sans_lv3':
							$sql_ele.=" AND liste_opt NOT LIKE '%|$lig_tmp->valeur|%'";
							break;
						case 'sans_autre':
							$sql_ele.=" AND liste_opt NOT LIKE '%|$lig_tmp->valeur|%'";
							break;

						case 'sans_profil':
							if($sql_sans_profil!='') {$sql_sans_profil.=" AND ";}
							$sql_sans_profil.="profil!='$lig_tmp->valeur'";
							break;
					}
				}
		
				//$tab_ele=array();

				if($sql_ele_id_classe_act!='') {$sql_ele.=" AND ($sql_ele_id_classe_act)";}
				if($sql_ele_classe_fut!='') {$sql_ele.=" AND ($sql_ele_classe_fut)";}
				if($sql_avec_profil!='') {$sql_ele.=" AND ($sql_avec_profil)";}
				if($sql_sans_profil!='') {$sql_ele.=" AND ($sql_sans_profil)";}

				// 20160629
				$tab_req_eff_fut=array();
				for($loop_fut=0;$loop_fut<count($classe_fut);$loop_fut++) {
					if(($classe_fut[$loop_fut]!="Dep")&&($classe_fut[$loop_fut]!="Red")) {
						$sql_tmp_eff=$sql_ele." AND classe_future='".$classe_fut[$loop_fut]."';";
						//echo $sql_tmp_eff."<br />";
						$res_tmp_eff=mysqli_query($GLOBALS["mysqli"], $sql_tmp_eff);
						$tab_req_eff_fut[$loop_fut]=mysqli_num_rows($res_tmp_eff);
						$tab_req_eff_tot_fut[$loop_fut]+=$tab_req_eff_fut[$loop_fut];
					}
				}
				/*
				$sql_tmp_eff=$sql_ele." AND classe_future=''";
				$res_tmp_eff=mysqli_query($GLOBALS["mysqli"], $sql_tmp_eff);
				$tab_req_eff_fut[]=mysqli_num_rows($res_tmp_eff);
				*/

				$sql_ele.=";";
				//echo "$sql_ele<br />\n";
				$res_ele=mysqli_query($GLOBALS["mysqli"], $sql_ele);

				/*
				// 20170707
				echo "\$classe_fut<pre>";
				print_r($classe_fut);
				echo "</pre>";

				echo "\$tab_req_eff_fut<pre>";
				print_r($tab_req_eff_fut);
				echo "</pre>";

				echo "\$tab_req_eff_fut<pre>";
				print_r($tab_req_eff_tot_fut);
				echo "</pre>";

				echo "\$tab_couleur_classe_fut<pre>";
				print_r($tab_couleur_classe_fut);
				echo "</pre>";
				*/

				$txt_requete.=" <span style='font-size:small;font-style:italic; font-weight:bold;".((mysqli_num_rows($res_ele)==0) ? "color:red;" : "")."' title=\"Effectif de la requête.\">(".mysqli_num_rows($res_ele).")</span> - ";

				//===========================================
				foreach($tab_req_eff_fut as $loop_fut => $current_eff_req_fut) {
					if($loop_fut>0) {
						$txt_requete.="|";
					}
					if($classe_fut[$loop_fut]!="") {
						//$txt_requete.="<span style='background-color:".$tab_couleur_classe_fut[$loop_fut]."' title=\"Effectif affecté en ".$classe_fut[$loop_fut]."\">".$classe_fut[$loop_fut].": ".$tab_req_eff_fut[$loop_fut]."</span>";
						$txt_requete.="<span style='background-color:".$tab_couleur_classe_fut[$loop_fut]."' title=\"Effectif affecté en ".$classe_fut[$loop_fut]." : ".$tab_req_eff_fut[$loop_fut]."\">".$tab_req_eff_fut[$loop_fut]."</span>";
					}
					else {
						//$txt_requete.="<span style='background-color:".$tab_couleur_classe_fut[$loop_fut]."' title=\"Effectif non affecté.\">...: ".$tab_req_eff_fut[$loop_fut]."</span>";
						//$txt_requete.="<span style='background-color:".$tab_couleur_classe_fut[$loop_fut]."' title=\"Effectif non affecté : ".$tab_req_eff_fut[$loop_fut]."\">".$tab_req_eff_fut[$loop_fut]."</span>";
						$txt_requete.="<span style='background-color:".$tab_couleur_classe_fut[$loop_fut]."' title=\"Effectif non affecté : ".$tab_req_eff_fut[$loop_fut]."\">".$tab_req_eff_fut[$loop_fut]."</span>";
					}
				}
				$txt_requete.="<br />";
				//===========================================


				$sql="SELECT * FROM gc_affichages WHERE projet='$projet'AND id_aff='$id_aff' AND type='id_clas_act' AND id_req='$lig->id_req';";
				$res2=mysqli_query($GLOBALS["mysqli"], $sql);
				if(mysqli_num_rows($res2)>0) {
					$txt_requete.="Classe actuelle (";
					$cpt=0;
					while($lig2=mysqli_fetch_object($res2)) {
						if($cpt>0) {$txt_requete.=", ";}
						if(($lig2->valeur=='Red')||($lig2->valeur=='Arriv')) {
							$txt_requete.=$lig2->valeur;
						}
						else {
							$txt_requete.=get_class_from_id($lig2->valeur);
						}
						$cpt++;
					}
					$txt_requete.=")<br />";
				}

				$sql="SELECT * FROM gc_affichages WHERE projet='$projet'AND id_aff='$id_aff' AND type='clas_fut' AND id_req='$lig->id_req';";
				$res2=mysqli_query($GLOBALS["mysqli"], $sql);
				if(mysqli_num_rows($res2)>0) {
					$txt_requete.="Classe future (";
					$cpt=0;
					while($lig2=mysqli_fetch_object($res2)) {
						if($cpt>0) {$txt_requete.=", ";}
						$txt_requete.=$lig2->valeur;
						$cpt++;
					}
					$txt_requete.=")<br />";
				}

				$sql="SELECT * FROM gc_affichages WHERE projet='$projet'AND id_aff='$id_aff' AND type LIKE 'avec_%' AND id_req='$lig->id_req';";
				$res2=mysqli_query($GLOBALS["mysqli"], $sql);
				if(mysqli_num_rows($res2)>0) {
					$txt_requete.="Avec les options (<span style='color:green;'>";
					$cpt=0;
					while($lig2=mysqli_fetch_object($res2)) {
						if($cpt>0) {$txt_requete.=", ";}
						$txt_requete.=$lig2->valeur;
						$cpt++;
					}
					$txt_requete.="</span>)<br />";
				}

				$sql="SELECT * FROM gc_affichages WHERE projet='$projet'AND id_aff='$id_aff' AND type LIKE 'sans_%' AND id_req='$lig->id_req';";
				$res2=mysqli_query($GLOBALS["mysqli"], $sql);
				if(mysqli_num_rows($res2)>0) {
					$txt_requete.="Sans les options (<span style='color:red;'>";
					$cpt=0;
					while($lig2=mysqli_fetch_object($res2)) {
						if($cpt>0) {$txt_requete.=", ";}
						$txt_requete.=$lig2->valeur;
						$cpt++;
					}
					$txt_requete.="</span>)<br />";
				}

				$txt_requete.="</td>\n";
/*
echo "<pre>";
print_r($classe_fut);
echo "</pre>";

echo "<pre>";
print_r($tab_req_eff_fut);
echo "</pre>";

echo "<pre>";
print_r($tab_couleur_classe_fut);
echo "</pre>";

				$txt_requete.="<td style='vertical-align:top'>\n";
				foreach($tab_req_eff_fut as $loop_fut => $current_eff_req_fut) {
					if($loop_fut>0) {
						$txt_requete.="|";
					}
					if($classe_fut[$loop_fut]!="") {
						//$txt_requete.="<span style='background-color:".$tab_couleur_classe_fut[$loop_fut]."' title=\"Effectif affecté en ".$classe_fut[$loop_fut]."\">".$classe_fut[$loop_fut].": ".$tab_req_eff_fut[$loop_fut]."</span>";
						$txt_requete.="<span style='background-color:".$tab_couleur_classe_fut[$loop_fut]."' title=\"Effectif affecté en ".$classe_fut[$loop_fut]." : ".$tab_req_eff_fut[$loop_fut]."\">".$tab_req_eff_fut[$loop_fut]."</span>";
					}
					else {
						//$txt_requete.="<span style='background-color:".$tab_couleur_classe_fut[$loop_fut]."' title=\"Effectif non affecté.\">...: ".$tab_req_eff_fut[$loop_fut]."</span>";
						$txt_requete.="<span style='background-color:".$tab_couleur_classe_fut[$loop_fut]."' title=\"Effectif non affecté : ".$tab_req_eff_fut[$loop_fut]."\">".$tab_req_eff_fut[$loop_fut]."</span>";
					}
				}
				$txt_requete.="</td>\n";
*/
				//===========================================


				$txt_requete.="</tr>\n";
				$txt_requete.="</table>\n";
				//$txt_requete.="</div>\n";

				echo $txt_requete;
/*
echo "<pre>";
print_r($classe_fut);
echo "</pre>";

echo "<pre>";
print_r($tab_req_eff_fut);
echo "</pre>";

echo "<pre>";
print_r($tab_couleur_classe_fut);
echo "</pre>";
*/
			}
			//echo "</table>\n";

			// Ajouter un bouton pour supprimer
			echo "<p>Cochez les requêtes à supprimer <img src='../images/delete16.png' class='icone16' />.<br /><a href='#' onclick=\"cocher_toutes_les_requetes(); alert('Si vous validez avec le bouton Ajouter, toutes les requêtes cochées seront supprimées.'); return false;\">Cocher toutes les requêtes dans la perspective de les supprimer.</a></p>
<script type='text/javascript'>
	function cocher_toutes_les_requetes() {
		var req=new Array(".mb_substr($chaine_id_requetes_existantes,1).");
		for(i=0;i<req.length;i++) {
			if(document.getElementById(req[i])) {
				document.getElementById(req[i]).checked=true;
			}
		}
	}
</script>\n";


/*
			// Attention : totaux pertinents que si les requetes sont disjointes.
			for($loop_fut=0;$loop_fut<count($classe_fut);$loop_fut++) {
				if(($classe_fut[$loop_fut]!="Dep")&&($classe_fut[$loop_fut]!="Red")) {
					$sql_tmp_eff=$sql_ele." AND classe_future='".$classe_fut[$loop_fut]."';";
					//echo $sql_tmp_eff."<br />";
					$res_tmp_eff=mysqli_query($GLOBALS["mysqli"], $sql_tmp_eff);
					$tab_req_eff_fut[$loop_fut]=mysqli_num_rows($res_tmp_eff);
					$tab_req_eff_fut[$loop_fut]+=$tab_req_eff_fut[$loop_fut];
				}
			}
*/


		}
	}

	echo "</form>\n";
	//================================

	if(!isset($txt_requete)) {
		echo "<script type='text/javascript'>
	if(document.getElementById('div_affich_listes')) {document.getElementById('div_affich_listes').style.display='none';}
	if(document.getElementById('nom_requete')) {document.getElementById('nom_requete').focus();}
</script>\n";
	}

	echo "<script type='text/javascript'>
	if(document.getElementById('nom_requete')) {document.getElementById('nom_requete').focus();}
</script>\n";

	echo "<p style='margin-top:2em;'><i>NOTES&nbsp;:</i></p>\n";
	echo "<ul>\n";
	echo "<li>Les colonnes Classe actuelle et Classe future sont traitées suivant le mode OU<br />
	Si vous cochez deux classes, les élèves pris en compte seront '<i>membre de Classe 1 OU membre de Classe 2</i>'</li>\n";
	echo "<li>Les colonnes d'options sont traitées suivant le mode ET.<br />
	Ce sera par exemple '<i>Avec AGL1 ET Avec ESP2 ET Avec LATIN ET Sans DECP3</i>'</li>\n";
	//echo "<li></li>\n";
	echo "</ul>\n";

}
else {
	//=============================================================================================
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//=============================================================================================
	// Les requêtes sont choisies, on va procéder à l'affichage des élèves correspondants
	// Affichage des listes pour $projet et $id_aff

	echo " | <a href='".$_SERVER['PHP_SELF']."?projet=$projet'>Autre sélection</a>";

	$id_aff=isset($_POST['id_aff']) ? $_POST['id_aff'] : (isset($_GET['id_aff']) ? $_GET['id_aff'] : NULL);
	if((preg_replace("/[0-9]/","",$id_aff)!="")||($id_aff=="")) {unset($id_aff);}
	if(!isset($id_aff)) {
		echo "<p>ERREUR: La variable 'id_aff' n'est pas affectée.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}

	//===================================================
	// 20170708
	include("gc_func.inc.php");
	$classe_fut=get_classe_fut();
	$classe_fut_0=$classe_fut;

	$classe_fut[]="Red";
	$classe_fut[]="Dep";
	$classe_fut[]=""; // Vide pour les Non Affectés
	include("lib_gc.php");
	//$classe_fut=$classe_fut_0;
	//===================================================

	$tab_aff_courant=get_infos_gc_affichage($id_aff);

	echo " | <a href='".$_SERVER['PHP_SELF']."?projet=$projet&amp;id_aff=$id_aff'>Modifier la liste de requêtes pour l'".casse_mot($tab_aff_courant['nom'], "min")."</a>";
	echo "</p>\n";

	echo "<div style='float:right; width:5em; text-align:center;' class='fieldset_opacite50'>
	<a href='affiche_listes2.php?projet=".$projet."&id_aff=$id_aff'>Affichage élèves seuls</a>
</div>";

	echo "<h2>Projet $projet : Affichage</h2>\n";


	//=========================================================
	// Liste des affichages précédemment programmés pour ce projet:
	$sql="SELECT DISTINCT id_aff,projet FROM gc_affichages WHERE projet='$projet' ORDER BY id_aff;";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res)>0) {
		echo "<div id='div_autres_aff' style='width:10em; float: right; text-align:center;'>\n";
		//echo "Autres affichages&nbsp;: ";
		echo "<p>Liste des affichages&nbsp;: ";
		$cpt=0;
		while($lig_tmp=mysqli_fetch_object($res)) {
			if($cpt>0) {echo ", ";}
			$tab_affichages[$lig_tmp->id_aff]=get_infos_gc_affichage($lig_tmp->id_aff);
			if($tab_affichages[$lig_tmp->id_aff]['nomme']) {
				echo "<a href='?projet=$lig_tmp->projet&amp;id_aff=$lig_tmp->id_aff&amp;afficher_listes=y' title=\"Affichage n°".$lig_tmp->id_aff.":\n".$tab_affichages[$lig_tmp->id_aff]['description']."\">".$tab_affichages[$lig_tmp->id_aff]['nom']."</a>\n";
			}
			else {
				echo "<a href='?projet=$lig_tmp->projet&amp;id_aff=$lig_tmp->id_aff&amp;afficher_listes=y'>$lig_tmp->id_aff</a>\n";
			}
			//echo "<a href='?projet=$lig_tmp->projet&amp;id_aff=$lig_tmp->id_aff&amp;afficher_listes=y'>$lig_tmp->id_aff</a>\n";
			$cpt++;
		}
		echo "</p>\n";

		echo "<hr />\n";

		echo "<div id='div_ods' style='text-align:center; border:1px solid black;' class='fieldset_opacite50'>\n";
		echo "</div>\n";
	
		echo "<hr />\n";

		echo "<div id='div_divers' style='text-align:center;'>\n";
		echo "<a href='#' onclick=\"afficher_div('recap_eff','y',-100,20);return false;\">Effectifs des requêtes</a>";
		echo "</div>\n";

		echo "</div>\n";
	}
	//=========================================================


	// Affichage...
	//Construire la requête SQL et l'afficher

	$eff_lv1=-1;
	$eff_lv2=-1;
	$eff_lv3=-1;
	$eff_autre=-1;

	/*
	function echo_debug($texte) {
		$debug=0;
		if($debug==1) {
			echo $texte;
		}
	}
	*/

	//$id_aff=isset($_POST['id_aff']) ? $_POST['id_aff'] : NULL;
	$id_aff=isset($_POST['id_aff']) ? $_POST['id_aff'] : (isset($_GET['id_aff']) ? $_GET['id_aff'] : NULL);
	if((preg_replace("/[0-9]/","",$id_aff)!="")||($id_aff=="")) {unset($id_aff);}
	if(!isset($id_aff)) {
		echo "<p>ERREUR: La variable 'id_aff' n'est pas affectée.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}
	else {
		unset($tab_id_req);
		$tab_id_req=array();
		$sql="SELECT DISTINCT id_req FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff' ORDER BY id_req;";
		$res=mysqli_query($GLOBALS["mysqli"], $sql);
		while($lig=mysqli_fetch_object($res)) {
			$tab_id_req[]=$lig->id_req;
		}
	}

	// 20180702
	$nom_affichage=get_valeur_champ('gc_noms_affichages',"id_aff='".$id_aff."' AND projet='".$projet."'", "nom");
	$description_affichage=get_valeur_champ('gc_noms_affichages',"id_aff='".$id_aff."' AND projet='".$projet."'", "description");
	if($nom_affichage=='') {
		echo "<p class='bold'>Affichage n°".$id_aff." <a href='".$_SERVER['PHP_SELF']."?projet=$projet&amp;id_aff=$id_aff&amp;mode=nommer_aff' title=\"Nommer/décrire l'affichage.\"><img src='../images/icons/wizard.png' class='icone16' alt='Modifier' /></a></p>";
	}
	else {
		echo "<p class='bold' title=\"".str_replace('"', "'", $description_affichage)."\">Affichage ".$nom_affichage." <a href='".$_SERVER['PHP_SELF']."?projet=$projet&amp;id_aff=$id_aff&amp;mode=nommer_aff' title=\"Nommer/décrire l'affichage.\"><img src='../images/icons/wizard.png' class='icone16' alt='Modifier' /></a></p>";
	}


// 20140623
//necessaire_affichage_infobulle_bull_simpl();


//============================================================
//============================================================
//============================================================
	//On n'effectue qu'une fois ces requêtes communes hors de la boucle sur la liste des requêtes associées à l'affichage choisi

	$classe_fut=array();
	$sql="SELECT DISTINCT classe FROM gc_divisions WHERE projet='$projet' AND statut='future' ORDER BY classe;";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res)==0) {
		echo "<p>Aucune classe future n'est encore définie pour ce projet.</p>\n";
		// Est-ce que cela doit vraiment bloquer la saisie des options?
		require("../lib/footer.inc.php");
		die();
	}
	else {
		$tab_opt_exclue=array();

		$chaine_classes_fut="tab_classes_fut=new Array(";
		$cpt_tmp=0;
		while($lig=mysqli_fetch_object($res)) {
			$classe_fut[]=$lig->classe;
			if($cpt_tmp>0) {$chaine_classes_fut.=",";}
			$chaine_classes_fut.="'".$lig->classe."'";

			$tab_opt_exclue["$lig->classe"]=array();
			//=========================
			// Options exlues pour la classe
			$sql="SELECT opt_exclue FROM gc_options_classes WHERE projet='$projet' AND classe_future='$lig->classe';";
			$res_opt_exclues=mysqli_query($GLOBALS["mysqli"], $sql);
			while($lig_opt_exclue=mysqli_fetch_object($res_opt_exclues)) {
				$tab_opt_exclue["$lig->classe"][]=mb_strtoupper($lig_opt_exclue->opt_exclue);
			}
			//=========================

			$cpt_tmp++;
		}
		$classe_fut[]="Red";
		$classe_fut[]="Dep";
		$classe_fut[]=""; // Vide pour les Non Affectés

		$chaine_classes_fut.=",'Red','Dep','')";
	}
	
	$id_classe_actuelle=array();
	$classe_actuelle=array();
	$sql="SELECT DISTINCT id_classe,classe FROM gc_divisions WHERE projet='$projet' AND statut='actuelle' ORDER BY classe;";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res)==0) {
		echo "<p>Aucune classe actuelle n'est encore sélectionnée pour ce projet.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}
	else {
		while($lig=mysqli_fetch_object($res)) {
			$id_classe_actuelle[]=$lig->id_classe;
			$classe_actuelle[]=$lig->classe;
		}

		// On ajoute redoublants et arrivants
		$id_classe_actuelle[]='Red';
		$classe_actuelle[]='Red';
	
		$id_classe_actuelle[]='Arriv';
		$classe_actuelle[]='Arriv';
	}

	$chaine_lv1="tab_lv1=new Array(";
	$lv1=array();
	$sql="SELECT * FROM gc_options WHERE projet='$projet' AND type='lv1' ORDER BY opt;";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res)>0) {
		$cpt_tmp=0;
		while($lig=mysqli_fetch_object($res)) {
			$lv1[]=$lig->opt;
			if($cpt_tmp>0) {$chaine_lv1.=",";}
			$chaine_lv1.="'".$lig->opt."'";
			$cpt_tmp++;
		}
	}
	$chaine_lv1.=")";


	$chaine_lv2="tab_lv2=new Array(";
	$lv2=array();
	$sql="SELECT * FROM gc_options WHERE projet='$projet' AND type='lv2' ORDER BY opt;";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res)>0) {
		$cpt_tmp=0;
		while($lig=mysqli_fetch_object($res)) {
			$lv2[]=$lig->opt;
			if($cpt_tmp>0) {$chaine_lv2.=",";}
			$chaine_lv2.="'".$lig->opt."'";
			$cpt_tmp++;
		}
	}
	$chaine_lv2.=")";
	
	$chaine_lv3="tab_lv3=new Array(";
	$lv3=array();
	$sql="SELECT * FROM gc_options WHERE projet='$projet' AND type='lv3' ORDER BY opt;";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res)>0) {
		$cpt_tmp=0;
		while($lig=mysqli_fetch_object($res)) {
			$lv3[]=$lig->opt;
			if($cpt_tmp>0) {$chaine_lv3.=",";}
			$chaine_lv3.="'".$lig->opt."'";
			$cpt_tmp++;
		}
	}
	$chaine_lv3.=")";
	
	$autre_opt=array();
	$sql="SELECT * FROM gc_options WHERE projet='$projet' AND type='autre' ORDER BY opt;";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res)>0) {
		while($lig=mysqli_fetch_object($res)) {
			$autre_opt[]=$lig->opt;
		}
	}

	echo "<script type='text/javascript'>
	// Tableau de listes pour assurer la colorisation des lignes
	var $chaine_classes_fut;
	var $chaine_lv1;
	var $chaine_lv2;
	var $chaine_lv3;
</script>\n";

	/*
	//=============================
	include("lib_gc.php");
	// On y initialise les couleurs
	// Il faut que le tableaux $classe_fut soit initialisé.
	//=============================
	*/

	necessaire_bull_simple();

	//=============================
	$titre="Sélection du profil";
	$texte="<p style='text-align:center;'>";
	for($loop=0;$loop<count($tab_profil);$loop++) {
		if($loop>0) {$texte.=" - ";}
		$texte.="<a href='#' onclick=\"set_profil('".$tab_profil[$loop]."');return false;\">$tab_profil[$loop]</a>";
	}
	$texte.="</p>\n";
	$tabdiv_infobulle[]=creer_div_infobulle('div_set_profil',$titre,"",$texte,"",14,0,'y','y','n','n');


	echo "<script type='text/javascript'>
	var couleur_profil=new Array($chaine_couleur_profil);
	var tab_profil=new Array($chaine_profil);

	function set_profil(profil) {
		var cpt=document.getElementById('profil_courant').value;
		document.getElementById('profil_'+cpt).value=profil;

		for(m=0;m<couleur_profil.length;m++) {
			if(document.getElementById('profil_'+cpt).value==tab_profil[m]) {
				document.getElementById('div_profil_'+cpt).style.color=couleur_profil[m];
			}
		}

		//document.getElementById('div_profil_'+cpt).innerHTML=profil;

		current_login_ele=document.getElementById('login_eleve_'+cpt).value;

		new Ajax.Updater($('div_profil_'+cpt),'affiche_listes.php?set_profil=y&login='+current_login_ele+'&projet=$projet&profil='+profil+'".add_token_in_url(false)."',{method: 'get'});

		cacher_div('div_set_profil');
	}

	function affiche_set_profil(cpt) {
		document.getElementById('profil_courant').value=cpt;
		afficher_div('div_set_profil','y',100,100);
	}
</script>\n";
	//=============================

//============================================================
//============================================================
//============================================================

	echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";

	echo "<input type='hidden' name='profil_courant' id='profil_courant' value='-1' />\n";

	// Colorisation
	/*
		echo "<select name='colorisation' id='colorisation' onchange=\"lance_colorisation();document.getElementById('colorisation_chgt_classe').value=document.forms[0].elements['colorisation'].options[document.forms[0].elements['colorisation'].selectedIndex].value\">
	*/
	$colorisation_courante="classe_fut";
	$classe_fut_checked_ou_pas="";
	$lv1_checked_ou_pas="";
	$lv2_checked_ou_pas="";
	$profil_checked_ou_pas="";
	$aucune_checked_ou_pas="";
	if((isset($_POST['colorisation_chgt_classe']))&&($_POST['colorisation_chgt_classe']!="")) {
		$colorisation_courante=$_POST['colorisation_chgt_classe'];

		if($colorisation_courante=='classe_fut') {
			$classe_fut_checked_ou_pas=" selected";
		}
		elseif($colorisation_courante=='lv1') {
			$lv1_checked_ou_pas=" selected";
		}
		elseif($colorisation_courante=='lv2') {
			$lv2_checked_ou_pas=" selected";
		}
		elseif($colorisation_courante=='profil') {
			$profil_checked_ou_pas=" selected";
		}
		elseif($colorisation_courante=='aucune') {
			$aucune_checked_ou_pas=" selected";
		}
	}
	echo "<p>Colorisation&nbsp;: ";
	echo "<select name='colorisation' id='colorisation' onchange=\"lance_colorisation();update_champs_colorisation_chgt_classe();\">
	<option value='classe_fut'$classe_fut_checked_ou_pas>Classe future</option>
	<option value='lv1'$lv1_checked_ou_pas>LV1</option>
	<option value='lv2'$lv2_checked_ou_pas>LV2</option>
	<option value='profil'$profil_checked_ou_pas>Profil</option>
	<option value='aucune'$aucune_checked_ou_pas>Aucune</option>
	</select>\n";
	echo "</p>\n";

//============================================================
//============================================================
//============================================================

	$tab_ele_toutes_requetes=array();

	$fich_csv="";

	$cpt=0;
	for($loop=0;$loop<count($tab_id_req);$loop++) {
		// Initialisation:
		$contenu_affichage_requete_courante="";

		// Pour lister les classes futures dans la requête courante
		$tab_classes_fut_de_cette_requete=array();

		$id_req=$tab_id_req[$loop];

		$id_clas_act=array();
		$clas_fut=array();
		$avec_lv1=array();
		$avec_lv2=array();
		$avec_lv3=array();
		$avec_autre=array();
		$sans_lv1=array();
		$sans_lv2=array();
		$sans_lv3=array();
		$sans_autre=array();

		//echo "<p><b>Requête n°$id_req</b><br />";
		$fich_csv.="Requête n°$id_req\n";

		$chaine_nom_requete="";
		$sql="SELECT DISTINCT nom_requete FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff' AND id_req='".$id_req."' AND nom_requete!='';";
		//$txt_requete.="<br />".$sql."<br />";
		$res_tmp=mysqli_query($GLOBALS["mysqli"], $sql);
		while($lig_tmp=mysqli_fetch_object($res_tmp)) {
			$chaine_nom_requete=" (<em>".$lig_tmp->nom_requete."</em>)";
		}
//				$txt_requete.=" <a href='affect_eleves_classes.php?choix_affich=y&amp;requete_definie=y&amp;id_aff=$id_aff&amp;id_req=$lig->id_req&amp;projet=$projet' title='Affecter les élèves dans des classes'><img src ='../images/icons/tableau_couleur.png' width='16' height='16' alt='Affecter' /></a></b>";

//https://127.0.0.1/steph/gepi_git_trunk/mod_genese_classes/affiche_listes.php?editer_requete=y&id_aff=7&id_req=1&projet=futures_5emes

		//$lien_affect="<p><a name='requete_$id_req'></a><a href='affect_eleves_classes.php?projet=$projet&amp;choix_affich=1";
		$lien_affect="<p><a name='requete_$id_req'></a><a href='affiche_listes.php?editer_requete=y&amp;projet=$projet&amp;id_aff=$id_aff&amp;id_req=$id_req";
		//$fin_lien_affect="' target='_blank'";
		$fin_lien_affect="' alt='Modifier la requête n°$id_req' title='Modifier la requête n°$id_req'><b>Requête n°$id_req</b>".$chaine_nom_requete."</a>";
		$fin_lien_affect.=" - <a href='#' onclick=\"afficher_div('div_requete_$id_req','y',100,100); return false;\"><img src='../images/vert.png' width='16' height='16' title='Afficher la requête n°$id_req en infobulle' /></a>";

		$fin_lien_affect.=" <a href='affect_eleves_classes.php?choix_affich=y&amp;requete_definie=y&amp;projet=$projet&amp;id_aff=$id_aff&amp;id_req=$id_req' title=\"Affecter les élèves de cette requête dans des classes.\"><img src='../images/icons/tableau_couleur.png' class='icone16' alt='Affecter' /></a>";

		$fin_lien_affect.="<br />";


		$tab_requete=array();
		$tab_requete_csv=array();

		//=========================
		// Début de la requête à forger pour ne retenir que les élèves souhaités
		$sql_ele="SELECT DISTINCT login FROM gc_eleves_options WHERE projet='$projet' AND classe_future!='Dep' AND classe_future!='Red'";

		$sql_ele_id_classe_act="";
		$sql_ele_classe_fut="";
		$sql_avec_profil="";
		$sql_sans_profil="";
		//=========================

		$sql="SELECT * FROM gc_affichages WHERE projet='$projet' AND id_aff='$id_aff' AND id_req='$id_req' ORDER BY type;";
		$res=mysqli_query($GLOBALS["mysqli"], $sql);
		while($lig=mysqli_fetch_object($res)) {
			switch($lig->type) {
				case 'id_clas_act':
					$id_clas_act[]=$lig->valeur;
					if(!isset($tab_requete[0])) {$tab_requete[0]="Classe actuelle (<span style='color:black;'>";$tab_requete_csv[0]="Classe actuelle (";} else {$tab_requete[0].=", ";$tab_requete_csv[0].=", ";}
					if(($lig->valeur=='Red')||($lig->valeur=='Arriv')) {
						$tab_requete[0].=$lig->valeur;$tab_requete_csv[0].=$lig->valeur;
					}
					else {
						$tab_requete[0].=get_class_from_id($lig->valeur);$tab_requete_csv[0].=get_class_from_id($lig->valeur);
					}

					$lien_affect.="&amp;id_clas_act[]=$lig->valeur";

					//$sql_ele.=" AND id_classe_actuelle='$lig->valeur'";

					if($sql_ele_id_classe_act!='') {$sql_ele_id_classe_act.=" OR ";}
					$sql_ele_id_classe_act.="id_classe_actuelle='$lig->valeur'";
					break;

				case 'clas_fut':
					$clas_fut[]=$lig->valeur;
					if(!isset($tab_requete[1])) {$tab_requete[1]="Classe future (<span style='color:black;'>";$tab_requete_csv[1]="Classe future (";} else {$tab_requete[1].=", ";$tab_requete_csv[1].=", ";}
					if(($lig->valeur=='Red')||($lig->valeur=='Dep')) {
						$tab_requete[1].=$lig->valeur;$tab_requete_csv[1].=$lig->valeur;
					}
					else {
						//$tab_requete[1].=get_class_from_id($lig->valeur);$tab_requete_csv[1].=get_class_from_id($lig->valeur);
						$tab_requete[1].=$lig->valeur;$tab_requete_csv[1].=$lig->valeur;
					}

					$lien_affect.="&amp;clas_fut[]=$lig->valeur";

					//$sql_ele.=" AND classe_future='$lig->valeur'";

					if($sql_ele_classe_fut!='') {$sql_ele_classe_fut.=" OR ";}
					$sql_ele_classe_fut.="classe_future='$lig->valeur'";
					break;

				case 'avec_lv1':
					$avec_lv1[]=$lig->valeur;
					if(!isset($tab_requete[2])) {$tab_requete[2]="Avec les options (<span style='color:green;'>";$tab_requete_csv[2]="Avec les options (";} else {$tab_requete[2].=", ";$tab_requete_csv[2].=", ";}
					$tab_requete[2].=$lig->valeur;$tab_requete_csv[2].=$lig->valeur;

					$lien_affect.="&amp;avec_lv1[]=$lig->valeur";

					$sql_ele.=" AND liste_opt LIKE '%|$lig->valeur|%'";
					break;
				case 'avec_lv2':
					$avec_lv2[]=$lig->valeur;
					if(!isset($tab_requete[2])) {$tab_requete[2]="Avec les options (<span style='color:green;'>";$tab_requete_csv[2]="Avec les options (";} else {$tab_requete[2].=", ";$tab_requete_csv[2].=", ";}
					$tab_requete[2].=$lig->valeur;$tab_requete_csv[2].=$lig->valeur;

					$lien_affect.="&amp;avec_lv2[]=$lig->valeur";

					$sql_ele.=" AND liste_opt LIKE '%|$lig->valeur|%'";
					break;
				case 'avec_lv3':
					$avec_lv3[]=$lig->valeur;
					if(!isset($tab_requete[2])) {$tab_requete[2]="Avec les options (<span style='color:green;'>";$tab_requete_csv[2]="Avec les options (";} else {$tab_requete[2].=", ";$tab_requete_csv[2].=", ";}
					$tab_requete[2].=$lig->valeur;$tab_requete_csv[2].=$lig->valeur;

					$lien_affect.="&amp;avec_lv3[]=$lig->valeur";

					$sql_ele.=" AND liste_opt LIKE '%|$lig->valeur|%'";
					break;

				case 'avec_autre':
					$avec_autre[]=$lig->valeur;
					if(!isset($tab_requete[2])) {$tab_requete[2]="Avec les options (<span style='color:green;'>";$tab_requete_csv[2]="Avec les options (";} else {$tab_requete[2].=", ";$tab_requete_csv[2].=", ";}
					$tab_requete[2].=$lig->valeur;$tab_requete_csv[2].=$lig->valeur;

					$lien_affect.="&amp;avec_autre[]=$lig->valeur";

					$sql_ele.=" AND liste_opt LIKE '%|$lig->valeur|%'";
					break;

				case 'avec_profil':
					$avec_profil[]=$lig->valeur;
					if(!isset($tab_requete[1])) {$tab_requete[1]="Avec profil (<span style='color:black;'>";$tab_requete_csv[1]="Avec profil (";} else {$tab_requete[1].=", ";$tab_requete_csv[1].=", ";}
					$tab_requete[1].=$lig->valeur;$tab_requete_csv[1].=$lig->valeur;

					$lien_affect.="&amp;avec_profil[]=$lig->valeur";

					if($sql_avec_profil!='') {$sql_avec_profil.=" OR ";}
					$sql_avec_profil.="profil='$lig->valeur'";
					break;

				case 'sans_lv1':
					$sans_lv1[]=$lig->valeur;
					if(!isset($tab_requete[3])) {$tab_requete[3]="Sans les options (<span style='color:red;'>";$tab_requete_csv[3]="Sans les options (";} else {$tab_requete[3].=", ";$tab_requete_csv[3].=", ";}
					$tab_requete[3].=$lig->valeur;$tab_requete_csv[3].=$lig->valeur;

					$lien_affect.="&amp;sans_lv1[]=$lig->valeur";

					$sql_ele.=" AND liste_opt NOT LIKE '%|$lig->valeur|%'";
					break;
				case 'sans_lv2':
					$sans_lv2[]=$lig->valeur;
					if(!isset($tab_requete[3])) {$tab_requete[3]="Sans les options (<span style='color:red;'>";$tab_requete_csv[3]="Sans les options (";} else {$tab_requete[3].=", ";$tab_requete_csv[3].=", ";}
					$tab_requete[3].=$lig->valeur;$tab_requete_csv[3].=$lig->valeur;

					$lien_affect.="&amp;sans_lv2[]=$lig->valeur";

					$sql_ele.=" AND liste_opt NOT LIKE '%|$lig->valeur|%'";
					break;
				case 'sans_lv3':
					$sans_lv3[]=$lig->valeur;
					if(!isset($tab_requete[3])) {$tab_requete[3]="Sans les options (<span style='color:red;'>";$tab_requete_csv[3]="Sans les options (";} else {$tab_requete[3].=", ";$tab_requete_csv[3].=", ";}
					$tab_requete[3].=$lig->valeur;$tab_requete_csv[3].=$lig->valeur;

					$lien_affect.="&amp;sans_lv3[]=$lig->valeur";

					$sql_ele.=" AND liste_opt NOT LIKE '%|$lig->valeur|%'";
					break;
				case 'sans_autre':
					$sans_autre[]=$lig->valeur;
					if(!isset($tab_requete[3])) {$tab_requete[3]="Sans les options (<span style='color:red;'>";$tab_requete_csv[3]="Sans les options (";} else {$tab_requete[3].=", ";$tab_requete_csv[3].=", ";}
					$tab_requete[3].=$lig->valeur;$tab_requete_csv[3].=$lig->valeur;

					$lien_affect.="&amp;sans_autre[]=$lig->valeur";

					$sql_ele.=" AND liste_opt NOT LIKE '%|$lig->valeur|%'";
					break;

				case 'sans_profil':
					$sans_profil[]=$lig->valeur;
					if(!isset($tab_requete[1])) {$tab_requete[1]="Sans profil (<span style='color:black;'>";$tab_requete_csv[1]="Sans profil (";} else {$tab_requete[1].=", ";$tab_requete_csv[1].=", ";}
					$tab_requete[1].=$lig->valeur;$tab_requete_csv[1].=$lig->valeur;

					$lien_affect.="&amp;sans_profil[]=$lig->valeur";

					if($sql_sans_profil!='') {$sql_sans_profil.=" AND ";}
					$sql_sans_profil.="profil='$lig->valeur'";
					break;
			}
		}

		$lien_affect.=$fin_lien_affect;

		//echo $lien_affect;
		$contenu_affichage_requete_courante.=$lien_affect;
		//"<br />\n";

		if(!isset($lignes_requetes)) {
			$lignes_requetes="<p>";
		}
		else {
			$lignes_requetes.="<p>";
		}
		$lignes_requetes.="<a href='#requete_$id_req'>Requête n°$id_req</a> (<i>\n";

		for($m=0;$m<4;$m++) {
			if(isset($tab_requete[$m])) {
				//echo $tab_requete[$m]."</span>)<br />\n";
				$contenu_affichage_requete_courante.=$tab_requete[$m]."</span>)<br />\n";
				$fich_csv.=$tab_requete_csv[$m].")\n";

				$lignes_requetes.=$tab_requete[$m]."</span>) ";
			}
		}
		$lignes_requetes.="</i>)&nbsp;: \n";

		// On réinitialise le tableau pour faire table rase des logins de la requête précédente
		$tab_ele=array();

		if($sql_ele_id_classe_act!='') {$sql_ele.=" AND ($sql_ele_id_classe_act)";}
		if($sql_ele_classe_fut!='') {$sql_ele.=" AND ($sql_ele_classe_fut)";}
		if($sql_avec_profil!='') {$sql_ele.=" AND ($sql_avec_profil)";}
		if($sql_sans_profil!='') {$sql_ele.=" AND ($sql_sans_profil)";}

		$sql_ele.=";";
		//echo "$sql_ele<br />\n";
		$res_ele=mysqli_query($GLOBALS["mysqli"], $sql_ele);
		while ($lig_ele=mysqli_fetch_object($res_ele)) {
			$tab_ele[]=$lig_ele->login;

			$sql_tmp="SELECT classe_future FROM gc_eleves_options WHERE projet='$projet' AND login='$lig_ele->login' AND classe_future!='' AND classe_future!='Dep' AND classe_future!='Red';";
			//echo "$sql_tmp<br />";
			$res_tmp_ele_clas=mysqli_query($GLOBALS["mysqli"], $sql_tmp);
			if(mysqli_num_rows($res_tmp_ele_clas)>0) {
				$lig_tmp_ele_clas=mysqli_fetch_object($res_tmp_ele_clas);
				if(!in_array($lig_tmp_ele_clas->classe_future,$tab_classes_fut_de_cette_requete)) {
					$tab_classes_fut_de_cette_requete[]=$lig_tmp_ele_clas->classe_future;
				}
			}
		}
		$lignes_requetes.=count($tab_ele)."</p>\n";

		//=========================================================================
		// $tab_ele est rempli
		// On va parcourir toutes les classes pour présenter les élèves retenus ($tab_ele) dans l'ordre des classes d'origine

		// Début du tableau des élèves pour la requête courante

		$contenu_affichage_requete_courante.="<table class='boireaus sortable resizable' border='1' summary='Tableau des options'>\n";
		//$contenu_affichage_requete_courante.="<thead>\n";
		//==========================================
		$contenu_affichage_requete_courante.="<tr>\n";

		//$contenu_affichage_requete_courante.="<th style='width:15em;'>Elève</th>\n";
		$contenu_affichage_requete_courante.="<th class='text' title=\"Cliquez pour trier par nom d'élève.\">Elève";
		//$contenu_affichage_requete_courante.="<br />\n<span id='eff_select_sexe$loop'></span>";
		$contenu_affichage_requete_courante.="</th>\n";

//20150623
		$contenu_affichage_requete_courante.="<th class='text' id='th_req_n__$loop'><span style='display:none'>Bidule</span>Profil";
		//$contenu_affichage_requete_courante.="<br />\n<span id='eff_select$loop'></span>";
		$contenu_affichage_requete_courante.="</th>\n";
		$contenu_affichage_requete_courante.="<th class='number' title=\"Cliquez pour trier par niveau scolaire.\">Niveau</th>\n";
		$contenu_affichage_requete_courante.="<th>Absences</th>\n";
		$contenu_affichage_requete_courante.="<th class='text' title=\"Cliquez pour trier par classe.\">Classe<br />actuelle</th>\n";
		//$fich_csv.="Elève;Classe actuelle;";
		$fich_csv.="Elève;Clas.act;";

		if(count($lv1)>0) {$contenu_affichage_requete_courante.="<th class='text' title=\"Cliquez pour trier par LV1.\">LV1</th>\n";$fich_csv.="LV1;";}
		if(count($lv2)>0) {$contenu_affichage_requete_courante.="<th class='text' title=\"Cliquez pour trier par LV2.\">LV2</th>\n";$fich_csv.="LV2;";}
		if(count($lv3)>0) {$contenu_affichage_requete_courante.="<th class='text' title=\"Cliquez pour trier par LV3.\">LV3</th>\n";$fich_csv.="LV3;";}
		if(count($autre_opt)>0) {$contenu_affichage_requete_courante.="<th class='text' title=\"Cliquez pour trier par Options.\">Options</th>\n";$fich_csv.="Options;";}

		//$contenu_affichage_requete_courante.="<th rowspan='2'>Observations</th>\n";
		$contenu_affichage_requete_courante.="<th>Observations</th>\n";

		$contenu_affichage_requete_courante.="</tr>\n";
		$fich_csv.="\n";

		//==========================================

		$contenu_affichage_requete_courante.="<tr>\n";
		//$contenu_affichage_requete_courante.="<th>Effectifs&nbsp;: <span id='eff_tot'>&nbsp;</span></th>\n";
		$contenu_affichage_requete_courante.="<th>Eff.select&nbsp;: <span id='eff_select$loop'>...</span></th>\n";
		$contenu_affichage_requete_courante.="<th id='eff_select_sexe$loop'>...</th>\n";

		$contenu_affichage_requete_courante.="<th>&nbsp;</th>\n";
		$contenu_affichage_requete_courante.="<th>&nbsp;</th>\n";
		$contenu_affichage_requete_courante.="<th>&nbsp;</th>\n";

		$tab_lv_et_opt_requete[$loop]=array();
		$tab_eff_lv_et_opt_requete[$loop]=array();
		/*
		if(count($lv1)>0) {$contenu_affichage_requete_courante.="<th id='th_lv1_$loop'>&nbsp;</th>\n";}
		if(count($lv2)>0) {$contenu_affichage_requete_courante.="<th id='th_lv2_$loop'>&nbsp;</th>\n";}
		if(count($lv3)>0) {$contenu_affichage_requete_courante.="<th id='th_lv3_$loop'>&nbsp;</th>\n";}
		if(count($autre_opt)>0) {$contenu_affichage_requete_courante.="<th id='th_autre_opt_$loop'>&nbsp;</th>\n";}
		*/
		/*
		if(count($lv1)>0) {$contenu_affichage_requete_courante.="<th>&nbsp;</th>\n";}
		if(count($lv2)>0) {$contenu_affichage_requete_courante.="<th>&nbsp;</th>\n";}
		if(count($lv3)>0) {$contenu_affichage_requete_courante.="<th>&nbsp;</th>\n";}
		if(count($autre_opt)>0) {$contenu_affichage_requete_courante.="<th id='th_autre_opt_$loop'>&nbsp;</th>\n";}
		*/
		if(count($lv1)>0) {$contenu_affichage_requete_courante.="<th name='th_lv1_$loop' title=\"Liste des LV1 dans cette requête\">&nbsp;</th>\n";}
		if(count($lv2)>0) {$contenu_affichage_requete_courante.="<th name='th_lv2_$loop' title=\"Liste des LV2 dans cette requête\">&nbsp;</th>\n";}
		if(count($lv3)>0) {$contenu_affichage_requete_courante.="<th name='th_lv3_$loop' title=\"Liste des LV3 dans cette requête\">&nbsp;</th>\n";}
		if(count($autre_opt)>0) {$contenu_affichage_requete_courante.="<th name='th_autre_opt_$loop' title=\"Liste des options dans cette requête\">&nbsp;</th>\n";}

		$contenu_affichage_requete_courante.="<th></th>\n";

		$contenu_affichage_requete_courante.="</tr>\n";

		//$contenu_affichage_requete_courante.="</thead>\n";
		//$contenu_affichage_requete_courante.="<tbody>\n";

		//==========================================
		$lignes_tab="";
		//==========================================

		$eff_tot_select=0;
		$eff_tot_select_M=0;
		$eff_tot_select_F=0;

		// Pour effectuer des moyennes, médiane,...
		$tab_moy_eleves=array();

		// Pour lister les classes futures dans la requête courante
		//$tab_classes_fut_de_cette_requete=array();

		$chaine_id_classe="";
		//$cpt=0;
		// Boucle sur toutes les classes actuelles
		for($j=0;$j<count($id_classe_actuelle);$j++) {
			//$eff_tot_classe_M=0;
			//$eff_tot_classe_F=0;

			if($chaine_id_classe!="") {$chaine_id_classe.=",";}
			$chaine_id_classe.="'$id_classe_actuelle[$j]'";
	
			//==========================================
			//$sql="SELECT DISTINCT e.* FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='$id_classe_actuelle[$j]' ORDER BY e.nom,e.prenom;";
			$num_per2=-1;
			if(($id_classe_actuelle[$j]!='Red')&&($id_classe_actuelle[$j]!='Arriv')) {
				$sql="SELECT DISTINCT e.* FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='$id_classe_actuelle[$j]' ORDER BY e.nom,e.prenom;";

				$sql_per="SELECT num_periode FROM periodes WHERE id_classe='$id_classe_actuelle[$j]' ORDER BY num_periode DESC LIMIT 1;";
				$res_per=mysqli_query($GLOBALS["mysqli"], $sql_per);
				if(mysqli_num_rows($res_per)>0) {
					$lig_per=mysqli_fetch_object($res_per);
					$num_per2=$lig_per->num_periode;
				}
			}
			else {
				$sql="SELECT DISTINCT e.* FROM eleves e, gc_ele_arriv_red gc WHERE gc.login=e.login AND gc.statut='$id_classe_actuelle[$j]' AND gc.projet='$projet' ORDER BY e.nom,e.prenom;";
			}
			//$contenu_affichage_requete_courante.="<tr><td colspan='5'>$sql</tr></tr>\n";
			$res=mysqli_query($GLOBALS["mysqli"], $sql);
			//$eff_tot_classe=mysqli_num_rows($res);
			//$eff_tot+=$eff_tot_classe;
			//==========================================
		
			if(mysqli_num_rows($res)>0) {
				while($lig=mysqli_fetch_object($res)) {

					if(in_array($lig->login,$tab_ele)) {
						$tab_ele_toutes_requetes[]=$lig->login;

						$eff_tot_select++;
						if(mb_strtoupper($lig->sexe)=='F') {
							$eff_tot_select_F++;
							$icone_photo="../mod_trombinoscopes/images/photo_f.png";
						}
						else {
							$eff_tot_select_M++;
							$icone_photo="../mod_trombinoscopes/images/photo_g.png";
						}

						//$num_eleve2_id_classe_actuelle[$j]=$cpt;

						$designation_eleve=casse_mot($lig->nom, 'maj')." ".casse_mot($lig->prenom, 'majf2');

						//$contenu_affichage_requete_courante.="<tr id='tr_eleve_$cpt' class='white_hover'>\n";
						$contenu_affichage_requete_courante.="<tr id='tr_eleve_$cpt' class='white_hover'>\n";
						//onmouseover=\"this.style.backgroundColor='white'\" onmouseout=\"this.style.backgroundColor='$tmp_bgcolor'\"
						$contenu_affichage_requete_courante.="<td>\n";
						$contenu_affichage_requete_courante.="<a name='eleve$cpt'></a>\n";
						if(nom_photo($lig->elenoet)) {
							/*
							$contenu_affichage_requete_courante.="<div style='float:right;width:16px;'><a href='#eleve$cpt' onmouseover=\"affiche_photo('".nom_photo($lig->elenoet)."','".addslashes($designation_eleve)."');afficher_div('div_photo','y',100,100);\" onmouseout=\"cacher_div('div_photo')\" onclick=\"return false;\" style='color:black;' title=\"Afficher la photo de $designation_eleve\"><img src='$icone_photo' class='icone16' alt='Photo' /></a></div>";

							$contenu_affichage_requete_courante.="<a href='#eleve$cpt' onmouseover=\"affiche_photo('".nom_photo($lig->elenoet)."','".addslashes($designation_eleve)."');afficher_div('div_photo','y',100,100);\" onmouseout=\"cacher_div('div_photo')\" onclick=\"return false;\" style='color:black;' title=\"Afficher la photo de $designation_eleve\">";
							*/

							$contenu_affichage_requete_courante.="<div style='float:right;width:16px;'><a href='#eleve$cpt' onmouseover=\"affiche_photo2('".nom_photo($lig->elenoet)."','".addslashes($designation_eleve)."');\" onmouseout=\"cacher_div('div_photo')\" onclick=\"return false;\" style='color:black;' title=\"Afficher la photo de $designation_eleve\"><img src='$icone_photo' class='icone16' alt='Photo' /></a></div>";

							$contenu_affichage_requete_courante.="<a href='#eleve$cpt' onmouseover=\"affiche_photo2('".nom_photo($lig->elenoet)."','".addslashes($designation_eleve)."');\" onmouseout=\"cacher_div('div_photo')\" onclick=\"return false;\" style='color:black;' title=\"Afficher la photo de $designation_eleve\">";

							$contenu_affichage_requete_courante.=$designation_eleve;
							$contenu_affichage_requete_courante.="</a>\n";
						}
						else {
							$contenu_affichage_requete_courante.=$designation_eleve;
						}
						$contenu_affichage_requete_courante.="<input type='hidden' name='eleve[$cpt]' id='login_eleve_$cpt' value='$lig->login' />\n";
						$contenu_affichage_requete_courante.="</td>\n";
						$lignes_tab.=$designation_eleve.";";

						//===================================
						// Initialisations
						$profil='RAS';
						$moy="-";
						$nb_absences="-";
						$non_justifie="-";
						$nb_retards="-";

						// On récupère les classe future, lv1, lv2, lv3 et autres options de l'élève $lig->login
						$fut_classe="";

						$tab_ele_opt=array();
						$sql="SELECT * FROM gc_eleves_options WHERE projet='$projet' AND login='$lig->login';";
						$res_opt=mysqli_query($GLOBALS["mysqli"], $sql);
						if(mysqli_num_rows($res_opt)>0) {
							$lig_opt=mysqli_fetch_object($res_opt);
	
							$fut_classe=$lig_opt->classe_future;
	
							$profil=$lig_opt->profil;
							$moy=$lig_opt->moy;
							$nb_absences=$lig_opt->nb_absences;
							$non_justifie=$lig_opt->non_justifie;
							$nb_retards=$lig_opt->nb_retards;
			
							$tmp_tab=explode("|",$lig_opt->liste_opt);
							for($n=0;$n<count($tmp_tab);$n++) {
								if($tmp_tab[$n]!="") {
									$tab_ele_opt[]=mb_strtoupper($tmp_tab[$n]);
								}
							}
						}
						else {
							// On récupère les options de l'année écoulée (année qui se termine)
							$sql="SELECT * FROM j_eleves_groupes jeg, j_groupes_matieres jgm WHERE jeg.id_groupe=jgm.id_groupe AND jeg.login='$lig->login';";
							$res_opt=mysqli_query($GLOBALS["mysqli"], $sql);
							if(mysqli_num_rows($res_opt)>0) {
								while($lig_opt=mysqli_fetch_object($res_opt)) {
									$tab_ele_opt[]=mb_strtoupper($lig_opt->id_matiere);
								}
							}
						}
						//===================================
						// Profil...
//20150623
						$contenu_affichage_requete_courante.="<td id='td_profil_req_".$loop."_ele_".$cpt."'>\n";
						$contenu_affichage_requete_courante.="<span style='display:none;'>".$profil."</span>";
						for($m=0;$m<count($tab_profil);$m++) {if($profil==$tab_profil[$m]) {$contenu_affichage_requete_courante.="<span style='color:".$tab_couleur_profil[$m].";'>";break;}}
						//$contenu_affichage_requete_courante.=$profil;
						$contenu_affichage_requete_courante.="<span style='display:none;'>".$profil."</span>";
						$contenu_affichage_requete_courante.="<span id='div_profil_$cpt' onclick=\"affiche_set_profil($cpt);changement();return false;\">$profil</span>\n";

						$contenu_affichage_requete_courante.="</span>\n";
						$contenu_affichage_requete_courante.="<input type='hidden' name='profil[$cpt]' id='profil_$cpt' value='$profil' />\n";
						$contenu_affichage_requete_courante.="</td>\n";

						// Niveau...
						$contenu_affichage_requete_courante.="<td>\n";
						if(($moy!="")&&(mb_strlen(preg_replace("/[0-9\.,]/","",$moy))==0)) {
							if($num_per2>0) {
								$contenu_affichage_requete_courante.="<a href=\"#\" onclick=\"afficher_div('div_bull_simp','y',-100,40); affiche_bull_simp('$lig->login','".$id_classe_actuelle[$j]."','1','$num_per2');return false;\" style='text-decoration:none;' title=\"Cliquer pour afficher les bulletins simplifiés de l'élève.\">";
							}
							if($moy<7) {
								$contenu_affichage_requete_courante.="<span style='color:red;'>";
							}
							elseif($moy<9) {
								$contenu_affichage_requete_courante.="<span style='color:orange;'>";
							}
							elseif($moy<12) {
								$contenu_affichage_requete_courante.="<span style='color:gray;'>";
							}
							elseif($moy<15) {
								$contenu_affichage_requete_courante.="<span style='color:green;'>";
							}
							else {
								$contenu_affichage_requete_courante.="<span style='color:blue;'>";
							}
							$contenu_affichage_requete_courante.="$moy\n";
							$contenu_affichage_requete_courante.="</span>";
							if($num_per2>0) {
								$contenu_affichage_requete_courante.="</a>\n";
							}
							$contenu_affichage_requete_courante.="<input type='hidden' name='moy[$cpt]' id='moy_$cpt' value='$moy' />\n";

							$tab_moy_eleves[]=$moy;
						}
						else {
							$contenu_affichage_requete_courante.="-\n";
							$contenu_affichage_requete_courante.="<input type='hidden' name='moy[$cpt]' id='moy_$cpt' value='-' />\n";
						}
						$contenu_affichage_requete_courante.="</td>\n";

						//===================================
						$contenu_affichage_requete_courante.="<td title=\"Absences/Non justifiées/Retards\">\n";
						$contenu_affichage_requete_courante.=colorise_abs($nb_absences,$non_justifie,$nb_retards,'return');
						$contenu_affichage_requete_courante.="<input type='hidden' name='nb_absences[$cpt]' id='nb_absences_$cpt' value='$nb_absences' />\n";
						$contenu_affichage_requete_courante.="<input type='hidden' name='non_justifie[$cpt]' id='non_justifie_$cpt' value='$non_justifie' />\n";
						$contenu_affichage_requete_courante.="<input type='hidden' name='nb_retards[$cpt]' id='nb_retards_$cpt' value='$nb_retards' />\n";
						$contenu_affichage_requete_courante.="</td>\n";

						//===================================
						$contenu_affichage_requete_courante.="<td>\n";
						//$contenu_affichage_requete_courante.="<input type='hidden' name='classe_fut[$cpt]' id='classe_fut_".$i."_".$cpt."' value='$fut_classe' />\n";
						$contenu_affichage_requete_courante.="<input type='hidden' name='ele_classe_fut[$cpt]' id='classe_fut_".$cpt."' value='$fut_classe' />\n";

						$contenu_affichage_requete_courante.=$classe_actuelle[$j];
						$contenu_affichage_requete_courante.="</td>\n";
						$lignes_tab.=$classe_actuelle[$j].";";

						if(count($lv1)>0) {
							$contenu_affichage_requete_courante.="<td>\n";
							for($i=0;$i<count($lv1);$i++) {
								if(in_array(mb_strtoupper($lv1[$i]),$tab_ele_opt)) {
									$contenu_affichage_requete_courante.=$lv1[$i];
									$contenu_affichage_requete_courante.="<input type='hidden' name='ele_lv1[$cpt]' id='lv1_".$cpt."' value='$lv1[$i]' />\n";

									$lignes_tab.=$lv1[$i].";";

									if((!isset($tab_lv_et_opt_requete[$loop]['lv1']))||(!in_array($lv1[$i], $tab_lv_et_opt_requete[$loop]['lv1']))) {
										$tab_lv_et_opt_requete[$loop]['lv1'][]=$lv1[$i];
									}

									if(!isset($tab_eff_lv_et_opt_requete[$loop]['lv1'][$lv1[$i]])) {
										$tab_eff_lv_et_opt_requete[$loop]['lv1'][$lv1[$i]]=1;
									}
									else {
										$tab_eff_lv_et_opt_requete[$loop]['lv1'][$lv1[$i]]++;
									}
								}
							}
							$contenu_affichage_requete_courante.="</td>\n";
						}
			

						if(count($lv2)>0) {
							$contenu_affichage_requete_courante.="<td>\n";
							for($i=0;$i<count($lv2);$i++) {
								if(in_array(mb_strtoupper($lv2[$i]),$tab_ele_opt)) {
									$contenu_affichage_requete_courante.=$lv2[$i];
									$contenu_affichage_requete_courante.="<input type='hidden' name='ele_lv2[$cpt]' id='lv2_".$cpt."' value='$lv2[$i]' />\n";

									$lignes_tab.=$lv2[$i].";";

									if((!isset($tab_lv_et_opt_requete[$loop]['lv2']))||(!in_array($lv2[$i], $tab_lv_et_opt_requete[$loop]['lv2']))) {
										$tab_lv_et_opt_requete[$loop]['lv2'][]=$lv2[$i];
									}

									if(!isset($tab_eff_lv_et_opt_requete[$loop]['lv2'][$lv2[$i]])) {
										$tab_eff_lv_et_opt_requete[$loop]['lv2'][$lv2[$i]]=1;
									}
									else {
										$tab_eff_lv_et_opt_requete[$loop]['lv2'][$lv2[$i]]++;
									}
								}
							}
							$contenu_affichage_requete_courante.="</td>\n";
						}

						if(count($lv3)>0) {
							$contenu_affichage_requete_courante.="<td>\n";
							for($i=0;$i<count($lv3);$i++) {
								if(in_array(mb_strtoupper($lv3[$i]),$tab_ele_opt)) {
									$contenu_affichage_requete_courante.=$lv3[$i];
									$contenu_affichage_requete_courante.="<input type='hidden' name='ele_lv3[$cpt]' id='lv3_".$cpt."' value='$lv3[$i]' />\n";

									$lignes_tab.=$lv3[$i].";";

									if((!isset($tab_lv_et_opt_requete[$loop]['lv3']))||(!in_array($lv3[$i], $tab_lv_et_opt_requete[$loop]['lv3']))) {
										$tab_lv_et_opt_requete[$loop]['lv3'][]=$lv3[$i];
									}

									if(!isset($tab_eff_lv_et_opt_requete[$loop]['lv3'][$lv3[$i]])) {
										$tab_eff_lv_et_opt_requete[$loop]['lv3'][$lv3[$i]]=1;
									}
									else {
										$tab_eff_lv_et_opt_requete[$loop]['lv3'][$lv3[$i]]++;
									}
								}
							}
							$contenu_affichage_requete_courante.="</td>\n";
						}


						if(count($autre_opt)>0) {
							$contenu_affichage_requete_courante.="<td>\n";
							$cpt_autre_opt=0;
							for($i=0;$i<count($autre_opt);$i++) {
								if(in_array(mb_strtoupper($autre_opt[$i]),$tab_ele_opt)) {
									if($cpt_autre_opt>0) {$contenu_affichage_requete_courante.=" ";}
									$contenu_affichage_requete_courante.=$autre_opt[$i];

									$lignes_tab.=$autre_opt[$i].";";

									if((!isset($tab_lv_et_opt_requete[$loop]['autre_opt']))||(!in_array($autre_opt[$i], $tab_lv_et_opt_requete[$loop]['autre_opt']))) {
										$tab_lv_et_opt_requete[$loop]['autre_opt'][]=$autre_opt[$i];
									}

									if(!isset($tab_eff_lv_et_opt_requete[$loop]['autre_opt'][$autre_opt[$i]])) {
										$tab_eff_lv_et_opt_requete[$loop]['autre_opt'][$autre_opt[$i]]=1;
									}
									else {
										$tab_eff_lv_et_opt_requete[$loop]['autre_opt'][$autre_opt[$i]]++;
									}

									$cpt_autre_opt++;
								}
							}
						}

						$contenu_affichage_requete_courante.="<td>\n";
						if(($fut_classe!='Red')&&($fut_classe!='Dep')&&($fut_classe!='')) {
							for($i=0;$i<count($tab_ele_opt);$i++) {
								if(in_array(mb_strtoupper($tab_ele_opt[$i]),$tab_opt_exclue["$fut_classe"])) {
									$contenu_affichage_requete_courante.="<span style='color:red;'>ERREUR: L'option $tab_ele_opt[$i] est exclue en $fut_classe</span>";
								}
							}
						}

						$titre_chgt_classe="Changement classe ".mb_strtoupper($lig->nom)." ".ucfirst(mb_strtolower($lig->prenom));
						$texte_chgt_classe="<form action='".$_SERVER['PHP_SELF']."' method='post'>\n";
						$texte_chgt_classe.="<div align='center'>\n";
						$texte_chgt_classe.="<table class='boireaus'>\n";
						$texte_chgt_classe.="<tr>\n";
						$texte_chgt_classe.=ligne_entete_classe_future();
						$texte_chgt_classe.="</tr>\n";
						$texte_chgt_classe.="<tr class='lig1'>\n";
						$texte_chgt_classe.=ligne_choix_classe_future($lig->login);
						$texte_chgt_classe.="</tr>\n";
						$texte_chgt_classe.="</table>\n";
						$texte_chgt_classe.="<input type='hidden' name='login_ele' value='$lig->login' >\n";
						$texte_chgt_classe.="<input type='hidden' name='chgt_classe' value='y' >\n";
						$texte_chgt_classe.="<input type='hidden' name='projet' value='$projet' >\n";
						$texte_chgt_classe.="<input type='hidden' name='id_aff' value='$id_aff' >\n";
						$texte_chgt_classe.="<input type='hidden' name='colorisation_chgt_classe' id='colorisation_chgt_classe_$cpt' value='$colorisation_courante' >\n";
						$texte_chgt_classe.="<input type='hidden' name='afficher_listes' value='y' >\n";
						$texte_chgt_classe.="<input type='submit' value='Valider' />\n";
						$texte_chgt_classe.="</div>\n";
						$texte_chgt_classe.="</form>\n";
						$tabdiv_infobulle[]=creer_div_infobulle('div_chgt_classe_'.$cpt,$titre_chgt_classe,"",$texte_chgt_classe,"",30,0,'y','y','n','n',1000);

						$contenu_affichage_requete_courante.="<a href='#' onclick=\"afficher_div('div_chgt_classe_$cpt','y',-100,20);return false;\" title=\"Cliquez pour choisir/modifier la classe future de l'élève.\"><img src='../images/icons/wizard.png' /></a>";

						$contenu_affichage_requete_courante.="</td>\n";

						$contenu_affichage_requete_courante.="</tr>\n";
						$lignes_tab.="\n";
						$cpt++;
					}
				}
			}
		}
		//$contenu_affichage_requete_courante.="</tbody>\n";
		$contenu_affichage_requete_courante.="</table>\n";
		if(count($tab_moy_eleves)>0) {
			$contenu_affichage_requete_courante.="&nbsp;<br />";
			$contenu_affichage_requete_courante.="<div style='float:left; width:10em;'>\n";
			$contenu_affichage_requete_courante.="<table class='boireaus' summary='Tableau des moyenne, médiane, min et max'>\n";
			$contenu_affichage_requete_courante.="<tr class='lig1'>\n";
			$contenu_affichage_requete_courante.="<th>Moyenne</th>\n";
			$contenu_affichage_requete_courante.="<td>".moyenne($tab_moy_eleves)."</td>\n";
			$contenu_affichage_requete_courante.="</tr>\n";
	
			$contenu_affichage_requete_courante.="<tr class='lig-1'>\n";
			$contenu_affichage_requete_courante.="<th>Médiane</th>\n";
			$contenu_affichage_requete_courante.="<td>".mediane($tab_moy_eleves)."</td>\n";
			$contenu_affichage_requete_courante.="</tr>\n";
	
			$contenu_affichage_requete_courante.="<tr class='lig1'>\n";
			$contenu_affichage_requete_courante.="<th>Min</th>\n";
			$contenu_affichage_requete_courante.="<td>".min($tab_moy_eleves)."</td>\n";
			$contenu_affichage_requete_courante.="</tr>\n";
	
			$contenu_affichage_requete_courante.="<tr class='lig-1'>\n";
			$contenu_affichage_requete_courante.="<th>Max</th>\n";
			$contenu_affichage_requete_courante.="<td>".max($tab_moy_eleves)."</td>\n";
			$contenu_affichage_requete_courante.="</tr>\n";
			$contenu_affichage_requete_courante.="</table>\n";
			$contenu_affichage_requete_courante.="</div>\n";


			if(count($tab_classes_fut_de_cette_requete)>0) {
				$contenu_affichage_requete_courante.="<div style='float:left; width:20em;' title=\"Liste des contraintes imposées sur des classes.\">\n";
				$contenu_affichage_requete_courante.=afficher_contraintes($tab_classes_fut_de_cette_requete);
				$contenu_affichage_requete_courante.="</div>\n";
			}

			$contenu_affichage_requete_courante.="<div style='clear:both;'>&nbsp;</div>\n";
		}




		$lignes_tab.="\n";

		echo $contenu_affichage_requete_courante;

		//=============================
		$titre="Requête n°$id_req";
		//$contenu_affichage_requete_courante=preg_replace("/div_profil_/","div_profil_bis_",$contenu_affichage_requete_courante);
		$contenu_affichage_requete_courante=preg_replace("/nb_absences_/","nb_absences_bis_",$contenu_affichage_requete_courante);
		$contenu_affichage_requete_courante=preg_replace("/nb_retards_/","nb_retards_bis_",$contenu_affichage_requete_courante);
		$contenu_affichage_requete_courante=preg_replace("/non_justifie_/","non_justifie_bis_",$contenu_affichage_requete_courante);
		$contenu_affichage_requete_courante=preg_replace("/profil_/","profil_bis_",$contenu_affichage_requete_courante);
		$contenu_affichage_requete_courante=preg_replace("/moy_/","moy_bis_",$contenu_affichage_requete_courante);
		$texte=$contenu_affichage_requete_courante;
		//$tabdiv_infobulle[]=creer_div_infobulle('div_requete_'.$id_req,$titre,"",$texte,"",42,0,'y','y','n','n');
		$tabdiv_infobulle[]=creer_div_infobulle2('div_requete_'.$id_req,$titre,"",$texte,"",42,0,'y','y','n','y');
		//=============================

		echo "&nbsp;";

		echo "<input type='hidden' name='projet' value='$projet' />\n";

		echo "<script type='text/javascript'>
	document.getElementById('eff_select'+$loop).innerHTML=$eff_tot_select;
	document.getElementById('eff_select_sexe'+$loop).innerHTML=$eff_tot_select_M+'/'+$eff_tot_select_F;
</script>\n";

		//$fich_csv.="Eff.select :$eff_tot_select;$eff_tot_select_M / $eff_tot_select_F";
		$fich_csv.="Eff.select :$eff_tot_select;$eff_tot_select_M-/-$eff_tot_select_F";
		if(count($lv1)>0) {
			//echo "<th>&nbsp;</th>\n";
			$fich_csv.=";";
		}
		if(count($lv2)>0) {
			//echo "<th>&nbsp;</th>\n";
			$fich_csv.=";";
		}
		if(count($lv3)>0) {
			//echo "<th>&nbsp;</th>\n";
			$fich_csv.=";";
		}
		if(count($autre_opt)>0) {
			//echo "<th>&nbsp;</th>\n";
			$fich_csv.=";";
		}
		//if(count($lv2)>0) {echo "<th>&nbsp;</th>\n";$fich_csv.=";";}
		//if(count($lv3)>0) {echo "<th>&nbsp;</th>\n";$fich_csv.=";";}
		//if(count($autre_opt)>0) {echo "<th>&nbsp;</th>\n";$fich_csv.=";";}
		$fich_csv.="\n";

		$fich_csv.=$lignes_tab;

		if(isset($tab_lv_et_opt_requete[$loop]['lv1'])) {
			$chaine_liste_lv1="";
			foreach($tab_lv_et_opt_requete[$loop]['lv1'] as $key => $value) {
				$chaine_liste_lv1.=" ".$value;

				if(isset($tab_eff_lv_et_opt_requete[$loop]['lv1'][$value])) {
					$chaine_liste_lv1.="&nbsp;<span style=font-size:x-small>(".$tab_eff_lv_et_opt_requete[$loop]['lv1'][$value].")</span>";
				}
			}
			echo "<script type='text/javascript'>
	/*
	if(document.getElementById('th_lv1_".$loop."')) {
		document.getElementById('th_lv1_".$loop."').innerHTML='".$chaine_liste_lv1."';
	}
	*/
	if(document.getElementsByName('th_lv1_".$loop."')) {
		document.getElementsByName('th_lv1_".$loop."')[0].innerHTML='".$chaine_liste_lv1."';
	}
</script>";
		}

		if(isset($tab_lv_et_opt_requete[$loop]['lv2'])) {
			$chaine_liste_lv2="";
			foreach($tab_lv_et_opt_requete[$loop]['lv2'] as $key => $value) {
				$chaine_liste_lv2.=" ".$value;

				if(isset($tab_eff_lv_et_opt_requete[$loop]['lv2'][$value])) {
					$chaine_liste_lv2.="&nbsp;<span style=font-size:x-small>(".$tab_eff_lv_et_opt_requete[$loop]['lv2'][$value].")</span>";
				}
			}
			echo "<script type='text/javascript'>
	/*
	if(document.getElementById('th_lv2_".$loop."')) {
		document.getElementById('th_lv2_".$loop."').innerHTML='".$chaine_liste_lv2."';
	}
	*/
	if(document.getElementsByName('th_lv2_".$loop."')) {
		document.getElementsByName('th_lv2_".$loop."')[0].innerHTML='".$chaine_liste_lv2."';
	}
</script>";
		}

		if(isset($tab_lv_et_opt_requete[$loop]['lv3'])) {
			$chaine_liste_lv3="";
			foreach($tab_lv_et_opt_requete[$loop]['lv3'] as $key => $value) {
				$chaine_liste_lv3.=" ".$value;

				if(isset($tab_eff_lv_et_opt_requete[$loop]['lv3'][$value])) {
					$chaine_liste_lv3.="&nbsp;<span style=font-size:x-small>(".$tab_eff_lv_et_opt_requete[$loop]['lv3'][$value].")</span>";
				}
			}
			echo "<script type='text/javascript'>
	/*
	if(document.getElementById('th_lv3_".$loop."')) {
		document.getElementById('th_lv3_".$loop."').innerHTML='".$chaine_liste_lv3."';
	}
	*/
	if(document.getElementsByName('th_lv3_".$loop."')) {
		document.getElementsByName('th_lv3_".$loop."')[0].innerHTML='".$chaine_liste_lv3."';
	}
</script>";
		}

		if(isset($tab_lv_et_opt_requete[$loop]['autre_opt'])) {
			$chaine_liste_autre_opt="";
			foreach($tab_lv_et_opt_requete[$loop]['autre_opt'] as $key => $value) {
				$chaine_liste_autre_opt.=" ".$value;

				if(isset($tab_eff_lv_et_opt_requete[$loop]['autre_opt'][$value])) {
					$chaine_liste_autre_opt.="&nbsp;<span style=font-size:x-small>(".$tab_eff_lv_et_opt_requete[$loop]['autre_opt'][$value].")</span>";
				}
			}
			echo "<script type='text/javascript'>
	/*
	if(document.getElementById('th_autre_opt_".$loop."')) {
		document.getElementById('th_autre_opt_".$loop."').innerHTML='".$chaine_liste_autre_opt."';
	}
	*/
	if(document.getElementsByName('th_autre_opt_".$loop."')) {
		document.getElementsByName('th_autre_opt_".$loop."')[0].innerHTML='".$chaine_liste_autre_opt."';
	}
</script>";
		}
	}

	echo "</form>\n";

	sort($tab_ele_toutes_requetes);
	$tmp_array=$tab_ele_toutes_requetes;
	$tab_ele_toutes_requetes=array_unique($tmp_array);

	// Liste des élèves non retenus dans les listes
	$tab_exclus=array();
	//$sql="SELECT DISTINCT geo.login FROM gc_eleves_options geo WHERE geo.projet='$projet' ORDER BY geo.login;";
	$sql="SELECT DISTINCT geo.login FROM gc_eleves_options geo WHERE geo.projet='$projet' ORDER BY geo.login;";
	$res=mysqli_query($GLOBALS["mysqli"], $sql);
	while($lig=mysqli_fetch_object($res)) {
		if(!in_array($lig->login,$tab_ele_toutes_requetes)) {
			$tab_exclus[]=$lig->login;
		}
	}

	echo "<p>Effectif de ces sélections&nbsp;: ".count($tab_ele_toutes_requetes)."</p>\n";

	if(count($tab_exclus)>0) {
		echo "<p><a href='#' onclick=\"modif_div_exclus();return false;\">Liste des élèves hors sélection</a></p>\n";
		echo "<div id='div_exclus'>\n";
		echo "<p>Effectif hors des sélections&nbsp;: ".count($tab_exclus)."</p>\n";
		echo "<table class='boireaus' border='1' summary='Liste des élèves hors sélection'>\n";
		echo "<tr><th>Elève</th><th>Options</th></tr>\n";
		$alt=1;
		for($loop=0;$loop<count($tab_exclus);$loop++) {
			$alt=$alt*(-1);
			echo "<tr class='lig$alt'>\n";
			echo "<td>\n";
			$sql="SELECT nom,prenom FROM eleves WHERE login='$tab_exclus[$loop]';";
			$res=mysqli_query($GLOBALS["mysqli"], $sql);
			$lig=mysqli_fetch_object($res);
			echo mb_strtoupper($lig->nom)." ".ucfirst(mb_strtolower($lig->prenom));
			echo "</td>\n";

			echo "<td>\n";
			//$sql="SELECT opt FROM gc_eleves_options WHERE projet='$projet' AND login='$tab_exclus[$loop]';";
			$sql="SELECT liste_opt FROM gc_eleves_options WHERE projet='$projet' AND login='$tab_exclus[$loop]';";
			$res=mysqli_query($GLOBALS["mysqli"], $sql);
			//$compteur=0;
			while($lig=mysqli_fetch_object($res)) {
				//if($compteur>0) {echo ", ";}
				//echo $lig->opt;

				//echo my_ereg_replace("^|","",my_ereg_replace("|$","",$lig->liste_opt));

				$compteur=0;
				$tmp_tab=explode("|",$lig->liste_opt);
				for($n=0;$n<count($tmp_tab);$n++) {
					if($tmp_tab[$n]!="") {
						if($compteur>0) {echo ", ";}

						echo strtoupper($tmp_tab[$n]);

						$compteur++;
					}
				}

				//$compteur++;
			}
			echo "</td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
		echo "</div>\n";

		echo "<script type='text/javascript'>
	//if(document.getElementById('div_exclus')) {
	//	document.getElementById('div_exclus').style.display='none';
	//}

	var etat_affiche_exclus=1;

	function modif_div_exclus() {
		etat_affiche_exclus=etat_affiche_exclus*(-1);

		if(document.getElementById('div_exclus')) {
			if(etat_affiche_exclus==1) {
				document.getElementById('div_exclus').style.display='';
			}
			else {
				document.getElementById('div_exclus').style.display='none';
			}
		}

		return false;
	}

	modif_div_exclus();

</script>\n";

	}

	//echo "<pre>";
	//echo $fich_csv;
	//echo "</pre>";

	if($user_temp_directory) {
		//=================================
		$date_fichier=date("Ymd_Hi");
		//$fich=fopen("csv/listes_".$projet."_".$date_fichier.".csv","w+");
		$fich=fopen("../temp/".$user_temp_directory."/listes_".$projet."_".$date_fichier.".csv","w+");
		fwrite($fich,$fich_csv);
		fclose($fich);
		//=================================

		echo "Fichiers <a href='../temp/".$user_temp_directory."/listes_".$projet."_".$date_fichier.".csv'>CSV</a>\n";
		echo " - ";
		echo "<a href='genere_ods.php?fichier_csv=listes_".$projet."_".$date_fichier.".csv&amp;projet=$projet&amp;detail=oui' target='_blank'>ODS_detaillé</a>\n";
		echo " - ";
		echo "<a href='genere_ods.php?fichier_csv=listes_".$projet."_".$date_fichier.".csv&amp;projet=$projet&amp;detail=non' target='_blank'>ODS</a>\n";
	
		echo "<script type='text/javascript'>
	document.getElementById('div_ods').innerHTML=\"Fichiers <a href='../temp/".$user_temp_directory."/listes_".$projet."_".$date_fichier.".csv'>CSV</a> <a href='genere_ods.php?fichier_csv=listes_".$projet."_".$date_fichier.".csv&amp;projet=$projet&amp;detail=non' target='_blank'>ODS</a> <a href='genere_ods.php?fichier_csv=listes_".$projet."_".$date_fichier.".csv&amp;projet=$projet&amp;detail=oui' target='_blank'>ODS_detaillé</a>\";
</script>\n";
	}
	else {
		echo "<p style='color:red'>Le dossier temporaire de l'utilisateur n'existe pas.<br />On ne génère pas de fichier CSV.</p>\n";

		echo "<script type='text/javascript'>
	document.getElementById('div_ods').innerHTML=\"<p style='color:red'>Le dossier temporaire de l'utilisateur n'existe pas.<br />On ne génère pas de fichier CSV.</p>\";
</script>\n";
	}
	//=================================

	//=================================
	$titre="<span id='entete_div_photo_eleve'>Elève</span>";
	$texte="<div id='corps_div_photo_eleve' align='center'>\n";
	$texte.="<br />\n";
	$texte.="</div>\n";
	
	$tabdiv_infobulle[]=creer_div_infobulle('div_photo',$titre,"",$texte,"",14,0,'y','y','n','n');
	
	echo "<script type='text/javascript'>
	function affiche_photo(photo,nom_prenom) {
		document.getElementById('entete_div_photo_eleve').innerHTML=nom_prenom;
		document.getElementById('corps_div_photo_eleve').innerHTML='<img src=\"'+photo+'\" width=\"150\" alt=\"Photo\" /><br />';
	}

	function affiche_photo2(photo,nom_prenom) {
		document.getElementById('entete_div_photo_eleve').innerHTML=nom_prenom;
		document.getElementById('corps_div_photo_eleve').innerHTML='<img src=\"'+photo+'\" width=\"150\" alt=\"Photo\" /><br />';

		afficher_div('div_photo','y',100,10);
		/*
		alertSize();
		tab=getScrollXY();
		alert('scrOfX='+tab[0]+' et scrOfY='+tab[1]);

		alert(document.getElementById('div_photo').style.top+' '+document.getElementById('div_photo').style.left+' '+document.getElementById('div_photo').style.width+' '+document.getElementById('div_photo').style.height);
		*/
	}


function alertSize() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  window.alert( 'Width = ' + myWidth );
  window.alert( 'Height = ' + myHeight );
}

function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
}
";
	//=================================

echo "
	var couleur_classe_fut=new Array($chaine_couleur_classe_fut);
	var couleur_lv1=new Array($chaine_couleur_lv1);
	var couleur_lv2=new Array($chaine_couleur_lv2);
	var couleur_lv3=new Array($chaine_couleur_lv3);

	var couleur_profil=new Array($chaine_couleur_profil);
	var tab_profil=new Array($chaine_profil);

	function colorise(mode,n) {
		var k;
		var i;
		var m;

		for(i=0;i<$cpt;i++) {
			if(mode!='profil') {
				if(mode!='aucune') {
					if(document.getElementById(mode+'_'+i)) {
						for(k=0;k<n;k++) {
							if(mode=='classe_fut') {
								if(document.getElementById(mode+'_'+i).value==tab_classes_fut[k]) {
									document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_classe_fut[k];
								}
							}
	
							if(mode=='lv1') {
								if(document.getElementById(mode+'_'+i).value==tab_lv1[k]) {
									document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_lv1[k];
								}
							}
	
							if(mode=='lv2') {
								if(document.getElementById(mode+'_'+i).value==tab_lv2[k]) {
									document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_lv2[k];
								}
							}
	
							if(mode=='lv3') {
								if(document.getElementById(mode+'_'+i).value==tab_lv3[k]) {
									document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_lv3[k];
								}
							}
						}
					}
				}
				else {
					document.getElementById('tr_eleve_'+i).style.backgroundColor='white';
				}
			}
			else {
				for(m=0;m<couleur_profil.length;m++) {
					if(document.getElementById('profil_'+i).value==tab_profil[m]) {
						document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_profil[m];
					}
				}
			}
		}
	}

	colorise('classe_fut',".count($classe_fut).");

	function lance_colorisation() {
		cat=document.forms[0].elements['colorisation'].options[document.forms[0].elements['colorisation'].selectedIndex].value;
		//alert(cat);
		if(cat=='classe_fut') {
			colorise(cat,".count($classe_fut).");
		}
		if(cat=='lv1') {
			colorise(cat,".count($lv1).");
		}
		if(cat=='lv2') {
			colorise(cat,".count($lv2).");
		}
		if(cat=='lv3') {
			colorise(cat,".count($lv3).");
		}
		if(cat=='profil') {
			colorise(cat,".count($tab_profil).");
		}
		if(cat=='aucune') {
			colorise(cat,0);
		}
	}

	lance_colorisation();

	function update_champs_colorisation_chgt_classe() {
		colorisation_courante=document.forms[0].elements['colorisation'].options[document.forms[0].elements['colorisation'].selectedIndex].value;
		for(i=0;i<$cpt;i++) {
			if(document.getElementById('colorisation_chgt_classe_'+i)){
				document.getElementById('colorisation_chgt_classe_'+i).value=colorisation_courante;
			}
		}
	}

	//document.getElementById('colorisation_chgt_classe').value='$colorisation_courante';
</script>\n";

	// A METTRE DANS UNE INFOBULLE?
	echo "<p class='bold'>Récapitulatif des effectifs&nbsp;:</p>\n";
	echo $lignes_requetes;

	$titre="Récapitulatif des effectifs";
	$texte=$lignes_requetes;
	$tabdiv_infobulle[]=creer_div_infobulle('recap_eff',$titre,"",$texte,"",30,0,'y','y','n','n');

}


require("../lib/footer.inc.php");
?>
