<?php
/*
 *
 *
 * Copyright 2001, 2019 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Julien Jocal, Stephane Boireau
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

$sql="SELECT 1=1 FROM droits WHERE id='/mod_engagements/extraction_engagements.php';";
$test=mysqli_query($GLOBALS["mysqli"], $sql);
if(mysqli_num_rows($test)==0) {
$sql="INSERT INTO droits SET id='/mod_engagements/extraction_engagements.php',
administrateur='V',
professeur='F',
cpe='V',
scolarite='V',
eleve='F',
responsable='F',
secours='F',
autre='F',
description='Extraction des engagements',
statut='';";
$insert=mysqli_query($GLOBALS["mysqli"], $sql);
}

if (!checkAccess()) {
	header("Location: ../logout.php?auto=2");
	die();
}

//debug_var();

$id_classe=isset($_POST['id_classe']) ? $_POST['id_classe'] : (isset($_GET['id_classe']) ? $_GET['id_classe'] : NULL);
$engagement_ele=isset($_POST['engagement_ele']) ? $_POST['engagement_ele'] : (isset($_GET['engagement_ele']) ? $_GET['engagement_ele'] : array());
$engagement_resp=isset($_POST['engagement_resp']) ? $_POST['engagement_resp'] : (isset($_GET['engagement_resp']) ? $_GET['engagement_resp'] : array());

$action=isset($_POST['action']) ? $_POST['action'] : NULL;

$tab_engagements=get_tab_engagements("");
if(count($tab_engagements['indice'])==0) {
	header("Location: ../accueil.php?msg=Aucun type d engagement n est actuellement défini.");
	die();
}

$nb_engagements=count($tab_engagements['indice']);

$msg="";

//debug_var();

if(isset($action)) {
	check_token();

	$login_user=isset($_POST['login_user']) ? $_POST['login_user'] : array();
	/*
	echo "engagement_resp<pre>";
	print_r($engagement_resp);
	echo "</pre>";
	echo "<hr />";
	*/
	if(count($login_user)==0) {
		$msg="ERREUR : Aucun utilisateur n'a été sélectionné.<br />";
	}
	elseif($action=="export_csv") {
		$csv="LOGIN;NOM;PRENOM;CLASSE;STATUT;ENGAGEMENTS;\r\n";

		for($loop=0;$loop<count($login_user);$loop++) {

			$tab_user=get_info_user($login_user[$loop]);
			if(count($tab_user)==0) {
				$tab_user=get_info_eleve($login_user[$loop]);
			}
			else {
				if(!isset($tab_user['nom'])) {
					$tab_user['nom']='Inconnu';
				}
				if(!isset($tab_user['prenom'])) {
					$tab_user['prenom']='Inconnu';
				}
				if(!isset($tab_user['statut'])) {
					$tab_user['statut']='Inconnu';
				}
			}

			$tab=get_tab_engagements_user($login_user[$loop]);

			/*
			echo "<hr />";
			echo "tab_user<pre>";
			print_r($tab_user);
			echo "</pre>";
			echo "<hr />";

			echo "tab<pre>";
			print_r($tab);
			echo "</pre>";
			echo "<hr />";
			*/

			$classe="";
			$engagements="";
			$tab_liste_engagements=array();
			if($tab_user['statut']=="eleve") {
				$classe=$tab_user['classes'];

				$cpt_eng=0;
				for($loop2=0;$loop2<count($tab['indice']);$loop2++) {
					if(in_array($tab['indice'][$loop2]['id_engagement'], $engagement_ele)) {
						if($cpt_eng>0) {$engagements.=", ";}

						if($tab['indice'][$loop2]['id_type']=='id_classe') {
							$tmp_nom_classe=get_nom_classe($tab['indice'][$loop2]['valeur']);
							// Il y a un souci de suppression des engagements liés à une classe lors de la suppression de classe.
							if($tmp_nom_classe!='') {
								//$engagements.=$tab['indice'][$loop2]['nom_engagement'];
								//$engagements.=" (".$tmp_nom_classe.")";

								if(!in_array($tab['indice'][$loop2]['nom_engagement']." (".$tmp_nom_classe.")", $tab_liste_engagements)) {
									$tab_liste_engagements[]=$tab['indice'][$loop2]['nom_engagement']." (".$tmp_nom_classe.")";
								}
								$cpt_eng++;
							}
						}
						else {
							//$engagements.=$tab['indice'][$loop2]['nom_engagement'];

							if(!in_array($tab['indice'][$loop2]['nom_engagement'], $tab_liste_engagements)) {
								$tab_liste_engagements[]=$tab['indice'][$loop2]['nom_engagement'];
							}
							$cpt_eng++;
						}
					}
				}
			}
			elseif($tab_user['statut']=="responsable") {
				//$classe=$tab_user['classes'];
				$classe='';
				$tmp_tab_classe=array();

				$cpt_eng=0;
				for($loop2=0;$loop2<count($tab['indice']);$loop2++) {
					//echo "\$tab['indice'][$loop2]['id_engagement']=".$tab['indice'][$loop2]['id_engagement']."<br />";
					if(in_array($tab['indice'][$loop2]['id_engagement'], $engagement_resp)) {
						if($cpt_eng>0) {$engagements.=", ";}

						if($tab['indice'][$loop2]['id_type']=='id_classe') {
							$tmp_nom_classe=get_nom_classe($tab['indice'][$loop2]['valeur']);
							//echo "get_nom_classe(\$tab['indice'][$loop2]['valeur'])=".$tmp_nom_classe."<br />";

							if($tmp_nom_classe!='') {
								//echo $tab['indice'][$loop2]['nom_engagement']." (".$tmp_nom_classe.")<br />";
								//$engagements.=$tab['indice'][$loop2]['nom_engagement'];
								//$engagements.=" (".$tmp_nom_classe.")";

								if(!in_array($tmp_nom_classe, $tmp_tab_classe)) {
									if($classe!='') {
										$classe.=", ";
									}
									$classe.=$tmp_nom_classe;
									$tmp_tab_classe[]=$tmp_nom_classe;
								}

								if(!in_array($tab['indice'][$loop2]['nom_engagement']." (".$tmp_nom_classe.")", $tab_liste_engagements)) {
									$tab_liste_engagements[]=$tab['indice'][$loop2]['nom_engagement']." (".$tmp_nom_classe.")";
								}
								$cpt_eng++;
							}
						}
						else {
							//$engagements.=$tab['indice'][$loop2]['nom_engagement'];
							//echo $tab['indice'][$loop2]['nom_engagement']."<br />";

							if(!in_array($tab['indice'][$loop2]['nom_engagement'], $tab_liste_engagements)) {
								$tab_liste_engagements[]=$tab['indice'][$loop2]['nom_engagement'];
							}
							$cpt_eng++;
						}
					}
				}
			}
			else {
				$classe="";
			}

			$engagements='';
			foreach($tab_liste_engagements as $key => $value) {
				if($engagements!='') {
					$engagements.=', ';
				}
				$engagements.=$value;
			}

			$csv.=$login_user[$loop].";".$tab_user['nom'].";".$tab_user['prenom'].";$classe;".$tab_user['statut'].";$engagements;\r\n";

		}

		//echo "<pre>$csv</pre>";

		//die();

		$nom_fic="fichier_engagements_".strftime("%Y%m%d_%H%M%S").".csv";
		send_file_download_headers('text/x-csv',$nom_fic);

		echo echo_csv_encoded($csv);
		die();
	}
	elseif($action=="message") {

		$record = 'yes';

		if (preg_match("#([0-9]{2})/([0-9]{2})/([0-9]{4})#", $_POST['display_date_debut'])) {
			$anneed = mb_substr($_POST['display_date_debut'],6,4);
			$moisd = mb_substr($_POST['display_date_debut'],3,2);
			$jourd = mb_substr($_POST['display_date_debut'],0,2);
			while ((!checkdate($moisd, $jourd, $anneed)) and ($jourd > 0)) $jourd--;
			$date_debut=mktime(0,0,0,$moisd,$jourd,$anneed);
		} else {
			$msg.="ATTENTION : La date de début d'affichage n'est pas valide.<br />(message non enregitré)<br />";
			$record = 'no';
		}

		if (preg_match("#([0-9]{2})/([0-9]{2})/([0-9]{4})#", $_POST['display_date_fin'])) {
			$anneef = mb_substr($_POST['display_date_fin'],6,4);
			$moisf = mb_substr($_POST['display_date_fin'],3,2);
			$jourf = mb_substr($_POST['display_date_fin'],0,2);
			while ((!checkdate($moisf, $jourf, $anneef)) and ($jourf > 0)) $jourf--;
			$date_fin=mktime(23,59,0,$moisf,$jourf,$anneef);
		} else {
			$msg.="ATTENTION : La date de fin d'affichage n'est pas valide.<br />(message non enregitré)<br />";
			$record = 'no';
		}

		if((!isset($_POST['message']))||($_POST['message']=="")) {
			$msg="ERREUR : Le message est vide.<br />";
		}
		elseif ($record == 'yes') {
			$date_decompte=$date_fin;

			//$contenu_cor = traitement_magic_quotes(corriger_caracteres($_POST['message']));
			$contenu_cor = $_POST['message'];

			$pos_crsf_alea=strpos($contenu_cor,"_CSRF_ALEA_");
			if($pos_crsf_alea!==false) {
				$contenu_cor=preg_replace("/_CSRF_ALEA_/","",$contenu_cor);
				$msg.="Contenu interdit.<br />";
				$record = 'no';
			}

			if ($record == 'yes') {

				$t_login_destinataires=$login_user;
				//if (count($t_login_destinataires)>1) {$statuts_destinataires="_";}
				$statuts_destinataires="_";

				foreach($t_login_destinataires as $login_destinataire) {
					$erreur=!set_message($contenu_cor,$date_debut,$date_fin,$date_decompte,$statuts_destinataires,$login_destinataire) && $erreur;
				}

				if (!$erreur) {
					$msg.="Le message a été enregistré.";
				} else {
					$msg.="Erreur lors de l'enregistrement du message&nbsp;: <br  />".mysqli_error($GLOBALS["mysqli"]);
				}
			}
		}
	}
	else {
		$msg="Mode non prévu.<br />";
	}
}

$style_specifique[] = "lib/DHTMLcalendar/calendarstyle";
$javascript_specifique[] = "lib/DHTMLcalendar/calendar";
$javascript_specifique[] = "lib/DHTMLcalendar/lang/calendar-fr";
$javascript_specifique[] = "lib/DHTMLcalendar/calendar-setup";

$javascript_specifique[] = "lib/tablekit";
$utilisation_tablekit="ok";
// ===================== entete Gepi ======================================//
$titre_page = "Extraction engagements";
require_once("../lib/header.inc.php");
// ===================== fin entete =======================================//

//debug_var();
/*
$tab_engagements=get_tab_engagements();
echo "<pre>";
print_r($tab_engagements);
echo "</pre>";
*/
?>
<script src="../ckeditor_4/ckeditor.js"></script>
<?php

echo "<div class='noprint'>";
if($_SESSION['statut']=='administrateur') {
	echo "<p class='bold'><a href='../classes/classes_const.php";
	if(isset($id_classe[0])) {
		echo "?id_classe=".$id_classe[0];
	}
	echo "'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>";
}
else {
	echo "<p class='bold'><a href='../accueil.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>";
}

if(acces("/mod_engagements/index_admin.php", $_SESSION['statut'])) {
	echo " | <a href='index_admin.php'>Définir les types d'engagements</a>";
}

if(acces("/mod_engagements/saisie_engagements.php", $_SESSION['statut'])) {
	echo " | <a href='saisie_engagements.php'>Saisir les engagements</a>";
}

if(acces("/mod_engagements/imprimer_documents.php", $_SESSION['statut'])) {
	echo " | <a href='imprimer_documents.php'>Imprimer les documents liés aux engagements</a>";
}

if((!isset($id_classe))||((count($engagement_ele)==0)&&(count($engagement_resp)==0))) {
	echo "</p>
</div>\n";

	echo "<p class='bold'>Choix des classes, statuts et engagements&nbsp;:</p>\n";

	// Liste des classes avec élève:
	$sql="(SELECT DISTINCT c.* FROM j_eleves_classes jec, classes c, engagements_user eu WHERE (c.id=jec.id_classe AND eu.valeur=jec.id_classe AND eu.id_type='id_classe' AND eu.login=jec.login) ORDER BY c.classe)";
	$sql.=" UNION (SELECT DISTINCT c.* FROM eleves e, 
								responsables2 r, 
								resp_pers rp, 
								j_eleves_classes jec, 
								classes c, 
								engagements_user eu 
							WHERE (c.id=jec.id_classe AND 
								eu.valeur=jec.id_classe AND 
								eu.id_type='id_classe' AND 
								eu.login=rp.login AND 
								rp.pers_id=r.pers_id AND 
								r.ele_id=e.ele_id AND 
								e.login=jec.login) ORDER BY c.classe);";
	//echo "$sql<br />";
	$call_classes=mysqli_query($GLOBALS["mysqli"], $sql);

	$nb_classes=mysqli_num_rows($call_classes);
	if($nb_classes==0){
		echo "<p>Aucune classe avec engagement saisi n'a été trouvée.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}

	// Affichage sur 3 colonnes
	$nb_classes_par_colonne=round($nb_classes/3);
	$cpt = 0;

	echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post' name='formulaire'>
	<fieldset class='fieldset_opacite50'>
		<p class='bold'>Engagements liés à des classes&nbsp;:</p>

		<table width='100%' summary='Choix des classes'>
			<tr valign='top' align='center'>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td align='left'>\n";

	while($lig_clas=mysqli_fetch_object($call_classes)) {

		//affichage 2 colonnes
		if(($cpt>0)&&(round($cpt/$nb_classes_par_colonne)==$cpt/$nb_classes_par_colonne)){
			echo "
				</td>
				<td align='left'>";
		}

		echo "
					<label id='label_tab_id_classe_$cpt' for='tab_id_classe_$cpt' style='cursor: pointer;'><input type='checkbox' name='id_classe[]' id='tab_id_classe_$cpt' value='$lig_clas->id' onchange='change_style_classe($cpt)' /> $lig_clas->classe</label><br />";
		$cpt++;
	}

	echo "
				</td>
			</tr>
		</table>

		<p><a href='#' onClick='ModifCase(true);return false;'>Cocher toutes les classes</a> / <a href='#' onClick='ModifCase(false);return false;'>Décocher toutes les classes</a></p>

		<table>
			<tr>
				<!--td style='vertical-align:top'>
					<input type='checkbox' name='engagement_statut[]' id='engagement_statut_eleve' value='eleve' checked onchange=\"checkbox_change('engagement_statut_responsable');checkbox_change('engagement_statut_eleve')\" />
				</td-->
				<td style='vertical-align:top'>
					<label for='engagement_statut_eleve' id='texte_engagement_statut_eleve'>Extraire des engagements élèves</label>
				</td>
				<td style='vertical-align:top'>";
	$cpt=0;
	foreach($tab_engagements['indice'] as $key => $current_engagement) {
		if($current_engagement['ConcerneEleve']) {
			echo "<input type='checkbox' name='engagement_ele[]' id='engagement_ele_$cpt' value='".$current_engagement['id']."' onchange=\"checkbox_change('engagement_ele_$cpt')\" /><label for='engagement_ele_$cpt' id='texte_engagement_ele_$cpt' title=\"".$current_engagement['nom']." (".$current_engagement['description'].") ".$current_engagement['effectif']." engagement(s) saisi(s).\"> ".$current_engagement['nom']."</label><br />";
			$cpt++;
		}
	}
	echo "
				</td>
			</tr>
			<tr>
				<!--td style='vertical-align:top'>
					<input type='checkbox' name='engagement_statut[]' id='engagement_statut_responsable' value='responsable' onchange=\"checkbox_change('engagement_statut_responsable');checkbox_change('engagement_statut_eleve')\" />
				</td-->
				<td style='vertical-align:top'>
					<label for='engagement_statut_responsable' id='texte_engagement_statut_responsable'>Extraire des engagements responsables</label>
				</td>
				<td style='vertical-align:top'>";
	$cpt=0;
	foreach($tab_engagements['indice'] as $key => $current_engagement) {
		if($current_engagement['ConcerneResponsable']) {
			echo "<input type='checkbox' name='engagement_resp[]' id='engagement_resp_$cpt' value='".$current_engagement['id']."' onchange=\"checkbox_change('engagement_resp_$cpt')\" /><label for='engagement_resp_$cpt' id='texte_engagement_resp_$cpt' title=\"".$current_engagement['nom']." (".$current_engagement['description'].") ".$current_engagement['effectif']." engagement(s) saisi(s).\"> ".$current_engagement['nom']."</label><br />";
			$cpt++;
		}
	}
	echo "
				</td>
			</tr>
		</table>

		<p><input type='submit' value='Valider' /></p>

		<p style='text-indent:-4em; margin-left:4em; margin-top:1em;'><em>NOTE&nbsp;:</em> Seules apparaissent les classes dans lesquelles des engagements sont saisis.<br />
		<em>Petite incohérence&nbsp;:</em> Pour extraire des engagements non liés à des classes, il faut tout de même choisir au moins une classe <em>(elle ne sera pas prise en compte pour l'extraction de ces engagements non liés à des classes, mais le présent formulaire nécessite qu'au moins une classe soit choisie pour passer à la suite)</em>.</p>
	</fieldset>
</form>

<p><br /></p>

<script type='text/javascript'>
	function ModifCase(mode) {
		for (var k=0;k<$nb_classes;k++) {
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

/*
		<pre>";
	print_r($tab_engagements);
	echo "
		</pre>
*/

	require("../lib/footer.inc.php");
	die();
}

echo " | <a href='".$_SERVER['PHP_SELF']."'>Extraire les engagements pour d'autres classes</a></p>
</div>\n";

//debug_var();

// Afficher les personnes extraites
// Pouvoir générer un CSV...
// Pouvoir envoyer un mail...

$cpt=0;
echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post' name='formulaire' id='formulaire' target='_blank'>
	<fieldset class='fieldset_opacite50'>
		".add_token_field()."
		<table class='boireaus boireaus_alt sortable resizable'>
			<tr>
				<th class='nosort'>
					<a href='javascript:modif_case(true)'><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a>/
					<a href='javascript:modif_case(false)'><img src='../images/disabled.png' width='15' height='15' alt='Tout décocher' /></a>
				</th>
				<th class='text'>Nom</th>
				<th class='text'>Prénom</th>
				<th class='text'>Statut</th>
				<th class='text'>Classe</th>
				<th class='text'>Engagements</th>
			</tr>";
for($loop=0;$loop<count($id_classe);$loop++) {
	$tab=get_tab_engagements_user("", $id_classe[$loop]);
	$nom_classe=get_nom_classe($id_classe[$loop]);

	/*
	echo $nom_classe."<pre>";
	print_r($tab);
	echo "</pre><hr />";
	*/

	foreach($tab['login_user'] as $current_login => $tab_engagement_current_user) {
		$tab_user=get_info_user($current_login);
		/*
		echo "<pre>";
		print_r($tab_user);
		echo "</pre>";
		*/
		if(!isset($tab_user['nom'])) {
			$tab_user=get_info_eleve($current_login);
		}
		if(!isset($tab_user['nom'])) {
			$tab_user=get_info_responsable($current_login);
		}
		// Un responsable sans login va poser problème... on va perdre la liaison en cas de suppression du compte en conservant la personne dans resp_pers
		// On risque même de ré-attribuer le login à un autre utilisateur
		// Il faut soit une autre clé que le login, soit supprimer l'engagement lors de la suppression du compte dans utilisateurs
		if(!isset($tab_user['nom'])) {
			$tab_user['nom']="ERREUR (nettoyage des tables requis)";
		}
		if(!isset($tab_user['prenom'])) {
			$tab_user['prenom']="ERREUR (nettoyage des tables requis)";
		}
		if(!isset($tab_user['statut'])) {
			$tab_user['statut']="ERREUR (nettoyage des tables requis)";
		}

		/*
		echo "<pre>";
		print_r($tab_user);
		echo "</pre>";
		*/

		$chaine_tr="
			<tr id='texte_login_user_$cpt'>
				<td><input type='checkbox' name='login_user[]' id='login_user_$cpt' value=\"$current_login\" onchange=\"checkbox_change('login_user_$cpt')\" /></td>
				<td><label for='login_user_$cpt'>".$tab_user['nom']."</label>";
				/*
				echo "<pre>";
				echo print_r($tab_user);
				echo "</pre>";
				*/
		$chaine_tr.="</td>
				<td><label for='login_user_$cpt'>".$tab_user['prenom']."</label></td>
				<td>".$tab_user['statut']."</td>
				<td>".$nom_classe."</td>
				<td>";

		$temoin_engagement_recherche="n";
		for($loop2=0;$loop2<count($tab_engagement_current_user);$loop2++) {
			if((($tab_user['statut']=="eleve")&&(in_array($tab['indice'][$tab_engagement_current_user[$loop2]]['id_engagement'], $engagement_ele)))||
			(($tab_user['statut']=="responsable")&&(in_array($tab['indice'][$tab_engagement_current_user[$loop2]]['id_engagement'], $engagement_resp)))) {
				/*
				echo "
				<pre>";
				print_r($tab);
				echo "
				</pre>";
				*/
				$chaine_tr.=$tab['indice'][$tab_engagement_current_user[$loop2]]['nom_engagement']."<br />";

				$temoin_engagement_recherche="y";
			}
		}

		$chaine_tr.="
				</td>
			</tr>";

		if($temoin_engagement_recherche=="y") {
			echo $chaine_tr;
		}

		$cpt++;
	}

}

// Engagements non liés à une classe:
$tab=get_tab_engagements_user();
/*
echo "<pre>";
print_r($tab);
echo "</pre><hr />";
*/
$nom_classe='';
foreach($tab['login_user'] as $current_login => $tab_engagement_current_user) {

	$tab_user=get_info_user($current_login);
	/*
	echo "<pre>";
	print_r($tab_user);
	echo "</pre>";
	*/
	if(!isset($tab_user['nom'])) {
		$tab_user=get_info_eleve($current_login);
	}
	if(!isset($tab_user['nom'])) {
		$tab_user=get_info_responsable($current_login);
	}
	// Un responsable sans login va poser problème... on va perdre la liaison en cas de suppression du compte en conservant la personne dans resp_pers
	// On risque même de ré-attribuer le login à un autre utilisateur
	// Il faut soit une autre clé que le login, soit supprimer l'engagement lors de la suppression du compte dans utilisateurs
	if(!isset($tab_user['nom'])) {
		$tab_user['nom']="ERREUR (nettoyage des tables requis)";
	}
	if(!isset($tab_user['prenom'])) {
		$tab_user['prenom']="ERREUR (nettoyage des tables requis)";
	}
	if(!isset($tab_user['statut'])) {
		$tab_user['statut']="ERREUR (nettoyage des tables requis)";
	}

	/*
	echo "<pre>";
	print_r($tab_user);
	echo "</pre>";
	*/

	$chaine_tr="
		<tr id='texte_login_user_$cpt'>
			<td><input type='checkbox' name='login_user[]' id='login_user_$cpt' value=\"$current_login\" onchange=\"checkbox_change('login_user_$cpt')\" /></td>
			<td><label for='login_user_$cpt'>".$tab_user['nom']."</label>";
			/*
			echo "<pre>";
			echo print_r($tab_user);
			echo "</pre>";
			*/
	$chaine_tr.="</td>
			<td><label for='login_user_$cpt'>".$tab_user['prenom']."</label></td>
			<td>".$tab_user['statut']."</td>
			<td>".$nom_classe."</td>
			<td>";

	$temoin_engagement_recherche="n";
	for($loop2=0;$loop2<count($tab_engagement_current_user);$loop2++) {
		if((($tab_user['statut']=="eleve")&&(in_array($tab['indice'][$tab_engagement_current_user[$loop2]]['id_engagement'], $engagement_ele))&&($tab['indice'][$tab_engagement_current_user[$loop2]]['id_type']==''))||
		(($tab_user['statut']=="responsable")&&(in_array($tab['indice'][$tab_engagement_current_user[$loop2]]['id_engagement'], $engagement_resp))&&($tab['indice'][$tab_engagement_current_user[$loop2]]['id_type']==''))) {
			/*
			echo "
			<pre>";
			print_r($tab);
			echo "
			</pre>";
			*/
			$chaine_tr.=$tab['indice'][$tab_engagement_current_user[$loop2]]['nom_engagement']."<br />";

			$temoin_engagement_recherche="y";
		}
	}

	$chaine_tr.="
			</td>
		</tr>";

	if($temoin_engagement_recherche=="y") {
		echo $chaine_tr;
	}

	$cpt++;
}


echo "
		</table>";
for($loop=0;$loop<count($id_classe);$loop++) {
	echo "
		<input type='hidden' name='id_classe[]' value='".$id_classe[$loop]."' />";
}
for($loop=0;$loop<count($engagement_ele);$loop++) {
	echo "
		<input type='hidden' name='engagement_ele[]' value='".$engagement_ele[$loop]."' />";
}
for($loop=0;$loop<count($engagement_resp);$loop++) {
	echo "
		<input type='hidden' name='engagement_resp[]' value='".$engagement_resp[$loop]."' />";
}
echo "
		<div class='noprint'>
		<p>
			<input type='radio' name='action' id='action_export_csv' value='export_csv' onchange=\"checkbox_change('action_export_csv');checkbox_change('action_message');\" checked /><label for='action_export_csv' id='texte_action_export_csv' style='font-weight:bold;'>Exporter en CSV</label><br />
			<input type='radio' name='action' id='action_message' value='message' onchange=\"checkbox_change('action_export_csv');checkbox_change('action_message');\" /><label for='action_message' id='texte_action_message'>Déposer un message dans le Panneau d'affichage Gepi</label><br />
			Texte du message&nbsp;:";

$contenu="";
?>

<textarea name="message" id ="message" style="border: 1px solid gray; width: 600px; height: 250px;"><?php echo $contenu; ?></textarea>
<script type='text/javascript'>
// Configuration via JavaScript
CKEDITOR.replace('message',{
    customConfig: '../lib/ckeditor_gepi_config.js'
});
</script>

<?php

$annee = strftime("%Y");
$mois = strftime("%m");
$jour = strftime("%d");
$display_date_debut = $jour."/".$mois."/".$annee;
$annee = strftime("%Y",time()+86400);
$mois = strftime("%m",time()+86400);
$jour = strftime("%d",time()+86400);
$display_date_fin = $jour."/".$mois."/".$annee;

echo "
			<br />

			<i>Le message sera affiché :</i><br />
			de la date : <input type='text' name = 'display_date_debut' id= 'display_date_debut' size='10' value = \"".$display_date_debut."\" onKeyDown=\"clavier_date(this.id,event);\" AutoComplete=\"off\" />".img_calendrier_js("display_date_debut", "img_bouton_display_date_debut")."
			&nbsp;à la date : <input type='text' name = 'display_date_fin' id = 'display_date_fin' size='10' value = \"".$display_date_fin."\" onKeyDown=\"clavier_date(this.id,event);\" AutoComplete=\"off\" />".img_calendrier_js("display_date_fin", "img_bouton_display_date_fin")."<br />(<span style='font-size:small'>Respectez le format jj/mm/aaaa</span>)<br />

			<i>Le destinataire peut supprimer ce message&nbsp;:&nbsp;</i>
			<label for='suppression_possible_oui' id='texte_suppression_possible_oui'>Oui </label><input type='radio' name='suppression_possible' id='suppression_possible_oui' value='oui' onchange=\"checkbox_change('suppression_possible_oui');checkbox_change('suppression_possible_non');\" />
			<label for='suppression_possible_non' id='texte_suppression_possible_non' style='font-weight:bold;'>Non </label><input type='radio' name='suppression_possible' id='suppression_possible_non' value='non' checked='checked' onchange=\"checkbox_change('suppression_possible_oui');checkbox_change('suppression_possible_non');\" />
		</p>

		<p>
			<input type='submit' id='input_submit' value='Valider' />
			<input type='button' value='Valider' id='input_button' style='display:none;' onclick=\"valider_form()\" />
		</p>

		</div>
	</fieldset>
</form>

<script type='text/javascript' language='javascript'>

	".js_checkbox_change_style('checkbox_change', 'texte_', 'n')."

	function modif_case(statut){
		// statut: true ou false
		for(k=0;k<$cpt;k++){
			if(document.getElementById('login_user_'+k)){
				document.getElementById('login_user_'+k).checked=statut;
				checkbox_change('login_user_'+k);
			}
		}
	}

	if(document.getElementById('input_button')) {
		document.getElementById('input_button').style.display='';
	}

	if(document.getElementById('input_submit')) {
		document.getElementById('input_submit').style.display='none';
	}

	function valider_form() {
		if(document.getElementById('p_bandeau_template_tbs_msg')) {
			document.getElementById('p_bandeau_template_tbs_msg').innerHTML='';
		}

		if(document.getElementById('action_export_csv')) {
			if(document.getElementById('action_export_csv').checked==true) {
				document.getElementById('formulaire').setAttribute('target', '_blank');
			}
			else {
				document.getElementById('formulaire').setAttribute('target', '_self');
			}
		}

		document.getElementById('formulaire').submit();
	}
</script>";

/*
echo "<pre>";
print_r($tab_engagements);
echo "</pre>";
*/
require("../lib/footer.inc.php");
?>
