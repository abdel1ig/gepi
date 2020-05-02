<?php
/**
 * Administration du trombinoscope
* $Id: trombinoscopes_admin.php 8586 2011-11-01 17:41:09Z mleygnac $
*
 * Copyright 2001, 2018 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Christian Chapel
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
 * 
 * @package Trombinoscope
 */


/*
 * Paramétrage du trombinoscope
 *
 * @param $_POST['activer'] activation/désactivation
 * @param $_POST['num_aid_trombinoscopes']
 * @param $_POST['activer_personnels']
 * @param $_POST['activer_redimensionne']
 * @param $_POST['activer_rotation']
 * @param $_POST['l_max_aff_trombinoscopes']
 * @param $_POST['h_max_imp_trombinoscopes']
 * @param $_POST['l_max_imp_trombinoscopes']
 * @param $_POST['h_max_imp_trombinoscopes']
 * @param $_POST['nb_col_imp_trombinoscopes']
 * @param $_POST['l_resize_trombinoscopes']
 * @param $_POST['h_resize_trombinoscopes']
 * @param $_POST['sousrub']
 * @param $_POST['supprime']
 *
 * @return $accessibilite
 * @return $titre_page
 * @return $niveau_arbo
 * @return $gepiPathJava
 * @return $msg
 * @return $repertoire_rne
 *
 */

$accessibilite="y";
$titre_page = "Gestion du module trombinoscope";
$niveau_arbo = 1;
$gepiPathJava="./..";



// Initialisations files
require_once("../lib/initialisations.inc.php");
require_once("../lib/share-csrf.inc.php");
require_once("../lib/share-trombinoscope.inc.php");


/**
 * Ré-encode les noms des fichiers photo des élèves
 * Cette fonction suppose que tous les noms des fichiers photo des élèves sont correctement encodés
 * Cette fonction ne peut être utilisée que si la valeur 'alea_nom_fichier' n'est pas définie dans la table setting
 * Renvoie un chaîne vide si tout s'est bien passé, les erreurs rencontrées sinon 
 */
function re_encode_nom_photo_eleves() {
	global $nb_modifs,$nb_erreurs;
	$bilan='';
	
	// Cas du multisite
	$rne="";
	if (isset($GLOBALS['multisite']) && $GLOBALS['multisite'] == 'y' && !$rne=$_COOKIE['RNE'])
			return "Multisite : erreur lors de la récupération du dossier photos de l'établissement.<br/>";
	$rne=(isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y')?$rne=$_COOKIE['RNE'].'/':'';
	$dossier_photos_eleves="../photos/".$rne."eleves/";
	
	// tableau stockant les noms des fichiers photo
	// on ne peut pas renommer les fichiers tout en parcourant le dossier photos
	// sinon des fichiers peuvent être renommés plusieurs fois
	$t_noms_photos=array();
	$R_dossier_photos_eleves=opendir($dossier_photos_eleves);
	while ($photo=readdir($R_dossier_photos_eleves)) {
		if (is_file($dossier_photos_eleves.$photo) && (strtolower(pathinfo($photo,PATHINFO_EXTENSION))=='jpg')) {
				$t_noms_photos[]=$photo;
		}
	}
	closedir($R_dossier_photos_eleves);

	// on renomme les fichiers photo
	foreach($t_noms_photos as $photo){
		$nom_photo=pathinfo($photo,PATHINFO_FILENAME);
		// suppression l'ancien encodage
		$nom_photo=substr($nom_photo,getSettingValue('encodage_photos_eleves_longueur'));
		// on en profite pour normaliser l'extension en .jpg
		if (rename($dossier_photos_eleves.$photo,$dossier_photos_eleves.encode_nom_photo($nom_photo).".jpg")) $nb_modifs++;
		else {
				$nb_erreurs++;
				if ($nb_erreurs<=10) $bilan.="Impossible de ré-encoder ".$nom_photo.".jpg<br />";
		}
	}
	return $bilan;
}

/**
 * Désactive l'encodage des noms des fichiers photo
 * Renvoie un chaîne vide si tout s'est bien passé, les erreurs rencontrées sinon 
 */
function des_encode_nom_photo_des_eleves() {
	global $nb_modifs,$nb_erreurs,$gepiSettings;
	$bilan="";
	// tableau stockant les noms des fichiers photo
	// on ne peut pas renommer les fichiers tout en parcourant le dossier photos
	// sinon des fichiers peuvent être renommés plusieurs fois
	$t_noms_photos=array();

	if (!getSettingAOui('encodage_nom_photo')) {return "L'encodage n'est pas activé.<br />";}
	else {
		// on désactive l'encodage des photos
		if (!saveSetting('encodage_nom_photo','no')) $bilan="Impossible de désactiver l'encodage.<br />";
		else {
			// Cas du multisite
			$rne="";
			if (isset($GLOBALS['multisite']) && $GLOBALS['multisite'] == 'y' && !$rne=$_COOKIE['RNE']) {
				$bilan="Multisite : erreur lors de la récupération du dossier photos de l'établissement.<br/>";
			}

			if ($bilan=="") {
				$rne=(isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y')?$rne=$_COOKIE['RNE']."/":"";
				$dossier_photos_eleves="../photos/".$rne."eleves/";
				$R_dossier_photos_eleves=opendir($dossier_photos_eleves);
				while ($photo=readdir($R_dossier_photos_eleves)) {
					if (is_file($dossier_photos_eleves.$photo) && strtolower(pathinfo($photo,PATHINFO_EXTENSION))=="jpg" && $photo!="index.html") {
						$t_noms_photos[]=$photo;
					}
				}
				closedir($R_dossier_photos_eleves);

				// on renomme les fichiers photo
				foreach($t_noms_photos as $photo) {
					$nom_photo=pathinfo($photo,PATHINFO_FILENAME);
					// supprimer l'ancien encodage
					$nom_photo=des_encode_nom_photo($nom_photo);
					// on en profite pour normaliser l'extension en .jpg
					if (rename($dossier_photos_eleves.$photo,$dossier_photos_eleves.$nom_photo.".jpg")) {$nb_modifs++;}
					else {
						$nb_erreurs++;
						if ($nb_erreurs<=10) $bilan.="Impossible de dés-encoder ".$nom_photo.".jpg<br />";
					}
				}
			}
		}
	}
	return $bilan;
}

/**
 * Vérifie la cohérence entre le contenu du dossier des photos des éléves
 * et les entrées de la table 'setting'
 * Renvoie un chaîne donnant l'état présent de l'encodage si tout est correct
 * ou un descriptif de l'incohérence et de la solution éventuelle
 */
function verifie_coherence_encodage() {

	// Cas du multisite
	$rne=''; $bilan=''; $nb_erreurs=0; $total_fichiers=0;
	
	if (isset($GLOBALS['multisite']) && $GLOBALS['multisite'] == 'y' && !$rne=$_COOKIE['RNE'])
			return "Multisite : erreur lors de la récupération du dossier photos de l'établissement.<br/>";
	$rne=(isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y')?$rne=$_COOKIE['RNE']."/":"";
	$dossier_photos_eleves="../photos/".$rne."eleves/";
	
	$R_dossier_photos_eleves=opendir($dossier_photos_eleves);
	while ($photo=readdir($R_dossier_photos_eleves)) {
		if (is_file($dossier_photos_eleves.$photo) && strtolower(pathinfo($photo,PATHINFO_EXTENSION))=='jpg') {
				$total_fichiers++;
				$nom_photo=pathinfo($photo,PATHINFO_FILENAME);
				if ($nom_photo!=encode_nom_photo(des_encode_nom_photo($nom_photo))) {
					$nb_erreurs++;
				}
		}
	}
	closedir($R_dossier_photos_eleves);
	if ($nb_erreurs==0) $bilan='Etat présent : pas d\'incohérence d\'encodage parmi les fichiers photo élève (d\'extension jpg ou JPG).';
	else 
		if ($nb_erreurs==$total_fichiers) $bilan='<span class="bold" style="color:red">Attention : l\'encodage des fichiers photo élève est incorrect, il faut les ré-encoder en cliquant sur ce <a href="?re_encoder_noms_photo=oui'.add_token_in_url().'">lien.</a></span>';
		else $bilan='<span class="bold" style="color:red">Attention : un ou des fichiers du dossier photos/eleves (d\'extension jpg ou JPG) ne correspondent à aucun élève ('.$nb_erreurs.' sur '.$total_fichiers.').</span><span class="bold"> Vous pouvez les supprimer en <a href="#purge">purgeant le dossier photo</a>.<span>';
	
	return $bilan;
}

function purge_dossier_photos($type_utilisateurs) {

	// $type_utilisateurs : eleves ou personnels
	global $repertoire_photos,$nb_photos_supp,$nb_erreurs;

	// $tab_identifiants : tableau des login ou elenoet présents dans la base
	$tab_identifiants=array(); $pt=0;
	// pour les élèves on cherchera parmi les fichiers elenoet.jpg
	if ($type_utilisateurs=="eleves")
		{
		$r_sql="SELECT `elenoet` FROM `eleves`";
		$R_identifiants=mysqli_query($GLOBALS["mysqli"], $r_sql);
		if ($R_identifiants)
			{
			while ($pt<mysqli_num_rows($R_identifiants))
				{
				$identifiant=old_mysql_result($R_identifiants,$pt++);
				$tab_identifiants[]=encode_nom_photo($identifiant);
				}
			}
		}
	// pour les personnels (et pour les élèves en multisite) on cherchera parmi les fichiers login.jpg
	$r_sql="SELECT `login` FROM `".($type_utilisateurs=="personnels"?"utilisateurs":"eleves")."`";
	$R_identifiants=mysqli_query($GLOBALS["mysqli"], $r_sql);
	if ($R_identifiants)
		{
		while ($pt<mysqli_num_rows($R_identifiants))
			{
			$identifiant=old_mysql_result($R_identifiants,$pt++);
			if ($type_utilisateurs=="personnels") $identifiant=md5(mb_strtolower($identifiant));
			if ($type_utilisateurs=="eleves") $identifiant=encode_nom_photo($identifiant);
			$tab_identifiants[]=$identifiant;
			}
		}

	// $tab_identifiants_inactifs : tableau des login ou elenoet des comptes inactifs présents dans la base
	$tab_identifiants_inactifs=array();
	if (isset($_POST['cpts_inactifs']) && $_POST['cpts_inactifs']=="oui")
		{
		if ($type_utilisateurs=="eleves")
			$r_sql="SELECT `utilisateurs`.`login`,`eleves`.`elenoet` FROM `utilisateurs`,`eleves` WHERE (`statut`='eleve' AND `etat`='inactif' AND `utilisateurs`.`login`=`eleves`.`login`)";
			else
			$r_sql="SELECT `utilisateurs`.`login` FROM `utilisateurs` WHERE `etat`='inactif'";
		$R_inactifs=mysqli_query($GLOBALS["mysqli"], $r_sql);
		if ($R_inactifs)
			{
			$pt=0;
			while ($pt<mysqli_num_rows($R_inactifs))
				{
				// dans tous les cas (élèves ou personnels) on cherchera parmi les fichiers login.jpg
				$identifiant=old_mysql_result($R_inactifs,$pt,'login');
				if ($type_utilisateurs=="personnels") $identifiant=md5(mb_strtolower($identifiant));
				if ($type_utilisateurs=="eleves") $identifiant=encode_nom_photo($identifiant);
				$tab_identifiants_inactifs[]=$identifiant;
				// dans le cas des élèves on cherchera également parmi les fichiers elenoet.jpg
				if ($type_utilisateurs=="eleves")
					{
					$identifiant=old_mysql_result($R_inactifs,$pt,'elenoet');
					$tab_identifiants_inactifs[]=encode_nom_photo($identifiant);
					}
				$pt++;
				}
			}
		}

	// on supprime les photos dont le nom ne se trouve pas dans $tab_identifiants
	// ou se trouve dans $tab_identifiants_inactifs
	$R_dossier_photos=opendir($repertoire_photos."/".$type_utilisateurs);
	while ($photo=readdir($R_dossier_photos))
		{
		if (is_file($repertoire_photos."/".$type_utilisateurs."/".$photo) && $photo!="index.html")
			{
			$nom_photo=pathinfo($photo,PATHINFO_FILENAME);
			// en principe on ne trouve que des fichiers JPEG dans le dossier
			// et on en profite pour normaliser l'extension
			@rename($repertoire_photos."/".$type_utilisateurs."/".$photo,$repertoire_photos."/".$type_utilisateurs."/".$nom_photo.".jpg");
			if (!in_array($nom_photo,$tab_identifiants) || in_array($nom_photo,$tab_identifiants_inactifs))
				if (@unlink($repertoire_photos."/".$type_utilisateurs."/".$nom_photo.".jpg")) $nb_photos_supp++; else $nb_erreurs++;
			}
		}
	closedir($R_dossier_photos);
}

function aplanir_tree($chemin,$destination) {
// déplace tous les fichiers du dossier $chemin dans le dossier $destination
// ! si deux fichiers de même nom se trouvent dans $chemin un seul sera déplacé
	$erreurs="";
    if ($chemin[strlen($chemin)-1]!="/") $chemin.= "/";
    if ($destination[strlen($destination)-1]!="/") $destination.= "/";
    if (is_dir($chemin)) {
		$dossier=opendir($chemin);
		while ($fichier = readdir($dossier)) {
			if ($fichier!="." && $fichier!="..") {
				$chemin_fichier=$chemin.$fichier;
				if (is_dir($chemin_fichier)) aplanir_tree($chemin_fichier,$destination);
					else 
						{
						if (!@copy($chemin_fichier,$destination."/".$fichier)) $erreurs.="Impossible de copier le fichier ".$chemin_fichier." vers ".$destination.".<br/>";
						if ($chemin_fichier!=$destination.$fichier)
							if (!@unlink($chemin_fichier)) $erreurs.="Impossible de supprimer le fichier ".$chemin_fichier.".<br/>";
						}
			}
		}
		closedir($dossier);
    }
	return $erreurs;
}


function del_tree($chemin) {
	// supprime le dossier ou le fichier $chemin
	$erreurs="";
    if ($chemin[strlen($chemin)-1] != "/") $chemin.= "/";
    if (is_dir($chemin)) {
		$dossier = opendir($chemin);
		while ($fichier = readdir($dossier)) {
			if ($fichier != "." && $fichier != "..") {
				$chemin_fichier = $chemin . $fichier;
				if (is_dir($chemin_fichier)) del_tree($chemin_fichier);
					else if (!@unlink($chemin_fichier)) $erreurs.="Impossible de supprimer le fichier ".$chemin_fichier.".<br/>";
			}
		}
		closedir($dossier);
		if (!@rmdir($chemin)) $erreurs.="Impossible de supprimer le dossier ".$chemin.".<br/>";
    }
	else if (!@unlink($chemin)) $erreurs.="Impossible de supprimer le fichier".$chemin.".<br/>";
	return $erreurs;
}


function copie_temp_vers_photos(&$nb_photos,$dossier_a_traiter,$type_a_traiter,$ecraser=true,$test_folder=false,$encodage=false)
// $dossier_a_traiter : 'eleves' ou 'personnels'
// $type_a_traiter :  : 'élève' ou 'personnel'
{
	global $repertoire_photos,$dir_temp,$msg_nb_trts,$msg,$avertissement;
	$folder = $dir_temp."/photos/".$dossier_a_traiter."/";
	if($test_folder && !file_exists($folder)) {
		$avertissement.="Votre ZIP ne contient pas l'arborescence /photos/".$dossier_a_traiter." :</b><br/><span style='font-variant:normal; font-size: smaller;'>Si vous souhaitiez restaurer des photos des ".$type_a_traiter."s, vous devriez avoir<br/>dans votre ZIP les photos des ".$type_a_traiter."s dans un sous-dossier photos/".$dossier_a_traiter."/</span><br/>\n";
	}
	else {
		$nb_photos=0;
		$dossier = opendir($folder);
		while ($Fichier = readdir($dossier)) {
			if (strtolower(pathinfo($Fichier,PATHINFO_EXTENSION))=='jpg') {
				$Fichier_dest=pathinfo($Fichier,PATHINFO_FILENAME);
				if ($encodage) $Fichier_dest=encode_nom_photo($Fichier_dest);
				$Fichier_dest.=".jpg";
				$source=$folder.$Fichier;
				$dest=$repertoire_photos.$dossier_a_traiter."/".$Fichier_dest;
				if ($ecraser) {
					@copy($source, $dest);
					$nb_photos++;
				} else {
					if (!is_file($dest)) {
						@copy($source, $dest);
						$nb_photos++;
					}
				}
			}
		}
		if($nb_photos>0) {$msg_nb_trts.=$nb_photos." photo(s) ".$type_a_traiter."(s) transférée(s).<br/>\n";}
		closedir($dossier);
	}
}

function redimensionne_photos($dossier)
	{
	$nb_photos_redim=0;
	$h_dossier = opendir($dossier);
	while ($fichier=readdir($h_dossier)) 
		{
		if (strtolower(pathinfo($fichier,PATHINFO_EXTENSION))=="jpg") 
			{
			if (getSettingValue("active_module_trombinoscopes_rt")!='')
				$redim_OK=redim_photo($dossier.$fichier,getSettingValue("l_resize_trombinoscopes"), getSettingValue("h_resize_trombinoscopes"),getSettingValue("active_module_trombinoscopes_rt"));
			else
				$redim_OK=redim_photo($dossier.$fichier,getSettingValue("l_resize_trombinoscopes"), getSettingValue("h_resize_trombinoscopes"));
			if ($redim_OK) $nb_photos_redim++;
			}
		}
	closedir($h_dossier);
	return $nb_photos_redim;
	}

// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
	header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
	die();
} else if ($resultat_session == '0') {
	header("Location: ../logout.php?auto=1");
	die();
}
// Check access
if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}

/******************************************************************
 *    Enregistrement des variables passées en $_POST si besoin
 ******************************************************************/

$msg=""; $modification_parametres=false;
$msg_parametres="";

if (isset($_POST['num_aid_trombinoscopes'])) {
	$modification_parametres=true;
	check_token();
	if ($_POST['num_aid_trombinoscopes']!='') {
		if (!saveSetting("num_aid_trombinoscopes", $_POST['num_aid_trombinoscopes']))
				$msg_parametres .= "Erreur lors de l'enregistrement du paramètre num_aid_trombinoscopes !<br />";
	} else {
		$del_num_aid_trombinoscopes = mysqli_query($GLOBALS["mysqli"], "delete from setting where NAME='num_aid_trombinoscopes'");
		$gepiSettings['num_aid_trombinoscopes']="";
	}
}

if (isset($_POST['activer'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("active_module_trombinoscopes", $_POST['activer']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre activation/désactivation !<br />";
	if (!cree_repertoire_multisite())
	$msg_parametres .= "Erreur lors de la création du répertoire photos de l'établissement !<br />";
}

if (isset($_POST['activer_personnels'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("active_module_trombino_pers", $_POST['activer_personnels']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre activation/désactivation du trombinoscope des personnels !";
}

if (isset($_POST['activer_redimensionne'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("active_module_trombinoscopes_rd", $_POST['activer_redimensionne']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre de redimenssionement des photos !<br />";
}

if (isset($_POST['activer_rotation'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("active_module_trombinoscopes_rt", $_POST['activer_rotation']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre rotation des photos !<br />";
}

if (isset($_POST['l_max_aff_trombinoscopes'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("l_max_aff_trombinoscopes", $_POST['l_max_aff_trombinoscopes']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre largeur maximum !<br />";
}
if (isset($_POST['h_max_aff_trombinoscopes'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("h_max_aff_trombinoscopes", $_POST['h_max_aff_trombinoscopes']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre hauteur maximum !<br />";
}

if (isset($_POST['l_max_imp_trombinoscopes'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("l_max_imp_trombinoscopes", $_POST['l_max_imp_trombinoscopes']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre largeur maximum !<br />";
}

if (isset($_POST['h_max_imp_trombinoscopes'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("h_max_imp_trombinoscopes", $_POST['h_max_imp_trombinoscopes']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre hauteur maximum !<br />";
}

if (isset($_POST['nb_col_imp_trombinoscopes'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("nb_col_imp_trombinoscopes", $_POST['nb_col_imp_trombinoscopes']))
			$msg_parametres .= "Erreur lors de l'enregistrement du nombre de colonnes sur les trombinos imprimés !<br />";
}

if (isset($_POST['l_resize_trombinoscopes'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("l_resize_trombinoscopes", $_POST['l_resize_trombinoscopes']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre l_resize_trombinoscopes !<br />";
}
if (isset($_POST['h_resize_trombinoscopes'])) {
	$modification_parametres=true;
	check_token();
	if (!saveSetting("h_resize_trombinoscopes", $_POST['h_resize_trombinoscopes']))
			$msg_parametres .= "Erreur lors de l'enregistrement du paramètre h_resize_trombinoscopes !<br />";
}


	$msg=($msg_parametres!='')?$msg_parametres:(($modification_parametres)?'Modifications enregistrées.</br>':'');

/******************************************************************
 *    Enregistrement des variables (fin)
 ******************************************************************/

// Redimensionner les photos
	if ((isset($_POST['redim_photos_pers']) && $_POST['redim_photos_pers']=="oui") || (isset($_POST['redim_photos_eleve']) && $_POST['redim_photos_eleve']=="oui"))
		{
		$msg="";
		check_token();
		if (cree_zip_archive("photos")==TRUE)
			{
			$repertoire_photos=""; $msg_multisite="";
			if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite']=='y')
				// On récupère le RNE de l'établissement
				if (!$repertoire_photos=$_COOKIE['RNE'])
					$msg_multisite="Multisite : erreur lors de la récupération du dossier photos de l'établissement.<br/>";
				if ($msg_multisite=="")
					{
					if ($repertoire_photos!="") $repertoire_photos.="/";
					$repertoire_photos="../photos/".$repertoire_photos;
					$nb_photos_redim=0;
					if (isset($_POST['redim_photos_pers']) && $_POST['redim_photos_pers']=="oui") $nb_photos_redim+=redimensionne_photos($repertoire_photos."personnels/");
					if (isset($_POST['redim_photos_eleve']) && $_POST['redim_photos_eleve']=="oui") $nb_photos_redim+=redimensionne_photos($repertoire_photos."eleves/");
					if ($nb_photos_redim>0)
						if ($nb_photos_redim>1) $msg=$nb_photos_redim." photos ont été redimensionnées.<br/>";
							else $msg="Une photo a été redimensionnée.<br/>";
						else $msg="Aucune photo n'a été redimensionnée.<br/>";
					}
				else $msg=msg_multisite;
			}
	}

// Suppression de photos
	if(isset($_POST['supp_photos']) && $_POST['supp_photos']=="personnels"){
		check_token();
		// suppression des photos du personnel
		$msg.=(efface_photos("personnels"))?"Photos du personnel supprimées<br/>":"Erreur lors de la suppression des photos du personnel<br/>";
		
	}
	if (isset($_POST['supp_photos']) && $_POST['supp_photos']=="eleves"){
		check_token();
		// suppression des photos des élèves
		$msg.=(efface_photos("eleves"))?"Photos des élèves supprimées<br/>":"Erreur lors de la suppression des photos des élèves<br/>";
	}

// Affichage du personnel sans photo
	if(isset ($_POST['voirPerso']) && $_POST['voirPerso']=="yes"){
		check_token();
		if (!recherche_personnel_sans_photo()){
		$msg .= "Erreur lors de la sélection de professeur(s) sans photo<br/>";
		}else{
			$personnel_sans_photo=recherche_personnel_sans_photo();
			$msg.="liste des professeurs sans photo en bas de page<br/>";
			}
	}

// Affichage des élèves sans photo
	if (isset ($_POST['voirEleve']) && $_POST['voirEleve']=="yes"){
		check_token();
		if (!recherche_eleves_sans_photo()){
			$msg .= "Erreur lors de la sélection des élèves sans photo<br/>";
		}
		else{
			$eleves_sans_photo=recherche_eleves_sans_photo();
			$msg.="liste des élèves sans photo en bas de page<br/>";
		}
	}

// Sauvegarde du dossier 'photos'
	if (isset($_POST['sauvegarder_dossier_photos']) && $_POST['sauvegarder_dossier_photos']=="oui")
		{
		check_token();
		$retour=cree_zip_archive_avec_msg_erreur('photos');
		if ($retour=='') $msg='Le dossier photos a été sauvegardé, vous pouvez le récupérer dans le <a href="../gestion/accueil_sauve.php">module de gestion des sauvegardes</a>.<br/>';
		else $msg='Echec de la sauvegarde du dossier photos : '.$retour.'<br/>';
		}

// Purge du dossier photos
	if (isset($_POST['purge_dossier_photos']) && $_POST['purge_dossier_photos']=="oui")
		{
		$msg="";
		check_token();
		if (cree_zip_archive("photos")==TRUE)
			{
			$repertoire_photos=""; $msg_multisite="";
			if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite']=='y')
				// On récupère le RNE de l'établissement
				if (!$repertoire_photos=$_COOKIE['RNE'])
					$msg_multisite.="Multisite : erreur lors de la récupération du dossier photos de l'établissement.<br/>";
					if ($msg_multisite=="")
						{
						if ($repertoire_photos!="") $repertoire_photos.="/";
						$repertoire_photos="../photos/".$repertoire_photos;


						$nb_photos_supp=0; $nb_erreurs=0;
						// purge du dossier photos/eleves
						purge_dossier_photos("eleves");
						// purge du dossier photos/personnels
						purge_dossier_photos("personnels");

						if ($nb_photos_supp>0)
							if ($nb_photos_supp>1) $msg=$nb_photos_supp." photos ont été suprimées.<br/>";
								else $msg="Une photo a été suprimée.<br/>";
							else $msg="Aucune photo n'a été supprimée.<br/>";
						if ($nb_erreurs>0)
							{
							if ($nb_erreurs>1) $msg.=$nb_erreurs." photos n'ont pu être supprimées.<br/>";
								else $msg.="Une photo n'a pu être supprimée.<br/>";
							}
						} else $msg=$msg_multisite.$msg;
			}
		else $msg.="Erreur lors de la création de la sauvegarde.<br/>";
		}

// Re-encodage des noms des fichiers photo des élèves
if  (isset($_GET['re_encoder_noms_photo'])) {
	$msg="";
	check_token();
	$nb_erreurs=0;

	$retour=re_encode_nom_photo_eleves();
	if ($nb_erreurs==0)
		if ($nb_modifs>0)
			if ($nb_modifs>1) $msg=$nb_modifs." noms de fichiers photo ont été ré-encodés.<br/>";
			else $msg="Un nom de fichier photo a été ré-encodé.<br/>";
		else $msg="Aucun nom de fichier photo n'a été ré-encodé (le dossier est peut-être vide).<br/>";
	else
		if ($nb_erreurs<=10) $msg=$retour;
		else $msg=$nb_erreurs." noms de fihiers photo n'ont pu être encodés.<br/>";
}

// Dés-encodage des noms des fichiers photo des élèves
if  ((isset($_POST['des_encoder_noms_photo']) and ($_POST['des_encoder_noms_photo']=='oui')))
	{
	$msg="";
	check_token();
	$nb_modifs=0; $nb_erreurs=0;
	$retour=des_encode_nom_photo_des_eleves();
	if ($retour!="" && $nb_erreurs==0) $msg=$retour;
	else 
		if ($nb_erreurs==0)
			if ($nb_modifs>0)
				if ($nb_modifs>1) $msg=$nb_modifs." noms de fichiers photo ont été dés-encodés.<br/>";
				else $msg="Un nom de fichier photo a été encodé.<br/>";
			else $msg="Aucun nom de fichier photo n'a été dés-encodé (le dossier est probablement vide).<br/>";
		else
			if ($nb_erreurs<=10) $msg=$retour;
			else $msg=$nb_erreurs." noms de fihiers photo n'ont pu être dés-encodés.<br/>";
	}


// Liste des données élève
if (isset($_GET['liste_eleves']) and ($_GET['liste_eleves']=='oui'))  {
	check_token();

	/*
	header("Content-Description: File Transfer");
	header("Content-Disposition: attachment; filename=eleves_".getSettingValue("gepiYear").".csv");
	header("Content-Type: text/csv; charset=utf-8");
	header("Content-Transfer-Encoding: base64");
	// pb de download avec IE
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
	{
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
	} 
		else {header('Pragma: no-cache');
	}
	*/
	$csv="";
	$csv.="\"classe\",\"nom\",\"prénom\",\"prénom nom\",\"login\",\"elenoet\"\n";
	$r_sql="SELECT `eleves`.`nom`,`eleves`.`prenom`,`eleves`.`login`,`eleves`.`elenoet`,`classes`.`nom_complet` FROM `eleves`,`j_eleves_classes`,`classes` WHERE (`eleves`.`login`=`j_eleves_classes`.`login` AND `j_eleves_classes`.`id_classe`=`classes`.`id`) GROUP BY `login` ORDER BY `nom_complet`,`nom`,`prenom`";
	$R_eleves=mysqli_query($GLOBALS["mysqli"], $r_sql);
	if ($R_eleves) {
		while ($un_eleve=mysqli_fetch_assoc($R_eleves)) {
			$csv.="\"".$un_eleve['nom_complet']."\",\"".$un_eleve['nom']."\",\"".$un_eleve['prenom']."\",\"".$un_eleve['prenom']." ".$un_eleve['nom']."\",\"".$un_eleve['login']."\",\"".$un_eleve['elenoet']."\"\n";
		}
	}

	$nom_fic="eleves_".getSettingValue("gepiYear").".csv";
	send_file_download_headers('text/x-csv',$nom_fic);
	//echo $csv;
	echo echo_csv_encoded($csv);
	die();
}
	
// Téléchargement des photos élèves
function erreur_rename_correspondances_csv()
	{
	global $msg,$une_ligne;
	$msg.="correspondances.csv : impossible de renommer \"".$une_ligne[0]."\" en \"".$une_ligne[1]."\"<br />";
	}

if (isset($_POST['action']) and ($_POST['action']=='upload_photos_eleves'))  {
	check_token();
	$msg="";
	// Le téléchargement s'est-il bien passé ?
	$sav_file = isset($_FILES["nom_du_fichier"]) ? $_FILES["nom_du_fichier"] : NULL;
	if ($sav_file) {
		// c'est dans $dir_temp que le travail se fera
		$dir_temp="../temp/trombinoscopes";
		if ($multisite=='y' && isset($_COOKIE['RNE'])) $dir_temp."_".$_COOKIE['RNE'];
		if (is_file($dir_temp) && !@unlink($dir_temp)) $msg.="Impossible de supprimer ".$dir_temp.".<br/>\n";
			else if (!file_exists($dir_temp)) 
				if (!@mkdir($dir_temp,0700,true)) $msg.="Impossible de créer ".$dir_temp."..<br/>\n";
		if ($msg=="") {
			// astuce : pour rester compatible avec le script de restauration
			// on crée l'arborescence /photos/eleves
			$dir_temp_photos_eleves=$dir_temp."/photos/eleves";
			if (!file_exists($dir_temp_photos_eleves)) 
				if (!@mkdir($dir_temp_photos_eleves,0700,true)) $msg.="Impossible de créer ".$dir_temp_photos_eleves.".<br/>\n"; ;
			if ($msg=="") {
				// copie du fichier ZIP dans $dir_temp
				$reponse=telecharge_fichier($sav_file,$dir_temp_photos_eleves,"zip",'application/zip application/octet-stream application/x-zip-compressed multipart/x-zip');
				if ($reponse!="ok") {
					$msg.=$reponse;
				} else {
					// dézipage du fichier
					$reponse=dezip_PclZip_fichier($dir_temp_photos_eleves."/".$sav_file['name'],$dir_temp_photos_eleves."/",1);
					if ($reponse!="ok") {
						$msg.=$reponse;
					} else {
						//suppression du fichier .zip
						if (!@unlink ($dir_temp_photos_eleves."/".$_FILES["nom_du_fichier"]['name'])) {
							$msg .= "Erreur lors de la suppression de ".$dir_temp."/".$_FILES["nom_du_fichier"]."<br/>\n";
						}
					// quelque soit la structure du fichier .zip on déplace les photos dans $dir_temp_photos_eleves
					aplanir_tree($dir_temp_photos_eleves,$dir_temp_photos_eleves);

					// on renomme éventuellement les photos
					if (file_exists($dir_temp_photos_eleves."/correspondances.csv")) { 
						if (($fichier_csv=fopen($dir_temp_photos_eleves."/correspondances.csv","r"))!==FALSE)
							{
							$old_error_handler = set_error_handler("erreur_rename_correspondances_csv");
							while (($une_ligne=fgetcsv($fichier_csv,1000,","))!==FALSE) 
								if (count($une_ligne)==2) rename($dir_temp_photos_eleves."/".$une_ligne[0],$dir_temp_photos_eleves."/".$une_ligne[1].".jpg");
							fclose($fichier_csv);
							restore_error_handler();
							}
					}

					$repertoire_photos=""; $msg_multisite="";
					if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite']=='y')
						// On récupère le RNE de l'établissement
						if (!$repertoire_photos=$_COOKIE['RNE'])
							{
							$msg_multisite.="Multisite : erreur lors de la récupération du dossier photos de l'établissement.<br/>";
							}

					if ($msg_multisite=="")
						{
						if ($repertoire_photos!="") $repertoire_photos.="/";
						$repertoire_photos="../photos/".$repertoire_photos;
						$msg_nb_trts=""; // nb de fichiers traités
						// copie des fichiers vers /photos
						$ecraser=isset($_POST["ecraser"]) && ($_POST["ecraser"]=="yes");
						copie_temp_vers_photos($nb_photos_eleves,'eleves','élève',$ecraser,false,true);
						if ($msg_nb_trts=="") $msg_nb_trts="Aucune photo n'a été transférée.<br/>\n";
						if ($msg==""){
							$msg= $msg_nb_trts;
							} else $msg=$msg_nb_trts.$msg;
						} else $msg= $msg.$msg_multisite;
					}
				}
			}
		}
	// quoiqu'il se soit passé on supprime le dossier ../temp/trombinoscopes
	del_tree("../temp/trombinoscopes");
	}
}

// Restauration d'une sauvegarde, ceci peut-ête appelé depuis gestion/accueil_sauve.php (on a alors isset($_POST['action']) à false)
if ((isset($_POST['action']) && $_POST['action'] == 'restaurer_sauvegarde') || (isset($_GET['action']) && $_GET['action'] == 'restaurer_photos' &&  isset($_GET['file']))) {
	check_token();
	$msg="";
	// Le fichier sauvegarde est-il téléchargé ou défini ?
	if (isset($_POST['action']))
		$fichier_sauvegarde = isset($_FILES["nom_du_fichier"]) ? $_FILES["nom_du_fichier"] : NULL;
		else $fichier_sauvegarde['name']=$_GET['file'];
	if ($fichier_sauvegarde) {
		// c'est dans $dir_temp que le travail se fera
		$dir_temp ="../temp/trombinoscopes";
		if ($multisite=='y' && isset($_COOKIE['RNE'])) $dir_temp."_".$_COOKIE['RNE'];
		if (is_file($dir_temp) && !@unlink($dir_temp)) $msg.="Impossible de supprimer ".$dir_temp."<br/>\n";
			else if (!file_exists($dir_temp)) 
				if (!@mkdir($dir_temp,0700,true)) $msg.="Impossible de créer ".$dir_temp."<br/>\n";
		if ($msg=="") {
			//  copie du fichier ZIP dans $dir_temp
			if (isset($_POST['action']))
				$reponse=telecharge_fichier($fichier_sauvegarde,$dir_temp,"zip",'application/zip application/octet-stream application/x-zip-compressed');
				else $reponse=(copy("../backup/".getSettingValue("backup_directory")."/".$fichier_sauvegarde['name'],$dir_temp."/".$fichier_sauvegarde['name']))?"ok":"";
			if ($reponse!="ok") {
				$msg.=$reponse;
			} else {
				// dézipage du fichier
				$reponse=dezip_PclZip_fichier($dir_temp."/".$fichier_sauvegarde['name'],$dir_temp,1);
				if ($reponse!="ok") {
					$msg .= $reponse;
				} else {
					//suppression du fichier .zip
					if (!@unlink ($dir_temp."/".$fichier_sauvegarde['name'])) {
						$msg .= "Erreur lors de la suppression de ".$dir_temp."/".$_FILES["nom_du_fichier"]."<br/>\n";
					}
					// détermination du dossier destination
					$repertoire_photos=""; $msg_multisite="";
					if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite']=='y')
						// On récupère le RNE de l'établissement
						if (!$repertoire_photos=$_COOKIE['RNE'])
							{
							$msg_multisite.="Multisite : erreur lors de la récupération du dossier photos de l'établissement.<br/>";
							}
					if ($msg_multisite=="")
						{
						if ($repertoire_photos!="") $repertoire_photos.="/";
						$repertoire_temp_photos=$dir_temp."/photos/".$repertoire_photos;
						$repertoire_photos="../photos/".$repertoire_photos;

						// Si on restaure une sauvegarde contenant des fichiers
						// encodés Gepi version <= 1.7.2 il faut les dés-encoder
						if (file_exists($repertoire_temp_photos."alea_nom_photo.txt")) {
							$t_noms_photos=array();
							$dossier_temp_photos_eleves=$repertoire_temp_photos.'/eleves/';
							$R_dossier_temp_photos_eleves=opendir($dossier_temp_photos_eleves);
							while ($photo=readdir($R_dossier_temp_photos_eleves)) {
								if (is_file($dossier_temp_photos_eleves.$photo) && strtolower(pathinfo($photo,PATHINFO_EXTENSION))=='jpg') {
									$t_noms_photos[]=$photo;
								}
							}
							closedir($R_dossier_temp_photos_eleves);
							foreach($t_noms_photos as $photo) {
								$nom_photo=pathinfo($dossier_temp_photos_eleves.$photo,PATHINFO_FILENAME);
								// supprimer l'ancien encodage
								$nom_photo=substr($nom_photo,5);
								if($nom_photo!='') {
									// on en profite pour normaliser l'extension en .jpg
									rename($dossier_temp_photos_eleves.$photo,$dossier_temp_photos_eleves.$nom_photo.".jpg");
								}
							}
						}

						// copie des fichiers vers /photos
						$msg_nb_trts=""; // nb de fichiers traités
						$avertissement=""; // si l'arborescence est incomplète
						$ecraser=(isset($_POST['action']))?(isset ($_POST["ecraser"]) && $_POST["ecraser"]=="yes"):true;
						//Elèves
						copie_temp_vers_photos($nb_photos_eleves,'eleves','élève',$ecraser,true,true);
						//Personnels
						copie_temp_vers_photos($nb_photos_personnels,'personnels','personnel',$ecraser,true);
						if ($msg_nb_trts=="") $msg_nb_trts="Aucune photo n'a été restaurée.<br/>\n";
						if ($msg==""){
							$msg= $msg_nb_trts.$avertissement;
							} else $msg= $msg_nb_trts.$avertissement.$msg;
						} else $msg= $avertissement.$msg.$msg_multisite;
					}
				}
			}
		// quoiqu'il se soit passé on supprime le dossier ../temp/trombinoscopes
		del_tree("../temp/trombinoscopes");
		}
}



// header
//$titre_page = "Gestion du module trombinoscope";


// En multisite, on ajoute le répertoire RNE
if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y') {
	  // On récupère le RNE de l'établissement
  $repertoire_rne=$_COOKIE['RNE']."/";
}else{
  $repertoire_rne="";
}

/****************************************************************
                     HAUT DE PAGE
****************************************************************/

// ====== Inclusion des balises head et du bandeau =====
include_once("../lib/header_template.inc.php");

if (!suivi_ariane($_SERVER['PHP_SELF'],$titre_page))
		echo "erreur lors de la création du fil d'ariane";
/****************************************************************
			FIN HAUT DE PAGE
****************************************************************/
//debug_var();
if (getSettingValue("GepiAccesModifMaPhotoEleve")=='yes') {

  $req_trombino = mysqli_query($GLOBALS["mysqli"], "select indice_aid, nom from aid_config order by nom");
  $nb_aid = mysqli_num_rows($req_trombino);
  $i = 0;
  for($i = 0;$i < $nb_aid;$i++){
	  $aid_trouve[$i]["indice"]= old_mysql_result($req_trombino,$i,'indice_aid');
	  $aid_trouve[$i]["nom"]= old_mysql_result($req_trombino,$i,'nom');
	  if (getSettingValue("num_aid_trombinoscopes")==$aid_trouve[$i]["indice"]){
		$aid_trouve[$i]["selected"]= TRUE;
		echo getSettingValue("num_aid_trombinoscopes")." : ".$aid_trouve[$i]["indice"];
	  }else {
		$aid_trouve[$i]["selected"]= FALSE;

	  }
  }
}


/*
 * TODO : 
 * <?php if ( $sousrub === 've' ) {
 * }
 *
 * if ( $sousrub === 'vp' ) {
 * }
 *
 * if ( $sousrub === 'de' ) {
 * }
 *
 * if ( $sousrub === 'dp' ) {
 * }
 *
 * if ( $sousrub === 'deok' ) {
 * }
 *
 * if ( $sousrub === 'dpok' ) {
 * }
 */


/****************************************************************
			BAS DE PAGE
****************************************************************/
$tbs_microtime	="";
$tbs_pmv="";
require_once ("../lib/footer_template.inc.php");

/****************************************************************
			On s'assure que le nom du gabarit est bien renseigné
****************************************************************/
if ((!isset($_SESSION['rep_gabarits'])) || (empty($_SESSION['rep_gabarits']))) {
	$_SESSION['rep_gabarits']="origine";
}

//==================================
// Décommenter la ligne ci-dessous pour afficher les variables $_GET, $_POST, $_SESSION et $_SERVER pour DEBUG:
// $affiche_debug=debug_var();


$nom_gabarit = '../templates/'.$_SESSION['rep_gabarits'].'/mod_trombinoscopes/trombinoscopes_admin_template.php';

$tbs_last_connection=""; // On n'affiche pas les dernières connexions
include($nom_gabarit);

// ------ on vide les tableaux -----
unset($menuAffiche);



?>
