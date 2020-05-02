<?php
/*
*
* Copyright 2016-2019 Régis Bouguin, Stephane Boireau
*
* This file is part of GEPI.
*
* GEPI is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 3 of the License, or
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

//debug_var();

$selectionClasse = $_SESSION['afficheClasse'];

//+++++++++++++++++++++++++++++++++++++++++
// Initialisation:
$LSUN_periodes_a_extraire="toutes";
// Nombre max de périodes:
$sql="SELECT MAX(num_periode) AS maxper FROM periodes p, classes c WHERE p.id_classe=c.id;";
$res_max_per=mysqli_query($mysqli, $sql);
$lig_maxper=mysqli_fetch_object($res_max_per);
$maxper=$lig_maxper->maxper;
// Liste des périodes à extraire
if(isset($_POST['LSUN_periodes_a_extraire'])) {
	if($_POST['LSUN_periodes_a_extraire']=="toutes") {
		$LSUN_periodes_a_extraire="toutes";

		if(isset($_SESSION['LSUN_periodes'])) {
			unset($_SESSION['LSUN_periodes']);
		}
	}
	elseif(!isset($_POST['LSUN_periodes'])) {
		$LSUN_periodes_a_extraire="toutes";

		if(isset($_SESSION['LSUN_periodes'])) {
			unset($_SESSION['LSUN_periodes']);
		}
	}
	else {
		$LSUN_periodes_a_extraire="restreindre";
		$_SESSION['LSUN_periodes']=$_POST['LSUN_periodes'];
		$LSUN_periodes=$_SESSION['LSUN_periodes'];
	}
	$_SESSION['LSUN_periodes_a_extraire']=$LSUN_periodes_a_extraire;
}
else {
	if(isset($_SESSION['LSUN_periodes_a_extraire'])) {
		if(($_SESSION['LSUN_periodes_a_extraire']=="toutes")||(!isset($_SESSION['LSUN_periodes']))) {
			$LSUN_periodes_a_extraire="toutes";
		}
		else {
			$LSUN_periodes_a_extraire="restreindre";
			$LSUN_periodes=$_SESSION['LSUN_periodes'];
		}
	}
	else {
		$LSUN_periodes_a_extraire="toutes";
	}

	$_SESSION['LSUN_periodes_a_extraire']=$LSUN_periodes_a_extraire;
}

if($LSUN_periodes_a_extraire=="toutes") {
	$checked_LSUN_periodes_a_extraire_toutes=" checked";
	$checked_LSUN_periodes_a_extraire_restreindre="";
	for($loop=1;$loop<=$maxper;$loop++) {
		$checked_LSUN_periodes[$loop]="";
	}
}
else {
	$checked_LSUN_periodes_a_extraire_toutes="";
	$checked_LSUN_periodes_a_extraire_restreindre=" checked";
	for($loop=1;$loop<=$maxper;$loop++) {
		if((is_array($LSUN_periodes))&&(in_array($loop, $LSUN_periodes))) {
			$checked_LSUN_periodes[$loop]=" checked";
		}
		else {
			$checked_LSUN_periodes[$loop]="";
		}
	}
}
//+++++++++++++++++++++++++++++++++++++++++
if(isset($_POST['LSUN_version_xsd'])) {
	$LSUN_version_xsd=$_POST['LSUN_version_xsd'];
	if(!saveSetting('LSUN_version_xsd', $LSUN_version_xsd)) {
		echo "<p style='color:red'>ERREUR lors de l'enregistrement du choix de format d'export XSD.</p>";
	}
	//echo "LSUN_version_xsd=$LSUN_version_xsd<br />".getSettingValue('LSUN_version_xsd')."<br />";
}

//debug_var();

//===== Mettre à jour les responsables
$metJourResp = filter_input(INPUT_POST, 'MetJourResp');
if ($metJourResp == 'y') {
	MetAJourResp();
}

//===== Choix des données à exporter =====
//===== Création du fichier =====
$creeFichier = filter_input(INPUT_POST, 'creeFichier');

if ($creeFichier == 'y') {
	if(filter_input(INPUT_POST, 'traiteVieSco')) {
		saveSetting('LSU_commentaire_vie_sco', filter_input(INPUT_POST, 'traiteVieSco'));
	}	else {
		saveSetting('LSU_commentaire_vie_sco', "n");
	}
	if(filter_input(INPUT_POST, 'traiteParent')) {
		saveSetting('LSU_Donnees_responsables', filter_input(INPUT_POST, 'traiteParent'));
	}	else {
		saveSetting('LSU_Donnees_responsables',  "n");
	}
	
	if(filter_input(INPUT_POST, 'traiteEPI')) {
		saveSetting('LSU_traite_EPI', filter_input(INPUT_POST, 'traiteEPI'));
	}	else {
		saveSetting('LSU_traite_EPI', "n");
	}
	
	if(filter_input(INPUT_POST, 'traiteEpiElv')) {
		saveSetting('LSU_traite_EPI_Elv', filter_input(INPUT_POST, 'traiteEPIElv'));
	}	else {
		saveSetting('LSU_traite_EPI_Elv', "n");
	}
	
	if(filter_input(INPUT_POST, 'traiteAP')) {
		saveSetting('LSU_traite_AP', filter_input(INPUT_POST, 'traiteAP'));
	}	else {
		saveSetting('LSU_traite_AP', "n");
	}

	if(filter_input(INPUT_POST, 'traiteAPElv')) {
		saveSetting('LSU_traite_AP_Elv', filter_input(INPUT_POST, 'traiteAPElv'));
	}	else {
		saveSetting('LSU_traite_AP_Elv', "n");
	}

	if(filter_input(INPUT_POST, 'traiteModSpeElv')) {
		saveSetting('LSU_traiteModSpeElv', filter_input(INPUT_POST, 'traiteModSpeElv'));
	}	else {
		saveSetting('LSU_traiteModSpeElv', "n");
	}

	// 20200219
	if(filter_input(INPUT_POST, 'traiteCompetencesNumeriques')) {
		saveSetting('LSU_Competences_Numeriques', filter_input(INPUT_POST, 'traiteCompetencesNumeriques'));
	}	else {
		saveSetting('LSU_Competences_Numeriques', "n");
	}

	if(filter_input(INPUT_POST, 'traiteSocle')) {
		saveSetting('LSU_Donnees_socle', filter_input(INPUT_POST, 'traiteSocle'));
	}	else {
		saveSetting('LSU_Donnees_socle',  "n");
	}

	if(filter_input(INPUT_POST, 'traiteBilanFinCycle')) {
		saveSetting('LSU_Donnees_BilanFinCycle', filter_input(INPUT_POST, 'traiteBilanFinCycle'));
	}	else {
		saveSetting('LSU_Donnees_BilanFinCycle',  "n");
	}

	if(filter_input(INPUT_POST, 'CreerAutomatiquementElementsProgrammes')) {
		saveSetting('LSU_CreerAutomatiquementElementsProgrammes', filter_input(INPUT_POST, 'CreerAutomatiquementElementsProgrammes'));
	}	else {
		saveSetting('LSU_CreerAutomatiquementElementsProgrammes',  "n");
	}

	if(filter_input(INPUT_POST, 'forceNotes')) {
		$_SESSION["forceNotes"]="y";
	}	else {
		$_SESSION["forceNotes"]="n";
	}

	if(filter_input(INPUT_POST, 'forceAppreciations')) {
		$_SESSION["forceAppreciations"]="y";
	}	else {
		$_SESSION["forceAppreciations"]="n";
	}

	if(filter_input(INPUT_POST, 'BilanFinCycle3')) {
		$_SESSION["BilanFinCycle3"]="y";
	}	else {
		$_SESSION["BilanFinCycle3"]="n";
	}

	if(filter_input(INPUT_POST, 'BilanFinCycle4')) {
		$_SESSION["BilanFinCycle4"]="y";
	}	else {
		$_SESSION["BilanFinCycle4"]="n";
	}

	if (0 == count($selectionClasse)) {
		echo "<p class='rouge center gras'>Vous devez valider la sélection d'au moins une classe</p> <p><a href = 'index.php'>Cliquez ici pour recharger la page</a></p>";
	}	else if ($creeFichier == 'y') {
		include_once 'creeFichier.php';
	}

	include_once 'creeFichier.php';

}

//==============================================================
// Pour tenir compte d'un ajout de champ 'annee' oublié en 1.7.1
check_tables_modifiees();
//==============================================================

//===== On récupère les données =====
$scolarites = getUtilisateurSurStatut('scolarite');
$cpes = getUtilisateurSurStatut('cpe');
$Enseignants = getUtilisateurSurStatut('professeur');
$responsables = getResponsables();
$parcoursCommuns = getParcoursCommuns();
$AidParcours = getAidParcours();
$ListeAidParcours = getLiaisonsAidParcours();

$listeMatieres = getMatiereLSUN();

$listeEPICommun = getEPICommun();

$listeAPCommun = getAPCommun();
//var_dump($listeAPCommun);
$listeAp = getApCommun();
$listeAidAp = getApAid();




//===== on charge les nomenclatures de LSUN =====
if (file_exists('LSUN_nomenclatures.xml')) {
	libxml_use_internal_errors(true);
	$xml = simplexml_load_file('LSUN_nomenclatures.xml');
	if(!$xml) {
		exit('Echec lors de la lecture du fichier LSUN_nomenclatures.xml avec SimpleXML.');
	}
	libxml_use_internal_errors(false);
} else {
	exit('Echec lors de l\'ouverture du fichier LSUN_nomenclatures.xml.');
}


//===== on charge les périodes =====
$periodes = getPeriodes();
$classes = getClasses();

$anomalies_tables=check_anomalie_mod_LSUN();
if($anomalies_tables!="") {
	echo "<div align='center'>".$anomalies_tables."</div>";
}

if(isset($msg_requetesAdmin)) {
	echo "<div align='center'>".$msg_requetesAdmin."</div>";
}

$msg_erreur="";
?>

<h2>Procédure</h2>
<div style='margin-left:3em;'>
	<h3>Les grandes lignes</h3>
	<div style='margin-left:3em;'>
		<p>L'opération de génération d'un fichier export XML à destination de l'application Livret Scolaire Unique <em title="Livret Scolaire Unique Numérique">(LSUN)</em> se déroule en plusieurs étapes&nbsp;:</p>
		<ol>
			<li><a href='#form_classes_a_exporter'>Sélection des classes</a></li>
			<li>Sélection/définition des <a href='#form_EPIs'>EPI</a>, <a href='#form_AP'>AP</a> et <a href='#form_parcours_communs'>Parcours</a></li>
			<li><a href='#form_export_des_donnees'>Sélection des éléments à exporter</a> <em>(Bilans périodiques, positionnement sur le Socle de composantes, Bilan de fin de cycle,...)</em><br />
			Certains éléments cochés/grisés doivent impérativement être présents <em>(d'où les champs cochés et grisés)</em>.</li>
		</ol>
		<p>Si lors de l'export des erreurs sont signalées, vous devrez compléter/corriger dans Gepi <em>(nomenclatures ou modalités de matières manquantes, identifiants de professeurs manquants,...)</em></p>
		<p>Si l'export produit est conforme, vous pourrez l'importer dans l'application LSUN.</p>
		<p>Voir la documentation sur le <a href='http://www.sylogix.org/projects/gepi/wiki/LSUN' target='_blank'>wiki</a>.</p>
	</div>
	<h3>Prérequis</h3>
	<div style='margin-left:3em;'>
		<p>Les nomenclatures, identifiants,... doivent être à jour.</p>

		<p style='margin-top:1em;'>Les nomenclatures des disciplines doivent avoir été renseignées d'après Sconet pour que l'association d'une Discipline avec un EPI ou une AP puisse être effectuée.<br />
		<?php
			$sql="SELECT DISTINCT m.matiere FROM matieres m, nomenclatures_valeurs nv WHERE nv.type='matiere' AND m.code_matiere=nv.code;";
			$res_mat=mysqli_query($mysqli, $sql);
			echo mysqli_num_rows($res_mat)." matière(s) a(ont) leur nomenclature renseignée.<br />";

			$sql="SELECT DISTINCT m.matiere, m.nom_complet FROM matieres m LEFT JOIN nomenclatures_valeurs nv ON m.code_matiere=nv.code WHERE nv.code IS NULL;";
			$res_mat=mysqli_query($mysqli, $sql);
			if(mysqli_num_rows($res_mat)>0) {
				echo "<span style='color:red; font-weight:bold;'>Problème potentiel&nbsp;:</span> ".mysqli_num_rows($res_mat)." matière(s) n'a(ont) pas leur nomenclature renseignée <em>(";
				$cpt_mat=0;
				while($lig_mat=mysqli_fetch_object($res_mat)) {
					if($cpt_mat>0) {
						echo ", ";
					}
					echo "<span title=\"$lig_mat->nom_complet\">$lig_mat->matiere</span>";
					$cpt_mat++;
				}
				echo ")</em>.<br /><span style='color:red'>Si ces matières ne sont pas destinées à remonter vers LSUN, vous pouvez ne pas tenir compte de cette alerte.</span>";
			}
			else {
				echo "Toutes les matières ont leur nomenclature renseignée.";
			}
		?><br />
		S'il manque des nomenclatures, sélectionnez/complétez manuellement un à un les codes matières dans <a href='../matieres/index.php'>Gestion des matières</a><br />
		ou globalement dans <a href='../gestion/admin_nomenclatures.php?action=importnomenclature'>Importer les nomenclatures</a>.</p>

		<?php
			$test_champ=mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM nomenclature_modalites_election;"));
			if ($test_champ==0) {
				echo "<span style='color:red;'><strong>ANOMALIE&nbsp;:</strong> Les modalités d'élection des matières sont manquantes.<br /><a href='../utilitaires/maj.php'>Forcer une mise à jour de la base</a> pour re-créer les modalités manquantes.</span><br /><br />";
			}
		?>

		<p style='margin-top:1em;'>S'il manque des éléments, les erreurs vous seront signalées et vous devrez corriger.</p>

		<p style='margin-top:1em;'>L'étape de définition des EPI nécessite <em title="Il est envisagé de permettre la création complète des EPI depuis la présente page, mais ce n'est pas encore réalisé/finalisé.">actuellement (*)</em> d'avoir <a href='../aid/index.php' target='_blank'>créé les AID</a> correspondants préalablement.<br />
		Les AID peuvent être <a href='../aid/transfert_groupe_aid.php' target='_blank'>créés/migrés depuis des enseignements classiques</a>.<br />
		Et dans la présente page, le lien est fait entre ces AID et les EPI que vous souhaitez exporter vers LSUN.</p>

		<p style='margin-top:1em;'>Les dates des périodes de cours doivent être définies dans <a href='../edt_organisation/edt_calendrier.php' target='_blank'>Emplois du temps/Gestion/Gestion du calendrier</a> et les classes associées à ces périodes.<br />
		<?php

			if((isset($selectionClasse))&&(count($selectionClasse)>0)) {
				$begin_bookings=getSettingValue('begin_bookings');
				$debut_annee=strftime("%Y-%m-%d", $begin_bookings);
				$end_bookings=getSettingValue('end_bookings');
				$fin_annee=strftime("%Y-%m-%d", $end_bookings);

				for($loop=0;$loop<count($selectionClasse);$loop++) {
					$tab_date=array();
					$sql="SELECT id_calendrier, numero_periode, jourdebut_calendrier, jourfin_calendrier FROM edt_calendrier AS ec2  WHERE ec2.numero_periode>0 AND FIND_IN_SET(".$selectionClasse[$loop].", replace(ec2.classe_concerne_calendrier, ';', ',')) > 0 ORDER BY numero_periode;";
					$res_clas=mysqli_query($mysqli, $sql);
					if(mysqli_num_rows($res_clas)>0) {
						//echo "<span style='color:red'>".mysqli_num_rows($res_mat)." matière(s) n'a(ont) pas leur nomenclature renseignée <em>(";
						$cpt_clas=0;

						$jourdebut_prec=$debut_annee;
						$jourfin_prec=$debut_annee;
						while($lig_clas=mysqli_fetch_object($res_clas)) {
							$tab_date[$lig_clas->numero_periode]=$lig_clas->jourdebut_calendrier;

							if(($lig_clas->numero_periode==1)&&($lig_clas->jourdebut_calendrier<$debut_annee)) {
								echo "<span style='color:red'><strong>Anomalie&nbsp;:</strong> Le premier jour de la <a href='../edt_organisation/edt_calendrier.php?calendrier=ok&modifier=".$lig_clas->id_calendrier."' target='_blank'>première période (".formate_date($lig_clas->jourdebut_calendrier).")</a> de la classe de ".get_nom_classe($selectionClasse[$loop])." est antérieur au <a href='../gestion/param_gen.php' target='_blank'>début de l'année scolaire</a>.</span><br />";
							}
							elseif($lig_clas->jourdebut_calendrier<$jourfin_prec) {
								echo "<span style='color:red'><strong>Anomalie&nbsp;:</strong> Le premier jour de la <a href='../edt_organisation/edt_calendrier.php?calendrier=ok&modifier=".$lig_clas->id_calendrier."' target='_blank'>période ".$lig_clas->numero_periode." <em>(".formate_date($lig_clas->jourdebut_calendrier).")</em></a> de la classe de ".get_nom_classe($selectionClasse[$loop])." est antérieur au dernier jour de la période précédente <em>($jourfin_prec)</em>.</span><br />";
							}

							$jourdebut_prec=$lig_clas->jourdebut_calendrier;
							$jourfin_prec=$lig_clas->jourfin_calendrier;
							if($jourdebut_prec>$jourfin_prec) {
								echo "<span style='color:red'><strong>Anomalie&nbsp;:</strong> Le premier jour de la <a href='../edt_organisation/edt_calendrier.php?calendrier=ok&modifier=".$lig_clas->id_calendrier."' target='_blank'>période ".$lig_clas->numero_periode." (".formate_date($lig_clas->jourdebut_calendrier).") est postérieure à la date de fin (".$lig_clas->jourfin_calendrier.")</a> pour la classe de ".get_nom_classe($selectionClasse[$loop]).".</span><br />";
							}
						}
					}

					for($loop_per=1;$loop_per<=$maxper;$loop_per++) {
						if(!isset($tab_date[$loop_per])) {
							echo "<span style='color:red'>La période $loop_per n'est pas définie dans <a href='../edt_organisation/edt_calendrier.php' target='_blank'>Emplois du temps/Gestion/Gestion du calendrier</a> pour la classe de ".get_nom_classe($selectionClasse[$loop]).".</span><br />";
						}
					}
				}
			}

			if((isset($selectionClasse))&&(count($selectionClasse)>0)) {
				for($loop=0;$loop<count($selectionClasse);$loop++) {
					$sql="SELECT DISTINCT e.login, e.nom, e.prenom FROM eleves e, 
										j_eleves_classes jec, 
										mef m 
									WHERE e.mef_code=m.mef_code AND 
										jec.login=e.login AND 
										jec.id_classe='".$selectionClasse[$loop]."' AND 
										((m.libelle_long LIKE '%ULIS%' AND e.id_eleve NOT IN (SELECT id_eleve FROM j_modalite_accompagnement_eleve WHERE code='ULIS')) OR 
										(m.libelle_long LIKE '%SEGPA%' AND e.id_eleve NOT IN (SELECT id_eleve FROM j_modalite_accompagnement_eleve WHERE code='SEGPA')))";
					//echo "$sql<br />";
					$test=mysqli_query($mysqli, $sql);
					if(mysqli_num_rows($test)>0) {
						echo "<br /><span style='color:red'>Un ou des élèves semblent avoir un MEF de type SEGPA ou ULIS, mais la modalité d'accompagnement n'est pas renseignée dans <a href='../gestion/saisie_modalites_accompagnement.php?id_classe=".$selectionClasse[$loop]."' target='_blank'>Modalités d'accompagnement en ".get_nom_classe($selectionClasse[$loop])."</a>&nbsp;: ";
						$cpt_em=0;
						while($lig=mysqli_fetch_object($test)) {
							if($cpt_em>0) {echo ", ";}
							echo "<a href='../gestion/saisie_modalites_accompagnement.php?login_eleve=".$lig->login."' target='_blank'>".$lig->nom." ".$lig->prenom."</a> ";
							$cpt_em++;
						}
						echo ".</span><br />";
					}
				}
			}

			if((isset($selectionClasse))&&(count($selectionClasse)>0)) {
				$gepi_prof_suivi=getSettingValue('gepi_prof_suivi');
				for($loop=0;$loop<count($selectionClasse);$loop++) {
					$sql="SELECT DISTINCT login,COUNT(professeur) AS nb_prof FROM j_eleves_professeurs WHERE id_classe='".$selectionClasse[$loop]."' GROUP BY login HAVING COUNT(professeur)>1;";
					$test=mysqli_query($mysqli, $sql);
					if(mysqli_num_rows($test)>0) {
						while($lig=mysqli_fetch_object($test)) {
							echo "<br /><span style='color:red'>Attention&nbsp;:</span> ".get_nom_prenom_eleve($lig->login)." a ".$lig->nb_prof." ".$gepi_prof_suivi."<br />C'est une anomalie. Vous ne devriez en avoir qu'un par élève/classe.<br />Effectuez un <a href='../utilitaires/clean_tables.php?maj=2".add_token_in_url()."' target='_blank'>Nettoyage des tables</a> pour n'en retenir qu'un par élève/classe ou revalidez la sélection affichée dans <a href='../classes/classes_const.php?id_classe=".$selectionClasse[$loop]."' target='_blank'>Gérer les élèves de la classe de ".get_nom_classe($selectionClasse[$loop])."</a> pour ne garder que le professeur affiché dans le champ SELECT.<br />Dans le cas contraire, <span style='color:red'>un seul des $gepi_prof_suivi sera arbitrairement retenu dans l'export</span>.<br />";
						}
					}
				}
			}

			//$sql="SELECT login,numind,cast(SUBSTRING(numind,2,10) AS UNSIGNED) as ma_valeur from utilisateurs where statut='professeur';";
			//$sql="SELECT login,numind,CAST(SUBSTRING(numind,2,255) AS UNSIGNED) AS ma_valeur FROM utilisateurs u,j_groupes_professeurs jgp WHERE u.statut='professeur' AND jgp.login=u.login GROUP BY ma_valeur HAVING COUNT(ma_valeur)>1;";
			//$sql="SELECT u.login,u.numind,CAST(SUBSTRING(numind,2,255) AS UNSIGNED) AS ma_valeur FROM utilisateurs u,j_groupes_professeurs jgp WHERE u.statut='professeur' AND jgp.login=u.login GROUP BY ma_valeur HAVING COUNT(ma_valeur)>1;";
			$sql="SELECT u.login,u.numind,CAST(SUBSTRING(numind,2,255) AS UNSIGNED) AS ma_valeur FROM utilisateurs u WHERE u.statut='professeur' AND u.login IN (SELECT DISTINCT login FROM j_groupes_professeurs) GROUP BY ma_valeur HAVING COUNT(ma_valeur)>1;";
			//echo "$sql<br />";
			$test_numind=mysqli_query($mysqli,$sql);
			if(mysqli_num_rows($test_numind)>0) {
				echo "<br /><span style='color:red'>Un ou des identifiants professeurs sont en doublons.<br />La valeur numérique suivant le P doit être unique.<br />";
				echo "</span>";
				while($lig_numind=mysqli_fetch_object($test_numind)) {

					$sql="SELECT DISTINCT u.login,u.numind,u.nom,u.prenom FROM utilisateurs u, j_groupes_professeurs jgp WHERE u.statut='professeur' AND CAST(SUBSTRING(numind,2,255) AS UNSIGNED)='".$lig_numind->ma_valeur."' AND u.login=jgp.login ORDER BY u.nom, u.prenom;";
					//echo "$sql<br />";
					$test_numind2=mysqli_query($mysqli,$sql);
					if(mysqli_num_rows($test_numind2)>0) {
						echo "<table class='boireaus boireaus_alt'>
						<tr>
							<th>Login</th>
							<th>Nom</th>
							<th>Prénom</th>
							<th>Numind</th>
						</tr>";
						while($lig_numind2=mysqli_fetch_object($test_numind2)) {
							echo "
						<tr>
							<td><a href='../utilisateurs/modify_user.php?user_login=".$lig_numind2->login."' target='_blank'>".$lig_numind2->login."</a></td>
							<td>".$lig_numind2->nom."</td>
							<td>".$lig_numind2->prenom."</td>
							<td>".$lig_numind2->numind."</td>
						</tr>";
						}
						echo "</table>
						<p style='color:red'>Vous devez faire en sorte d'avoir des identifiants STS différents pour la partie numérique.</p>";
					}
				}
			}


			$sql="SELECT DISTINCT m.* FROM mef m, eleves e, j_eleves_classes jec WHERE e.mef_code=m.mef_code AND jec.login=e.login AND m.mef_rattachement='' ORDER BY libelle_long, libelle_edition;";
			$test=mysqli_query($mysqli, $sql);
			if(mysqli_num_rows($test)>0) {
				echo "<br /><span style='color:red'>Un ou des MEF n'ont pas de <strong>mef_rattachement</strong>.<br />Les MEF des élèves correspondants ne vont pas être identifiés.<br /><a href='../mef/admin_mef.php' target='_blank'>Corriger</a> par exemple en important un <strong>Nomenclature.xml</strong> de Sconet.<br />Liste des MEF concernés&nbsp;: ";
				$cpt_mef=0;
				while($lig=mysqli_fetch_object($test)) {
					if($cpt_mef>0) {
						echo ", ";
					}
					echo $lig->libelle_edition;
					$cpt_mef++;
				}
				echo ".</span><br />";
			}


			if((getSettingAOui("LSU_Donnees_BilanFinCycle"))&&(isset($selectionClasse))&&(count($selectionClasse)>0)) {

				$gepiYear=getSettingValue("gepiYear");
				$gepiYear_debut=mb_substr($gepiYear, 0, 4);
				if(!preg_match("/^20[0-9]{2}/", $gepiYear_debut)) {
					echo "<span style='color:red'>Année scolaire non définie dans Gestion générale/Configuration générale.</span><br />";
				}
				else {

					$tab_domaine_socle=array();
					$tab_domaine_socle["CPD_FRA"]="Comprendre, s'exprimer en utilisant la langue française à l'oral et à l'écrit";
					$tab_domaine_socle["CPD_ETR"]="Comprendre, s'exprimer en utilisant une langue étrangère et, le cas échéant, une langue régionale";
					$tab_domaine_socle["CPD_SCI"]="Comprendre, s'exprimer en utilisant les langages mathématiques, scientifiques et informatiques";
					$tab_domaine_socle["CPD_ART"]="Comprendre, s'exprimer en utilisant les langages des arts et du corps";
					$tab_domaine_socle["MET_APP"]="Les méthodes et outils pour apprendre";
					$tab_domaine_socle["FRM_CIT"]="La formation de la personne et du citoyen";
					$tab_domaine_socle["SYS_NAT"]="Les systèmes naturels et les systèmes techniques";
					$tab_domaine_socle["REP_MND"]="Les représentations du monde et l'activité humaine";

					for($loop=0;$loop<count($selectionClasse);$loop++) {
						$tab_ele_saisie_incomplete=array();
						//$sql="SELECT e.* FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='".$id_classe."' AND jec.periode='$periode' ORDER BY e.nom, e.prenom;";
						$sql="SELECT DISTINCT e.* FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='".$selectionClasse[$loop]."' ORDER BY e.nom, e.prenom;";
						//echo "$sql<br />";
						$res=mysqli_query($GLOBALS["mysqli"], $sql);
						if(mysqli_num_rows($res)>0) {
							while($lig=mysqli_fetch_assoc($res)) {
								$mef_code_ele=$lig['mef_code'];
								if(!isset($tab_cycle[$mef_code_ele])) {
									$tmp_tab_cycle_niveau=calcule_cycle_et_niveau($mef_code_ele, "", "");
									$cycle=$tmp_tab_cycle_niveau["mef_cycle"];
									$niveau=$tmp_tab_cycle_niveau["mef_niveau"];
									$tab_cycle[$mef_code_ele]=$cycle;
								}

								if((!isset($tab_cycle[$mef_code_ele]))||($tab_cycle[$mef_code_ele]=="")) {
									echo "
							<span style='color:red'>Le cycle courant pour <a href='../eleves/visu_eleve.php?ele_login=".$lig['login']."' target='_blank'>".$lig['nom']." ".$lig['prenom']."</a> n'a pas pu être identifié&nbsp;???<br />";
								}
								else {
									if($lig['no_gep']=="") {
										echo "<span style='color:red; margin-bottom:1em;'>Le numéro national INE de l'élève <a href='../eleves/visu_eleve.php?ele_login=".$lig["login"]."' target='_blank'>".$lig['nom']." ".$lig['prenom']."</a> est vide.<br />La saisie du niveau de maitrise sur les composantes du socle n'est pas possible pour cet élève.<br />";
									}
									else {

										foreach($tab_domaine_socle as $code => $libelle) {
											//$sql="SELECT 1=1 FROM socle_eleves_composantes WHERE ine='".$lig['no_gep']."' AND cycle='".$tab_cycle[$mef_code_ele]."' AND code_composante='".$code."' AND periode='$periode' AND annee='".$gepiYear_debut."';";
											$sql="SELECT 1=1 FROM socle_eleves_composantes WHERE ine='".$lig['no_gep']."' AND cycle='".$tab_cycle[$mef_code_ele]."' AND code_composante='".$code."' AND annee='".$gepiYear_debut."';";
											//echo "$sql<br />";
											$test=mysqli_query($GLOBALS["mysqli"], $sql);
											if(mysqli_num_rows($test)==0) {
												if(!array_key_exists($lig['login'], $tab_ele_saisie_incomplete)) {
													$tab_ele_saisie_incomplete[$lig['login']]=$lig;
												}
												$tab_ele_saisie_incomplete[$lig['login']]["code_composante"][$code]="vide";
											}
										}

										$sql="SELECT 1=1 FROM socle_eleves_syntheses WHERE ine='".$lig['no_gep']."' AND cycle='".$tab_cycle[$mef_code_ele]."' AND synthese!='' AND annee='".$gepiYear_debut."';";
										//echo "$sql<br />";
										$test=mysqli_query($GLOBALS["mysqli"], $sql);
										if(mysqli_num_rows($test)==0) {
											if(!array_key_exists($lig['login'], $tab_ele_saisie_incomplete)) {
												$tab_ele_saisie_incomplete[$lig['login']]=$lig;
											}
											$tab_ele_saisie_incomplete[$lig['login']]["synthese_vide"]="y";
										}
									}
								}
							}

							//echo "<pre>";
							//print_r($tab_ele_saisie_incomplete);
							//echo "</pre>";

							if(count($tab_ele_saisie_incomplete)>0) {
								$current_classe=get_nom_classe($selectionClasse[$loop]);
								echo "
						<p style='color:red; margin-top:1em;'>Les saisies de composantes du socle sont <strong>incomplètes</strong> pour le ou les élèves suivants de ".$current_classe."&nbsp;:</p>
						<table class='boireaus boireaus_alt'>
							<thead>
								<tr>
									<th rowspan='2'>Élève</th>
									<th rowspan='2'>Classe</th>
									<th colspan='".count($tab_domaine_socle)."'>Composantes</th>
									<th rowspan='2'>Synthèse</th>
								</tr>
								<tr>";
								foreach($tab_domaine_socle as $code => $libelle) {
									echo "
									<th title=\"$libelle\">".$code."</th>";
								}
								echo "
								</tr>
							</thead>
							<tbody>";
								foreach($tab_ele_saisie_incomplete as $login_ele => $current_tab) {
									echo "
								<tr onmouseover=\"this.style.backgroundColor='white';\" onmouseout=\"this.style.backgroundColor='';\">
									<td>".$current_tab["nom"]." ".$current_tab["prenom"]."</td>
									<td>".$current_classe."</td>";
								foreach($tab_domaine_socle as $code => $libelle) {
									echo "
									<td title=\"$libelle\">".(isset($current_tab["code_composante"][$code]) ? "<img src='../images/disabled.png' class='icone16' alt='Vide' />" : "<img src='../images/enabled.png' class='icone16' alt='Ok' />")."</td>";
								}
								echo "
									<td>".(isset($current_tab["synthese_vide"]) ? "<img src='../images/disabled.png' class='icone16' alt='Vide' />" : "<img src='../images/enabled.png' class='icone16' alt='Ok' />")."</td>
								</tr>";
								}
								echo "
							</tbody>
						</table>
						<p style='color:red;'>L'enregistrement du bilan de fin de cycle de ces élèves sera refusé dans LSU.</p>";
							}
						}
					}
				}
			}

		?>
		</p>
	</div>
</div>

<h2>Formulaires</h2>

<form action="index.php" method="post" id="responsables">
	<fieldset class='fieldset_opacite50'>
		<legend class='fieldset_opacite50' title="Données saisies dans les paramètres des classes" >Responsables de l'établissement</legend>
		<ul>
<?php while ($responsable = $responsables->fetch_object()){ ?>
		<li> 
			<?php echo $responsable->suivi_par; ?>
		</li>
 
<?php }  ?>
		</ul>
		<p class="center">
			<button type="submit" name="MetJourResp" id="MetJourResp" value="y" >
				Mettre à jour
			</button>
		</p>
		
  </fieldset>
</form>


<form action="index.php" method="post" id="selectionClasse">
	<fieldset class='fieldset_opacite50'>
		<a name='form_classes_a_exporter'></a>
		<legend class='fieldset_opacite50'>Classes à exporter</legend>
		<div class="lsun3colonnes" >
<?php 
$toutesClasses = getClasses();
$cpt = 0;
$cptClasse = 0;
$coupe = ceil($toutesClasses->num_rows/4);
while ($afficheClasse = $toutesClasses->fetch_object()) {
	if (!$cpt) {echo "			<div class='colonne'>\n";}
?>
				<p>
					<input type="checkbox" 
						   name="afficheClasse[<?php echo $afficheClasse->id; ?>]"
						   <?php if(isset($selectionClasse) && count($selectionClasse)>0 && in_array($afficheClasse->id, $selectionClasse)){echo 'checked';} ?>
						   id="afficheClasse_<?php echo $cptClasse; ?>"
						   onchange="checkbox_change(this.id)"
						   />
					<label for="afficheClasse_<?php echo $cptClasse; ?>" id="texte_afficheClasse_<?php echo $cptClasse; ?>">
						<?php echo $afficheClasse->classe; ?>
					</label>
				</p>
<?php 
	$cpt=$cpt+1;
	$cptClasse ++;
	if ($cpt > $coupe) {echo "			</div>\n"; $cpt = 0;}
}
if ($cpt) {echo "			</div>\n";}
?>
		</div>
		
		<p class="center"><a href='#' onClick='CocherClasses(true);return false;'>Tout cocher</a> / <a href='#' onClick='CocherClasses(false);return false;'>Tout décocher</a></p>

		<p class="center">
			<button type="submit" name="soumetSelection" value="y" >
				Sélectionner
			</button>
		</p>


		<?php

		?>
		<p style='margin-top:1em; margin-left:8.6em; text-indent:-8.6em;'>
			<!--
			<span style='color:red;font-weight:bold;'>Expérimental&nbsp;:</span> 
			-->
			<span style='font-weight:bold;'>Période(s)&nbsp;:</span> 
		<input type='radio' name='LSUN_periodes_a_extraire' id='LSUN_periodes_a_extraire_toutes' value='toutes'<?php echo $checked_LSUN_periodes_a_extraire_toutes?> onchange="checkbox_change('LSUN_periodes_a_extraire_toutes');checkbox_change('LSUN_periodes_a_extraire_restreindre')" /><label for='LSUN_periodes_a_extraire_toutes' id='texte_LSUN_periodes_a_extraire_toutes'> Extraire toutes les périodes</label><br />
		<input type='radio' name='LSUN_periodes_a_extraire' id='LSUN_periodes_a_extraire_restreindre' value='restreindre'<?php echo $checked_LSUN_periodes_a_extraire_restreindre?> onchange="checkbox_change('LSUN_periodes_a_extraire_toutes');checkbox_change('LSUN_periodes_a_extraire_restreindre')" /><label for='LSUN_periodes_a_extraire_restreindre' id='texte_LSUN_periodes_a_extraire_restreindre'> Restreindre l'extraction aux périodes&nbsp;: </label>
		<?php
			for($loop=1;$loop<=$maxper;$loop++) {
				if($loop>1) {
					echo " - ";
				}
				echo "<input type='checkbox' name='LSUN_periodes[]' id='LSUN_periodes_".$loop."' value='".$loop."'".$checked_LSUN_periodes[$loop]." onchange=\"checkbox_change(this.id);if(this.checked==true) {document.getElementById('LSUN_periodes_a_extraire_restreindre').checked=true;checkbox_change('LSUN_periodes_a_extraire_toutes');checkbox_change('LSUN_periodes_a_extraire_restreindre');}\" /><label for='LSUN_periodes_".$loop."' id='texte_LSUN_periodes_".$loop."'>P.".$loop."</label>";
			}
		?>
		</p>

		<p style='margin-top:1em; margin-left:4em; text-indent:-4em;'><em>NOTE&nbsp;:</em> Le choix des EPI/AP/Parcours n'est proposé qu'une fois le choix des classes effectué.</p>


<script type='text/javascript'> 
	<?php echo js_checkbox_change_style(); ?>

	function CocherClasses(mode) {
		for (var k=0;k<<?php echo $cptClasse; ?>;k++) {
			//alert('afficheClasse_'+k);
			if(document.getElementById('afficheClasse_'+k)){
				document.getElementById('afficheClasse_'+k).checked = mode;
				checkbox_change('afficheClasse_'+k);
			}
		}
	}

	// Pour re-mettre en gras les classes sélectionnées lors du re-chargement de la page
	for (var k=0;k<<?php echo $cptClasse; ?>;k++) {
		if(document.getElementById('afficheClasse_'+k)){
			checkbox_change('afficheClasse_'+k);
		}
	}

	checkbox_change('LSUN_periodes_a_extraire_toutes');
	checkbox_change('LSUN_periodes_a_extraire_restreindre')

	for (var k=1;k<=<?php echo $maxper; ?>;k++) {
		if(document.getElementById('LSUN_periodes_'+k)){
			checkbox_change('LSUN_periodes_'+k);
		}
	}
</script>

  </fieldset>
</form>


<div id="defAid">
<!-- ======================================================================= -->
<!-- Formulaire Parcours -->

<form action="index.php" method="post" id="parcours">
	<a name='form_parcours_communs'></a>
	<fieldset class='fieldset_opacite50'>
		<legend class='fieldset_opacite50' title="Contient l’ensemble des informations relatives aux parcours éducatifs communs à une classe (contrainte d’unicité sur les combinaison de champs 'periodes', 'division' et 'Type de parcours').">
				Parcours communs
		</legend>
		<table>
			<caption style="caption-side:bottom">Parcours éducatifs communs à une classe pour une période</caption>
			<thead>
				<tr>
					<th>Période</th>
					<th>Division</th>
					<th>Type de parcours éducatifs</th>
					<th>Description</th>
					<th>liaison AID</th>
					<th title="Seule la ligne choisie est traitée. Les autres modifications ne sont pas prises en compte.">Action<br />individuelle</th>
					<th>Suppression<br />
					<a href='#' onclick="suppr_parcours(true);return false;" title='Cocher tous les Parcours comme devant être supprimés.'><img src='../images/delete16.png' class='icone16' /></a> / 
					<a href='#' onclick="suppr_parcours(false);return false;" title='Décocher tous les Parcours.'><img src='../images/case_blanche.png' class='icone16' /></a>
					</th>
				</tr>
			</thead>
<?php 
	$cpt_ligne_parcours=0;
	while ($parcoursCommun = $parcoursCommuns->fetch_object()) { ?>
			<tr>
				<td>
					<input type="hidden" name="modifieParcoursId[<?php echo $parcoursCommun->id; ?>]" value="<?php echo $parcoursCommun->id; ?>" />
					<input type="hidden" name="modifieParcoursPeriode[<?php echo $parcoursCommun->id; ?>]" value="<?php echo $parcoursCommun->periode; ?>" />
					<?php
						// Vérifier si l'AID choisi est bien associé à la période, sinon, afficher une alerte
						$sql="SELECT * FROM lsun_j_aid_parcours ljap, 
									lsun_parcours_communs lpc 
								WHERE lpc.id='".$parcoursCommun->id."' AND 
									lpc.id=ljap.id_parcours";
						//echo "$sql<br />";
						$test=mysqli_query($mysqli, $sql);
						if(mysqli_num_rows($test)>0) {
							$lig_test=mysqli_fetch_object($test);

							$id_aid=$lig_test->id_aid;
							$sql="SELECT * FROM aid a WHERE id='".$id_aid."';";
							//echo "$sql<br />";
							$test=mysqli_query($mysqli, $sql);
							if(mysqli_num_rows($test)==0) {
								echo "<div style='float:right; width:16px' title=\"L'AID initialement associé n'existe pas.\nIl a pu être supprimé lors de l'initialisation de l'année.\nIl faut soit (re)créer un AID pour ce Parcours\nsoit sélectionner un AID dans la colonne Liaison AID du tableau.\"><a href='../aid/index.php' target='_blank'><img src='../images/icons/flag2.gif' class='icone16' alt='Attention' /></a></div>";
							}
							else {
								$sql="SELECT * FROM aid a, 
											aid_config ac, 
											lsun_j_aid_parcours ljap, 
											lsun_parcours_communs lpc 
										WHERE lpc.id='".$parcoursCommun->id."' AND 
											lpc.id=ljap.id_parcours AND 
												ljap.id_aid=a.id AND 
												a.indice_aid=ac.indice_aid AND 
												ac.display_begin<=lpc.periode AND 
												ac.display_end>=lpc.periode";
								//echo "$sql<br />";
								$test=mysqli_query($mysqli, $sql);
								if(mysqli_num_rows($test)==0) {
									echo "<div style='float:right; width:16px' title=\"Le Parcours a été déclaré pour la classe sur une période\nnon couverte par les périodes de la catégorie AID.\n(aucune extraction ne sera réalisée, sauf à modifier le paramétrage de la catégorie)\"><a href='../aid/config_aid.php?aid_id=".$lig_test->id_aid."' target='_blank'><img src='../images/icons/flag2.gif' class='icone16' alt='Attention' /></a></div>";
								}
							}
						}

						echo $parcoursCommun->periode; 
					?>
				</td>
				<td>
					<input type="hidden" name="modifieParcoursClasse[<?php echo $parcoursCommun->id; ?>]" value="<?php echo $parcoursCommun->classe; ?>" />
					<?php 
						//echo getClasses($parcoursCommun->classe)->fetch_object()->nom_complet;
						// Quand aucune classe n'est encore sélectionnée, on a
						// PHP Notice:  Trying to get property 'nom_complet' of non-object
						
						$current_objet_classe=getClasses($parcoursCommun->classe)->fetch_object();
						if(is_object($current_objet_classe)) {
							echo $current_objet_classe->nom_complet;
						}
						else {
							echo "<span style='color:red'>Pas de classe sélectionnée</span>";
						}
					?>
				</td>
				<td>
					<select name="modifieParcoursCode[<?php echo $parcoursCommun->id; ?>]">
<?php foreach ($xml->{'liste-parcours'}->parcours as $parcours) { ?>
						<option value="<?php echo $parcours['code'] ?>" <?php if($parcours['code'] == $parcoursCommun->codeParcours){echo " selected ";} ?> >
							<?php echo $parcours['libelle'] ?>
						</option>
<?php } ?>
					</select>
				</td>
				<td>
					<input type="text" name="modifieParcoursTexte[<?php echo $parcoursCommun->id; ?>]" size="60" value="<?php echo htmlspecialchars($parcoursCommun->description); ?>"/>
				</td>
				<td>
<?php
/*
$AidParcours->data_seek(0);
$AidParc = $AidParcours->fetch_object();
echo "<pre>";
print_r($AidParc);
echo "</pre>";
*/
?>
					<select name="modifieParcoursLien[<?php echo $parcoursCommun->id; ?>]">
						<option value=""></option>
<?php
	$AidParcours->data_seek(0);
	while ($AidParc = $AidParcours->fetch_object()) { 
		$sql="SELECT DISTINCT id_classe FROM aid a, 
								j_aid_eleves jae, 
								j_eleves_classes jec 
							WHERE id='".$AidParc->idAid."' AND 
								jec.login=jae.login AND 
								jae.id_aid=a.id AND 
								jec.id_classe='".$parcoursCommun->classe."';";
		$test_id_classe_aid=mysqli_query($mysqli, $sql);
		if(mysqli_num_rows($test_id_classe_aid)>0) {
?>
						<option value="<?php echo $AidParc->idAid; ?>" <?php if (getLiaisonsAidParcours($AidParc->idAid, $parcoursCommun->id)->num_rows && getLiaisonsAidParcours($AidParc->idAid, $parcoursCommun->id)->fetch_object()->id_aid) {echo " selected";} ?> title="<?php echo $AidParc->nom_complet;?>">
							<?php
								echo $AidParc->aid;
								// DEBUG
								//echo " (".$AidParc->idAid.")";
								//echo " (".$AidParc->nom_complet.")";
							?>
						</option>
<?php
		}
	}
?>
					</select>
				
				</td>
				
				<td>
					<input type="submit" class="btnValide" 
						   alt="Submit button" 
						   name="modifieParcours" 
						   value="<?php echo $parcoursCommun->id; ?>"
						   title="Modifier ce parcours" />
					/
					<input type="submit" class="btnSupprime" 
						   alt="Boutton supprimer" 
						   name="supprimeParcours[<?php echo $parcoursCommun->id; ?>]" 
						   value="y"
						   title="Supprimer ce parcours" />
				</td>
				<td>
					<input type='checkbox' name='suppr_parcours[]' id='suppr_parcours_<?php echo $cpt_ligne_parcours; ?>' value='<?php echo $parcoursCommun->id; ?>' />
				</td>
			</tr>
<?php
		$cpt_ligne_parcours++;
	 } ?>
			<!-- Ajout d'un nouveau parcours-->
			<tr>
				<td>
					<select name="newParcoursPeriode">
						<option value=""></option>
<?php while ($periode = $periodes->fetch_object()) { ?>
						<option value="<?php echo $periode->num_periode; ?>"><?php echo $periode->num_periode; ?></option>
<?php } ?>
					</select>
				</td>
				<td>
					<select name="newParcoursClasse">
						<option value=""></option>
<?php while ($classe = $classes->fetch_object()) { ?>
						<option value="<?php echo $classe->id; ?>"><?php echo $classe->nom_complet; ?></option>
<?php } ?>
					</select>
				</td>
				<td>
					<select name="newParcoursCode">
						<option value=""></option>
<?php foreach ($xml->{'liste-parcours'}->parcours as $parcours) { ?>
						<option value="<?php echo $parcours['code'] ?>"><?php echo $parcours['libelle'] ?></option>
<?php } ?>
					</select>
				</td>
				<td>
					<input type="text" name="newParcoursTexte" size="70" />
				</td>
				<td>
					<select name="newParcoursLien">
						<option value=""></option>
<?php $AidParcours->data_seek(0);
while ($APCommun = $AidParcours->fetch_object()) { ?>
						<option value="<?php 
								//echo $APCommun->indice_aid; 
								echo $APCommun->idAid; 
							?>" title="<?php echo $APCommun->nom_complet;?>">
							<?php
								echo $APCommun->aid;
								// DEBUG
								//echo " (".$APCommun->indice_aid.")";
								//echo " (".$APCommun->idAid.")";
								//echo " (".$APCommun->nom_complet.")";
							?>
						</option>
<?php } ?>
					</select>
				</td>
				<td>
					<input type="submit" class="btnValide" 
						   alt="Submit button" 
						   name="ajouteParcours" 
						   value="y"
						   title="Ajouter ce parcours" />
				</td>
				<td>
				</td>
			</tr>
		</table> 
		<p><input type="submit" 
			   alt="Submit button" 
			   name="modifiePlusieursParcours" 
			   value="Valider les modifications ci-dessus"
			   title="Valider les modifications ci-dessus" />
		</p>
		<p style='margin-top:1em; margin-left:4em; text-indent:-4em;'><em>NOTE&nbsp;:</em> Les parcours affichés sont ceux des classes sélectionnées.<br />
		Si vous voulez voir tous les parcours saisis, sélectionnez toutes les classes dans le premier formulaire et validez la sélection.</p>

		<script type='text/javascript'>
			function suppr_parcours(mode) {
				for(i=0;i<<?php echo $cpt_ligne_parcours;?>;i++) {
					if(document.getElementById('suppr_parcours_'+i)) {
						document.getElementById('suppr_parcours_'+i).checked=mode;
					}
				}
			}
		</script>

		<?php
			if(isset($selectionClasse)) {
				$tab_aid_parcours=array();
				$sql="SELECT a.* FROM aid a, 
						aid_config ac
					WHERE ac.type_aid='3' AND 
						a.indice_aid=ac.indice_aid
					ORDER BY ac.nom, a.numero, a.nom;";
				//echo "$sql<br />";
				$res_aid=mysqli_query($mysqli, $sql);
				$cpt_aid_parcours=0;
				while($lig_aid=mysqli_fetch_assoc($res_aid)) {
					$tab_aid_parcours[$cpt_aid_parcours]=$lig_aid;
					$tab_aid_parcours[$cpt_aid_parcours]['id_classe']=array();
					$sql="SELECT DISTINCT id_classe FROM j_eleves_classes jec, 
											j_aid_eleves jae 
										WHERE jec.login=jae.login AND 
											jae.id_aid='".$lig_aid['id']."';";
					//echo "$sql<br />";
					$res_aid_classe=mysqli_query($mysqli, $sql);
					if(mysqli_num_rows($res_aid_classe)>0) {
						while($lig_aid_classe=mysqli_fetch_object($res_aid_classe)) {
							$tab_aid_parcours[$cpt_aid_parcours]['id_classe'][]=$lig_aid_classe->id_classe;
						}
					}
					$cpt_aid_parcours++;
				}
				echo "
		<!--
		<div id='div_ajout_plusieurs_parcours' onmouseover=\"document.getElementById('tableau_ajout_plusieurs_parcours').style.display='';\" onmouseout=\"document.getElementById('tableau_ajout_plusieurs_parcours').style.display='none';\">
		-->
		<div id='div_ajout_plusieurs_parcours' class='fieldset_opacite50' style='margin-top:1em; padding:1em;'>
		<p style='margin-top:1em; font-weight:bold;' onclick=\"if(document.getElementById('tableau_ajout_plusieurs_parcours').style.display=='none') {document.getElementById('tableau_ajout_plusieurs_parcours').style.display='';} else {document.getElementById('tableau_ajout_plusieurs_parcours').style.display='none';}\" title=\"Cliquez pour afficher/masquer le tableau d'ajout par lots\"><img src='../images/icons/add.png' class='icone16' alt='Ajouter' />Ajouter plusieurs Parcours d'un coup</p>
		<br />
		<div id='tableau_ajout_plusieurs_parcours' style='display:none;'>
		<table class='boireaus boireaus_alt'>
			<thead>
				<tr>
					<th rowspan='2'>Classe</th>
					<th rowspan='2'>Type</th>
					<th colspan='$maxper'><a href='#' onclick=\"";
				for($loop_per=1;$loop_per<=$maxper;$loop_per++) {
					echo "change_coche_parcours_periode($loop_per);";
				}
				echo "return false;\">Période</a></th>
					<th rowspan='2'>Description <a href='#' onclick=\"parcours_remplir_description();return false;\" title=\"Si la description est vide, prendre le contenu de la colonne Type comme description.\"><img src='../images/icons/wizard.png' class='icone16' /></a></th>
					<th rowspan='2'>Liaison</th>
				</tr>
				<tr>";
				for($loop_per=1;$loop_per<=$maxper;$loop_per++) {
					echo "
					<th title='Période $loop_per'><a href='#' onclick=\"change_coche_parcours_periode($loop_per);return false;\">P.$loop_per</a></th>";
				}
				echo "
				</tr>
			</thead>
			<tbody>";
				//$tab_type_parcours=array("Parcours avenir","Parcours citoyen","Parcours d'éducatin artistique et culturelle","Parcours éducatif et santé");
				for($loop=0;$loop<count($selectionClasse);$loop++) {
					echo "
				<tr>
					<td rowspan='4'>".get_nom_classe($selectionClasse[$loop])."</td>";
					$loop2=0;
					//for($loop2=0;$loop2<count($tab_type_parcours);$loop2++) {
					foreach($tab_type_parcours as $code_parcours => $texte_type_parcours) {
						if($loop2>0) {
							echo "
				<tr>";
						}
						echo "
					<td><a href='#' id='nouveau_parcours_classe_".$selectionClasse[$loop]."_type_".$code_parcours."_type' onclick=\"if(document.getElementById('nouveau_parcours_classe_".$selectionClasse[$loop]."_type_".$code_parcours."_description').value=='') {
						document.getElementById('nouveau_parcours_classe_".$selectionClasse[$loop]."_type_".$code_parcours."_description').value=this.innerHTML;
					};return false;\" title='Si la description est vide, prendre le contenu de cette colonne comme description.'>".$texte_type_parcours."<a/></td>";

						// Boucle sur les périodes
						for($loop_per=1;$loop_per<=$maxper;$loop_per++) {
							echo "
					<td title='Période $loop_per'><input type='checkbox' name='nouveau_parcours_classe_".$selectionClasse[$loop]."_type_".$code_parcours."_periode[]' id='nouveau_parcours_classe_".$selectionClasse[$loop]."_type_".$code_parcours."_periode_".$loop_per."' value='$loop_per' /></td>";
						}

						// Description
						echo "
					<td><input type='text' name='nouveau_parcours_classe_".$selectionClasse[$loop]."_type_".$code_parcours."_description' id='nouveau_parcours_classe_".$selectionClasse[$loop]."_type_".$code_parcours."_description' value='' /></td>";

						// Liaison AID
						/*
						// Récupérer les AID non déjà associés
						$sql="SELECT * FROM aid a, 
								aid_config ac, 
								lsun_j_aid_parcours ljap, 
								lsun_parcours_communs lpc
							WHERE ac.type_aid='3' AND 
								a.indice_aid=ac.indice_aid AND 
								a.id NOT IN ();";
						*/

						echo "
					<td title=\"Seuls les AID (de type Parcours) avec des élèves de la classe inscrits sont proposés ici.\">
						<select name='nouveau_parcours_classe_".$selectionClasse[$loop]."_type_".$code_parcours."_liaison'>
							<option value=''></option>";
						for($loop_aid=0;$loop_aid<count($tab_aid_parcours);$loop_aid++) {
							if(in_array($selectionClasse[$loop], $tab_aid_parcours[$loop_aid]['id_classe'])) {
								echo "
							<option value='".$tab_aid_parcours[$loop_aid]['id']."'>".$tab_aid_parcours[$loop_aid]['nom']."</option>";
							}
						}
						echo "
						</select>
					</td>
				</tr>";
						$loop2++;
					}
				}
				echo "
			</tbody>
		</table>
		<p><input type=\"submit\" 
			   alt=\"Submit button\" 
			   name=\"ajoutePlusieursParcours\" 
			   value=\"Déclarer les Parcours\"
			   title=\"Ajouter ces parcours\" />
		</p>
		</div>
		</div>";
			}
		?>

	</fieldset>
</form>

<script type='text/javascript'>

	function change_coche_parcours_periode(periode) {
		var etat_releve=0;
		var etat;
		champs_input=document.getElementsByTagName('input');
		for(i=0;i<champs_input.length;i++) {
			type=champs_input[i].getAttribute('type');
			if(type=='checkbox'){
				id=champs_input[i].getAttribute('id');
				if((id.substring(0,24)=='nouveau_parcours_classe_')&&(id.indexOf('periode_'+periode)!=-1)) {
					
					if(etat_releve==0) {
						etat=champs_input[i].checked;
						etat_releve=1;
					}
					if(etat) {
						champs_input[i].checked=false;
					}
					else {
						champs_input[i].checked=true;
					}
				}
			}
		}
	}

	function parcours_remplir_description() {
		champs_input=document.getElementsByTagName('input');
		for(i=0;i<champs_input.length;i++) {
			type=champs_input[i].getAttribute('type');
			if(type=='text'){
				id=champs_input[i].getAttribute('id');
				if((id)&&(id.substring(0,24)=='nouveau_parcours_classe_')&&(id.indexOf('_description')!=-1)) {
					if(champs_input[i].value=='') {
						champs_input[i].value=document.getElementById(id.replace('_description', '_type')).innerHTML;
					}
				}
			}
		}
	}
</script>

<style type='text/css'>
	.table_no_border {
		border:0px;
	}
	.table_no_border td {
		border:0px;
		vertical-align:top;
		text-align:left;
	}
	.table_no_border th {
		border:0px;
		vertical-align:top;
	}
</style>

<!-- ======================================================================= -->
<!-- Formulaire EPI -->

<form action="index.php" method="post" id="definitionEPI">
	<a name='form_EPIs'></a>
	<fieldset class='fieldset_opacite50'>
		<legend class='fieldset_opacite50'>EPIs</legend>
		<div id="div_epi">
			<p>Enseignements Pratiques Interdisciplinaires</p>
<?php
	/*
	// A quoi sert cette section?
	$tableauClasses = array();
	if (count($_SESSION['afficheClasse'])) {
		foreach ($_SESSION['afficheClasse'] as $classeSelectionne) {
			$tableauClasses[]=$classeSelectionne;
		}
	}
	*/

	$cpt_EPI_tmp=0;
	while ($epiCommun = $listeEPICommun->fetch_object()) {
		$cpt_EPI_tmp++;
		$tableauMatieresEPI = array();
		$listeMatieresEPI=getMatieresEPICommun($epiCommun->id);
		while ($matiereEPI = $listeMatieresEPI->fetch_object()) {
			$tableauMatieresEPI[] = array('matiere'=>$matiereEPI->id_matiere, 'modalite'=>$matiereEPI->modalite);
		}
		/*
		echo "EPI ".$epiCommun->id."<pre>";
		print_r($tableauMatieresEPI);
		echo "</pre>";
		*/
?>
			<div class="lsun_cadre fieldset_opacite50">
				<div style='float:left; width:50em; font-weight:bold; font-size:x-large; text-align:left; margin-left:1em;'><?php echo $epiCommun->intituleEpi;?></div>
				<div align='center'>
					<a name='ancre_EPI_<?php echo $epiCommun->id; ?>'></a>
					<input type="hidden" 
						   name="modifieEpiId[<?php echo $epiCommun->id; ?>]" 
						   value="<?php echo $epiCommun->id; ?>" />

					<table class='table_no_border'>
						<tr>
							<th>Thématique</th>
							<th>Intitulé</th>
							<th>Description</th>
						</tr>
						<tr>
							<td>
								<select name="modifieEpiCode[<?php echo $epiCommun->id; ?>]">
<?php foreach ($xml->{'thematiques-epis'}->{'thematique-epi'} as $thematiqueEpi) { ?>
									<option value="<?php echo $thematiqueEpi['code'] ?>" 
										<?php if($thematiqueEpi['code'] == $epiCommun->codeEPI){echo " selected ";} ?>
											title="<?php echo $thematiqueEpi['libelle']; ?>" >
										<?php //echo substr($epi['libelle'],0,40); ?>
										<?php echo substr($thematiqueEpi['libelle'],0,40); ?>
									</option>
<?php } ?>
								</select>
							</td>
							<td>
								<input type="text" size="40" name="modifieEpiIntitule[<?php echo $epiCommun->id; ?>]" value="<?php echo $epiCommun->intituleEpi; ?>" /><?php
								if($epiCommun->intituleEpi=="") {
									echo "
								<img src='../images/icons/ico_attention.png' class='icone32' alt='Attention' title=\"L'intitulé ne doit pas être vide.\" style='vertical-align:top;' />";
								}
								?>
							</td>
							<td>
								<textarea rows="6" cols="50" name="modifieEpiDescription[<?php echo $epiCommun->id; ?>]" /><?php echo $epiCommun->descriptionEpi; ?></textarea><?php
								if($epiCommun->descriptionEpi=="") {
									echo "
								<img src='../images/icons/ico_attention.png' class='icone32' alt='Attention' title=\"La description ne doit pas être vide.\" style='vertical-align:top;' />";
								}

								?>
							</td>
						</tr>
					</table>

					<table class='table_no_border'>
						<tr>
							<th colspan='2'>Divisions</th>
							<th>Période de fin</th>
						</tr>
						<tr>
							<td>
<?php
		$classes->data_seek(0);
		$cpt_classe=0;
		$cpt_classe_associees=0;
		$tab_classes_non_associees=array();
		while ($classe = $classes->fetch_object()) { 
			if(estClasseEPI($epiCommun->id,$classe->id)) {
				echo "
									<input type='checkbox' name='modifieEpiClasse".$epiCommun->id."[]' id='modifieEpiClasse".$epiCommun->id."_".$cpt_classe."' value='".$classe->id."' checked onchange=\"checkbox_change(this.id)\" /><label for='modifieEpiClasse".$epiCommun->id."_".$cpt_classe."' id='texte_modifieEpiClasse".$epiCommun->id."_".$cpt_classe."'>".$classe->classe." <em>(".$classe->classe.")</em></label><br />";
				$cpt_classe_associees++;
			}
			else {
				$tab_classes_non_associees[]=$classe->id;
			}
			$cpt_classe++;
		}
?>
							</td>

<?php

		if(count($tab_classes_non_associees)==0) {
			echo "
			<td style='color:red'>Aucune classe ne reste à associer</td>";
		}
		else {
?>
							<td onmouseover="document.getElementById('span_ajout_modifieEpiClasse_<?php echo $epiCommun->id;?>').style.display=''" 
								onmouseout="affiche_masque_select_EPI_AP_Parcours('modifieEpiClasse', <?php echo $epiCommun->id;?>)">
<?php
		if($cpt_classe_associees==0) {
			echo "
								<span style='color:red'><img src='../images/icons/ico_attention.png' class='icone32' alt='Attention' />Aucune classe n'est associée.</span><br />";
		}
?>
								<img src='../images/icons/add.png' class='icone16' alt='Ajouter' />
								Ajouter des divisions<br />
<!--
								<span id='span_ajout_modifieEpiClasse_<?php echo $cpt_EPI_tmp;?>'>
-->
<?php
$tab_span_champs_select[]='span_ajout_modifieEpiClasse_'.$epiCommun->id;
?>
								<span id='span_ajout_modifieEpiClasse_<?php echo $epiCommun->id;?>'>
								<select name="modifieEpiClasse<?php echo $epiCommun->id; ?>[]" 
										id="modifieEpiClasse<?php echo $epiCommun->id; ?>"
										multiple 
										title="Pour sélectionner plusieurs classes, effectuer CTRL+Clic sur chaque classe.">
<?php
			$classes->data_seek(0);
			while ($classe = $classes->fetch_object()) { 
				if (!estClasseEPI($epiCommun->id,$classe->id)) {
?>
									<option value="<?php echo $classe->id; ?>">
										<?php echo $classe->classe; ?> <?php echo $classe->nom_complet; ?>
									</option>
<?php
				}
			}
?>
								</select>
								</span>
							</td>
<?php
		}
?>
							<td>
								<input type="hidden" 
									   name="modifieEpiPeriode1[<?php echo $epiCommun->id; ?>]" 
									   value="<?php echo $epiCommun->periode; ?>" />
								<?php //echo $epiCommun->periode; ?>

								<select name="modifieEpiPeriode[<?php echo $epiCommun->id; ?>]">
									<option value=""></option>
<?php
		$periodes->data_seek(0);
		while ($periode = $periodes->fetch_object()) {
?>
									<option value="<?php echo $periode->num_periode; ?>"
										<?php if ($periode->num_periode == $epiCommun->periode) {echo " selected ";} ?> >
										<?php echo $periode->num_periode; ?>
									</option>
<?php
		}
?>
								</select>
<?php
		if(($epiCommun->periode=="")||($epiCommun->periode=="0")) {
			echo "
								<span style='color:red'><img src='../images/icons/ico_attention.png' class='icone32' alt='Attention' />Période de fin non définie.</span><br />";
		}
?>
							</td>
						</tr>
					</table>

					<table class='table_no_border'>
						<tr>
<?php
		if(count($tableauMatieresEPI)==0) {
			// Aucune matière n'est encore associée !
			echo "
							<th style='vertical-align:top; border:0px;'>
								Disciplines&nbsp;:&nbsp;
							</th>

							<td style='vertical-align:top; border:0px;' title=\"Associer des disciplines\">
								<span style='color:red'><img src='../images/icons/ico_attention.png' class='icone32' alt='Attention' />Aucune discipline n'est encore associée.</span><br />
								Associer des disciplines&nbsp;:<br />
								<select multiple 
										size='6' 
										name=\"modifieEpiMatiere".$epiCommun->id."[]\" 
										id=\"modifieEpiMatiere".$epiCommun->id."\" 
										title=\"Pour sélectionner plusieurs matières, effectuer CTRL+Clic sur chaque matière.\">";
			$listeMatieres->data_seek(0);
			while ($matiere = $listeMatieres->fetch_object()) { 
				if($matiere->code_modalite_elect=="") {
					echo "
									<option value='' disabled title=\"Modalité non définie.\" style='color:orange'>";
				}
				else {
					$style_matiere="";
					if($matiere->code_matiere=="") {
						$style_matiere=" style='color:red' title=\"La nomenclature de la matière ".$matiere->matiere." n'est pas renseignée.\nL'export ne sera pas valide sans que les nomenclatures soient corrigées.\"";
					}
					echo "
								<option value=\"".$matiere->matiere.$matiere->code_modalite_elect."\"".$style_matiere.">";
				}
				echo $matiere->matiere." (".$matiere->nom_complet;

				if ($matiere->code_modalite_elect == 'O') {
					echo '- option obligatoire';
				} elseif ($matiere->code_modalite_elect == 'F') {
					echo '- option facultative';
				} elseif ($matiere->code_modalite_elect == 'X') {
					echo '- mesure spécifique';
				}
				elseif ($matiere->code_modalite_elect =="N") {echo "- obligatoire ou facultatif";}
				elseif ($matiere->code_modalite_elect =="L") {echo "- ajout académique";}
				elseif ($matiere->code_modalite_elect =="R") {echo "- enseignement religieux";}
				echo ")</option>";
			}
			echo "
								</select>
							</td>
						</tr>";
		}
		else {
			// Il y a déjà des matières associées
			echo "
							<th style='vertical-align:top; border:0px;' rowspan='".count($tableauMatieresEPI)."'>
								Disciplines&nbsp;:&nbsp;
							</th>";
			$cpt_row=0;
			foreach ($tableauMatieresEPI as $matEPI) {
				if($cpt_row>0) {
					echo "
						<tr>";
				}

				// Matières déjà associées:
				echo "
								<td style='border:0px;'>
									<input type='checkbox' name='modifieEpiMatiere".$epiCommun->id."[]' id='modifieEpiMatiere".$epiCommun->id."_".$cpt_row."' value=\"".$matEPI['matiere'].$matEPI['modalite']."\" onchange=\"checkbox_change(this.id)\" checked />
								</td>
								<td style='text-align:left; border:0px;'>
									<label for='modifieEpiMatiere".$epiCommun->id."_".$cpt_row."' id='texte_modifieEpiMatiere".$epiCommun->id."_".$cpt_row."'>";
				echo $matEPI['matiere']." (";
				echo getMatiereOnMatiere($matEPI['matiere'])->nom_complet;
				echo "<span style='color:grey'>";
				if ($matEPI['modalite'] =="O") { echo " option obligatoire"; } 
				elseif ($matEPI['modalite'] =="F") {echo " option facultative";} 
				elseif ($matEPI['modalite'] =="X") {echo " mesure spécifique";}
				elseif ($matEPI['modalite'] =="N") {echo " obligatoire ou facultatif";}
				elseif ($matEPI['modalite'] =="L") {echo " ajout académique";}
				elseif ($matEPI['modalite'] =="R") {echo " enseignement religieux";}
				echo "</span>)</label>";
				if(getMatiereOnMatiere($matEPI['matiere'])->code_matiere=="") {
					echo "<img src='../images/icons/ico_attention.png' class='icone32' alt='Attention' title=\"La nomenclature de la matière ".$matEPI['matiere']." n'est pas renseignée.\nL'export ne sera pas valide sans que les nomenclatures soient corrigées.\" />";
				}
				echo "<br />";
				echo "
								</td>";

				// Ajouter des matières
				if($cpt_row==0) {
					$message_nb_matieres_EPI="";
					if(count($tableauMatieresEPI)<=1) {
						$message_nb_matieres_EPI="<span style='color:red'>Un EPI nécessite au moins 2 matières associées.</span><br />";
					}

					$tab_matieres_non_associees=array();
					$listeMatieres->data_seek(0);
					while ($matiere = $listeMatieres->fetch_assoc()) { 
						if(!in_array(array('matiere'=>$matiere['matiere'],'modalite'=>$matiere['code_modalite_elect']), $tableauMatieresEPI)) {
							$tab_matieres_non_associees[]=$matiere;
						}
					}

					if(count($tab_matieres_non_associees)==0) {
						echo "
						<td style='color:red'>".$message_nb_matieres_EPI."Aucune matière ne reste à associer</td>";
					}
					else {

						$tab_span_champs_select[]='span_ajout_modifieEpiMatiere_'.$epiCommun->id;
						echo "
								<td style='vertical-align:top; border:0px;' 
									title=\"Associer d'autres disciplines\" 
									rowspan='".count($tableauMatieresEPI)."' 
									onmouseover=\"document.getElementById('span_ajout_modifieEpiMatiere_".$epiCommun->id."').style.display=''\" 
									onmouseout=\"affiche_masque_select_EPI_AP_Parcours('modifieEpiMatiere', ".$epiCommun->id.")\">
									".$message_nb_matieres_EPI."
									<img src='../images/icons/add.png' class='icone16' alt='Ajouter' />
									Associer d'autres disciplines<br />
									<span id='span_ajout_modifieEpiMatiere_".$epiCommun->id."'>
									<select multiple 
											size='6' 
											name=\"modifieEpiMatiere".$epiCommun->id."[]\" 
											id=\"modifieEpiMatiere".$epiCommun->id."\" 
											title=\"Pour sélectionner plusieurs matières, effectuer CTRL+Clic sur chaque matière.\">";
						$listeMatieres->data_seek(0);
						while ($matiere = $listeMatieres->fetch_object()) { 
							if(!in_array(array('matiere'=>$matiere->matiere,'modalite'=>$matiere->code_modalite_elect), $tableauMatieresEPI)) {
								if($matiere->code_modalite_elect=="") {
									echo "
										<option value='' disabled title=\"Modalité non définie.\" style='color:orange'>";
								}
								else {
									$style_matiere="";
									if($matiere->code_matiere=="") {
										$style_matiere=" style='color:red' title=\"La nomenclature de la matière ".$matiere->matiere." n'est pas renseignée.\nL'export ne sera pas valide sans que les nomenclatures soient corrigées.\"";
									}
									echo "
										<option value=\"".$matiere->matiere.$matiere->code_modalite_elect."\"".$style_matiere.">";
								}

								echo $matiere->matiere." (".$matiere->nom_complet;
								if ($matiere->code_modalite_elect == 'O') {
									echo '- option obligatoire';
								} elseif ($matiere->code_modalite_elect == 'F') {
									echo '- option facultative';
								} elseif ($matiere->code_modalite_elect == 'X') {
									echo '- mesure spécifique';
								}
								elseif ($matiere->code_modalite_elect =="N") {echo "- obligatoire ou facultatif";}
								elseif ($matiere->code_modalite_elect =="L") {echo "- ajout académique";}
								elseif ($matiere->code_modalite_elect =="R") {echo "- enseignement religieux";}
								echo ")</option>";
							}
						}
					}
					echo "
									</select>
									</span>
								</td>";
				}
				echo "
							</tr>";
				$cpt_row++;
			}
		}
?>
					</table>

<?php
		$listeLiaisons = getLiaisonEpiEnseignementByIdEpi($epiCommun->id); 
		//echo "\$listeLiaisons=getLiaisonEpiEnseignementByIdEpi($epiCommun->id)<br />mysqli_num_rows(\$listeLiaisons)=".mysqli_num_rows($listeLiaisons)."<br />";
?>
					<table class='table_no_border'>
<?php
		if(mysqli_num_rows($listeLiaisons)==0) {
			// Aucune liaison AID à ce stade
			echo "
						<tr>
							<th>Liaisons&nbsp;:</th>
							<td>
								<span style='color:red'><img src='../images/icons/ico_attention.png' class='icone32' alt='Attention' />Aucune liaison AID n'est encore effectuée.</span><br />
								Associer des AID aux EPI<br />
								<select multiple 
										size='6' 
										name=\"modifieEpiLiaison".$epiCommun->id."[]\" 
										id=\"modifieEpiLiaison".$epiCommun->id."\" 
										title=\"Pour sélectionner plusieurs AID, effectuer CTRL+Clic sur chaque AID à associer à cet EPI.\">
									<option value=\"\"></option>";

							$listeAids = getEpiAid(); 
							while ($aid = $listeAids->fetch_object()) {
								if(!estCoursEpi($epiCommun->id ,"aid-".$aid->id_enseignement)) {
									$style_erreurs_aid="";
									$sql="SELECT * FROM aid WHERE indice_aid='".$aid->id_enseignement."' AND nom='';";
									$test=mysqli_query($mysqli, $sql);
									if(mysqli_num_rows($test)>0) {
										$style_erreurs_aid=" style='color:red' title=\"Un ou des AID de la catégorie ont un nom vide. Il faudra corriger cela.\"";
									}
									echo "
									<option value=\"aid-".$aid->id_enseignement."\"".$style_erreurs_aid.">aid-".$aid->description;
									//echo " (".$aid->id_enseignement.")";
									echo "</option>";
								}
							}

							echo "
								</select>
							</td>
						</tr>";
		}
		else {
			// Il y a déjà des liaisons AID
			$chaine_rowspan="";
			if(mysqli_num_rows($listeLiaisons)>1) {
				$chaine_rowspan=" rowspan='".mysqli_num_rows($listeLiaisons)."'";
			}
			echo "
						<tr>
							<th".$chaine_rowspan.">Liaison&nbsp;:</th>";

			$cpt_row=0;
			while ($liaison = $listeLiaisons->fetch_object()) {
				if ($liaison->aid) {
					if($cpt_row>0) {
						echo "
						<tr>";
					}

					$chaine_erreurs_aid="";
					$sql="SELECT * FROM aid WHERE indice_aid='".$liaison->id_enseignements."' AND nom='';";
					$test=mysqli_query($mysqli, $sql);
					if(mysqli_num_rows($test)>0) {
						while($lig_test=mysqli_fetch_object($test)) {
							$chaine_erreurs_aid.="<br /><span style='color:red'>L'AID n°".$lig_test->id." a un nom vide. Il faut <a href='../aid/modif_fiches.php?aid_id=".$lig_test->id."&indice_aid=".$liaison->id_enseignements."&action=modif&retour=index2.php' target='_blank'>corriger</a>.</span>";
						}
					}

					echo "
							<td>
								<input type='checkbox' name='modifieEpiLiaison".$epiCommun->id."[]' id='modifieEpiLiaison".$epiCommun->id."_".$cpt_row."' value=\"aid-".$liaison->id_enseignements."\" onchange=\"checkbox_change(this.id)\" checked />
							</td>
							<td>
								<label for='modifieEpiLiaison".$epiCommun->id."_".$cpt_row."' id='texte_modifieEpiLiaison".$epiCommun->id."_".$cpt_row."'>AID - ".getAID($liaison->id_enseignements)->nom."</label>".$chaine_erreurs_aid."
							</td>";

					if($cpt_row==0) {
						// Ajouter une liaison

						$tab_aid_non_associes=array();
						$listeAids = getEpiAid(); 
						while ($aid = $listeAids->fetch_assoc()) {
							if(!estCoursEpi($epiCommun->id ,"aid-".$aid['id_enseignement'])) {
								$tab_aid_non_associes[]=$aid;
							}
						}

						if(count($tab_aid_non_associes)==0) {
							echo "
							<td style='color:red'>Aucun AID restant à associer</td>";
						}
						else {
							$tab_span_champs_select[]='span_ajout_modifieEpiLiaison_'.$epiCommun->id;
							echo "
							<td ".$chaine_rowspan." 
								onmouseover=\"document.getElementById('span_ajout_modifieEpiLiaison_".$epiCommun->id."').style.display=''\" 
								onmouseout=\"affiche_masque_select_EPI_AP_Parcours('modifieEpiLiaison', ".$epiCommun->id.")\">
								<img src='../images/icons/add.png' class='icone16' alt='Ajouter' />
								Associer d'autres AID à l'EPI<br />
								<span id='span_ajout_modifieEpiLiaison_".$epiCommun->id."'>
									<select multiple 
											size='6' 
											name=\"modifieEpiLiaison".$epiCommun->id."[]\" 
											id=\"modifieEpiLiaison".$epiCommun->id."\" 
											title=\"Pour sélectionner plusieurs AID, effectuer CTRL+Clic sur chaque AID à associer à cet EPI.\">
										<option value=\"\"></option>";

							$listeAids = getEpiAid(); 
							while ($aid = $listeAids->fetch_object()) {
								if(!estCoursEpi($epiCommun->id ,"aid-".$aid->id_enseignement)) {
									$style_erreurs_aid="";
									$sql="SELECT * FROM aid WHERE indice_aid='".$aid->id_enseignement."' AND nom='';";
									$test=mysqli_query($mysqli, $sql);
									if(mysqli_num_rows($test)>0) {
										$style_erreurs_aid=" style='color:red' title=\"Un ou des AID de la catégorie ont un nom vide. Il faudra corriger cela.\"";
									}

									echo "
										<option value=\"aid-".$aid->id_enseignement."\"".$style_erreurs_aid.">aid-".$aid->description."</option>";
								}
							}

							echo "
									</select>
								</span>
							</td>";
						}
					}
					echo "
						</tr>";
					$cpt_row++;
				}
			}
		}
?>
					</table>

					<!-- Validation des modifications de cet EPI -->

					<div>
							<button type="submit" name="modifieEpi" value="<?php echo $epiCommun->id; ?>" ><img src='../images/enabled.png' />Modifier cet EPI</button>
							<button type="submit" name="supprimeEpi" value="<?php echo $epiCommun->id; ?>" ><img src='../images/disabled.png' style="width: 16px;" /> Supprimer cet EPI</button>
							<button type="submit" name="creeAidEpi" value="<?php echo $epiCommun->id; ?>" disabled hidden><img src='../images/icons/copy-16.png' /> Créer un AID pour cet EPI</button>
					</div>
				</div>
			</div>
<?php
	}
?>

			<!-- Nouvel EPI -->

			<div class="lsun_cadre fieldset_opacite50">
				<div style='float:left; width:5em; font-weight:bold;'>Nouvel EPI</div>
				<div>
					<p>
						Période de fin :
						<select name="newEpiPeriode">
							<option value=""></option>
	<?php $periodes->data_seek(0);
	while ($periode = $periodes->fetch_object()) { ?>
							<option value="<?php echo $periode->num_periode; ?>"><?php echo $periode->num_periode; ?></option>
	<?php } ?>
						</select>
						
						Division :
						<select name="newEpiClasse[]" multiple size='6' title="Pour sélectionner plusieurs classes, effectuer CTRL+Clic sur chaque classe.">
							<option value=""></option>
<?php $classes->data_seek(0);
while ($classe = $classes->fetch_object()) { ?>
							<option value="<?php echo $classe->id; ?>">
								<?php echo $classe->classe; ?> <?php echo $classe->nom_complet; ?>
							</option>
<?php } ?>
						</select>
						
						Thématique :
						<select name="newEpiCode">
							<option value=""></option>
<?php foreach ($xml->{'thematiques-epis'}->{'thematique-epi'} as $epi) { ?>
							<option value="<?php echo $epi['code']; ?>" title="<?php echo $epi['libelle']; ?>" >
								<?php echo substr($epi['libelle'],0,40); ?>
							</option>
<?php } ?>
						</select>
						Intitule :
						<input type="text" name="newEpiIntitule" size="40" />
					</p>
					<p>
						Disciplines :
						<select multiple size='6' name="newEpiMatiere[]" size="8" title="Pour sélectionner plusieurs matières, effectuer CTRL+Clic sur chaque matière.">
<?php
	$listeMatieres->data_seek(0);
	while ($matiere = $listeMatieres->fetch_object()) {
		if($matiere->code_modalite_elect=="") {
			echo "
							<option value='' disabled title=\"Modalité non définie.\" style='color:orange'>";
		}
		else {
			$style_matiere="";
			if($matiere->code_matiere=="") {
				$style_matiere=" style='color:red' title=\"La nomenclature de la matière ".$matiere->matiere." n'est pas renseignée.\nL'export ne sera pas valide sans que les nomenclatures soient corrigées.\"";
			}
			echo "
							<option value=\"".$matiere->matiere.$matiere->code_modalite_elect."\"".$style_matiere.">";
		}

		//echo $matiere->nom_complet;
		echo $matiere->matiere." (".$matiere->nom_complet;
		if ($matiere->code_modalite_elect == 'O') {
			echo '- option obligatoire';
		} elseif ($matiere->code_modalite_elect == 'F') {
			echo '- option facultative';
		} elseif ($matiere->code_modalite_elect == 'X') {
			echo '- mesure spécifique';
		}
		elseif ($matiere->code_modalite_elect =="N") {echo "- obligatoire ou facultatif";}
		elseif ($matiere->code_modalite_elect =="L") {echo "- ajout académique";}
		elseif ($matiere->code_modalite_elect =="R") {echo "- enseignement religieux";}

		echo ")";
?>
							</option>
<?php } ?>
						</select>
						
						Description :
						<textarea rows="4" cols="50" name="newEpiDescription" /></textarea> 
					</p>
				<div>
					<p>
						<button type="submit" name="ajouteEPI" value="y" ><img src='../images/enabled.png' />Ajouter cet EPI</button>
					</p>
				</div>
			</div>
			
		</div> 
	
		</div> 
	</fieldset>
</form>

<!-- ======================================================================= -->
<!-- Formulaire AP -->

<form action="index.php" method="post" id="definitionAP">
	<a name='form_AP'></a>
	<fieldset class='fieldset_opacite50'>
		<legend class='fieldset_opacite50'>AP</legend>
		<div id="div_ap">
			<p>Accompagnements personnalisés</p>
			
<?php

	// Les AP déjà définis

	$listeAPCommun->data_seek(0);
	$cpt2 = 0;
	while ($ap = $listeAPCommun->fetch_object()) { ?>
			
			<div class="lsun_cadre fieldset_opacite50">
				<!-- AP <?php //echo $ap->id; ?> -->

				<div style='float:left; width:50em; font-weight:bold; font-size:x-large; text-align:left; margin-left:1em;'><?php echo $ap->intituleAP;?></div>
				<div align='center'>

					<table class='table_no_border'>
						<tr>
							<th>Intitulé</th>
							<th>Description</th>
							<th>Liaison</th>
						</tr>
						<tr>
							<td>
								<input type="text" name="intituleAp[<?php echo $ap->id; ?>]" value="<?php echo $ap->intituleAP; ?>" />
							</td>
							<td>
								<textarea rows="4" cols="50" id="ApDescription<?php echo $ap->id; ?>" name="ApDescription[<?php echo  $ap->id; ?>]" /><?php echo $ap->descriptionAP; ?></textarea>
							</td>
							<td>
<?php 
	$res_liaison=getAidConfig($ap->id_aid);
	if(mysqli_num_rows($res_liaison)==0) {
		echo "
								<span style='color:red'><img src='../images/icons/ico_attention.png' class='icone32' alt='Attention' />Aucune liaison AID n'est encore effectuée.</span><br />Sélectionnez un AID et Enregistrez la modification&nbsp;:<br />";
	}
?>
								<select name="liaisonApAid[<?php echo $ap->id; ?>]">
<?php
	$listeAidAp->data_seek(0);

	while ($liaison = $listeAidAp->fetch_object()) {
		$selected="";
		if($liaison->indice_aid == $ap->id_aid) {$selected='selected';}
		echo "
									<option value=\"".$liaison->indice_aid."\"".$selected." title=\"".$liaison->groupe." (".$liaison->description.")\">".$liaison->groupe."</option>";
	}
?>
								</select>
							</td>
						</tr>
					</table>

<?php
	$listeAidAp->data_seek(0);


	$listeMatiereAP = disciplineAP($ap->id);
	//echo "\$listeMatiereAP = disciplineAP(".$ap->id.")<br />";
	$tableauMatiere=array();
	while ($matiereAP = $listeMatiereAP->fetch_object()) {
		/*
		echo "<pre>";
		print_r($matiereAP);
		echo "</pre>";
		echo "getMatiereSurMEF(".$matiereAP->id_enseignements.")<br />";
		*/
		//$tableauMatiere[] = $matiereAP->id_enseignements.$matiereAP->modalite;
		$matiere_courante=getMatiereSurMEF($matiereAP->id_enseignements)->fetch_object();
		$tableauMatiere[] = $matiere_courante->matiere.$matiereAP->modalite;
	}
	/*
	echo "<pre>";
	print_r($tableauMatiere);
	echo "</pre>";
	*/

?>

					<table class='table_no_border'>
						<tr>
<?php
		if(count($tableauMatiere)==0) {
			// Aucune matière n'est encore associée !
			echo "
							<th style='vertical-align:top; border:0px;'>
								Disciplines de référence&nbsp;:&nbsp;
							</th>

							<td style='vertical-align:top; border:0px;' title=\"Associer des disciplines\">
								<span style='color:red'><img src='../images/icons/ico_attention.png' class='icone32' alt='Attention' />Aucune discipline n'est encore associée.</span><br />
								Associer des disciplines&nbsp;:<br />
								<select multiple 
										size='6' 
										name=\"ApDisciplines".$ap->id."[]\" 
										id=\"ApDisciplines".$ap->id."\" 
										title=\"Pour sélectionner plusieurs matières, effectuer CTRL+Clic sur chaque matière.\">";
			$listeMatieres->data_seek(0);
			while ($matiere = $listeMatieres->fetch_object()) {
				if($matiere->code_matiere=="") {
					echo "
							<option value='' disabled title=\"La nomenclature de la matière ".$matiere->matiere." n'est pas renseignée.\" style='color:red'>";
					echo $matiere->matiere." (".$matiere->nom_complet;
					if ($matiere->code_modalite_elect == 'O') {
						echo '- option obligatoire';
					} elseif ($matiere->code_modalite_elect == 'F') {
						echo '- option facultative';
					} elseif ($matiere->code_modalite_elect == 'X') {
						echo '- mesure spécifique';
					}
					elseif ($matiere->code_modalite_elect =="N") {echo " - obligatoire ou facultatif";}
					elseif ($matiere->code_modalite_elect =="L") {echo " - ajout académique";}
					elseif ($matiere->code_modalite_elect =="R") {echo " - enseignement religieux";}
					echo ")</option>";
				}
				elseif($matiere->code_modalite_elect=="") {
					echo "
							<option value='' disabled title=\"Modalité non définie.\" style='color:orange'>";
					echo $matiere->matiere." (".$matiere->nom_complet;
					if ($matiere->code_modalite_elect == 'O') {
						echo '- option obligatoire';
					} elseif ($matiere->code_modalite_elect == 'F') {
						echo '- option facultative';
					} elseif ($matiere->code_modalite_elect == 'X') {
						echo '- mesure spécifique';
					}
					elseif ($matiere->code_modalite_elect =="N") {echo " - obligatoire ou facultatif";}
					elseif ($matiere->code_modalite_elect =="L") {echo " - ajout académique";}
					elseif ($matiere->code_modalite_elect =="R") {echo " - enseignement religieux";}
					echo ")</option>";
				}
				//elseif(!in_array($matiere->code_matiere.$matiere->code_modalite_elect ,$tableauMatiere)) {
				elseif(!in_array($matiere->matiere.$matiere->code_modalite_elect ,$tableauMatiere)) {
					echo "
							<option value=\"".$matiere->matiere.$matiere->code_modalite_elect."\">";
					echo $matiere->matiere." (".$matiere->nom_complet;
					if ($matiere->code_modalite_elect == 'O') {
						echo '- option obligatoire';
					} elseif ($matiere->code_modalite_elect == 'F') {
						echo '- option facultative';
					} elseif ($matiere->code_modalite_elect == 'X') {
						echo '- mesure spécifique';
					}
					elseif ($matiere->code_modalite_elect =="N") {echo "- obligatoire ou facultatif";}
					elseif ($matiere->code_modalite_elect =="L") {echo "- ajout académique";}
					elseif ($matiere->code_modalite_elect =="R") {echo "- enseignement religieux";}
					echo ")</option>";
				}
			}

			echo "
								</select>
							</td>
						</tr>";
		}
		else {

			// Il y a déjà des matières associées
			echo "
							<th style='vertical-align:top; border:0px;' rowspan='".count($tableauMatiere)."'>
								Disciplines de référence&nbsp;:&nbsp;
							</th>";
			$cpt_row=0;
			//$tableauMatiere=array();
			$listeMatiereAP->data_seek(0);
			while ($matiereAP = $listeMatiereAP->fetch_object()) {
				if(($matiereAP->id_enseignements!="")&&($matiereAP->modalite!="")) {
					if($cpt_row>0) {
						echo "
							<tr>";
					}

					// Matières déjà associées:
					$matiere_courante=getMatiereSurMEF($matiereAP->id_enseignements)->fetch_object();
					echo "
									<td style='border:0px;'>
										<input type='checkbox' name='ApDisciplines".$ap->id."[]' id='ApDisciplines".$ap->id."_".$cpt_row."' value=\"".$matiere_courante->matiere.$matiereAP->modalite."\" onchange=\"checkbox_change(this.id)\" checked />
									</td>
									<td style='text-align:left; border:0px;'>
										<label for='ApDisciplines".$ap->id."_".$cpt_row."' id='texte_ApDisciplines".$ap->id."_".$cpt_row."'>";
					echo $matiere_courante->matiere." (";
					echo $matiere_courante->nom_complet;
					echo "<span style='color:grey'>";
					if ($matiereAP->modalite =="O") { echo " option obligatoire"; } 
					elseif ($matiereAP->modalite =="F") {echo " option facultative";} 
					elseif ($matiereAP->modalite =="X") {echo " mesure spécifique";}
					elseif ($matiereAP->modalite =="N") {echo " obligatoire ou facultatif";}
					elseif ($matiereAP->modalite =="L") {echo " ajout académique";}
					elseif ($matiereAP->modalite =="R") {echo " enseignement religieux";}
					echo "</span>)</label>";
					echo "<br />";
					echo "
									</td>";

					// Ajouter des matières
					if($cpt_row==0) {

						$tab_span_champs_select[]='span_ajout_ApDisciplines_'.$ap->id;
						echo "
								<td style='vertical-align:top; border:0px;' 
									title=\"Associer d'autres disciplines\" 
									rowspan='".count($tableauMatiere)."' 
									onmouseover=\"document.getElementById('span_ajout_ApDisciplines_".$ap->id."').style.display=''\" 
									onmouseout=\"affiche_masque_select_EPI_AP_Parcours('ApDisciplines', ".$ap->id.")\">

									<img src='../images/icons/add.png' class='icone16' alt='Ajouter' />
									Associer d'autres disciplines<br />
									<span id='span_ajout_ApDisciplines_".$ap->id."'>
									<select multiple 
											size='6' 
											name=\"ApDisciplines".$ap->id."[]\" 
											id=\"ApDisciplines".$ap->id."\" 
											title=\"Pour sélectionner plusieurs matières, effectuer CTRL+Clic sur chaque matière.\">";
						$listeMatieres->data_seek(0);
						while ($matiere = $listeMatieres->fetch_object()) {
							if($matiere->code_matiere=="") {
								echo "
										<option value='' disabled title=\"La nomenclature de la matière ".$matiere->matiere." n'est pas renseignée.\" style='color:red'>";
								echo $matiere->matiere." (".$matiere->nom_complet;
								if ($matiere->code_modalite_elect == 'O') {
									echo '- option obligatoire';
								} elseif ($matiere->code_modalite_elect == 'F') {
									echo '- option facultative';
								} elseif ($matiere->code_modalite_elect == 'X') {
									echo '- mesure spécifique';
								}
								elseif ($matiere->code_modalite_elect =="N") {echo " - obligatoire ou facultatif";}
								elseif ($matiere->code_modalite_elect =="L") {echo " - ajout académique";}
								elseif ($matiere->code_modalite_elect =="R") {echo " - enseignement religieux";}
								echo ")</option>";
							}
							elseif($matiere->code_modalite_elect=="") {
								echo "
										<option value='' disabled title=\"Modalité non définie.\" style='color:orange'>";
								echo $matiere->matiere." (".$matiere->nom_complet;
								if ($matiere->code_modalite_elect == 'O') {
									echo '- option obligatoire';
								} elseif ($matiere->code_modalite_elect == 'F') {
									echo '- option facultative';
								} elseif ($matiere->code_modalite_elect == 'X') {
									echo '- mesure spécifique';
								}
								elseif ($matiere->code_modalite_elect =="N") {echo " - obligatoire ou facultatif";}
								elseif ($matiere->code_modalite_elect =="L") {echo " - ajout académique";}
								elseif ($matiere->code_modalite_elect =="R") {echo " - enseignement religieux";}
								echo ")</option>";
							}
							//elseif(!in_array($matiere->code_matiere.$matiere->code_modalite_elect ,$tableauMatiere)) {
							elseif(!in_array($matiere->matiere.$matiere->code_modalite_elect ,$tableauMatiere)) {
								echo "
										<option value=\"".$matiere->matiere.$matiere->code_modalite_elect."\">";
								echo $matiere->matiere." (".$matiere->nom_complet;
								if ($matiere->code_modalite_elect == 'O') {
									echo '- option obligatoire';
								} elseif ($matiere->code_modalite_elect == 'F') {
									echo '- option facultative';
								} elseif ($matiere->code_modalite_elect == 'X') {
									echo '- mesure spécifique';
								}
								elseif ($matiere->code_modalite_elect =="N") {echo "- obligatoire ou facultatif";}
								elseif ($matiere->code_modalite_elect =="L") {echo "- ajout académique";}
								elseif ($matiere->code_modalite_elect =="R") {echo "- enseignement religieux";}
								//echo $matiere->matiere.$matiere->code_modalite_elect;
								echo ")</option>";
							}
						}

						echo "
										</select>
										</span>
									</td>";
					}
					echo "
								</tr>";
					$cpt_row++;
				}
			}

		}
?>
						</table>

				<p>
					<button type="submit" name="modifierAp" value="<?php echo  $ap->id; ?>" id="modifierAp_<?php echo  $ap->id; ?>" title="Enregistrer les modifications pour cet Accompagnement Personnalisé" ><img src='../images/enabled.png' /> Modifier</button>
					<button type="submit" name="supprimerAp" value="<?php echo  $ap->id; ?>" id="supprimeAp_<?php echo  $ap->id; ?>" title="Supprimer cet Accompagnement Personnalisé" ><img src='../images/disabled.png' style="width: 16px;" /> Supprimer</button>
					<button type="submit" name="creeAidAp" value="<?php echo $ap->id; ?>" disabled hidden ><img src='../images/icons/copy-16.png' /> Créer un AID pour cet AP</button>
					
					</p>
				
			</div>
			</div>
<?php 
	$cpt2 ++;
}  ?>	

			<!-- Nouvel AP -->
			<div class="lsun_cadre fieldset_opacite50">
				<div style='float:left; width:5em; font-weight:bold;'>Nouvel AP</div>

				<div align='center'>

				<div>
					<p>
						<label for="newApIntituleAP">intitulé :</label>
						<input type="text" id="newApIntituleAP" name="newApIntituleAP" maxlength="150" />
						-
						<label for="newApDescription">Description :</label>
						<textarea rows="4" cols="50" id="newApDescription" name="newApDescription" /></textarea> 
						-
						<label for="newApDisciplines">Discipline(s) de référence</label>
						<select multiple size='6' name="newApDisciplines[]" size="8">
<?php
	$listeMatieres->data_seek(0);
	while ($matiere = $listeMatieres->fetch_object()) {
		if($matiere->code_modalite_elect=="") {
			echo "
							<option value='' disabled title=\"Modalité non définie.\" style='color:orange'>";
		}
		else {
			$style_matiere="";
			if($matiere->code_matiere=="") {
				$style_matiere=" style='color:red' title=\"La nomenclature de la matière ".$matiere->matiere." n'est pas renseignée.\nL'export ne sera pas valide sans que les nomenclatures soient corrigées.\"";
			}
			echo "
							<option value=\"".$matiere->matiere.$matiere->code_modalite_elect."\"".$style_matiere.">";
		}
		echo $matiere->matiere." (".$matiere->nom_complet;

		if ($matiere->code_modalite_elect == 'O') {
			echo '- option obligatoire';
		} elseif ($matiere->code_modalite_elect == 'F') {
			echo '- option facultative';
		} elseif ($matiere->code_modalite_elect == 'X') {
			echo '- mesure spécifique';
		}
		elseif ($matiere->code_modalite_elect =="N") {echo "- obligatoire ou facultatif";}
		elseif ($matiere->code_modalite_elect =="L") {echo "- ajout académique";}
		elseif ($matiere->code_modalite_elect =="R") {echo "- enseignement religieux";}
		echo ")";
?>
							</option>
<?php } ?>
						</select>
						-
						<label for="newApLiaisonAID">Liaison</label>
						<select name="newApLiaisonAID">
							<option>
							</option>
<?php 
//var_dump($listeAidAp);
$listeAidAp->data_seek(0);
while ($liaison = $listeAidAp->fetch_object()) { ?>
							<option value="<?php echo $liaison->indice_aid; ?>" title="<?php echo $liaison->groupe." (".$liaison->description.")";?>">
								<?php echo $liaison->groupe; ?>
							</option>
<?php } ?>
						</select>
					</p>
					<p>
						<button type="submit" name="creeAP" value="y"><img src='../images/enabled.png' /> Créer cet AP</button>
					</p>
					
				</div>
				</div>
			</div>
			
		</div> 
	</fieldset>
</form>


<script type='text/javascript'>
	function affiche_masque_select_EPI_AP_Parcours(prefixe_champ, idEPI) {
		if(document.getElementById(prefixe_champ+idEPI).selectedIndex=='-1') {
			document.getElementById("span_ajout_"+prefixe_champ+"_"+idEPI).style.display='none';
		}
	}

<?php
	if(isset($tab_span_champs_select)) {
		for($loop=0;$loop<count($tab_span_champs_select);$loop++) {
			echo "
	document.getElementById(\"".$tab_span_champs_select[$loop]."\").style.display='none';";
		}
	}
?>
</script>

<!-- ======================================================================= -->
<!-- Formulaire Export des données -->

<form action="index.php" method="post" id="exportDonnees">
	<a name='form_export_des_donnees'></a>
	<fieldset class='fieldset_opacite50'>
		<legend class='fieldset_opacite50'>Export des données</legend>

		<?php
			if(isset($selectionClasse)) {
				if(count($selectionClasse)>0) {
					/*
					echo "<pre>";
					print_r($selectionClasse);
					echo "</pre>";
					*/
					echo "<p style='color:green'>".count($selectionClasse)." classe(s) sélectionnée(s)&nbsp;: ";
					$cpt=0;
					foreach($selectionClasse as $indice => $id_classe) {
						if($cpt>0) {echo ", ";}
						echo get_nom_classe($id_classe);
						$cpt++;
					}
					echo "</p>";
				}
				else {
					echo "<p style='color:red'>Aucune classe n'est sélectionnée.</p>";
				}
			}
		?>

		<div class="lsun3colonnes">
			<div style='text-align:left;'>
				<ul class='pasPuces' disable>
					<li>
						<input type="checkbox" name="traiteEPI" id="traiteEPI" value="y" onchange="checkbox_change(this.id)" 
							   <?php if (getSettingValue("LSU_traite_EPI") != "n") {echo ' checked '; }  ?> />
						<label for="traiteEPI" id="texte_traiteEPI" label="Exporter les données générales des EPI">enseignements pratiques interdisciplinaires (EPI)</label>
					</li>
					<li>
						<input type="checkbox" name="traiteEpiElv" id="traiteEpiElv" value="y" onchange="checkbox_change(this.id)" 
							   
							   <?php if ((getSettingValue("LSU_traite_EPI") != "n") && (getSettingValue("LSU_traite_EPI_Elv") != "n")) {echo ' checked '; }  ?> />
						<label for="traiteEpiElv" id="texte_traiteEpiElv">données élèves des EPI</label>
					</li>
					<li>
						<input type="checkbox" name="traiteElemProg" id="traiteElemProg" value="y" checked disabled />
						<label for="traiteElemProg" class="desactive" id="texte_traiteElemProg">éléments de programme</label>
					</li>
					<li>
						<input type="checkbox" name="forceNotes" id="forceNotes" value="y" onchange="checkbox_change(this.id)" 
							   <?php if ($_SESSION['forceNotes'] == "y") {echo ' checked '; }  ?> />
						<label for="forceNotes" id="texte_forceNotes" title="Exporter les Acquis même si la note n'est pas remplie. L'élève est déclaré 'non-noté'">
							forcer l'export des appréciations sans note
						</label>
					</li>
					<li>
						<input type="checkbox" name="forceAppreciations" id="forceAppreciations" value="y" onchange="checkbox_change(this.id)" 
							   <?php if ($_SESSION['forceAppreciations'] == "y") {echo ' checked '; }  ?> />
						<label for="forceAppreciations" id="texte_forceAppreciations" title="Exporter les Acquis même si le commentaire n'est pas rempli. Un - est mis en commentaire">
							forcer l'export des notes sans appréciation
						</label>
					</li>
					<li>
						<input type="checkbox" name="traiteVieSco" id="traiteVieSco" value="y" onchange="checkbox_change(this.id)" 
							   <?php if (getSettingValue("LSU_commentaire_vie_sco") != "n") {echo ' checked '; }  ?> />
						<label for="traiteVieSco" id="texte_traiteVieSco" title="Exporter les commentaires de vie scolaire en plus des absences">commentaires de vie scolaire</label>
					</li>
				</ul>
			</div>
			<div style='text-align:left;'>
				<ul class='pasPuces' disable>
					<li>
						<input type="checkbox" name="traiteAP" id="traiteAP" value="y" onchange="checkbox_change(this.id)" 
							   <?php if (getSettingValue("LSU_traite_AP") != "n") {echo ' checked '; }  ?>  />
						<label for="traiteAP" id="texte_traiteAP">accompagnements personnalisés (AP)</label>
					</li>
					<li>
						<input type="checkbox" name="traiteAPElv" id="traiteAPElv" value="y" onchange="checkbox_change(this.id)" 
							   <?php if ((getSettingValue("LSU_traite_AP") != "n") && (getSettingValue("LSU_traite_AP_Elv") != "n")) {echo ' checked '; }  ?>  />
						<label for="traiteAPElv" id="texte_traiteAPElv">données élèves des AP</label>
					</li>
					<li>
						<input type="checkbox" name="traiteModSpeElv" id="traiteModSpeElv" value="y" checked />
						<label for="traiteModSpeElv" id="texte_traiteModSpeElv">modalités spécifiques d’accompagnement des élèves</label>
					</li>
					<li>
						<input type="checkbox" name="traiteParent" id="traiteParent" value="y" onchange="checkbox_change(this.id)" 
							   <?php if (getSettingValue("LSU_Donnees_responsables") != "n") {echo ' checked '; }  ?> />
						<label for="traiteParent" id="texte_traiteParent" title="Exporter les informations relatives aux responsables (nom prénom adresse">
							informations relatives aux responsables de l’élève
						</label>
					</li>
					<li>
						<input type="checkbox" name="traiteParcours" id="traiteParcours" value="y" onchange="checkbox_change(this.id)" 
							   <?php if (getSettingValue("LSU_Parcours") != "n") {echo ' checked '; }  ?> />
						<label for="traiteParcours" id="texte_traiteParcours">parcours éducatifs</label>
					</li>
					<li>
						<input type="checkbox" name="traiteParcoursElv" id="traiteParcoursElv" value="y" onchange="checkbox_change(this.id)" 
							   <?php if ((getSettingValue("LSU_Parcours") != "n") && (getSettingValue("LSU_ParcoursElv") != "n")) {echo ' checked '; }  ?>  />
						<label for="traiteParcoursElv" id="texte_traiteParcoursElv">données élèves des Parcours</label>
					</li>

					<li title="Les Compétences Numériques ne sont évaluées et remontées vers LSU que pour les 6èmes en fin de cycle 3 pour l'année 2019-2020. Cette extraction implique de choisir le schéma d'export de mars 2020. Voir la note en bas de page.">
						<!-- 20200219 -->
						<input type="checkbox" name="traiteCompetencesNumeriques" id="traiteCompetencesNumeriques" value="y" onchange="checkbox_change(this.id)" 
							   <?php if (getSettingAOui("LSU_Competences_Numeriques")) {echo ' checked '; }  ?>  />
						<label for="traiteCompetencesNumeriques" id="texte_traiteCompetencesNumeriques">données élèves des Compétences Numériques</label>
					</li>

				</ul>
			</div>
			<div style='text-align:left;'>
				<ul class='pasPuces' disable>
					<li>
						<input type="checkbox" name="traiteProfP" id="traiteProfP" value="y" checked disabled />
						<label for="traiteProfP" id="texte_traiteProfP" class="desactive" >professeur(s) principal(aux)</label>
					</li>
					<li>
						<input type="checkbox" name="traiteSocle" id="traiteSocle" value="y" onchange="checkbox_change(this.id)" 
							   <?php if (getSettingValue("LSU_Donnees_socle") == "y") {echo ' checked '; }  ?> />
						<label for="traiteSocle" id="texte_traiteSocle" title="Le positionnement n'est remonté pour un élève donné sur une période donnée que si le positionnement sur les 8 domaines est renseigné.">positionnement des élèves sur les domaines du socle commun</label>
					</li>
					<li>
						<input type="checkbox" name="traiteBilanFinCycle" id="traiteBilanFinCycle" value="y" onchange="checkbox_change(this.id)" 
							   <?php if (getSettingValue("LSU_Donnees_BilanFinCycle") == "y") {echo ' checked '; }  ?> />
						<label for="traiteBilanFinCycle" id="texte_traiteBilanFinCycle" title="Le Bilan n'est remonté n'est remonté pour un élève donné que si le positionnement sur les 8 domaines est renseigné et si la synthèse est renseignée.">Bilan de fin de Cycle</label>
					</li>
					<li>
						<input type="checkbox" name="BilanFinCycle3" id="BilanFinCycle3" value="y" onchange="checkbox_change(this.id)" 
							   <?php if ((isset($_SESSION['BilanFinCycle3']))&&($_SESSION['BilanFinCycle3'] == "y")) {echo ' checked '; }  ?> />
						<label for="BilanFinCycle3" id="texte_BilanFinCycle3" title="">Générer, si les saisies existent, un bilan de fin de cycle 3, indépendamment du cycle courant de l'élève</label>
					</li>
					<li>
						<input type="checkbox" name="BilanFinCycle4" id="BilanFinCycle4" value="y" onchange="checkbox_change(this.id)" 
							   <?php if ((isset($_SESSION['BilanFinCycle4']))&&($_SESSION['BilanFinCycle4'] == "y")) {echo ' checked '; }  ?> />
						<label for="BilanFinCycle4" id="texte_BilanFinCycle4" title="">Générer, si les saisies existent, un bilan de fin de cycle 4, indépendamment du cycle courant de l'élève</label>
					</li>
				</ul>
			</div>
		</div>

		<?php
			$LSUN_version_xsd=getSettingValue('LSUN_version_xsd');
			if($LSUN_version_xsd=='') {
				//if(strftime('%d/%m/%Y')>'27/04/2018') {
				$ts=mktime(0, 0, 0, 3, 1, 2020);
				$ts1=mktime(0, 0, 0, 4, 27, 2019);
				$ts2=mktime(0, 0, 0, 4, 27, 2018);
				if(time()>$ts) {
					$checked_version_20200301=' checked';
					$checked_version_20190427='';
					$checked_version_20180427=' ';
					$checked_version_20171009='';
				}
				elseif(time()>$ts1) {
					$checked_version_20200301='';
					$checked_version_20190427=' checked';
					$checked_version_20180427='';
					$checked_version_20171009='';
				}
				elseif(time()>$ts2) {
					$checked_version_20200301='';
					$checked_version_20190427='';
					$checked_version_20180427=' checked';
					$checked_version_20171009='';
				}
				else {
					$checked_version_20200301='';
					$checked_version_20190427='';
					$checked_version_20180427='';
					$checked_version_20171009=' checked';
				}
			}
			elseif($LSUN_version_xsd=='20171009') {
					$checked_version_20200301='';
					$checked_version_20190427='';
					$checked_version_20180427='';
					$checked_version_20171009=' checked';
			}
			elseif($LSUN_version_xsd=='20180427') {
					$checked_version_20200301='';
					$checked_version_20190427='';
					$checked_version_20180427=' checked';
					$checked_version_20171009='';
			}
			elseif($LSUN_version_xsd=='20190427') {
					$checked_version_20200301='';
					$checked_version_20190427=' checked';
					$checked_version_20180427='';
					$checked_version_20171009='';
			}
			else {
					$checked_version_20200301=' checked';
					$checked_version_20190427='';
					$checked_version_20180427='';
					$checked_version_20171009='';
			}
			echo "
				<p><strong>Schéma de l'export&nbsp;:</strong><br />
				<input type='radio' name='LSUN_version_xsd' id='LSUN_version_xsd_20171009' value='20171009' onchange=\"change_style_radio()\"".$checked_version_20171009."/><label for='LSUN_version_xsd_20171009' id='texte_LSUN_version_xsd_20171009'>Version octobre 2017</label>
				<br />
				<input type='radio' name='LSUN_version_xsd' id='LSUN_version_xsd_20180427' value='20180427' onchange=\"change_style_radio()\"".$checked_version_20180427."/><label for='LSUN_version_xsd_20180427' id='texte_LSUN_version_xsd_20180427'>Version fin avril 2018 (LSU 18.2.0.0)</label>
				<br />
				<input type='radio' name='LSUN_version_xsd' id='LSUN_version_xsd_20190427' value='20190427'  onchange=\"change_style_radio()\"".$checked_version_20190427."/><label for='LSUN_version_xsd_20190427' id='texte_LSUN_version_xsd_20190427'>Version fin avril 2019 (LSU 19.2.0.0)</label>
				<br />
				<!-- 20200219 -->
				<input type='radio' name='LSUN_version_xsd' id='LSUN_version_xsd_20200301' value='20200301'  onchange=\"change_style_radio()\"".$checked_version_20200301."/><label for='LSUN_version_xsd_20200301' id='texte_LSUN_version_xsd_20200301'>Version mars 2020 (LSU x.x.x.x)</label>
			</p>";
		?>


		<p class="lsun_cadre fieldset_opacite50" >
			<a href="lib/creeXML.php" target="exportLSUN.xml" title="Affiche le fichier dans un nouvel onglet en interceptant les erreurs" >Afficher l'export</a>
		</p>
		<p class="center">
			<button type="submit" name="creeFichier" value="y">Créer le fichier</button>
		</p>
	</fieldset>
</form>

<?php

echo $msg_erreur;


echo "<p style='margin-top:1em; margin-left:4em; text-indent:-4em;'><em>NOTES&nbsp;:</em></p>
<ul>
	<li><p>";

$sql="SELECT jgec.id_groupe FROM j_groupes_enseignements_complement jgec, groupes g WHERE jgec.id_groupe=g.id;";
$res=mysqli_query($mysqli, $sql);
$nb_ens_compl=mysqli_num_rows($res);
if($nb_ens_compl==0) {
	echo "Aucun enseignement n'est déclaré enseignement de complément.<br />
		Serait-ce un oubli de paramétrage.<br />";
}
elseif($nb_ens_compl==1) {
	echo "Un seul enseignement de complément est déclaré.<br />
		Cela parait peu.<br />";
}
else {
	echo $nb_ens_compl." enseignements sont déclarés enseignements de complément.<br />";
}

echo "
		Habituellement, des enseignements de latin, euro,... sont tagués enseignements de complément.<br />
		Ces enseignements rapportent alors des points supplémentaires aux élèves dans le bilan de fin de cycle.<br />
		Le marquage 'enseignement de complément' se fait en administrateur dans <strong>Gestion des bases/Gestion des classes/&lt;Telle classe&gt; Enseignements</strong> colonne <strong>Ens.compl</strong>.<br />
		Le marquage peut être effectué par lots via <strong>Gestion des bases/Gestion des classes/<a href='../classes/classes_param.php' target='_blank'>Paramétrage par lots</a>/Modifier le type d'enseignement de complément des enseignements de...</strong></p>
		<br />
	</li>
	<li>
		<p>L'arrêté relatif à l'<strong>évaluation des compétences numériques</strong> <a href='https://www.legifrance.gouv.fr/jo_pdf.do?id=JORFTEXT000039005188' target='_blank'>https://www.legifrance.gouv.fr/jo_pdf.do?id=JORFTEXT000039005188</a> acquises par les élèves des écoles et collèges été publié au Journal officiel le 1er septembre 2019.<br />
Le CRCN définit 16 compétences numériques attendues dans 5 domaines d'activité et propose 8 niveaux de maitrise de ces compétences pour les élèves de l'enseignement scolaire mais aussi les étudiants de l'enseignement supérieur et dans un contexte de formation d'adultes.<br />
Au cycle 3, l'évaluation sera limitée aux 3 premiers niveaux de maitrise.<br />
Dès l'année 2019-2020, les enseignants de cycle 3 auront à renseigner dans le dernier bilan périodique des élèves de CM2 et de 6e les niveaux de maitrise des compétences numériques atteints par les élèves.<br />
Pour en savoir plus sur les compétences numériques, reportez-vous à la page Eduscol dédiée <a href='https://eduscol.education.fr/pid38816/certification-des-competences-numeriques.html' target='_blank'>https://eduscol.education.fr/pid38816/certification-des-competences-numeriques.html</a></p>
	</li>
</ul>";

?>

</div>

<script type='text/javascript'>
	<?php 
		echo js_change_style_all_checkbox("n", "y");
		echo js_change_style_radio();
	?>
	change_style_radio();
</script>

<?php if (!$selectionClasse) { ?>

<script type='text/javascript'>
	document.getElementById("defAid").style.display='none';
</script>
<?php } ?>
