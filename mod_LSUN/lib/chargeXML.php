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


/*==========================================================================
 *             On charge les données
 ==========================================================================*/
$msgErreur = "";
include_once 'lib/fonctions.php';
include_once 'chargeDonnees.php';

$_AP = 1;
$_EPI = 2;
$_PARCOURS = 3;

//++++++++++++++++++++++++++++++++++
// Initialisations
$msg_erreur_remplissage="";
$tab_periodes_extraites=array();
$liste_creation_auto_element_programme="";
$tab_classes_avec_date_debut_periode_manquante=array();

$tab_effectifs=array();

//$gepiYear=getSettingValue("gepiYear");
//$millesime=preg_replace("/[^0-9]{1,}[0-9]*/","",$gepiYear);
$date_creation=strftime("%Y-%m-%d");

$longueur_limite_lignes_adresse["ligne1"]=50;
$longueur_limite_lignes_adresse["ligne2"]=50;
$longueur_limite_lignes_adresse["ligne3"]=50;
$longueur_limite_lignes_adresse["ligne4"]=50;
$longueur_limite_lignes_adresse["code-postal"]=10;
$longueur_limite_lignes_adresse["commune"]=100;

$tab_erreur_adr=array();

//========================
$debug=0; // Passer à 1 pour afficher les requêtes
$login_debug="bouamar_f";
$code_matiere_debug="030102";
//========================
//++++++++++++++++++++++++++++++++++

//========================
// 20190118
$tab_classe_ele=array();
if (isset($selectionClasse) && count($selectionClasse)) {
	$myData=implode(",", $selectionClasse);
	$sql="SELECT * FROM j_eleves_classes WHERE id_classe IN ($myData);";
	$tmp_res_clas=mysqli_query($mysqli, $sql);
	if(mysqli_num_rows($tmp_res_clas)>0) {
		while($lig_tmp_clas=mysqli_fetch_object($tmp_res_clas)) {
			$tab_classe_ele[$lig_tmp_clas->login][$lig_tmp_clas->periode]=$lig_tmp_clas->id_classe;
		}
	}
}
//========================

$xml = new DOMDocument('1.0', 'utf-8');

$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;

$items = $xml->createElementNS('urn:fr:edu:scolarite:lsun:bilans:import','lsun-bilans');
	
$xml->appendChild($items);

	$LSUN_version_xsd=getSettingValue('LSUN_version_xsd');
	if($LSUN_version_xsd=='') {
		$LSUN_version_xsd=20171009;
	}

	if($LSUN_version_xsd==20171009) {
		/*
		$items->setAttributeNS(
			'http://www.w3.org/2001/XMLSchema-instance', // xmlns namespace URI
			'xsi:schemaLocation',
			'urn:fr:edu:scolarite:lsun:bilans:import import-bilan-complet_20171009.xsd'
		);
		*/
		$items->setAttributeNS(
			'http://www.w3.org/2001/XMLSchema-instance', // xmlns namespace URI
			'xsi:schemaLocation',
			'urn:fr:edu:scolarite:lsun:bilans:import import-bilan-complet.xsd'
		);
	}
	else {
		$items->setAttributeNS(
			'http://www.w3.org/2001/XMLSchema-instance', // xmlns namespace URI
			'xsi:schemaLocation',
			'urn:fr:edu:scolarite:lsun:bilans:import import-bilan-complet.xsd'
		);
	}

	// 20200219
	if($LSUN_version_xsd>=20200301) {
		$longueur_max_chaine_code_division=20;
	}
	else {
		$longueur_max_chaine_code_division=8;
	}

	$items->setAttribute(
		'schemaVersion',
		'3.0'
	);
		/*----- Entête -----*/
		$entete = $xml->createElement('entete');
			$etablissement= getSettingValue('gepiSchoolRne');
			$noeudsEntete = array("editeur"=>'Contributeurs GEPI','application'=>'GEPI','etablissement'=>$etablissement);
			foreach ($noeudsEntete as $cle=>$valeur) {
				$noeudEntete = $xml->createElement($cle,$valeur);
				$entete->appendChild($noeudEntete);
			}
			
	$items->appendChild($entete);
	
	/*----- Données -----*/
	$donnees = $xml->createElement('donnees');
	$items->appendChild($donnees);
		
		/*----- Responsables-etab -----*/
		$responsablesEtab = $xml->createElement('responsables-etab');
		// TODO: il faudra gérer un tableau
		while ($responsable = $listeResponsables->fetch_object()){
				$noeudResponsableEtab = $xml->createElement('responsable-etab');
				$attResponsableEtabId= $xml->createAttribute('id');
				$attResponsableEtabId->value = "RESP_".$responsable->id;
				$noeudResponsableEtab->appendChild($attResponsableEtabId);
				$attResponsableEtabLibelle= $xml->createAttribute('libelle');
				$attResponsableEtabLibelle->value = trim($responsable->suivi_par);
				$noeudResponsableEtab->appendChild($attResponsableEtabLibelle);
				$responsablesEtab->appendChild($noeudResponsableEtab);
			}
			
		$donnees->appendChild($responsablesEtab);
		
		/*----- Élèves -----*/
		$tab_ele_deja=array();
		$tab_ele_derniere_classe=array();

		// 20170531

		$sql_ajout_restriction_periode="";
		if($LSUN_periodes_a_extraire!='toutes') {
			$myData=implode(",", $LSUN_periodes);
			$sql_ajout_restriction_periode=" AND periode IN ($myData) ";
		}
		// Tester les doublons élèves, pour retenir la classe de la dernière période.
		while ($eleve = $listeEleves->fetch_object()) {
			if(!in_array('EL_'.$eleve->id_eleve, $tab_ele_deja)) {
				// Récupérer la dernière classe de l'élève.
				$sql="SELECT classe FROM classes c, j_eleves_classes jec WHERE c.id=jec.id_classe AND jec.login='".$eleve->login."'".$sql_ajout_restriction_periode." ORDER BY jec.periode DESC LIMIT 1;";
				$res_class_tmp=mysqli_query($mysqli, $sql);
				if(mysqli_num_rows($res_class_tmp)>0) {
					$lig_class_tmp=mysqli_fetch_object($res_class_tmp);
					$tab_ele_derniere_classe[$eleve->login]=$lig_class_tmp->classe;
				}
			}
			$tab_ele_deja[]='EL_'.$eleve->id_eleve;
		}
		mysqli_data_seek($listeEleves, 0);

		$tab_ele_deja=array();
		$tab_idbe_ele_deja=array();
		$eleves = $xml->createElement('eleves');
		while ($eleve = $listeEleves->fetch_object()) {
			/*
			echo "<pre>";
			print_r($eleve);
			echo "</pre>";
			*/
			$prendre_en_compte_cet_eleve=true;
			if($LSUN_periodes_a_extraire!='toutes') {
				//||(in_array($vieScoCommun->periode, $LSUN_periodes))) {
				$prendre_en_compte_cet_eleve=false;

				if (isset($selectionClasse) && count($selectionClasse)) {
					$myData=implode(",", $selectionClasse);
					$sql_ajout_classes="id_classe IN ($myData) ";

					for($loop_tmp=0;$loop_tmp<count($LSUN_periodes);$loop_tmp++) {
						$sql="SELECT 1=1 FROM j_eleves_classes WHERE login='".$eleve->login."' AND periode='".$LSUN_periodes[$loop_tmp]."' AND ".$sql_ajout_classes;
						//echo "$sql<br />";
						$test=mysqli_query($mysqli, $sql);
						if(mysqli_num_rows($test)>0) {
							$prendre_en_compte_cet_eleve=true;
							break;
						}
					}
				}
			}

			if($prendre_en_compte_cet_eleve) {
				$noeudEleve = $xml->createElement('eleve');

				if(!preg_match("/^[0-9]{1,}$/", $eleve->ele_id)) {
					$msg_erreur_remplissage.="L'élève ".$eleve->nom." ".$eleve->prenom." n'est pas associé à un élève dans Sconet <em>(Identifiant ELE_ID non valide)</em>&nbsp;: <a href='../responsables/corrige_ele_id.php' target='_blank'>Corriger</a><br /><br />";
				}

				$tmp_classe=$eleve->classe;
				if((!getSettingANon("LSU_LastDivEleve"))&&(isset($tab_ele_derniere_classe[$eleve->login]))) {
					$tmp_classe=$tab_ele_derniere_classe[$eleve->login];
				}

				$attributsEleve = array('id'=>'EL_'.$eleve->id_eleve,'id-be'=>$eleve->ele_id,
					'nom'=>mb_substr($eleve->nom,0,100,'UTF-8'),
					'prenom'=>mb_substr($eleve->prenom,0,100,'UTF-8'),
					'code-division'=>mb_substr($tmp_classe,0,$longueur_max_chaine_code_division,'UTF-8'));
				foreach ($attributsEleve as $cle=>$valeur) {
					$attEleve = $xml->createAttribute($cle);
					$attEleve->value = $valeur;
					$noeudEleve->appendChild($attEleve);
				}

				//$eleves->appendChild($noeudEleve);

				if(in_array('EL_'.$eleve->id_eleve, $tab_ele_deja)) {
					//$msg_erreur_remplissage.="<strong>ATTENTION&nbsp;:</strong> L'élève ".'EL_'.$eleve->id_eleve." (<a href='../eleves/modify_eleve.php?eleve_login=".$eleve->login."' target='_blank'>".$eleve->nom." ".$eleve->prenom."</a>) apparait plusieurs fois. Cela correspond probablement à un changement de classe.<br />L'export ne va pas être valide. Il faut exporter séparément les classes de cet élève.<br /><br />";
					$msg_erreur_remplissage.="<strong>ATTENTION&nbsp;:</strong> L'élève ".'EL_'.$eleve->id_eleve." (<a href='../eleves/modify_eleve.php?eleve_login=".$eleve->login."' target='_blank'>".$eleve->nom." ".$eleve->prenom."</a>) apparait plusieurs fois. Cela correspond probablement à un changement de classe.<br />La dernière classe de l'élève sera retenue dans l'export.<br /><br />";
				}
				else {
					if(in_array($eleve->ele_id, $tab_idbe_ele_deja)) {
						//20170606
						// On ne devrait pas arriver à cette situation
						// L'ele_id n'est pas éditable directement dans Gepi, seulement par responsables/corrige_ele_id.php (et responsables/maj_import3.php)

						$msg_erreur_remplissage.="<strong>ATTENTION&nbsp;:</strong> L'élève <a href='../eleves/modify_eleve.php?eleve_login=".$eleve->login."' target='_blank'>".$eleve->nom." ".$eleve->prenom."</a> a un identifiant ELEVE_ID qui n'est pas unique.<br />Il faut corriger.<br /><br />";
					}

					$eleves->appendChild($noeudEleve);
				}
				$tab_ele_deja[]='EL_'.$eleve->id_eleve;
				$tab_idbe_ele_deja[]=$eleve->ele_id;
			}
		}
		$donnees->appendChild($eleves);
		
		/*----- Périodes -----*/
		$periodes = $xml->createElement('periodes');
		while ($periode = $listePeriodes->fetch_object()){
				if(($LSUN_periodes_a_extraire=='toutes')||(in_array($periode->num_periode, $LSUN_periodes))) {
					$noeudPeriode = $xml->createElement('periode');
						if($periode->num_periode < 10) {$num_periode = "0".$periode->num_periode;} else {$num_periode = $periode->num_periode;}
						$attributsPeriode = array('id'=>'P_'.$num_periode,'millesime'=>$millesime,
							'indice'=>$periode->num_periode,'nb-periodes'=>$listePeriodes->num_rows);
						foreach ($attributsPeriode as $cle=>$valeur) {
							$attPeriode = $xml->createAttribute($cle);
							$attPeriode->value = $valeur;
							$noeudPeriode->appendChild($attPeriode);
						}
					$periodes->appendChild($noeudPeriode);
				}
			}
		$donnees->appendChild($periodes);
		
		/*----- Disciplines -----*/
		// 20180601
		$tab_disciplines_modalites=array();
		$tab_disciplines_global_deja=array();
		$disciplines = $xml->createElement('disciplines');
		while ($discipline = $listeDisciplines->fetch_object()) {
			$noeudDiscipline = $xml->createElement('discipline');
				//if($discipline->id < 10) {$id_discipline = "0".$discipline->id;} else {$id_discipline = $discipline->id;}
			$codesAutorises = array('S', 'O', 'F', 'L', 'R', 'X');
			if (!in_array($discipline->code_modalite_elect, $codesAutorises)) {
				$msgErreur .= "La matière $discipline->nom_complet a pour modalité $discipline->code_modalite_elect. Cette modalité n'est pas autorisée. <a href='../gestion/gerer_modalites_election_enseignements.php#forcer_modalites_telles_matieres' target='_blank'>Corriger</a>.<br /><br />";
			}
			if($discipline->code_matiere=="") {
				$msgErreur .= "La matière ".$discipline->nom_complet." a un code vide <em>(non rattaché à une <strong>nomenclature</strong>)</em>. Le XML ne va pas être valide. <a href='../matieres/modify_matiere.php?current_matiere=".$discipline->id_matiere."' target='_blank'>Corriger</a>.<br /><br />";
			}
			else {

				$matiere = "DI_".$discipline->code_matiere.$discipline->code_modalite_elect;

				// 20180601
				$tab_disciplines_modalites[$matiere]=$discipline->nom_complet." (".$discipline->code_modalite_elect.")";

				if(in_array($matiere, $tab_disciplines_global_deja)) {
					//$sql="SELECT valeur FROM nomenclatures_valeurs WHERE code='".$discipline->code_matiere."' AND nom='libelle_edition';";
					$nom_matiere=get_valeur_champ("nomenclatures_valeurs", "code='".$discipline->code_matiere."' AND nom='libelle_edition'", "valeur");
					// Ca ne convient pas: On ne liste pas les matières qui ont cette nomenclature
					//$msg_erreur_remplissage.="Plusieurs enseignements de <b><a href='../matieres/modify_matiere.php?current_matiere=".$nom_matiere."' target='_blank'>".$nom_matiere."</a></b> avec la même modalité (".$discipline->code_modalite_elect.").<br />Ce n'est pas possible.<br />Il faut corriger.<br /><br />";
					$info_matieres="";
					$sql="SELECT DISTINCT m.matiere FROM matieres m, j_groupes_matieres jgm WHERE m.code_matiere='".$discipline->code_matiere."' AND jgm.id_matiere=m.matiere ORDER BY matiere;";
					$res_mat=mysqli_query($mysqli, $sql);
					if(mysqli_num_rows($res_mat)>0) {
						$info_matieres.=" <em title=\"Les matières associées à la nomenclature ".$discipline->code_matiere." sont celles-ci.\">(";
						$cpt_mat=0;
						while($lig_mat=mysqli_fetch_object($res_mat)) {
							if($cpt_mat>0) {$info_matieres.=", ";}
							$info_matieres.="<a href='../matieres/modify_matiere.php?current_matiere=".$lig_mat->matiere."' target='_blank'>".$lig_mat->matiere."</a>";
							$cpt_mat++;
						}
						$info_matieres.=")</em>";
					}
					$msg_erreur_remplissage.="Plusieurs enseignements de <b>".$nom_matiere."</b> avec la même modalité (".$discipline->code_modalite_elect.").<br />Cela peut arriver quand une même nomenclature matière est associée à plusieurs matières et que les différentes matières sont extraites avec la même modalité dans un export XML".$info_matieres.".<br />Ce n'est pas possible.<br />Il faut corriger.<br /><br />";
				}
				$tab_disciplines_global_deja[]=$matiere;
			}


			if(($debug==1)&&($discipline->code_matiere==$code_matiere_debug)) {
				echo "$matiere groupe ".(isset($discipline->id_groupe) ? $discipline->id_groupe : "Pas d'id_groupe")."<br />";
			}


			$attributsDiscipline = array('id'=>'DI_'.$discipline->code_matiere.$discipline->code_modalite_elect,'code'=>$discipline->code_matiere,
				'modalite-election'=>$discipline->code_modalite_elect,'libelle'=>htmlspecialchars($discipline->nom_complet));
			foreach ($attributsDiscipline as $cle=>$valeur) {
				$attDiscipline = $xml->createAttribute($cle);
				$attDiscipline->value = $valeur;
				$noeudDiscipline->appendChild($attDiscipline);
			}
			$disciplines->appendChild($noeudDiscipline);
		}

		//======================================================
		// 20170722
		// Disciplines et modalités non déclarées dans des enseignements, mais présentes dans les EPI, AP, Parcours
		if (getSettingValue("LSU_traite_EPI") != "n") {
			$codesAutorises = array('S', 'O', 'F', 'L', 'R', 'X');

			$listeEPICommun = getEPICommun();
			while ($epiCommun = $listeEPICommun->fetch_object()) { 
				$matieres = getMatieresEPICommun($epiCommun->id);
				foreach ($matieres as $matiere) {
					$discipline=getMatiereOnMatiere($matiere["id_matiere"]);
					$ref = "DI_".$discipline->code_matiere.$matiere["modalite"];

					//$noeudDiscipline = $xml->createElement('discipline');
					//assureDisciplinePresente($ref);

					if (!in_array($matiere["modalite"], $codesAutorises)) {
						$msgErreur .= "La matière ".$discipline->nom_complet." a pour modalité ".$matiere["modalite"].". Cette modalité n'est pas autorisée. <a href='../gestion/gerer_modalites_election_enseignements.php#forcer_modalites_telles_matieres' target='_blank'>Corriger</a>.<br /><br />";
					}
					if($discipline->code_matiere=="") {
						$msgErreur .= "La matière ".$discipline->nom_complet." a un code vide <em>(non rattaché à une <strong>nomenclature</strong>)</em>. Le XML ne va pas être valide. <a href='../matieres/modify_matiere.php?current_matiere=".$discipline->id_matiere."' target='_blank'>Corriger</a>.<br /><br />";
					}
					else {

						if(!in_array($ref, $tab_disciplines_global_deja)) {
							// Ajouter la matière

							$noeudDiscipline = $xml->createElement('discipline');

							$attributsDiscipline = array('id'=>'DI_'.$discipline->code_matiere.$matiere["modalite"],'code'=>$discipline->code_matiere,
								'modalite-election'=>$matiere["modalite"],'libelle'=>htmlspecialchars($discipline->nom_complet));
							foreach ($attributsDiscipline as $cle=>$valeur) {
								$attDiscipline = $xml->createAttribute($cle);
								$attDiscipline->value = $valeur;
								$noeudDiscipline->appendChild($attDiscipline);
							}
							$disciplines->appendChild($noeudDiscipline);

							$tab_disciplines_global_deja[]=$ref;
						}
					}

				}
			}
		}
		//======================================================
		if (getSettingValue("LSU_traite_AP") != "n") {
			$codesAutorises = array('S', 'O', 'F', 'L', 'R', 'X');

			$listeApCommuns = getAPCommun();
			if ($listeApCommuns->num_rows) {
				while ($apCommun = $listeApCommuns->fetch_object()) {
					$disciplinesAP = getDisciplines($apCommun->id);

/*
mysql> SELECT * FROM lsun_j_ap_matiere WHERE id_ap = '4';
+----+------------------+----------+-------+
| id | id_enseignements | modalite | id_ap |
+----+------------------+----------+-------+
| 15 | 030201           | S        |     4 |
| 16 | 100100           | S        |     4 |
| 17 | 030602           | S        |     4 |
| 18 | 020700           | F        |     4 |
+----+------------------+----------+-------+
4 rows in set (0.00 sec)

mysql> 
*/

					while ($matiere = $disciplinesAP->fetch_object()) {
						$matieresAP="DI_".$matiere->id_enseignements.$matiere->modalite;

						$sql="SELECT * FROM matieres WHERE code_matiere='".$matiere->id_enseignements."';";
						$res_mat=mysqli_query($mysqli, $sql);
						$lig_matiere=mysqli_fetch_object($res_mat);

						if (!in_array($matiere->modalite, $codesAutorises)) {
							$msgErreur .= "La matière ".$lig_matiere->nom_complet." a pour modalité ".$matiere->modalite.". Cette modalité n'est pas autorisée. <a href='../gestion/gerer_modalites_election_enseignements.php#forcer_modalites_telles_matieres' target='_blank'>Corriger</a>.<br /><br />";
						}
						if($matiere->id_enseignements=="") {
							$msgErreur .= "La matière ".$lig_matiere->nom_complet." a un code vide <em>(non rattaché à une <strong>nomenclature</strong>)</em>. Le XML ne va pas être valide. <a href='../matieres/modify_matiere.php?current_matiere=".$lig_matiere->matiere."' target='_blank'>Corriger</a>.<br /><br />";
						}
						else {

							if(!in_array($matieresAP, $tab_disciplines_global_deja)) {
								// Ajouter la matière

								$noeudDiscipline = $xml->createElement('discipline');

								$attributsDiscipline = array('id'=>'DI_'.$matiere->id_enseignements.$matiere->modalite,'code'=>$matiere->id_enseignements,
									'modalite-election'=>$matiere->modalite,'libelle'=>htmlspecialchars($lig_matiere->nom_complet));
								foreach ($attributsDiscipline as $cle=>$valeur) {
									$attDiscipline = $xml->createAttribute($cle);
									$attDiscipline->value = $valeur;
									$noeudDiscipline->appendChild($attDiscipline);
								}
								$disciplines->appendChild($noeudDiscipline);

								$tab_disciplines_global_deja[]=$matieresAP;
							}
						}


					}
				}
			}
		}
		//======================================================

		$donnees->appendChild($disciplines);


		/*----- Enseignants -----*/
		$enseignants = $xml->createElement('enseignants');
		while ($enseignant = $listeEnseignants->fetch_object()) {
				$noeudEnseignant = $xml->createElement('enseignant');
						
					//on ne conserve que les chiffres pour id-sts
					if (!$enseignant->numind) {
						$msgErreur .= $enseignant->nom." ".$enseignant->prenom." n'a pas d'identifiant STS, vous devez corriger cette erreur avant de continuer&nbsp;: <em><a href=\"../utilisateurs/modify_user.php?user_login=".$enseignant->login."\" target=\"_BLANK\" >Corriger</a></em><br /><br />";
						continue;
					}
					if((!$enseignant->nom)||($enseignant->nom=="")) {
						$msgErreur .= "L'enseignant '$enseignant->numind' n'a pas de nom, vous devez <a href='../utilisateurs/modify_user.php?user_login=".$enseignant->login."' target='_blank'>corriger</a> cette erreur.<br /><br />";
						continue;
					}
					if((!$enseignant->prenom)||($enseignant->prenom=="")) {
						$msgErreur .= "L'enseignant $enseignant->nom ($enseignant->numind) n'a pas de prénom, vous devez <a href='../utilisateurs/modify_user.php?user_login=".$enseignant->login."' target='_blank'>corriger</a> cette erreur.<br /><br />";
						continue;
					}

					if((preg_match('#[0-9]+#',$enseignant->numind))&&(substr($enseignant->numind,1)==0)) {
						$msgErreur .= $enseignant->nom." ".$enseignant->prenom." a un identifiant STS non valide (".$enseignant->numind."). Cela doit être P suivi d'un entier non nul.<br />Vous devez corriger cette erreur avant de continuer&nbsp;: <em><a href=\"../utilisateurs/modify_user.php?user_login=".$enseignant->login."\" target=\"_BLANK\" >Corriger</a></em><br /><br />";
						continue;
					}

					preg_match_all('#[0-9]+#',$enseignant->numind,$extract);
					/*
					echo "$enseignant->numind<pre>";
					print_r($enseignant);
					print_r($extract);
					echo "</pre>";
					*/
					if((!isset($extract[0]))||(!isset($extract[0][0]))) {
						$msgErreur .= "Le format de l'identifiant NUMIND de ".$enseignant->nom." ".$enseignant->prenom." n'est pas valide.<br />Ce doit être un <strong>P</strong> suivi d'<strong>un ou plusieurs chiffres</strong>; vous devez corriger cette erreur avant de continuer&nbsp;: <em><a href=\"../utilisateurs/modify_user.php?user_login=".$enseignant->login."\" target=\"_BLANK\" >Corriger</a></em><br /><br />";
						continue;
					}

					$idSts = $extract[0][0];
					$type = $enseignant->type ? $enseignant->type : "local";
					$civilite = $enseignant->civilite == "Mme" ? 'MME' : 'M' ;
					$attributsEnseignant = array('id'=>'ENS_'.$idSts, 'type'=>$type, 'id-sts'=>$idSts,
						'civilite'=>$civilite, 'nom'=>$enseignant->nom, 'prenom'=>$enseignant->prenom);
					foreach ($attributsEnseignant as $cle=>$valeur) {
						$attEnseignant = $xml->createAttribute($cle);
						$attEnseignant->value = $valeur;
						$noeudEnseignant->appendChild($attEnseignant);
					}
				$enseignants->appendChild($noeudEnseignant);
			}
		$donnees->appendChild($enseignants);
		
		/*----- Éléments du programme -----*/
		$elementsProgramme = $xml->createElement('elements-programme');
		// on crée un élément de programme "Pas d'élément de programme saisi pour la période".
		$noeudPasEP = $xml->createElement('element-programme');
		$attributsElementProgramme = array('id'=>'EP_0000', 'libelle'=>"Pas d'élément de programme saisi pour la période");
			foreach ($attributsElementProgramme as $cle=>$valeur) {
				$attElementProgramme = $xml->createAttribute($cle);
				$attElementProgramme->value = $valeur;
				$noeudPasEP->appendChild($attElementProgramme);
			}
			$elementsProgramme->appendChild($noeudPasEP);
		while ($elementProgramme = $listeElementsProgramme->fetch_object()) {
			$noeudElementProgramme = $xml->createElement('element-programme');
			$elePro = trim($elementProgramme->libelle) ? mb_substr(htmlspecialchars($elementProgramme->libelle),0,300,'UTF-8') : "-";
			$attributsElementProgramme = array('id'=>'EP_'.$elementProgramme->id, 'libelle'=>$elePro);
			foreach ($attributsElementProgramme as $cle=>$valeur) {
				$attElementProgramme = $xml->createAttribute($cle);
				$attElementProgramme->value = $valeur;
				$noeudElementProgramme->appendChild($attElementProgramme);
			}
			$elementsProgramme->appendChild($noeudElementProgramme);
		}
		$donnees->appendChild($elementsProgramme);


		// 20200219
		/*----- Compétences Numériques -----*/
		/*
		<competences-numeriques-communs>
			<competences-numeriques-commun id="CNC_01" code-structure="6EME_4" type-structure="D">
				Appréciation 
				littérale pour la classe de 6EME_4 pour les compétences numériques du dernier trimestre.  
				Présente les projets réalisés en spécifiant les outils mobilisés et l’implication globale de 
				la classe.
			</competences-numeriques-commun>
			<competences-numeriques-commun id="CNC_02" code-structure="6_TEC_2__6_TEC_2" type-structure="G">
				Appréciation 
				littérale pour le groupe 6_TEC_2__6_TEC_2 pour les compétences numériques. Présente les projets réalisés 
				en spécifiant les outils mobilisés et l’implication globale de la classe.
			</competences-numeriques-commun>
		</competences-numeriques-communs>
		*/
		if ((getSettingValue("LSU_Competences_Numeriques") != "n")&&($LSUN_version_xsd>=20200301)) {
			if (isset($selectionClasse) && count($selectionClasse)>0) {
				//$gepiYear=getSettingValue("gepiYear");
				//$gepiYear_debut=mb_substr($gepiYear, 0, 4);
				$tmp_tab=array();
				for($loop=0;$loop<count($selectionClasse);$loop++) {
					//$sql="SELECT * FROM socle_classes_syntheses_numeriques WHERE id_classe='".$selectionClasse[$loop]."' AND annee='".$gepiYear_debut."';";
					$sql="SELECT * FROM socle_classes_syntheses_numeriques WHERE id_classe='".$selectionClasse[$loop]."' AND annee='".$millesime."';";
					//echo "$sql<br />";
					$test=mysqli_query($GLOBALS["mysqli"], $sql);
					if(mysqli_num_rows($test)>0) {
						$tmp_lig=mysqli_fetch_object($test);
						$tmp_tab['CNC_'.$selectionClasse[$loop]]['classe']=get_nom_classe($selectionClasse[$loop]);
						$tmp_tab['CNC_'.$selectionClasse[$loop]]['synthese']=$tmp_lig->synthese;
					}
				}

				if(count($tmp_tab)>0) {
					$CompetencesNumeriquesCommuns = $xml->createElement('competences-numeriques-communs');
					foreach($tmp_tab as $key => $current_tab) {
						$tmp_chaine=nettoye_texte_vers_chaine($current_tab['synthese']);
						$synthese = ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8'));

						$noeudCompetencesNumeriquesCommun= $xml->createElement('competences-numeriques-commun', $synthese);

						$attributsCompetencesNumeriquesCommun = array('id'=>$key, 
							'code-structure'=>mb_substr(htmlspecialchars($current_tab['classe']), 0, $longueur_max_chaine_code_division, 'UTF-8'), 
							'type-structure'=>'D');
						foreach ($attributsCompetencesNumeriquesCommun as $cle=>$valeur) {
							$attCompetencesNumeriquesCommun = $xml->createAttribute($cle);
							$attCompetencesNumeriquesCommun->value = $valeur;
							$noeudCompetencesNumeriquesCommun->appendChild($attCompetencesNumeriquesCommun);
						}
						//echo "plop<br />";
						$CompetencesNumeriquesCommuns->appendChild($noeudCompetencesNumeriquesCommun);
					}

					$donnees->appendChild($CompetencesNumeriquesCommuns);
				}
			}
		}


		/*----- Parcours -----*/
if (getSettingValue("LSU_Parcours") != "n") {
	if ($listeParcoursCommuns->num_rows) {
		$parcoursCommuns = $xml->createElement('parcours-communs');
		while ($parcoursCommun = $listeParcoursCommuns->fetch_object()) {
			if(($LSUN_periodes_a_extraire=='toutes')||(in_array($parcoursCommun->periode, $LSUN_periodes))) {
				$noeudParcoursCommun= $xml->createElement('parcours-commun');
					if($parcoursCommun->periode < 10) {$num_periode = "0".$parcoursCommun->periode;} else {$num_periode = $parcoursCommun->periode;}
					$parcoursClasse = getClasses($parcoursCommun->classe)->fetch_object()->classe;
					$attributsParcoursCommun = array('periode-ref'=>'P_'.$num_periode, 'code-division'=>mb_substr(htmlspecialchars($parcoursClasse),0,$longueur_max_chaine_code_division,'UTF-8'));
					foreach ($attributsParcoursCommun as $cle=>$valeur) {
						$attParcoursCommun = $xml->createAttribute($cle);
						$attParcoursCommun->value = $valeur;
						$noeudParcoursCommun->appendChild($attParcoursCommun);
					}
					
					$listeParcours = getParcoursCommuns(NULL, $parcoursCommun->classe, $parcoursCommun->periode);
					//var_dump($listeParcours);
					while ($parcours = $listeParcours->fetch_object()){
						//echo $parcours->description.'<br>';
						$noeudParcours = $xml->createElement('parcours',$parcours->description);
						$attributsParcours = array('code'=>$parcours->codeParcours);
						foreach ($attributsParcours as $cle=>$valeur) {
							$attParcours = $xml->createAttribute($cle);
							$attParcours->value = $valeur;
							$noeudParcours->appendChild($attParcours);
						}
						// 20190118
						$tab_effectifs['parcours'][$parcoursCommun->classe][$parcoursCommun->periode][$parcours->codeParcours]=0;
						$noeudParcoursCommun->appendChild($noeudParcours);
					}
					
				$parcoursCommuns->appendChild($noeudParcoursCommun);
			}
		}
		$donnees->appendChild($parcoursCommuns);
	}
}	

			/*----- Vie scolaire -----*/

if (getSettingAOui("LSU_commentaire_vie_sco")) {
	if ($listeVieScoCommun->num_rows) {
		$viesScolairesCommuns = $xml->createElement('vies-scolaires-communs');
		while ($vieScoCommun = $listeVieScoCommun->fetch_object()) {
			$au_moins_un_commentaire_vie_sco=false;
			if(($LSUN_periodes_a_extraire=='toutes')||(in_array($vieScoCommun->periode, $LSUN_periodes))) {
				$noeudVieSco =  $xml->createElement('vie-scolaire-commun');
				if($vieScoCommun->periode < 10) {$num_periode = "0".$vieScoCommun->periode;} else {$num_periode = $vieScoCommun->periode;}
				$attributsVieSco = array('periode-ref'=>'P_'."$num_periode" , 'code-division'=>mb_substr("$vieScoCommun->classe", 0, $longueur_max_chaine_code_division, 'UTF-8'));
				foreach ($attributsVieSco as $cle=>$valeur) {
					$attVieSco = $xml->createAttribute($cle);
					$attVieSco->value = $valeur;
					$noeudVieSco->appendChild($attVieSco);
				}
				$tmp_chaine=nettoye_texte_vers_chaine($vieScoCommun->appreciation);
				$comVieSco = ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8'));
				//echo "-".$VieScoCommun."-";
				if (!$comVieSco) {
					$comVieSco = "-";
					$msgErreur .= "<p class='rouge'>La classe ".$vieScoCommun->classe." n'a pas de commentaire en vie scolaire en période $num_periode, vous devez vous assurer que c'est normal <em>(mais ce n'est pas bloquant)</em>.</p>";
				}
				$comVieScoCommun = $xml->createElement('commentaire', $comVieSco);

				$noeudVieSco->appendChild($comVieScoCommun);
				$viesScolairesCommuns->appendChild($noeudVieSco);
				$au_moins_un_commentaire_vie_sco=true;
			}
		}
		
		if($au_moins_un_commentaire_vie_sco) {
			$donnees->appendChild($viesScolairesCommuns);
		}
	}
	else {
		//$msgErreur .= "<p class='rouge'>La classe ".$vieScoCommun->classe." n'a pas de commentaire en vie scolaire.</p>";
		$msgErreur .= "<p class='rouge'>Pas de commentaires trouvés en vie scolaire <em>(mais ce n'est pas bloquant)</em>.</p>";
	}
}

			/*----- epis -----*/
if (getSettingValue("LSU_traite_EPI") != "n") {
			// 20180601
			$tab_EPI_disciplines=array();
			$tab_EPI_nom=array();
			$epis = $xml->createElement('epis');
			$listeEPICommun = getEPICommun();
			while ($epiCommun = $listeEPICommun->fetch_object()) { 
				$noeudEpiCommun = $xml->createElement('epi');
				$matieres = getMatieresEPICommun($epiCommun->id);
				$refDisciplines = "";
				$cpt_disc_epi=0;
				foreach ($matieres as $matiere) {
					$ref = "DI_".getMatiereOnMatiere($matiere["id_matiere"])->code_matiere.$matiere["modalite"];
					assureDisciplinePresente($ref);
					$refDisciplines .= $ref." ";
					$cpt_disc_epi++;

					// 20180601 : Liste des disciplines associées à l'EPI dans le module LSU de Gepi
					if((!isset($tab_EPI_disciplines["EPI_".$epiCommun->id]))||
					(!in_array($ref, $tab_EPI_disciplines["EPI_".$epiCommun->id]))) {
						$tab_EPI_disciplines["EPI_".$epiCommun->id][]=$ref;
					}
				}
				if($cpt_disc_epi<=1) {
					$msg_erreur_remplissage.="EPI n°".$epiCommun->id."&nbsp;: Un EPI nécessite au moins 2 matières associées. <a href='#ancre_EPI_".$epiCommun->id."'>Corriger</a> plus bas dans la page.<br /><br />";
				}

				if($epiCommun->intituleEpi=="") {
					$msg_erreur_remplissage.="EPI n°".$epiCommun->id."&nbsp;: Intitulé non défini. <a href='#ancre_EPI_".$epiCommun->id."'>Corrigez</a> dans la présente page.<br /><br />";
				}

				if($epiCommun->codeEPI=="") {
					$msg_erreur_remplissage.="EPI n°".$epiCommun->id."&nbsp;: Thématique non définie. <a href='#ancre_EPI_".$epiCommun->id."'>Corrigez</a> dans la présente page.<br /><br />";
				}

				if($refDisciplines=="") {
					$msg_erreur_remplissage.="EPI n°".$epiCommun->id."&nbsp;: Aucune matière n'est associée. <a href='#ancre_EPI_".$epiCommun->id."'>Corrigez</a> dans la présente page.<br /><br />";
				}

				if(nettoye_texte_vers_chaine($epiCommun->descriptionEpi)=="") {
					$msg_erreur_remplissage.="EPI n°".$epiCommun->id."&nbsp;: Description vide. <a href='#ancre_EPI_".$epiCommun->id."'>Corrigez</a> dans la présente page.<br /><br />";
				}

				// 20180601
				$tab_EPI_nom["EPI_".$epiCommun->id]=$epiCommun->intituleEpi;
				$attributsEpiCommun = array('id'=>"EPI_$epiCommun->id", 'intitule'=>"$epiCommun->intituleEpi", 'thematique'=>"$epiCommun->codeEPI", 'discipline-refs'=>"$refDisciplines");
				foreach ($attributsEpiCommun as $cle=>$valeur) {
					$attsEpiCommun = $xml->createAttribute($cle);
					$attsEpiCommun->value = $valeur;
					$noeudEpiCommun->appendChild($attsEpiCommun);
				}
				$noeudDexcriptionEpiCommun = $xml->createElement('description', nettoye_texte_vers_chaine($epiCommun->descriptionEpi));
				$noeudEpiCommun->appendChild($noeudDexcriptionEpiCommun);
				$epis->appendChild($noeudEpiCommun);
			}
		$donnees->appendChild($epis);
//}

			/*----- epis-groupes -----*/
//if (FALSE) {
			$episGroupes = $xml->createElement('epis-groupes');
			$creeEpisGroupes = FALSE;
			$listeEpisGroupes = getEpisGroupes();
			if ($listeEpisGroupes->num_rows) {
				$creeEpisGroupes = TRUE;
			}
			$tab_id_epi_groupes=array();
			while ($episGroupe = $listeEpisGroupes->fetch_object()) {
				if($episGroupe->id=="") {
					// 20170404
					$msg_erreur_remplissage.="<strong>ANOMALIE&nbsp;:</strong> Un AID a un identifiant vide&nbsp;: ".$episGroupe->nom." (<a href='../utilitaires/clean_tables.php#correction_tables_aid' target='_blank'>Corriger</a>)<br />";
				}
				else {
					if($LSUN_periodes_a_extraire=='toutes') {
						$epi_grp_a_prendre_en_compte=true;
					}
					else {
						$epi_grp_a_prendre_en_compte=false;
						//(in_array($vieScoCommun->periode, $LSUN_periodes))
						for($loop_tmp=0;$loop_tmp<count($LSUN_periodes);$loop_tmp++) {
							if($episGroupe->periode>=$LSUN_periodes[$loop_tmp]) {
								$epi_grp_a_prendre_en_compte=true;
								break;
							}
						}
					}
					if($epi_grp_a_prendre_en_compte) {
						$tab_EPI_groupe_disciplines=array();

						$noeudEpisGroupes = $xml->createElement('epi-groupe');
						//id="EPI_GROUPE_02"
						/*
						echo "<p>\$episGroupe->id=$episGroupe->id</p><pre>";
						print_r($episGroupe);
						echo "</pre>";
						*/
						if($episGroupe->nom=="") {
							$msg_erreur_remplissage.="<strong>ANOMALIE&nbsp;:</strong> Un AID a un nom vide&nbsp;: <a href='../aid/modif_fiches.php?aid_id=".$episGroupe->id."&indice_aid=".$episGroupe->indice_aid."&action=modif&retour=index2.php' target='_blank'>AID n°".$episGroupe->id."</a>.<br />";
							$tmp_nom_AID="AID n°".$episGroupe->id;
						}
						else {
							$tmp_nom_AID=$episGroupe->nom;
						}
						$attributsEpiGroupe = array('id'=>"EPI_GROUPE_".$episGroupe->id, 'intitule'=>$tmp_nom_AID, 'epi-ref'=>'EPI_'.$episGroupe->id_epi );
						foreach ($attributsEpiGroupe as $cle=>$valeur) {
							$attsEpiGroupe = $xml->createAttribute($cle);
							$attsEpiGroupe->value = $valeur;
					
							$noeudEpisGroupes->appendChild($attsEpiGroupe);
						}
						if(in_array("EPI_GROUPE_".$episGroupe->id, $tab_id_epi_groupes)) {
							$msg_erreur_remplissage.="<strong>".get_valeur_champ("lsun_epi_communs", "id='".$episGroupe->id_epi."'", "intituleEpi")."&nbsp;:</strong> L'AID <a href='../aid/modify_aid.php?aid_id=".$episGroupe->id_epi."' target='_blank'>".$episGroupe->nom."</a> est déjà associé à un autre EPI.<br />Un même AID ne peut pas être associé à plusieurs EPI; vous devez créer des catégories AID distinctes, y associer les AID correspondant et n'associer à l'EPI que les catégories AID appropriées.<br /><br />";
						}
						$tab_id_epi_groupes[]="EPI_GROUPE_".$episGroupe->id;
				
						// Commentaire → Résumé + appréciation du groupe
						$CommentaireEPI1 = trim(getResumeAid($episGroupe->id));
						//echo "\$CommentaireEPI1=$CommentaireEPI1<br />";
						$commentairesGroupe = getCommentaireGroupe($episGroupe->id,$episGroupe->periode);
						if ($commentairesGroupe->num_rows) {
							//echo "coucou ".trim($commentairesGroupe->fetch_object()->appreciation)."<br>";
							$CommentaireEPI1 .= " ".trim($commentairesGroupe->fetch_object()->appreciation);
					
						}
						$tmp_chaine=nettoye_texte_vers_chaine($CommentaireEPI1);
						// 20171208
						//$CommentaireEPI = ensure_utf8(mb_substr($tmp_chaine, 0, 600,'UTF-8'));
						$CommentaireEPI=tronque_chaine($tmp_chaine, 0, 600,'UTF-8');
						if(mb_strlen($CommentaireEPI,'UTF-8')!=mb_strlen($tmp_chaine,'UTF-8')) {
							$msg_erreur_remplissage.="Le commentaire/description de l'EPI n°".$episGroupe->id." 'intitulé ".$tmp_nom_AID." est trop long.<br />
							Il a été tronqué dans le XML pour&nbsp;:<br />
							<span style='color:orange'>".$CommentaireEPI."</span><br />
							<a href='../aid/modif_fiches.php?aid_id=".$episGroupe->id."&action=modif&retour=index2.php' target='_blank'>Modifiez le résumé dans les fiches des AID</a> si cela ne vous convient pas et refaites l'export.<br />
							NOTE&nbsp;: Ce commentaire est la concaténation du résumé de description de l'AID et de l'appréciation saisie pour le groupe-classe dans les bulletins.<br />";
						}
						//$CommentaireEPI = "AAA".ensure_utf8(mb_substr($tmp_chaine, 0, 500,'UTF-8'));
						//$CommentaireEPI = mb_substr(ensure_utf8(mb_substr($tmp_chaine, 0, 600,'UTF-8')), 0, 600,'UTF-8');
						//echo "<p style='color:orange'>DEBUG : ".$CommentaireEPI."</p><hr />";
						if ($CommentaireEPI) {
							$noeudEpisGroupesCommentaire = $xml->createElement('commentaire',$CommentaireEPI);
							$noeudEpisGroupes->appendChild($noeudEpisGroupesCommentaire);
						}
				

						$episGroupes->appendChild($noeudEpisGroupes);

						// 20190119
						$tab_effectifs['epi']["EPI_GROUPE_".$episGroupe->id]['intitule']=$tmp_nom_AID;

						// enseignants
						$noeudEnseigneDis = $xml->createElement('enseignants-disciplines');

						//$modaliteEns = getModaliteGroupeAP($episGroupe->id);
						$modaliteEns = getModaliteGroupe($episGroupe->id);

						if ($modaliteEns->num_rows>0) {
							while ($ensModalite = $modaliteEns->fetch_object()) {
								$noeudProf = $xml->createElement('enseignant-discipline');
								$attsMat =  $xml->createAttribute('discipline-ref');
								$attsMat->value = 'DI_'.$ensModalite->code_matiere.$ensModalite->modalite;
								$noeudProf->appendChild($attsMat);
								$prof = substr($ensModalite->numind,1);
								$attsProf =  $xml->createAttribute('enseignant-ref');
								$attsProf->value = 'ENS_'.$prof;
								$noeudProf->appendChild($attsProf);
								$noeudEnseigneDis->appendChild($noeudProf);

								// 20180601 : Liste des disciplines associées à l'AID correspondant à l'EPI courant
								if(!in_array($attsMat->value, $tab_EPI_groupe_disciplines)) {
									$tab_EPI_groupe_disciplines[]=$attsMat->value;
									//echo "\$tab_EPI_groupe_disciplines[]=$attsMat->value;<br />";
								}
							}
						}
						else {
							// Si il y a bien un professeur associé à l'EPI, il faut que ce soit un professeur avec une matière associée à l'EPI dans LSUN

							$sql="SELECT id_utilisateur FROM j_aid_utilisateurs WHERE id_aid='".$episGroupe->id."';";
							$test_prof_aid=mysqli_query($mysqli, $sql);
							if(mysqli_num_rows($test_prof_aid)==0) {
								$msg_erreur_remplissage.="EPI&nbsp;: Aucun professeur n'est associé à l'AID ".$episGroupe->nom."&nbsp;: <a href='../aid/modify_aid.php?flag=prof&aid_id=".$episGroupe->id."' target='_blank'>Corriger</a><br /><br />";
								//&indice_aid=1
							}
							else {
								$sql="SELECT * FROM j_aid_utilisateurs jau, 
											j_professeurs_matieres jpm, 
											lsun_j_epi_matieres ljem 
										WHERE jau.id_aid='".$episGroupe->id."' AND 
											jau.id_utilisateur=jpm.id_professeur AND 
											jpm.id_matiere=ljem.id_matiere AND 
											id_epi='".$episGroupe->id_epi."';";
								$test_prof_aid2=mysqli_query($mysqli, $sql);
								if(mysqli_num_rows($test_prof_aid2)==0) {
									$msg_erreur_remplissage.="EPI&nbsp;: Le ou les professeurs associé(s) à l'AID ".$episGroupe->nom." ne sont pas associés aux matières de l'EPI déclarées dans la présente page.<br />
									Vous pouvez ajouter des matières à l'EPI plus bas dans la page,<br />
									ou associer d'autres matières aux enseignants de l'AID pour correspondre aux matières déclarées dans l'EPI plus bas dans la page&nbsp;: ";
									while($lig_prof_aid=mysqli_fetch_object($test_prof_aid)) {
										$msg_erreur_remplissage.="<a href='../utilisateurs/modify_user.php?user_login=".$lig_prof_aid->id_utilisateur."' target='_blank'>".civ_nom_prenom($lig_prof_aid->id_utilisateur)."</a> - ";
									}
									$msg_erreur_remplissage.="<br /><br />";
								}
							}
						}

						// 20180601 : Comparaison de la liste des disciplines associées à l'EPI dans le module LSU de Gepi
						//            avec la liste des disciplines associées à l'AID correspondant à l'EPI courant

							// L'alimentation du $tab_EPI_groupe_disciplines semble foirer.
							// Il manque des disciplines correspondant à des ENS_... effectivement inscrits dans le noeud $noeudEnseigneDis

								/*
								echo "\$tab_EPI_groupe_disciplines<pre>";
								print_r($tab_EPI_groupe_disciplines);
								echo "</pre>";

								echo "\$tab_EPI_disciplines['EPI_$episGroupe->id_epi']<pre>";
								print_r($tab_EPI_disciplines['EPI_'.$episGroupe->id_epi]);
								echo "</pre>";

								echo "\$noeudEnseigneDis<pre>";
								print_r($noeudEnseigneDis);
								echo "</pre>";
								*/


						$noeudEpisGroupes->appendChild($noeudEnseigneDis);


						for($loop_epi=0;$loop_epi<count($tab_EPI_groupe_disciplines);$loop_epi++) {
							if(!in_array($tab_EPI_groupe_disciplines[$loop_epi], $tab_EPI_disciplines['EPI_'.$episGroupe->id_epi])) {
								$msg_erreur_remplissage.="La matière/modalité ".$tab_disciplines_modalites[$tab_EPI_groupe_disciplines[$loop_epi]]." associée à l'<a href='../aid/modify_aid.php?flag=prof&aid_id=".$episGroupe->id."' target='_blank'>AID ".$episGroupe->nom."</a> correspondant à l'EPI n°".'EPI_'.$episGroupe->id_epi." n'est pas déclarée dans la liste des matières associées à l'EPI <strong>".$tab_EPI_nom["EPI_".$episGroupe->id_epi]."</strong> dans le module LSU de Gepi <em>(<a href='#ancre_EPI_".$episGroupe->id_epi."'>plus bas dans la page</a>)</em><br />";
								// <a href='../utilisateurs/modify_user.php?user_login=".$lig_prof_aid->id_utilisateur."' target='_blank'>".civ_nom_prenom($lig_prof_aid->id_utilisateur)."</a> - 
								//aid/modify_aid.php?flag=prof&aid_id=22&indice_aid=11
							}
						}

						for($loop_epi=0;$loop_epi<count($tab_EPI_disciplines['EPI_'.$episGroupe->id_epi]);$loop_epi++) {
							if(!in_array($tab_EPI_disciplines['EPI_'.$episGroupe->id_epi][$loop_epi], $tab_EPI_groupe_disciplines)) {
								//$msg_erreur_remplissage.="La matière/modalité ".$tab_disciplines_modalites[$tab_EPI_disciplines['EPI_'.$episGroupe->id_epi][$loop_epi]]." déclarée dans la liste des matières associées à l'EPI <strong>".$tab_EPI_nom["EPI_".$episGroupe->id_epi]."</strong> dans le module LSU de Gepi <em>(<a href='#ancre_EPI_".$episGroupe->id_epi."'>plus bas dans la page</a>)</em> n'est assurée par aucun des professeurs déclarés dans l'<a href='../aid/modify_aid.php?flag=prof&aid_id=".$episGroupe->id."' target='_blank'>AID ".$episGroupe->nom."</a>.<br />";

								if(!isset($tab_disciplines_modalites[$tab_EPI_disciplines['EPI_'.$episGroupe->id_epi][$loop_epi]])) {
									$tmp_code_matiere=substr(substr($tab_EPI_disciplines['EPI_'.$episGroupe->id_epi][$loop_epi],0, strlen($tab_EPI_disciplines['EPI_'.$episGroupe->id_epi][$loop_epi])-1), 3);
									//$msg_erreur_remplissage.="\$tmp_code_matiere=$tmp_code_matiere<br />";
									$tab_disciplines_modalites[$tab_EPI_disciplines['EPI_'.$episGroupe->id_epi][$loop_epi]]=get_valeur_champ("matieres", "code_matiere='".$tmp_code_matiere."'", "nom_complet");
								}

								$msg_erreur_remplissage.="La matière/modalité ";
								//$msg_erreur_remplissage.='EPI_'.$episGroupe->id_epi.'<br />';
								//$msg_erreur_remplissage.="\$tab_EPI_disciplines['EPI_".$episGroupe->id_epi."'][$loop_epi]=".$tab_EPI_disciplines['EPI_'.$episGroupe->id_epi][$loop_epi].'<br />';
								//$msg_erreur_remplissage.=$tab_disciplines_modalites[$tab_EPI_disciplines['EPI_'.$episGroupe->id_epi][$loop_epi]].'<br />';
								//$msg_erreur_remplissage.="\$tab_EPI_nom['EPI_".$episGroupe->id_epi."']=".$tab_EPI_nom["EPI_".$episGroupe->id_epi].'<br />';
								$msg_erreur_remplissage.="<strong>".$tab_disciplines_modalites[$tab_EPI_disciplines['EPI_'.$episGroupe->id_epi][$loop_epi]]."</strong> déclarée dans la liste des matières associées à l'EPI <strong>".$tab_EPI_nom["EPI_".$episGroupe->id_epi]."</strong> dans le module LSU de Gepi <em>(<a href='#ancre_EPI_".$episGroupe->id_epi."'>plus bas dans la page</a>)</em> n'est assurée par aucun des professeurs déclarés dans l'<a href='../aid/modify_aid.php?flag=prof&aid_id=".$episGroupe->id."' target='_blank'>AID ".$episGroupe->nom."</a>.<br />";

								/*
								echo "<pre>";
								print_r($episGroupe);
								echo "</pre>";
								*/
								// <a href='../utilisateurs/modify_user.php?user_login=".$lig_prof_aid->id_utilisateur."' target='_blank'>".civ_nom_prenom($lig_prof_aid->id_utilisateur)."</a> - 
								//aid/modify_aid.php?flag=prof&aid_id=".$episGroupe->id."&indice_aid=11
							}
						}
					}
				}
			}
			if ($creeEpisGroupes) {
				$donnees->appendChild($episGroupes);
			}
		
}

if (getSettingValue("LSU_traite_AP") != "n") {
	$tab_acc_perso_groupes=array();
	$tab_acc_perso_groupes_ele_msg=array();
	$tab_acc_perso_groupes_info=array();

			/*----- acc-persos -----*/
	$listeApCommuns = getAPCommun();
	if ($listeApCommuns->num_rows) {
		
		$accPersos = $xml->createElement('acc-persos');
		
		while ($apCommun = $listeApCommuns->fetch_object()) {
			$noeudApCommun = $xml->createElement('acc-perso');
			$disciplines = getDisciplines($apCommun->id);
			$matieresAP = "";
			while ($matiere = $disciplines->fetch_object()) {
				$matieresAP .= "DI_".$matiere->id_enseignements.$matiere->modalite." ";
			}
			$attributsAPCommun = array('id'=>'ACC_PERSO_'.$apCommun->id , 'intitule'=>  mb_substr(trim($apCommun->intituleAP), 0, 150,'UTF-8') , 'discipline-refs'=>"$matieresAP");
			foreach ($attributsAPCommun as $cle=>$valeur) {
				$attsApCommun = $xml->createAttribute($cle);
				$attsApCommun->value = $valeur;
				$noeudApCommun->appendChild($attsApCommun);
			}
			$tmp_descriptionAP=trim(ensure_utf8(mb_substr(trim($apCommun->descriptionAP),0,600,'UTF-8')));
			if ($tmp_descriptionAP!="") {
				$noeudApDescription = $xml->createElement('description', nettoye_texte_vers_chaine($tmp_descriptionAP));
				$noeudApCommun->appendChild($noeudApDescription);
			}

			//descriptionAP
			$accPersos->appendChild($noeudApCommun);
		}
		$donnees->appendChild($accPersos);
	//}

		
			/*----- acc-persos-groupes -----*/

		$accPersosGroupes = $xml->createElement('acc-persos-groupes');
		$listeApGroupes = getApGroupes();
		while ($apGroupe = $listeApGroupes->fetch_object()) {
			$noeudApGroupes = $xml->createElement('acc-perso-groupe');
			$attributsEpiGroupe = array('id'=>"ACC_PERSO_GROUPE_".$apGroupe->id, 'intitule'=>$apGroupe->nom, 'acc-perso-ref'=>'ACC_PERSO_'.$apGroupe->id_ap );
			//echo " - AP ".$apGroupe->id. " ";
			foreach ($attributsEpiGroupe as $cle=>$valeur) {
				$attsEpiGroupe = $xml->createAttribute($cle);
				$attsEpiGroupe->value = $valeur;

				$noeudApGroupes->appendChild($attsEpiGroupe);
			}
			//On a que 1 commentaire de groupe dans l'export alors qu'on peut en avoir 1 par trimestre, on prend le dernier
			
			$commentairesGroupeAp = getCommentaireGroupe($apGroupe->id);

			if ($commentairesGroupeAp->num_rows) {
				$commentaires = "";

				//20170531
				// Commentaire → Résumé + appréciation du groupe
				$resumeAID=trim(getResumeAid($apGroupe->id));
				if($resumeAID!="") {
					$commentaires=$resumeAID."\n";
				}

				while ($commentaire = $commentairesGroupeAp->fetch_object()) {
					$commentaires .= trim($commentaire->appreciation)."\n";
				}
				if (trim($commentaires)) {
					$tmp_chaine=nettoye_texte_vers_chaine($commentaires);
					$noeudComGroupeAp = $xml->createElement('commentaire',ensure_utf8(mb_substr(trim($commentaires),0,600,'UTF-8')));
					$noeudApGroupes->appendChild($noeudComGroupeAp);
				}
			}
			
				
				
			
			// on ajoute les enseignants
			//print_r($apGroupe);
			//echo '<br>11<br>';
			$profMatiere = getModaliteGroupeAP($apGroupe->id);
			//print_r($profMatiere);
			//echo '<br><br>';
			if ($profMatiere->num_rows) {
				$noeudProfs = $xml->createElement('enseignants-disciplines');
				//print_r($profMatiere);
				while ($ensModalite = $profMatiere->fetch_object()) {
					$noeudProf = $xml->createElement('enseignant-discipline');
					$attsMat =  $xml->createAttribute('discipline-ref');
					//$matiere = getMatiereOnMatiere($ensModalite->matiere);
					$attsMat->value = 'DI_'.$ensModalite->code_matiere.$ensModalite->modalite;
					$noeudProf->appendChild($attsMat);
					$prof = substr(getUtilisateur($ensModalite->login)->numind,1);
					$attsProf =  $xml->createAttribute('enseignant-ref');
					$attsProf->value = 'ENS_'.$prof;
					$noeudProf->appendChild($attsProf);
					$noeudProfs->appendChild($noeudProf);
				}
				$noeudApGroupes->appendChild($noeudProfs);
				
				$accPersosGroupes->appendChild($noeudApGroupes);

				// 20190119
				$tab_effectifs['ap']["ACC_PERSO_GROUPE_".$apGroupe->id]['intitule']=$apGroupe->nom;

				$tab_acc_perso_groupes[]="ACC_PERSO_GROUPE_".$apGroupe->id;
			}
		}
		
		$donnees->appendChild($accPersosGroupes);	
	}
}

		// Initialisation des tableau des cycles et MEF associés
		$tab_cycle=array();
		$tab_domaines=array("CPD_FRA", "CPD_ETR", "CPD_SCI", "CPD_ART", "MET_APP", "FRM_CIT", "SYS_NAT", "REP_MND");

		/*----- Bilans périodiques -----*/
		$bilansPeriodiques = $xml->createElement('bilans-periodiques');

		$tab_id_eleve=array();
		$tab_eleve_sans_pp=array();
		$tab_classe_periode_sans_date_conseil=array();
		$eleves = getElevesExport();

		// Si retour vide, ajouter un test sur les éléments de la requête pour trouver où cela plante.
		if(mysqli_num_rows($eleves)==0) {
			$msg_erreur_remplissage.="Aucun élève n'a été trouvé. Commencez par valider le formulaire <strong><a href='index.php#responsables'>Responsables de l'établissement</a></strong> en cliquant sur <strong>Mettre à jour</strong><br />";
		}

		// 20181209
		if (isset($selectionClasse) && count($selectionClasse)){
			for($loop_tmp=0;$loop_tmp<count($selectionClasse);$loop_tmp++) {
				$sql="select 1=1 from lsun_responsables lr, classes c where lr.login=c.suivi_par and c.id='".$selectionClasse[$loop_tmp]."';";
				//echo "$sql<br />";
				$test=mysqli_query($mysqli, $sql);
				if(mysqli_num_rows($test)==0) {
					$msg_erreur_remplissage.="Le <strong>Responsable du suivi</strong> de la classe de <strong>".get_nom_classe($selectionClasse[$loop_tmp])."</strong> n'est pas à jour dans le module LSUN.<br />Commencez par valider le formulaire <strong><a href='index.php#responsables'>Responsables de l'établissement</a></strong> en cliquant sur <strong>Mettre à jour</strong><br />";
				}
			}
		}

		while ($eleve = $eleves->fetch_object()) {
			$exporteEleve = FALSE;
			$desAcquis = FALSE;
			$noeudBilanElevePeriodique = $xml->createElement('bilan-periodique');
			$respEtabElv = "RESP_".$eleve->id_resp_etab;

//echo "DEBUG: \$eleve->login=".$eleve->login."<br />";


			$profResponsable="";
			//$profResponsable = getUtilisateur($eleve->professeur)->numind;
			if(!isset($eleve->professeur)) {
				if(!in_array($eleve->login, $tab_eleve_sans_pp)) {
					$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> n'a pas de professeur principal.<br />Il faut <a href='../classes/classes_const.php?id_classe=".$eleve->id_classe."' target='_blank'>corriger</a>.<br /><br />";
					$tab_eleve_sans_pp[]=$eleve->login;
				}
			}
			else {
				$obj_u=getUtilisateur($eleve->professeur);
				if(!isset($obj_u->numind)) {
					if(!in_array($eleve->login, $tab_eleve_sans_pp)) {
						$msg_erreur_remplissage.="Le professeur principal ($eleve->professeur) associé à l'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> n'a pas de d'identifiant ID/NUMIND.<br />Il faut <a href='../utilisateurs/modify_user.php?login_user=".$eleve->professeur."' target='_blank'>corriger</a>.<br /><br />";
						$tab_eleve_sans_pp[]=$eleve->login;
					}
				}
				elseif(mb_strlen($obj_u->numind)<=1) {
					if(!in_array($eleve->login, $tab_eleve_sans_pp)) {
						$msg_erreur_remplissage.="Le professeur principal ($eleve->professeur) associé à l'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a un identifiant ID/NUMIND non standard (moins de deux caractères).<br />Il faut <a href='../utilisateurs/modify_user.php?login_user=".$eleve->professeur."' target='_blank'>corriger</a>.<br /><br />";
						$tab_eleve_sans_pp[]=$eleve->login;
					}
				}
				else {
					$profResponsable = substr($obj_u->numind,1);
				}
			}
			//$profResponsable = substr(getUtilisateur($eleve->professeur)->numind,1);
			//echo "\$profResponsable = substr(getUtilisateur($eleve->professeur)->numind,1)= = substr(".getUtilisateur($eleve->professeur)->numind.",1)=$profResponsable<br />";

			if(($LSUN_periodes_a_extraire=='toutes')||(in_array($eleve->periode, $LSUN_periodes))) {
				if($eleve->periode < 10) {$num_periode = "0".$eleve->periode;} else {$num_periode = $eleve->periode;}
				$datecolarite = dateScolarite($eleve->login, $eleve->periode);
				$date_conseil_classe = $eleve->date_conseil;
				if(($date_conseil_classe==NULL)||($date_conseil_classe=="0000-00-00")) {
					$date_conseil_classe=$eleve->date_verrou;
					if(!in_array($eleve->id_classe."|".$num_periode, $tab_classe_periode_sans_date_conseil)) {
						$msg_erreur_remplissage.="La date du conseil de classe de <strong>".get_nom_classe($eleve->id_classe)."</strong> en période <strong>".$num_periode."</strong> n'est pas valide.<br />Utilisation de la date de verrouillage de la période (".formate_date($date_conseil_classe).") à la place, mais si vous avez la date effective du conseil de classe, vous devriez corriger en <strong>compte scolarité</strong> dans la page de <strong>Verrouillage/déverrouillage des périodes</strong>.<br /><br />";
						$tab_classe_periode_sans_date_conseil[]=$eleve->id_classe."|".$num_periode;
					}
				}
				elseif($date_conseil_classe<$datecolarite) {
					$msg_erreur_remplissage.="La date du conseil de classe de <strong>".get_nom_classe($eleve->id_classe)."</strong> en période <strong>".$num_periode."</strong> (".formate_date($date_conseil_classe).") est antérieure à la date de scolarité (".formate_date($datecolarite).") de ".$eleve->prenom." ".$eleve->nom.".<br />Vous devriez corriger en <strong>compte scolarité</strong> dans la page de <strong>Verrouillage/déverrouillage des périodes</strong>.<br /><br />";
				}
				$attributsElevePeriode = array('prof-princ-refs'=>"ENS_".$profResponsable , 'eleve-ref'=>"EL_".$eleve->id_eleve , 'periode-ref'=>'P_'.$num_periode , 'date-conseil-classe'=>$date_conseil_classe , 'date-scolarite'=>"$datecolarite" , 'date-verrou'=>"$eleve->date_verrou" , 'responsable-etab-ref'=>"$respEtabElv" );
				foreach ($attributsElevePeriode as $cle=>$valeur) {
					$attsElevePeriode = $xml->createAttribute($cle);
					$attsElevePeriode->value = $valeur;

					$noeudBilanElevePeriodique->appendChild($attsElevePeriode);
					//echo "DEBUG:     Préparation du noeud: $cle : $valeur<br />";
				}

				$tab_disciplines_deja=array();

				$listeAcquis = $xml->createElement('liste-acquis');
				// 1 note ou Abs, Disp, NN et 1 appréciation
				$acquisEleves = getAcquisEleve($eleve->login, $eleve->periode);
				// <acquis discipline-ref="DI_030602" enseignant-refs="ENS_0123456789ABE" element-programme-refs="EP_05" moyenne-eleve="18/20" moyenne-structure="15/20">
				// <appreciation>Appréciation pour la matière espagnol</appreciation>
				// matieres_notes - matiere_element_programme - matieres_appreciations
				while ($acquisEleve = $acquisEleves->fetch_object()) {
					$desAcquis = TRUE;
					$noeudAcquis = $xml->createElement('acquis');
					$matiere = $acquisEleve->code_matiere;
					$moyenne = getMoyenne($acquisEleve->id_groupe, $eleve->periode);
					$modalite = getModalite($acquisEleve->id_groupe, $eleve->login, $acquisEleve->mef_code, $acquisEleve->code_matiere);
					$matiere = "DI_".$acquisEleve->code_matiere.$modalite;

					//echo "DEBUG:     Acquis: $acquisEleve->id_groupe<br />";

					if(($debug==1)&&($eleve->login==$login_debug)&&($acquisEleve->code_matiere==$code_matiere_debug)) {
						echo "$matiere groupe $acquisEleve->id_groupe<br />getModalite($acquisEleve->id_groupe, $eleve->login, $acquisEleve->mef_code, $acquisEleve->code_matiere)<br />";
					}
				

					if(in_array($matiere, $tab_disciplines_deja)) {
						$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a plusieurs enseignements de ".get_valeur_champ("matieres", "code_matiere='".$acquisEleve->code_matiere."'", "matiere")." avec la même modalité (".$modalite.").<br />Ce n'est pas possible.<br />Il faut <a href='../classes/eleve_options.php?login_eleve=".$eleve->login."&id_classe=".$eleve->id_classe."' target='_blank'>corriger</a> ou <a href='../gestion/gerer_modalites_election_enseignements.php#forcer_modalites_telles_matieres' target='_blank'>forcer les modalités globalement</a>.<br /><br />";
					}
					$tab_disciplines_deja[]=$matiere;

					if(!in_array($matiere, $tab_disciplines_global_deja)) {
						$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a un enseignement de ".get_valeur_champ("matieres", "code_matiere='".$acquisEleve->code_matiere."'", "matiere")." avec la modalité (".$modalite.") non déclaré au niveau global pour la classe.<br />La différence ne porte peut-être que sur la modalité.<br />Cela risque de provoquer une erreur&nbsp;: <a href='../classes/eleve_options.php?login_eleve=".$eleve->login."&id_classe=".$eleve->id_classe."' target='_blank'>Voir les enseignements de l'élève</a> ou <a href='../gestion/gerer_modalites_election_enseignements.php#forcer_modalites_telles_matieres' target='_blank'>contrôler et éventuellement forcer les modalités</a>.<br /><br />";
					}

					$donneesProfs = getProfGroupe ($acquisEleve->id_groupe);
					$prof = "";
					while ($profMatiere = $donneesProfs->fetch_object()) {
						$prof .= "ENS_".$profMatiere->numind." ";
					}
					// Pour virer l'espace de fin:
					$prof=trim($prof);
				
					$elementsProgramme = getEPeleve ($eleve->login, $acquisEleve->id_groupe,$eleve->periode );
					$elementProgramme = "";
					while ($elemProgramme = $elementsProgramme->fetch_object()) {
						$elementProgramme .= "EP_".$elemProgramme->idEP." ";
						//TODO VÉRIFIER que l'élément de programme existe
					}
					if (!$elementProgramme) {
						$elementProgramme = "EP_0000";
						$absenceEP = true;
						if(!isset($liste_absenceEP)) {
							$liste_absenceEP="";
						}
						$liste_absenceEP.="<span style='color:red'>".get_nom_prenom_eleve($eleve->login)." n'a pas d'élément de programme en ".$acquisEleve->id_matiere." en période ".$eleve->periode.".</span><br />";
						//$msgErreur .= get_nom_prenom_eleve($eleve->login)." n'a pas d'élément de programme en $acquisEleve->id_matiere. Vérifiez si c'est une erreur ou volontaire<br>";
						//$msgErreur .= $eleve->login." n'a pas d'élément de programme en $matiere, votre fichier n'est pas valide.<br>";
					}
					$attributsAcquis = array('discipline-ref'=>$matiere , 'enseignant-refs'=>$prof, 'element-programme-refs'=>$elementProgramme, 'moyenne-structure'=>$moyenne."/20");
				
					$note = $acquisEleve->note;
					if (intval($note)) {
						$attributsAcquis['moyenne-eleve'] = $note."/20";
					} else {
						$statutNote = getStatutNote($eleve->login,$acquisEleve->id_groupe,$eleve->periode);
						//if (getStatutNote($eleve->login,$acquisEleve->id_groupe,$eleve->periode)) {
						if ($statutNote) {
							$attributsAcquis['eleve-non-note'] = "1";
							switch ($statutNote) {
								case "disp":
									$statutNote = "Disp. ";
									break;
								case "abs":
									$statutNote = "Abs. ";
									break;
								case "-":
									$statutNote = "N N. ";
									break;
								default:
									$statutNote = $statutNote;
							}
							$acquisEleve->appreciation = $statutNote.$acquisEleve->appreciation;
						} else {
							$attributsAcquis['moyenne-eleve'] = $note."/20";
						}
					}
				
				
				
					foreach ($attributsAcquis as $cle=>$valeur) {
						$attsAcquis= $xml->createAttribute($cle);
						$attsAcquis->value = $valeur;
						$noeudAcquis->appendChild($attsAcquis);
					
					}
					$tmp_chaine=nettoye_texte_vers_chaine($acquisEleve->appreciation);
					$tmp_chaine=ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8'));
					if($tmp_chaine=='') {
						// 20190604
						$lien_bull_simp="";
						if(acces_impression_bulletins_simplifies($eleve->login)) {
							$tmp_tab_clas=get_class_dates_from_ele_login($eleve->login);
							if(isset($tmp_tab_clas[$eleve->periode]["id_classe"])) {
								$lien_bull_simp=" <a href='../prepa_conseil/edit_limite.php?id_classe=".$tmp_tab_clas[$eleve->periode]["id_classe"]."&amp;periode1=".$eleve->periode."&amp;periode2=".$eleve->periode."&amp;choix_edit=2&amp;login_eleve=".$eleve->login."&couleur_alterne=y' target='_blank' title=\"Voir dans un nouvel onglet les bulletins simplifiés.\"><img src='../images/icons/bulletin_16.png' class='icone16' alt='BullSimp' /></a>";
							}
						}

						$msg_erreur_remplissage.="L'appréciation de <strong>".get_nom_prenom_eleve($eleve->login)."</strong> est vide en ".get_info_grp($acquisEleve->id_groupe)." pour la période <strong>".$eleve->periode."</strong>".$lien_bull_simp.".<br />Le <strong>professeur</strong> peut corriger si la période est ouverte en saisie. Sinon, l'opération est possible avec un compte de statut <strong>secours</strong>.<br /><br />";

					}
					$noeudAcquisAppreciation = $xml->createElement('appreciation', $tmp_chaine);
					$noeudAcquis->appendChild($noeudAcquisAppreciation);
					$listeAcquis->appendChild($noeudAcquis);
				}
				
	// Abs, Disp ou NN sans appréciation → on exporte
				//$acquisEleve = getStatutSansApp($eleve->login,$acquisEleve->id_groupe,$eleve->periode);
				$noNotesSansApp = getStatutSansApp($eleve->login,$eleve->periode);
				while ($acquisEleve = $noNotesSansApp->fetch_object()) {
					$desAcquis = TRUE;
					$noeudAcquis = $xml->createElement('acquis');

					$matiere = $acquisEleve->code_matiere;
					$moyenne = getMoyenne($acquisEleve->id_groupe, $eleve->periode);
					$modalite = getModalite($acquisEleve->id_groupe, $eleve->login, $acquisEleve->mef_code, $acquisEleve->code_matiere);
					$matiere = "DI_".$acquisEleve->code_matiere.$modalite;

					if(in_array($matiere, $tab_disciplines_deja)) {
						$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a plusieurs enseignements de ".get_valeur_champ("matieres", "code_matiere='".$acquisEleve->code_matiere."'", "matiere")." avec la même modalité (".$modalite.").<br />Ce n'est pas possible.<br />Il faut <a href='../classes/eleve_options.php?login_eleve=".$eleve->login."&id_classe=".$eleve->id_classe."' target='_blank'>corriger</a>.<br /><br />";
					}
					$tab_disciplines_deja[]=$matiere;

					if(!in_array($matiere, $tab_disciplines_global_deja)) {
						$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a un enseignement de ".get_valeur_champ("matieres", "code_matiere='".$acquisEleve->code_matiere."'", "matiere")." avec la modalité (".$modalite.") non déclaré au niveau global pour la classe.<br />La différence ne porte peut-être que sur la modalité.<br />Cela risque de provoquer une erreur&nbsp;: <a href='../classes/eleve_options.php?login_eleve=".$eleve->login."&id_classe=".$eleve->id_classe."' target='_blank'>Voir les enseignements de l'élève</a> ou <a href='../gestion/gerer_modalites_election_enseignements.php#forcer_modalites_telles_matieres' target='_blank'>contrôler et éventuellement forcer les modalités</a>.<br /><br />";
					}

					$donneesProfs = getProfGroupe ($acquisEleve->id_groupe);
					$prof = "";
					while ($profMatiere = $donneesProfs->fetch_object()) {
						$prof .= "ENS_".$profMatiere->numind." ";
					}
					// Pour virer l'espace de fin:
					$prof=trim($prof);

					$elementsProgramme = getEPeleve ($eleve->login, $acquisEleve->id_groupe,$eleve->periode );
					$elementProgramme = "";
					while ($elemProgramme = $elementsProgramme->fetch_object()) {
						$elementProgramme .= "EP_".$elemProgramme->idEP." ";
						//TODO VÉRIFIER que l'élément de programme existe
					}
					if (!$elementProgramme) {
						$elementProgramme = "EP_0000";
						$absenceEP = true;
						if(!isset($liste_absenceEP)) {
							$liste_absenceEP="";
						}
						$liste_absenceEP.="<span style='color:red'>".get_nom_prenom_eleve($eleve->login)." n'a pas d'élément de programme en ".$acquisEleve->id_matiere." en période ".$eleve->periode.".</span><br />";
					}
					$attributsAcquis = array('discipline-ref'=>$matiere , 'enseignant-refs'=>$prof, 'element-programme-refs'=>$elementProgramme, 'moyenne-structure'=>$moyenne."/20");


					/*
					$note = $acquisEleve->note;
					if (intval($note)) {
						$attributsAcquis['moyenne-eleve'] = $note."/20";
						$acquisEleve->appreciation = $note."/20";
					} else {
						$statutNote = getStatutNote($eleve->login,$acquisEleve->id_groupe,$eleve->periode);
						//if (getStatutNote($eleve->login,$acquisEleve->id_groupe,$eleve->periode)) {
						if ($statutNote) {
							$attributsAcquis['eleve-non-note'] = "1";
							switch ($statutNote) {
								case "disp":
									$statutNote = "Disp. ";
									break;
								case "abs":
									$statutNote = "Abs. ";
									break;
								case "-":
									$statutNote = "N N. ";
									break;
								default:
									$statutNote = $statutNote;
							}
							//$acquisEleve->appreciation = $statutNote.$acquisEleve->appreciation;
							$acquisEleve->appreciation = $statutNote;
						} else {
							$attributsAcquis['moyenne-eleve'] = $note."/20";
							$acquisEleve->appreciation = $note."/20";
						}
					}
					*/

					$statutNote = getStatutNote($eleve->login,$acquisEleve->id_groupe,$eleve->periode);
				
					$attributsAcquis['eleve-non-note'] = "1";
					switch ($statutNote) {
						case "disp":
							$statutNote = "Dispensé ";
							break;
						case "abs":
							$statutNote = "Absent. ";
							break;
						case "-":
							$statutNote = "Non Noté. ";
							break;
						default:
							$statutNote = $statutNote;
					}
					$acquisEleve->appreciation = $statutNote;
				
				
				
					foreach ($attributsAcquis as $cle=>$valeur) {
						$attsAcquis= $xml->createAttribute($cle);
						$attsAcquis->value = $valeur;
						$noeudAcquis->appendChild($attsAcquis);
					}
					$tmp_chaine=nettoye_texte_vers_chaine($acquisEleve->appreciation);
					if(trim($tmp_chaine)=="") {

						// 20170511
						$lien_bull_simp="";
						if(acces_impression_bulletins_simplifies($eleve->login)) {
							$tmp_tab_clas=get_class_dates_from_ele_login($eleve->login);
							if(isset($tmp_tab_clas[$eleve->periode]["id_classe"])) {
								$lien_bull_simp=" <a href='../prepa_conseil/edit_limite.php?id_classe=".$tmp_tab_clas[$eleve->periode]["id_classe"]."&amp;periode1=".$eleve->periode."&amp;periode2=".$eleve->periode."&amp;choix_edit=2&amp;login_eleve=".$eleve->login."&couleur_alterne=y' target='_blank' title=\"Voir dans un nouvel onglet les bulletins simplifiés.\"><img src='../images/icons/bulletin_16.png' class='icone16' alt='BullSimp' /></a>";
							}
						}

						// Apparemment, on ne récupère que les enseignements avec appréciation non vide... Exact?
						$msg_erreur_remplissage.="L'appréciation de <strong>".get_nom_prenom_eleve($eleve->login)."</strong> est vide en ".get_info_grp($acquisEleve->id_groupe)." pour la période <strong>".$eleve->periode."</strong>".$lien_bull_simp.".<br />Le <strong>professeur</strong> peut corriger si la période est ouverte en saisie. Sinon, l'opération est possible avec un compte de statut <strong>secours</strong>.<br /><br />";
					}
					$noeudAcquisAppreciation = $xml->createElement('appreciation' ,ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8')));
					$noeudAcquis->appendChild($noeudAcquisAppreciation);
					$listeAcquis->appendChild($noeudAcquis);
				}


	// 1 note sans appréciation → on n'exporte pas sauf forcé avec '-' en commentaire
				if ($forceAppreciations) {
					$notesForcees = getNotesForcees($eleve->login,$eleve->periode);
					while ($acquisEleve = $notesForcees->fetch_object()) {
						$desAcquis = TRUE;
						$noeudAcquis = $xml->createElement('acquis');

						$matiere = $acquisEleve->code_matiere;
						$moyenne = getMoyenne($acquisEleve->id_groupe, $eleve->periode);
						$modalite = getModalite($acquisEleve->id_groupe, $eleve->login, $acquisEleve->mef_code, $acquisEleve->code_matiere);
						$matiere = "DI_".$acquisEleve->code_matiere.$modalite;

						//echo "$matiere : $moyenne<br />";

						if(in_array($matiere, $tab_disciplines_deja)) {
							$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a plusieurs enseignements de ".get_valeur_champ("matieres", "code_matiere='".$acquisEleve->code_matiere."'", "matiere")." avec la même modalité (".$modalite.").<br />Ce n'est pas possible.<br />Il faut <a href='../classes/eleve_options.php?login_eleve=".$eleve->login."&id_classe=".$eleve->id_classe."' target='_blank'>corriger</a>.<br /><br />";
						}
						$tab_disciplines_deja[]=$matiere;

						if(!in_array($matiere, $tab_disciplines_global_deja)) {
							$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a un enseignement de ".get_valeur_champ("matieres", "code_matiere='".$acquisEleve->code_matiere."'", "matiere")." avec la modalité (".$modalite.") non déclaré au niveau global pour la classe.<br />La différence ne porte peut-être que sur la modalité.<br />Cela risque de provoquer une erreur&nbsp;: <a href='../classes/eleve_options.php?login_eleve=".$eleve->login."&id_classe=".$eleve->id_classe."' target='_blank'>Voir les enseignements de l'élève</a> ou <a href='../gestion/gerer_modalites_election_enseignements.php#forcer_modalites_telles_matieres' target='_blank'>contrôler et éventuellement forcer les modalités</a>.<br /><br />";
						}

						$donneesProfs = getProfGroupe ($acquisEleve->id_groupe);
						$prof = "";
						while ($profMatiere = $donneesProfs->fetch_object()) {
							$prof .= "ENS_".$profMatiere->numind." ";
						}
						// Pour virer l'espace de fin:
						$prof=trim($prof);

						$elementsProgramme = getEPeleve ($eleve->login, $acquisEleve->id_groupe,$eleve->periode );
						$elementProgramme = "";
						while ($elemProgramme = $elementsProgramme->fetch_object()) {
							$elementProgramme .= "EP_".$elemProgramme->idEP." ";
							//TODO VÉRIFIER que l'élément de programme existe
						}
						if (!$elementProgramme) {
							$elementProgramme = "EP_0000";
							$absenceEP = true;
							if(!isset($liste_absenceEP)) {
								$liste_absenceEP="";
							}
							$liste_absenceEP.="<span style='color:red'>".get_nom_prenom_eleve($eleve->login)." n'a pas d'élément de programme en ".$acquisEleve->id_matiere." en période ".$eleve->periode.".</span><br />";
						}

						//$attributsAcquis = array('discipline-ref'=>$matiere , 'enseignant-refs'=>$prof, 'element-programme-refs'=>$elementProgramme, 'moyenne-structure'=>$moyenne."/20", 'eleve-non-note' => "1");
						$attributsAcquis = array('discipline-ref'=>$matiere , 'enseignant-refs'=>$prof, 'element-programme-refs'=>$elementProgramme, 'moyenne-structure'=>$moyenne."/20");


						$tmp_chaine="-";

						//++++++++++++++++++++++++++++++++++++++++++++++
						$note = $acquisEleve->note;
						if (intval($note)) {
							$attributsAcquis['moyenne-eleve'] = $note."/20";
							//$acquisEleve->appreciation = $note."/20";
						} else {
							$statutNote = getStatutNote($eleve->login,$acquisEleve->id_groupe,$eleve->periode);
							//if (getStatutNote($eleve->login,$acquisEleve->id_groupe,$eleve->periode)) {
							if ($statutNote) {
								$attributsAcquis['eleve-non-note'] = "1";
								switch ($statutNote) {
									case "disp":
										$statutNote = "Disp. ";
										break;
									case "abs":
										$statutNote = "Abs. ";
										break;
									case "-":
										$statutNote = "N N. ";
										break;
									default:
										$statutNote = $statutNote;
								}
								//$acquisEleve->appreciation = $statutNote.$acquisEleve->appreciation;
								//$acquisEleve->appreciation = $statutNote;
								$tmp_chaine=$statutNote;
							} else {
								$attributsAcquis['moyenne-eleve'] = $note."/20";
								//$acquisEleve->appreciation = $note."/20";
							}
						}
						//++++++++++++++++++++++++++++++++++++++++++++++

						foreach ($attributsAcquis as $cle=>$valeur) {
							$attsAcquis= $xml->createAttribute($cle);
							$attsAcquis->value = $valeur;
							$noeudAcquis->appendChild($attsAcquis);
						}
						//$tmp_chaine="-";

						// 20170511
						$lien_bull_simp="";
						if(acces_impression_bulletins_simplifies($eleve->login)) {
							$tmp_tab_clas=get_class_dates_from_ele_login($eleve->login);
							if(isset($tmp_tab_clas[$eleve->periode]["id_classe"])) {
								$lien_bull_simp=" <a href='../prepa_conseil/edit_limite.php?id_classe=".$tmp_tab_clas[$eleve->periode]["id_classe"]."&amp;periode1=".$eleve->periode."&amp;periode2=".$eleve->periode."&amp;choix_edit=2&amp;login_eleve=".$eleve->login."&couleur_alterne=y' target='_blank' title=\"Voir dans un nouvel onglet les bulletins simplifiés.\"><img src='../images/icons/bulletin_16.png' class='icone16' alt='BullSimp' /></a>";
							}
						}

						$msg_erreur_remplissage.="L'appréciation de <strong>".get_nom_prenom_eleve($eleve->login)."</strong> est vide en ".get_info_grp($acquisEleve->id_groupe)." pour la période <strong>".$eleve->periode."</strong>".$lien_bull_simp.".<br />Le <strong>professeur</strong> peut corriger si la période est ouverte en saisie. Sinon, l'opération est possible avec un compte de statut <strong>secours</strong>.<br /><br />";

						$noeudAcquisAppreciation = $xml->createElement('appreciation' ,ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8')));
						$noeudAcquis->appendChild($noeudAcquisAppreciation);
						$listeAcquis->appendChild($noeudAcquis);

					}
				
				}
			
			
	// 1 appréciation sans note ni Abs, Disp ou NN → on n'exporte pas
				if ($forceNotes) {
					$appForcees = getAppForcees($eleve->login,$eleve->periode);
				
					while ($acquisEleve = $appForcees->fetch_object()) {
						$desAcquis = TRUE;
						$noeudAcquis = $xml->createElement('acquis');

						$matiere = $acquisEleve->code_matiere;
						$moyenne = getMoyenne($acquisEleve->id_groupe, $eleve->periode);
						$modalite = getModalite($acquisEleve->id_groupe, $eleve->login, $acquisEleve->mef_code, $acquisEleve->code_matiere);
						$matiere = "DI_".$acquisEleve->code_matiere.$modalite;


						if(in_array($matiere, $tab_disciplines_deja)) {
							$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a plusieurs enseignements de ".get_valeur_champ("matieres", "code_matiere='".$acquisEleve->code_matiere."'", "matiere")." avec la même modalité (".$modalite.").<br />Ce n'est pas possible.<br />Il faut <a href='../classes/eleve_options.php?login_eleve=".$eleve->login."&id_classe=".$eleve->id_classe."' target='_blank'>corriger</a>.<br /><br />";
						}
						$tab_disciplines_deja[]=$matiere;

						if(!in_array($matiere, $tab_disciplines_global_deja)) {
							$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a un enseignement de ".get_valeur_champ("matieres", "code_matiere='".$acquisEleve->code_matiere."'", "matiere")." avec la modalité (".$modalite.") non déclaré au niveau global pour la classe.<br />La différence ne porte peut-être que sur la modalité.<br />Cela risque de provoquer une erreur&nbsp;: <a href='../classes/eleve_options.php?login_eleve=".$eleve->login."&id_classe=".$eleve->id_classe."' target='_blank'>Voir les enseignements de l'élève</a> ou <a href='../gestion/gerer_modalites_election_enseignements.php#forcer_modalites_telles_matieres' target='_blank'>contrôler et éventuellement forcer les modalités</a>.<br /><br />";
						}


						$donneesProfs = getProfGroupe ($acquisEleve->id_groupe);
						$prof = "";
						while ($profMatiere = $donneesProfs->fetch_object()) {
							$prof .= "ENS_".$profMatiere->numind." ";
						}
						// Pour virer l'espace de fin:
						$prof=trim($prof);

						$elementsProgramme = getEPeleve ($eleve->login, $acquisEleve->id_groupe,$eleve->periode );
						$elementProgramme = "";
						while ($elemProgramme = $elementsProgramme->fetch_object()) {
							$elementProgramme .= "EP_".$elemProgramme->idEP." ";
							//TODO VÉRIFIER que l'élément de programme existe
						}
						if (!$elementProgramme) {
							$elementProgramme = "EP_0000";
							$absenceEP = true;
							if(!isset($liste_absenceEP)) {
								$liste_absenceEP="";
							}
							$liste_absenceEP.="<span style='color:red'>".get_nom_prenom_eleve($eleve->login)." n'a pas d'élément de programme en ".$acquisEleve->id_matiere." en période ".$eleve->periode.".</span><br />";
							}
						$attributsAcquis = array('discipline-ref'=>$matiere , 'enseignant-refs'=>$prof, 'element-programme-refs'=>$elementProgramme, 'moyenne-structure'=>$moyenne."/20", 'eleve-non-note' => "1");

						foreach ($attributsAcquis as $cle=>$valeur) {
							$attsAcquis= $xml->createAttribute($cle);
							$attsAcquis->value = $valeur;
							$noeudAcquis->appendChild($attsAcquis);
						}
						$tmp_chaine=nettoye_texte_vers_chaine($acquisEleve->appreciation);


						// 20170511
						$lien_bull_simp="";
						if(acces_impression_bulletins_simplifies($eleve->login)) {
							$tmp_tab_clas=get_class_dates_from_ele_login($eleve->login);
							if(isset($tmp_tab_clas[$eleve->periode]["id_classe"])) {
								$lien_bull_simp=" <a href='../prepa_conseil/edit_limite.php?id_classe=".$tmp_tab_clas[$eleve->periode]["id_classe"]."&amp;periode1=".$eleve->periode."&amp;periode2=".$eleve->periode."&amp;choix_edit=2&amp;login_eleve=".$eleve->login."&couleur_alterne=y' target='_blank' title=\"Voir dans un nouvel onglet les bulletins simplifiés.\"><img src='../images/icons/bulletin_16.png' class='icone16' alt='BullSimp' /></a>";
							}
						}

						$msg_erreur_remplissage.="La note de <strong>".get_nom_prenom_eleve($eleve->login)."</strong> est vide en ".get_info_grp($acquisEleve->id_groupe)." pour la période <strong>".$eleve->periode."</strong>".$lien_bull_simp.".<br />Le <strong>professeur</strong> peut corriger si la période est ouverte en saisie. Sinon, l'opération est possible avec un compte de statut <strong>secours</strong>.<br /><br />";

						$noeudAcquisAppreciation = $xml->createElement('appreciation' ,ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8')));
						$noeudAcquis->appendChild($noeudAcquisAppreciation);
						$listeAcquis->appendChild($noeudAcquis);

					}
				
				}

				$noeudBilanElevePeriodique->appendChild($listeAcquis);


				// 20200219
				/*
				<competences-numeriques comp-num-commun-refs="CNC_02">
					<appreciation-num>
						Appréciation littérale pour l'élève Grenelle Victor pour les compétences 
						numériques du T1. Présente les projets réalisés en spécifiant les outils mobilisés et 
						l’implication globale de l'élève.
					</appreciation-num>
					<competence-num code="CN_INF_MEN" niveau="1" />
					<competence-num code="CN_INF_GER" niveau="2" />
					<competence-num code="CN_INF_TRA" niveau="3" />
					<competence-num code="CN_COM_INT" niveau="1" />
					<competence-num code="CN_COM_PAR" niveau="2" />
				</competences-numeriques>
				*/
				if ((getSettingValue("LSU_Competences_Numeriques") != "n")&&($LSUN_version_xsd>=20200301)) {
					//$eleve->login,$eleve->periode

					$mef_code_ele=$eleve->mef_code;
					if(!isset($tab_cycle[$mef_code_ele])) {
						$tmp_tab_cycle_niveau=calcule_cycle_et_niveau($mef_code_ele, "", "");
						$cycle_eleve_courant=$tmp_tab_cycle_niveau["mef_cycle"];
						$niveau_eleve_courant=$tmp_tab_cycle_niveau["mef_niveau"];
						$tab_cycle[$mef_code_ele]=$cycle_eleve_courant;
					}

					$ine_ele=get_valeur_champ('eleves', "login='".$eleve->login."'", 'no_gep');

					$synthese='';
					$sql="SELECT * FROM socle_eleves_syntheses_numeriques WHERE ine='".$ine_ele."' AND cycle='".$tab_cycle[$mef_code_ele]."' AND periode='".$eleve->periode."' AND annee='".$millesime."';";
					//echo "$sql<br />";
					$test=mysqli_query($GLOBALS["mysqli"], $sql);
					if(mysqli_num_rows($test)>0) {
						$lig=mysqli_fetch_object($test);
						$synthese=$lig->synthese;
					}

					$tmp_tab_comp_num=array();
					$sql="SELECT * FROM socle_eleves_competences_numeriques WHERE ine='".$ine_ele."' AND cycle='".$tab_cycle[$mef_code_ele]."' AND periode='".$eleve->periode."' AND annee='".$millesime."';";
					//echo "$sql<br />";
					$test=mysqli_query($GLOBALS["mysqli"], $sql);
					if(mysqli_num_rows($test)>0) {
						while($lig=mysqli_fetch_object($test)) {
							$tmp_tab_comp_num[$lig->code_competence]=$lig->niveau_maitrise;
						}
					}

					$tmp_id_classe='';
					$sql="SELECT id_classe FROM j_eleves_classes WHERE login='".$eleve->login."' AND periode='".$eleve->periode."';";
					//echo "$sql<br />";
					$test=mysqli_query($GLOBALS["mysqli"], $sql);
					if(mysqli_num_rows($test)>0) {
						$lig=mysqli_fetch_object($test);
						$tmp_id_classe=$lig->id_classe;
					}

					if(($tmp_id_classe!='')&&
					(($synthese!='')||(count($tmp_tab_comp_num)>0))) {
						$noeudCompetencesNumeriques=$xml->createElement('competences-numeriques');
						$attsCompNum = $xml->createAttribute('comp-num-commun-refs');
						$attsCompNum->value = "CNC_".$tmp_id_classe;
						$noeudCompetencesNumeriques->appendChild($attsCompNum);

						if($synthese!='') {
							$tmp_chaine=nettoye_texte_vers_chaine($synthese);
							$synthese = ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8'));

							$appCompNum= $xml->createElement('appreciation-num', $synthese);
							$noeudCompetencesNumeriques->appendChild($appCompNum);
						}

						if(count($tmp_tab_comp_num)>0) {
							foreach($tmp_tab_comp_num as $code_competence => $niveau_maitrise) {
								$noeudCompNum=$xml->createElement('competence-num');

								$attsCompNum = $xml->createAttribute('code');
								$attsCompNum->value = $code_competence;
								$noeudCompNum->appendChild($attsCompNum);

								$attsCompNum = $xml->createAttribute('niveau');
								$attsCompNum->value = $niveau_maitrise;
								$noeudCompNum->appendChild($attsCompNum);

								$noeudCompetencesNumeriques->appendChild($noeudCompNum);
							}
						}

						$noeudBilanElevePeriodique->appendChild($noeudCompetencesNumeriques);
					}
				}


				if ((getSettingValue("LSU_traite_EPI") != "n") && (getSettingValue("LSU_traite_EPI_Elv") != "n")) {
					// non obligatoire
					$episEleve = getAidEleve($eleve->login, $_EPI, $eleve->periode);
					if ($episEleve->num_rows) {
						//echo "<br /><p>$eleve->login EPI: $_EPI en période $eleve->periode<br />";
						//var_dump($episEleve);
						$listeEpisEleve = $xml->createElement('epis-eleve');
						$existeEpi = false;
						while ($epiEleve = $episEleve->fetch_object()) {
							/*
							echo "<pre>";
							print_r($epiEleve);
							echo "</pre>";
							*/
							//on verifie que le groupe est bien déclaré
							if (!verifieGroupeEPI($epiEleve->id_aid)) {
								$complement_aid="";
								$sql="SELECT DISTINCT id_aid FROM j_aid_eleves jae, aid a WHERE a.id=jae.id_aid AND jae.indice_aid='".$epiEleve->indice_aid."' AND jae.login='".$eleve->login."';";
								$res_aid=mysqli_query($mysqli, $sql);
								if(mysqli_num_rows($res_aid)>0) {
									$complement_aid=" (AID";
									while($lig_aid=mysqli_fetch_object($res_aid)) {
										$complement_aid.=" n°<a href='../aid/modif_fiches.php?aid_id=".$lig_aid->id_aid."&indice_aid=".$epiEleve->indice_aid."&action=modif&retour=index2.php' target='_blank'>".$lig_aid->id_aid."</a>";
									}
									$complement_aid.=")";
								}
								$msgErreur .= "<p class='rouge' title=\"Si il s'agit d'un AID projet d'EPI finalement non réalisé, vous pouvez ne pas tenir compte de cette alerte. En revanche, si c'est un oubli de déclaration d'EPI dans la présente page, corrigez l'oubli plus bas dans cette page.\">".$eleve->nom." ".$eleve->prenom." a dans les AID un EPI qui n'est pas déclaré en administrateur dans la présente page, il ne sera pas exporté (<a href='../aid/config_aid.php?indice_aid=".$epiEleve->indice_aid."' target='_blank'>Catégorie AID ".$epiEleve->indice_aid."</a>".$complement_aid.").</p>";
							} else {
								$noeudEpiEleve = $xml->createElement('epi-eleve');
								$attsEpisEleve = $xml->createAttribute('epi-groupe-ref');
								$attsEpisEleve->value = "EPI_GROUPE_".$epiEleve->id_aid;
								$noeudEpiEleve->appendChild($attsEpisEleve);
								$existeEpi = TRUE;
								$commentaireEpiElv = getCommentaireAidElv($eleve->login, $epiEleve->id_aid, $eleve->periode);
								if ($commentaireEpiElv->num_rows) {
									$tmp_chaine=nettoye_texte_vers_chaine($commentaireEpiElv->fetch_object()->appreciation);
									$comm = ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8'));
									// 20171208
									//$comm = "BBB".ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8'));
									if ($comm) {
										$noeudComEpiEleve = $xml->createElement('commentaire', $comm);
										$noeudEpiEleve->appendChild($noeudComEpiEleve);
									}
								}
								$listeEpisEleve->appendChild($noeudEpiEleve);

								// 20190119
								if(isset($tab_classe_ele[$eleve->login][$eleve->periode])) {
									if(!isset($tab_effectifs['epi']["EPI_GROUPE_".$epiEleve->id_aid]['periodes'][$eleve->periode]['classes'][$tab_classe_ele[$eleve->login][$eleve->periode]]['effectif'])) {
										$tab_effectifs['epi']["EPI_GROUPE_".$epiEleve->id_aid]['periodes'][$eleve->periode]['classes'][$tab_classe_ele[$eleve->login][$eleve->periode]]['effectif']=0;
									}
									$tab_effectifs['epi']["EPI_GROUPE_".$epiEleve->id_aid]['periodes'][$eleve->periode]['classes'][$tab_classe_ele[$eleve->login][$eleve->periode]]['effectif']++;
								}
							}
						
						}
						if ($existeEpi) {
							$noeudBilanElevePeriodique->appendChild($listeEpisEleve);
						}
					
					}
				}
			
				if ((getSettingValue("LSU_traite_AP") != "n") && (getSettingValue("LSU_traite_AP_Elv") != "n")) {
					// non obligatoire
					$apEleve = getAidEleve($eleve->login, $_AP, $eleve->periode);
					if ($apEleve->num_rows) {
						$listeAccPersosEleve = $xml->createElement('acc-persos-eleve');
						$au_moins_un_aid=false;
						while ($accPersosEleve = $apEleve->fetch_object()) {
							if(in_array("ACC_PERSO_GROUPE_".$accPersosEleve->id_aid, $tab_acc_perso_groupes)) {
								$au_moins_un_aid=true;
								$noeudAPEleve = $xml->createElement('acc-perso-eleve');
								$attsAPEleve = $xml->createAttribute('acc-perso-groupe-ref');
								$attsAPEleve->value = "ACC_PERSO_GROUPE_".$accPersosEleve->id_aid;
								$noeudAPEleve->appendChild($attsAPEleve);
						
								$commentaireAPEleve = getCommentaireAidElv($eleve->login, $accPersosEleve->id_aid, $eleve->periode);
								if ($commentaireAPEleve->num_rows) {
									$tmp_chaine=nettoye_texte_vers_chaine($commentaireAPEleve->fetch_object()->appreciation);
									$comm = ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8'));
									if ($comm) {
										$noeudComApEleve = $xml->createElement('commentaire', $comm);
										$noeudAPEleve->appendChild($noeudComApEleve);
									}

								}

								// 20190119
								if(isset($tab_classe_ele[$eleve->login][$eleve->periode])) {
									if(!isset($tab_effectifs['ap']["ACC_PERSO_GROUPE_".$accPersosEleve->id_aid]['periodes'][$eleve->periode]['classes'][$tab_classe_ele[$eleve->login][$eleve->periode]]['effectif'])) {
										$tab_effectifs['ap']["ACC_PERSO_GROUPE_".$accPersosEleve->id_aid]['periodes'][$eleve->periode]['classes'][$tab_classe_ele[$eleve->login][$eleve->periode]]['effectif']=0;
									}
									$tab_effectifs['ap']["ACC_PERSO_GROUPE_".$accPersosEleve->id_aid]['periodes'][$eleve->periode]['classes'][$tab_classe_ele[$eleve->login][$eleve->periode]]['effectif']++;
								}


								$listeAccPersosEleve->appendChild($noeudAPEleve);
							}
							elseif(!in_array($eleve->login."_".$accPersosEleve->id_aid, $tab_acc_perso_groupes_ele_msg)) {
								/*
								echo "<pre>";
								print_r($accPersosEleve);
								echo "</pre>";
								*/
								if(!array_key_exists($accPersosEleve->id_aid, $tab_acc_perso_groupes_info)) {
									$tab_acc_perso_groupes_info[$accPersosEleve->id_aid]=get_valeur_champ("aid","id='".$accPersosEleve->id_aid."'","nom");
								}
								$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> a un AID (<a href='../aid/index2.php?indice_aid=".$accPersosEleve->indice_aid."' target='_blank'>".$tab_acc_perso_groupes_info[$accPersosEleve->id_aid]."</a>) de type AP qui n'est pas déclaré dans le présent module LSU.<br />Déclarez l'AP dans la présente page et rattachez-y la catégorie de l'AID, ou ne tenez pas compte de l'alerte si cet AID ne doit pas être exporté.<br /><br />";
								$tab_acc_perso_groupes_ele_msg[]=$eleve->login."_".$accPersosEleve->id_aid;
							}
						}
						if($au_moins_un_aid) {
							$noeudBilanElevePeriodique->appendChild($listeAccPersosEleve);
						}
					}
				}
			
				if ((getSettingValue("LSU_Parcours") != "n") && (getSettingValue("LSU_ParcoursElv") != "n")) {
					// non obligatoire
					$parcoursEleve = getAidEleve($eleve->login, $_PARCOURS, $eleve->periode);
					if ($parcoursEleve->num_rows) {
						$listeParcoursEleve = $xml->createElement('liste-parcours');
						$creeParcours = FALSE;
						while ($parcoursElv = $parcoursEleve->fetch_object()) {
							$verifieParcoursOuvert = parcoursOuvert($parcoursElv->id_aid, $eleve->periode);
							if ($verifieParcoursOuvert) {
								$typeParcoursEleve = getCodeParcours($parcoursElv->id_aid);
								if ($typeParcoursEleve->num_rows) {
									$typeParcoursEleve = $typeParcoursEleve->fetch_object();

									// Commentaire → Résumé + appréciation du groupe
									$resumeAID=trim(getResumeAid($parcoursElv->id_aid));
									$commentaireEleve=$resumeAID;

									$res_commentaireEleve = getCommentaireEleveParcours($eleve->login,$parcoursElv->id_aid, $eleve->periode);//
									if ($res_commentaireEleve->num_rows>0) {
										$tmp_chaine=nettoye_texte_vers_chaine($res_commentaireEleve->fetch_object()->appreciation);
										$commentaireEleve.=ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8'));
									}

									if (strlen($commentaireEleve)) {
										$noeudParcoursEleve = $xml->createElement('parcours',$commentaireEleve);
										$attsParcoursEleve = $xml->createAttribute('code');
										$attsParcoursEleve->value = $typeParcoursEleve->codeParcours;
										$noeudParcoursEleve->appendChild($attsParcoursEleve);
										$listeParcoursEleve->appendChild($noeudParcoursEleve);
										$creeParcours = TRUE;

										// 20190118
										if(isset($tab_classe_ele[$eleve->login][$eleve->periode])) {
											$tmp_id_classe=$tab_classe_ele[$eleve->login][$eleve->periode];
											$tab_effectifs['parcours'][$tmp_id_classe][$eleve->periode][$typeParcoursEleve->codeParcours]++;
										}
									}

								}
							}
						}
						if ($creeParcours) {
							$noeudBilanElevePeriodique->appendChild($listeParcoursEleve);
						}
					
					}
				}

				if (getSettingAOui("LSU_traiteModSpeElv")) {

					/*
						<modalites-accompagnement>
							<modalite-accompagnement code="PAP" />
							<modalite-accompagnement code="PPS" />
							<modalite-accompagnement code="PPRE">
								<complement-ppre>Complément de la modalité PPRE</complement-ppre>
							</modalite-accompagnement>
						</modalites-accompagnement>
					*/
					//echo "Modalités d'accompagnement pour ".$eleve->login." en période ".$eleve->periode."<br />";
					$tab_modalites_accompagnement_eleve=get_tab_modalites_accompagnement_eleve($eleve->login, $eleve->periode);
					if(count($tab_modalites_accompagnement_eleve)>0) {
						//echo count($tab_modalites_accompagnement_eleve);
						$modalitesAccompagnement = $xml->createElement('modalites-accompagnement');
						for($loop_modalite=0;$loop_modalite<count($tab_modalites_accompagnement_eleve);$loop_modalite++) {
							$modaliteAcc = $xml->createElement('modalite-accompagnement');
							$attsModaliteAcc = $xml->createAttribute('code');
							$attsModaliteAcc->value = $tab_modalites_accompagnement_eleve[$loop_modalite]["code"];
							$modaliteAcc->appendChild($attsModaliteAcc);

							if(($tab_modalites_accompagnement_eleve[$loop_modalite]["code"]=="PPRE")&&
							($tab_modalites_accompagnement_eleve[$loop_modalite]["commentaire"]!="")) {
								$tmp_chaine=nettoye_texte_vers_chaine($tab_modalites_accompagnement_eleve[$loop_modalite]["commentaire"]);
								if(trim($tmp_chaine)!="") {
									$complement_ppre=$xml->createElement('complement-ppre' ,ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8')));
									$modaliteAcc->appendChild($complement_ppre);
								}
							}

							if($LSUN_version_xsd!=20171009) {
								// 20180210 : Modalité d'accompagnement CTR Contrat de réussite
								if(($tab_modalites_accompagnement_eleve[$loop_modalite]["code"]=="CTR")&&
								($tab_modalites_accompagnement_eleve[$loop_modalite]["commentaire"]!="")) {
									$tmp_chaine=nettoye_texte_vers_chaine($tab_modalites_accompagnement_eleve[$loop_modalite]["commentaire"]);
									if(trim($tmp_chaine)!="") {
										$complement_CTR=$xml->createElement('complement-contrat-reussite' ,ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8')));
										$modaliteAcc->appendChild($complement_CTR);
									}
								}
							}

							$modalitesAccompagnement->appendChild($modaliteAcc);

							// 20190119
							if(!isset($tab_effectifs['accompagnement'][$tab_modalites_accompagnement_eleve[$loop_modalite]["code"]][$eleve->periode])) {
								$tab_effectifs['accompagnement'][$tab_modalites_accompagnement_eleve[$loop_modalite]["code"]][$eleve->periode]=0;
							}
							$tab_effectifs['accompagnement'][$tab_modalites_accompagnement_eleve[$loop_modalite]["code"]][$eleve->periode]++;
						}

						$noeudBilanElevePeriodique->appendChild($modalitesAccompagnement);
					}
					//echo "<br />";
				}

				$avis_conseil_extrait=true;
				$retourAvisElv=getAppConseil($eleve->login , $eleve->periode);
				if ($retourAvisElv->num_rows) {
					$exporteEleve = true;
					$avisElv = $retourAvisElv->fetch_object()->avis;
					$avisConseil = $avisElv;
					if(trim($avisConseil)=="") {
						$exporteEleve = false;
						$avis_conseil_extrait=false;

						$lien_bull_simp="";
						if(acces_impression_bulletins_simplifies($eleve->login)) {
							$tmp_tab_clas=get_class_dates_from_ele_login($eleve->login);
							if(isset($tmp_tab_clas[$eleve->periode]["id_classe"])) {
								$lien_bull_simp=" <a href='../prepa_conseil/edit_limite.php?id_classe=".$tmp_tab_clas[$eleve->periode]["id_classe"]."&amp;periode1=".$eleve->periode."&amp;periode2=".$eleve->periode."&amp;choix_edit=2&amp;login_eleve=".$eleve->login."&couleur_alterne=y' target='_blank' title=\"Voir dans un nouvel onglet les bulletins simplifiés.\"><img src='../images/icons/bulletin_16.png' class='icone16' alt='BullSimp' /></a>";
							}
						}

						$msg_erreur_remplissage.="Aucun avis du conseil de classe n'est saisi pour <strong>".get_nom_prenom_eleve($eleve->login)."</strong> pour la période <strong>".$eleve->periode."</strong>".$lien_bull_simp.".<br />Les saisies concernant cet élève ne seront pas extraites et donc pas remontées vers LSU.<br />Un compte scolarité, professeur principal,... <em>(selon les droits d'accès paramétrés)</em> peut corriger si la période est ouverte en saisie. Sinon, l'opération est possible avec un compte de statut <strong>secours</strong>.<br /><br />";
					}
					else {
						$tmp_chaine=nettoye_texte_vers_chaine($avisConseil);
						$acquisConseils = $xml->createElement('acquis-conseils', ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8')));
						$noeudBilanElevePeriodique->appendChild($acquisConseils);
					}
				}

				// Ajouter un paramètre avec-devoirs-faits
				if($LSUN_version_xsd!=20171009) {
					// 20180210: Devoirs faits
					$tmp_chaine='';
					// Chercher un AID de type Devoirs faits...
					// aid_config where type_aid='4'
					$sql="SELECT aa.appreciation FROM aid_appreciations aa, 
								aid a, 
								aid_config ac 
							WHERE aa.id_aid=a.id AND 
								aa.indice_aid=a.indice_aid AND 
								a.indice_aid=ac.indice_aid AND 
								ac.type_aid='4' AND 
								aa.periode='".$eleve->periode."' AND 
								aa.login='".$eleve->login."' AND 
								aa.appreciation!='' 
							ORDER BY a.numero, a.nom;";
					/*
					if($eleve->login=='delalonq') {
						echo "$sql<br />";
					}
					*/
					//echo "$sql<br />";
					$res_df=mysqli_query($mysqli, $sql);
					/*
					if(mysqli_num_rows($res_df)==0) {
						$tmp_chaine='-';
					}
					else {
					*/
					if(mysqli_num_rows($res_df)>0) {
						$tmp_chaine='';
						while($lig_df=mysqli_fetch_object($res_df)) {
							if($tmp_chaine!='') {
								if(preg_match("/\.$/", $tmp_chaine)) {
									$tmp_chaine.=' ';
								}
								else {
									$tmp_chaine.='. ';
								}
							}
							$tmp_chaine.=$lig_df->appreciation;
						}
						//echo "tmp_chaine=$tmp_chaine<br />";
					}
					if($tmp_chaine!='') {
						$noeudDevoirsFaits = $xml->createElement('devoirs-faits', ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8')));
						//echo "PLOP<br />";
						$noeudBilanElevePeriodique->appendChild($noeudDevoirsFaits);

						// 20190119
						if(!isset($tab_effectifs['devoirs_faits'])) {
							$tab_effectifs['devoirs_faits']=0;
						}
						$tab_effectifs['devoirs_faits']++;
					}
				}

				//$retardEleve = getRetardsEleve($eleve->login , $eleve->periode)->fetch_object();
				$retardEleve = getAbsencesEleve($eleve->login , $eleve->periode);
				$vieScolaire = $xml->createElement('vie-scolaire');
				$retardsJustifies = $retardEleve['absences'] - $retardEleve['nj'];

				//$attributsVieScolaire = array('nb-retards'=>$retardEleve->nb_retards , 'nb-abs-justifiees'=>$retardsJustifies, 'nb-abs-injustifiees'=>$retardEleve->non_justifie);
				if(!preg_match("/^[0-9]{1,}$/", $retardEleve['retards'])) {
					$msg_erreur_remplissage.="Le nombre de retards pour <strong>".get_nom_prenom_eleve($eleve->login)."</strong> en période <strong>".$eleve->periode."</strong> est invalide (".$retardEleve['retards'].").<br />Valeur mise à zéro dans l'export pour ne pas provoquer d'erreur (mais vous devriez corriger).<br /><br />";
					$retardEleve['retards']=0;
				}
				if(!preg_match("/^[0-9]{1,}$/", $retardsJustifies)) {
					$msg_erreur_remplissage.="Le nombre d'absences justifiées pour <strong>".get_nom_prenom_eleve($eleve->login)."</strong> en période <strong>".$eleve->periode."</strong> est invalide (".$retardsJustifies.").<br />Valeur mise à zéro dans l'export pour ne pas provoquer d'erreur (mais vous devriez corriger).<br /><br />";
					$retardsJustifies=0;
				}
				if(!preg_match("/^[0-9]{1,}$/", $retardEleve['nj'])) {
					$msg_erreur_remplissage.="Le nombre de retards pour <strong>".get_nom_prenom_eleve($eleve->login)."</strong> en période <strong>".$eleve->periode."</strong> est invalide (".$retardEleve['nj'].").<br />Valeur mise à zéro dans l'export pour ne pas provoquer d'erreur (mais vous devriez corriger).<br /><br />";
					$retardEleve['nj']=0;
				}
				$attributsVieScolaire = array('nb-retards'=>$retardEleve['retards'] , 'nb-abs-justifiees'=>$retardsJustifies, 'nb-abs-injustifiees'=>$retardEleve['nj']);

				foreach ($attributsVieScolaire as $cle=>$valeur) {
					$attsVieSco= $xml->createAttribute($cle);
					$attsVieSco->value = $valeur;
					$vieScolaire->appendChild($attsVieSco);
				}
				if (trim($retardEleve['appreciation']) && getSettingAOui("LSU_commentaire_vie_sco")) {
					// non obligatoire
					$tmp_chaine=nettoye_texte_vers_chaine($retardEleve['appreciation']);
					$comVieSco = $xml->createElement('commentaire', ensure_utf8(mb_substr(trim($tmp_chaine),0,600,'UTF-8')));
					$vieScolaire->appendChild($comVieSco);
				}
		
		
				$noeudBilanElevePeriodique->appendChild($vieScolaire);

				$socle = $xml->createElement('socle');
				// non obligatoire
				if (getSettingValue("LSU_Donnees_socle") == "y") {

					$mef_code_ele=$eleve->mef_code;
					if(!isset($tab_cycle[$mef_code_ele])) {
						$tmp_tab_cycle_niveau=calcule_cycle_et_niveau($mef_code_ele, "", "");
						$cycle_eleve_courant=$tmp_tab_cycle_niveau["mef_cycle"];
						$niveau_eleve_courant=$tmp_tab_cycle_niveau["mef_niveau"];
						$tab_cycle[$mef_code_ele]=$cycle_eleve_courant;
					}

					$tab_positionnement_trouve=array();
					$sql="SELECT DISTINCT sec.* FROM socle_eleves_composantes sec, eleves e WHERE sec.ine=e.no_gep AND e.ele_id='".$eleve->ele_id."' AND sec.cycle='".$tab_cycle[$mef_code_ele]."' AND periode='".$eleve->periode."' AND annee='".$millesime."';";
					$res_ele_socle=mysqli_query($GLOBALS["mysqli"], $sql);
					if(mysqli_num_rows($res_ele_socle)>0) {
						while($lig_ele_socle=mysqli_fetch_object($res_ele_socle)) {

							if((in_array($lig_ele_socle->code_composante, $tab_domaines))&&(!in_array($lig_ele_socle->code_composante, $tab_positionnement_trouve))) {
								$tab_positionnement_trouve[]=$lig_ele_socle->code_composante;
							}

							$socleElv=$xml->createElement('domaine');

							$attsSocle=$xml->createAttribute('code');
							$attsSocle->value=$lig_ele_socle->code_composante;
							$socleElv->appendChild($attsSocle);

							$attsSocle=$xml->createAttribute('positionnement');
							$attsSocle->value=$lig_ele_socle->niveau_maitrise;
							$socleElv->appendChild($attsSocle);

							$socle->appendChild($socleElv);
						}

						if(count($tab_domaines)==count($tab_positionnement_trouve)) {
							$noeudBilanElevePeriodique->appendChild($socle);
						}
						elseif(isset($socle)) {
							unset($socle);
						}
					}
				}

				if (getSettingValue("LSU_Donnees_responsables") != "n") {
					$noeudResponsables = $xml->createElement('responsables');
					// non obligatoire
					$responsablesEleve = getResponsableEleve($eleve->ele_id);
					// 20170404
					//echo "\$responsablesEleve = getResponsableEleve($eleve->ele_id);<br />";
					if(mysqli_num_rows($responsablesEleve)==0) {
						$msg_erreur_remplissage.="Pas de responsable légal pour <a href='../eleves/visu_eleve.php?ele_login=".$eleve->login."' target='_blank'>".$eleve->nom." ".$eleve->prenom."</a>.<br /><br />";
					}
					else {
						while ($responsable = $responsablesEleve->fetch_object()) {
							//echo $responsable->pers_id.' '.$responsable->civilite.' '.$responsable->nom.' '.$responsable->prenom.' '.$responsable->resp_legal.' ';
							//echo $responsable->adr1.' '.$responsable->adr2.' '.$responsable->adr3.' '.$responsable->adr4.' '.$responsable->cp.' '.$responsable->pays.' '.$responsable->commune;
							//echo "<br>";
							$legal1 = $responsable->resp_legal == 1 ? 1 : 0;
							$legal2 = $responsable->resp_legal == 2 ? 1 : 0;
							$respElv = $xml->createElement('responsable');
							$attributsResponsable = array('civilite'=>$responsable->civilite , 'nom'=>$responsable->nom, 'prenom'=>$responsable->prenom, 'legal1'=>$legal1, 'legal2'=>$legal2);
							foreach ($attributsResponsable as $cle=>$valeur) {
								$attsResp = $xml->createAttribute($cle);
								$attsResp->value = $valeur;
								$respElv->appendChild($attsResp);
					
							}
				
							if (trim($responsable->adr1) && $responsable->cp && $responsable->commune) {
								$noeudAdresse = $xml->createElement('adresse');
								$responsableAdr1 = trim($responsable->adr1) ? trim($responsable->adr1) : "-";
								$attributsAdresse = array('ligne1'=>$responsableAdr1, 'code-postal'=>$responsable->cp, 'commune'=>$responsable->commune);
								if (trim($responsable->adr2) != "") {$attributsAdresse['ligne2'] = $responsable->adr2;}
								if (trim($responsable->adr3) != "") {$attributsAdresse['ligne3'] = $responsable->adr3;}
								if (trim($responsable->adr4) != "") {$attributsAdresse['ligne4'] = $responsable->adr4;}
								foreach ($attributsAdresse as $cle=>$valeur) {
									if (!$valeur) {continue ;}

									if((isset($longueur_limite_lignes_adresse[$cle]))&&(mb_strlen($valeur)>$longueur_limite_lignes_adresse[$cle])&&(!in_array("longueur_adresse_resp_".$responsable->pers_id, $tab_erreur_adr))) {
										$msg_erreur_remplissage.="La ".$cle." de l'adresse postale de <a href='../responsables/modify_resp.php?pers_id=".$responsable->pers_id."' target='_blank'>".$responsable->civilite." ".$responsable->nom." ".$responsable->prenom."</a> dépasse ".$longueur_limite_lignes_adresse[$cle]." caractères.<br /><br />";
										$tab_erreur_adr[]="longueur_adresse_resp_".$responsable->pers_id;
									}
									$attAdresse = $xml->createAttribute($cle);
									$attAdresse->value = $valeur;
									$noeudAdresse->appendChild($attAdresse);
								}
								$respElv->appendChild($noeudAdresse);
							}
				
							$noeudResponsables->appendChild($respElv);
						}
						//echo "<br>";
					}
			
			
					if ($responsablesEleve->num_rows) {
						$noeudBilanElevePeriodique->appendChild($noeudResponsables);
					}
				}

				if ($desAcquis && $exporteEleve) {
					//echo "PLIP<br />";
					if(in_array("EL_".$eleve->id_eleve."_".$eleve->periode, $tab_id_eleve)) {
						$msg_erreur_remplissage.="L'élève <strong>".get_nom_prenom_eleve($eleve->login)."</strong> (EL_".$eleve->id_eleve."_".$eleve->periode.") est inscrit plusieurs fois en période EL_".$eleve->id_eleve.".<br />Cela correspond probablement à un changement de classe en cours d'année.<br />Il faudrait exporter les différentes classes de l'élève en plusieurs fois&nbsp;: un fichier XML par classe de l'élève.<br /><br />";
					}

					$tab_id_eleve[]="EL_".$eleve->id_eleve."_".$eleve->periode;

					// DEBUG 20180625
					//echo $eleve->id_eleve." période ".$eleve->periode." (";
					//echo $tmp_classe;
					$classe_ele_courant='';
					if(isset($tab_ele_derniere_classe[$eleve->login])) {
						$classe_ele_courant=$tab_ele_derniere_classe[$eleve->login];
						//echo $classe_ele_courant;
					}
					//echo ")<br />";

					// 20180619
					/*
					if((!isset($tab_periodes_extraites[$tmp_classe]))||(!in_array($eleve->periode, $tab_periodes_extraites[$tmp_classe]))) {
						$tab_periodes_extraites[$tmp_classe][]=$eleve->periode;
					}
					*/
					if(($classe_ele_courant!='')&&
					((!isset($tab_periodes_extraites[$classe_ele_courant]))||(!in_array($eleve->periode, $tab_periodes_extraites[$classe_ele_courant])))) {
						$tab_periodes_extraites[$classe_ele_courant][]=$eleve->periode;
					}

					$bilansPeriodiques->appendChild($noeudBilanElevePeriodique);
				}
			}
		}	
		$donnees->appendChild($bilansPeriodiques);

		/*
		echo "<pre>";
		sort($tab_id_eleve);
		print_r($tab_id_eleve);
		echo "</pre>";
		*/
	/*
		// Bilans de fin de cycle

        <bilans-cycle>
            <bilan-cycle eleve-ref="EL_01" cycle="3" millesime="2015" date-creation="2016-06-21" date-verrou="2016-06-23T06:00:00" responsable-etab-ref="RESP_01" prof-princ-refs="ENS_0123456789ABC">
                <socle>
                   <domaine code="CPD_FRA" positionnement="1"/>
                   <domaine code="CPD_ETR" positionnement="0"/>
                   <domaine code="CPD_SCI" positionnement="3"/>
                   <domaine code="CPD_ART" positionnement="4"/>
                   <domaine code="MET_APP" positionnement="1"/>
                   <domaine code="FRM_CIT" positionnement="2"/>
                   <domaine code="SYS_NAT" positionnement="3"/>
                   <domaine code="REP_MND" positionnement="4"/>
                </socle>
                <synthese>Synthèse des acquis scolaires pour Victor</synthese>
                <enseignement-complement code="LCA" positionnement="2" />
                <responsables>
                    <responsable civilite="M" nom="NOM RESPONSABLE 1" prenom="Prénom responsable 1" lien-parente="PERE" legal1="true" legal2="false">
                        <adresse ligne1="68" ligne2="GALERIE DE L'ARLEQUIN" ligne3="GRENOBLE" ligne4="BP 241" code-postal="38036" commune="GRENOBLE Cedex 2"/>
                    </responsable>
                </responsables>
            </bilan-cycle>
            <bilan-cycle eleve-ref="EL_02" cycle="3" millesime="2015" date-creation="2016-06-22" date-verrou="2016-06-24T06:00:00" responsable-etab-ref="RESP_01" prof-princ-refs="ENS_0123456789ABC">
                <socle>
                   <domaine code="CPD_FRA" positionnement="4"/>
                   <domaine code="CPD_ETR" positionnement="3"/>
                   <domaine code="CPD_SCI" positionnement="2"/>
                   <domaine code="CPD_ART" positionnement="1"/>
                   <domaine code="MET_APP" positionnement="4"/>
                   <domaine code="FRM_CIT" positionnement="3"/>
                   <domaine code="SYS_NAT" positionnement="2"/>
                   <domaine code="REP_MND" positionnement="1"/>
                </socle>
                <synthese>Synthèse des acquis scolaires pour Aline</synthese>
                <enseignement-complement code="AUC" />
            </bilan-cycle>
        </bilans-cycle>

	*/
		//echo "getSettingValue(LSU_Donnees_BilanFinCycle)=".getSettingValue("LSU_Donnees_BilanFinCycle")."<br />";
		if ((getSettingValue("LSU_Donnees_BilanFinCycle") == "y")||
			((isset($_SESSION["BilanFinCycle3"]))&&($_SESSION["BilanFinCycle3"]=="y"))||
			((isset($_SESSION["BilanFinCycle4"]))&&($_SESSION["BilanFinCycle4"]=="y"))) {
			$bilansFinCycle = $xml->createElement('bilans-cycle');

			$on_a_des_BilansFinCycle=FALSE;

			// Problème : On récupère chaque élève autant de fois qu'on a de période
			//$eleves = getElevesExport();
			$eleves = getElevesExportCycle();

			while ($eleve = $eleves->fetch_object()) {
/*
echo "<pre>";
print_r($eleve);
echo "</pre>";
*/
				$mef_code_ele=$eleve->mef_code;
				//if(!isset($tab_cycle[$mef_code_ele])) {
					$tmp_tab_cycle_niveau=calcule_cycle_et_niveau($mef_code_ele, "", "");
					$cycle_eleve_courant=$tmp_tab_cycle_niveau["mef_cycle"];
					$niveau_eleve_courant=$tmp_tab_cycle_niveau["mef_niveau"];
					$tab_cycle[$mef_code_ele]=$cycle_eleve_courant;
				//}

				$cycle_eleve_courant=$tmp_tab_cycle_niveau["mef_cycle"];
				$niveau_eleve_courant=$tmp_tab_cycle_niveau["mef_niveau"];
//echo "\$cycle_eleve_courant=$cycle_eleve_courant<br />";
//echo "\$niveau_eleve_courant=$niveau_eleve_courant<br />";

				if(((getSettingValue("LSU_Donnees_BilanFinCycle") == "y"))&&($tab_cycle[$mef_code_ele]=="")) {
					$msg_erreur_remplissage.="Cycle courant de <a href='../eleves/visu_eleve.php?ele_login=".$eleve->login."' target='_blank'>".$eleve->nom." ".$eleve->prenom."</a> ($mef_code_ele) en classe de ".get_chaine_liste_noms_classes_from_ele_login($eleve->login)." non identifié (<a href='../mef/associer_eleve_mef.php?type_selection=nom_eleve&nom_eleve=".preg_replace("/^[^A-Za-z0-9 _]*$/", "%", $eleve->nom)."' target='_blank'>Mef élève</a> <a href='../mef/admin_mef.php' target='_blank' title=\"Contrôler tous les MEFS\"><img src='../images/icons/configure.png' class='icone16' alt='Config' /></a>).<br /><br />";
					//$generer_bilan_pour_cet_eleve=false;
				}

// 20170603: REMPLIR UN TABLEAU DES CYCLES POUR LESQUELS TENTER DE GENERER UN EXPORT
				$tableau_des_cycles_pour_lesquels_generer_bilan=array();
				if((getSettingValue("LSU_Donnees_BilanFinCycle") == "y")&&
					((($cycle_eleve_courant==3)&&($niveau_eleve_courant==6))||
					(($cycle_eleve_courant==4)&&($niveau_eleve_courant==3)))) {
					$tableau_des_cycles_pour_lesquels_generer_bilan[]=$cycle_eleve_courant;
				}
				if((isset($_SESSION["BilanFinCycle3"]))&&($_SESSION["BilanFinCycle3"]=="y")&&(!in_array(3, $tableau_des_cycles_pour_lesquels_generer_bilan))) {
					$tableau_des_cycles_pour_lesquels_generer_bilan[]=3;
				}
				if((isset($_SESSION["BilanFinCycle4"]))&&($_SESSION["BilanFinCycle4"]=="y")&&(!in_array(4, $tableau_des_cycles_pour_lesquels_generer_bilan))) {
					$tableau_des_cycles_pour_lesquels_generer_bilan[]=4;
				}

				/*
				if((($cycle_eleve_courant==3)&&($niveau_eleve_courant==6))||
				(($cycle_eleve_courant==4)&&($niveau_eleve_courant==3))) {
				*/
				for($loop_cycle=0;$loop_cycle<count($tableau_des_cycles_pour_lesquels_generer_bilan);$loop_cycle++) {
					$on_a_un_BilanFinCycle_pour_cet_eleve=FALSE;
					$generer_bilan_pour_cet_eleve=true;

					$noeudBilanEleveFinCycle = $xml->createElement('bilan-cycle');

					// Récupérer la dernière classe de l'élève... même si ce n'est pas dans la liste de classes à exporter?
					// Ou ajouter un test pour ne retenir que les élèves qui sont dans la classe en dernière période?
					$sql="SELECT jec.id_classe, 
								c.classe, 
								c.suivi_par, 
								jec.periode, 
								DATE_FORMAT(p.date_verrouillage, '%Y-%m-%d') AS date_verrou
							FROM j_eleves_classes jec, 
								classes c, 
								periodes p 
							WHERE jec.periode=p.num_periode AND 
								jec.id_classe=p.id_classe AND 
								jec.id_classe=c.id AND 
								jec.login='".$eleve->login."' 
							ORDER BY periode DESC LIMIT 1;";
					//echo "$sql<br />";
					$res_class=mysqli_query($mysqli, $sql);
					$lig_clas=mysqli_fetch_object($res_class);

					$date_verrou=$lig_clas->date_verrou;

					$sql="SELECT lr.id AS id_resp_etab FROM lsun_responsables AS lr WHERE lr.login='".$lig_clas->suivi_par."';";
					//echo "$sql<br />";
					$res_lr=mysqli_query($mysqli, $sql);
					if(mysqli_num_rows($res_lr)>0) {
						$lig_lr=mysqli_fetch_object($res_lr);

						$respEtabElv = "RESP_".$lig_lr->id_resp_etab;
					}
					else {
						$msg_erreur_remplissage.="Le principal/adjoint chargé suivi de la classe de ".$lig_clas->classe." n'est pas défini.<br /><br />";
						$generer_bilan_pour_cet_eleve=false;
					}

					$sql="SELECT jep.professeur 
							FROM j_eleves_professeurs jep 
							WHERE jep.id_classe='".$lig_clas->id_classe."' AND 
								jep.login='".$eleve->login."';";
					//echo "$sql<br />";
					$res_profprinc=mysqli_query($mysqli, $sql);
					if(mysqli_num_rows($res_profprinc)>0) {
						$lig_profprinc=mysqli_fetch_object($res_profprinc);
						$profResponsable = substr(getUtilisateur($lig_profprinc->professeur)->numind,1);
					}
					else {
						$msg_erreur_remplissage.="Le professeur principal chargé suivi de ".$eleve->nom." ".$eleve->prenom." en classe de ".$lig_clas->classe." n'est pas défini.<br /><br />";
						$generer_bilan_pour_cet_eleve=false;
					}

					/*
							<bilan-cycle 
								eleve-ref="EL_02" 
								cycle="3" 
								millesime="2015" 
								date-creation="2016-06-22" 
								date-verrou="2016-06-24T06:00:00" 
								responsable-etab-ref="RESP_01" 
								prof-princ-refs="ENS_0123456789ABC">
					*/

					if($generer_bilan_pour_cet_eleve) {
						$attributsEleveCycle = array('eleve-ref'=>"EL_".$eleve->id_eleve , 
											//'cycle'=>$tab_cycle[$mef_code_ele], 
											'cycle'=>$tableau_des_cycles_pour_lesquels_generer_bilan[$loop_cycle], 
											'millesime'=>$millesime , 
											'date-creation'=>$date_creation , 
											'date-verrou'=>"$date_verrou" , 
											'responsable-etab-ref'=>"$respEtabElv", 
											'prof-princ-refs'=>"ENS_".$profResponsable );
						foreach ($attributsEleveCycle as $cle=>$valeur) {
							$attsEleveCycle = $xml->createAttribute($cle);
							$attsEleveCycle->value = $valeur;

							$noeudBilanEleveFinCycle->appendChild($attsEleveCycle);
						}

						$socle = $xml->createElement('socle');

// A VERIFIER : Il peut y avoir un domaine avec AB (absent)?

						// Récupération des positionnements dans les domaines du socle
						$tab_positionnement_trouve=array();
						//$sql="SELECT DISTINCT sec.* FROM socle_eleves_composantes sec, eleves e WHERE sec.ine=e.no_gep AND e.ele_id='".$eleve->ele_id."' AND sec.cycle='".$tab_cycle[$mef_code_ele]."' AND sec.niveau_maitrise!='' AND sec.niveau_maitrise!='0' AND sec.annee='".$millesime."' ORDER BY sec.annee, sec.periode;";
						$sql="SELECT DISTINCT sec.* FROM socle_eleves_composantes sec, eleves e WHERE sec.ine=e.no_gep AND e.ele_id='".$eleve->ele_id."' AND sec.cycle='".$tableau_des_cycles_pour_lesquels_generer_bilan[$loop_cycle]."' AND sec.niveau_maitrise!='' AND sec.niveau_maitrise!='0' AND sec.annee='".$millesime."' ORDER BY sec.annee, sec.periode;";
						//echo "$sql<br />";
						$res_ele_socle=mysqli_query($GLOBALS["mysqli"], $sql);
						if(mysqli_num_rows($res_ele_socle)>0) {
							$tab_positionnement_domaine=array();
							// En faisant plusieurs tours par période, on va écraser et retenir le dernier niveau validé.
							while($lig_ele_socle=mysqli_fetch_object($res_ele_socle)) {
								$tab_positionnement_domaine[$lig_ele_socle->code_composante]=$lig_ele_socle->niveau_maitrise;
							}

							foreach($tab_positionnement_domaine as $code_composante => $niveau_maitrise) {
								if((in_array($code_composante, $tab_domaines))&&(!in_array($code_composante, $tab_positionnement_trouve))) {
									$tab_positionnement_trouve[]=$code_composante;
								}

								$socleElv=$xml->createElement('domaine');

								$attsSocle=$xml->createAttribute('code');
								$attsSocle->value=$code_composante;
								$socleElv->appendChild($attsSocle);

								$attsSocle=$xml->createAttribute('positionnement');
								$attsSocle->value=$niveau_maitrise;
								$socleElv->appendChild($attsSocle);

								$socle->appendChild($socleElv);
							}
						}

						// DEBUG:
						//echo "count(\$tab_domaines)=".count($tab_domaines)."<br />count(\$tab_positionnement_trouve)=".count($tab_positionnement_trouve)."<br />";
						// A-t-on les 8 postionnements?
						if(count($tab_domaines)==count($tab_positionnement_trouve)) {
							$noeudBilanEleveFinCycle->appendChild($socle);

							// On vérifie aussi qu'on a une synthèse:
							//$sql="SELECT DISTINCT sec.* FROM socle_eleves_syntheses sec, eleves e WHERE sec.ine=e.no_gep AND e.ele_id='".$eleve->ele_id."' AND sec.cycle='".$tab_cycle[$mef_code_ele]."' AND sec.synthese!='' AND sec.annee='".$millesime."';";
							$sql="SELECT DISTINCT sec.* FROM socle_eleves_syntheses sec, eleves e WHERE sec.ine=e.no_gep AND e.ele_id='".$eleve->ele_id."' AND sec.cycle='".$tableau_des_cycles_pour_lesquels_generer_bilan[$loop_cycle]."' AND sec.synthese!='' AND sec.annee='".$millesime."';";
							//echo "$sql<br />";
							$res_ele_synthese=mysqli_query($GLOBALS["mysqli"], $sql);
							if(mysqli_num_rows($res_ele_synthese)>0) {
								$lig_ele_synthese=mysqli_fetch_object($res_ele_synthese);

								$synthese=$xml->createElement('synthese' ,$lig_ele_synthese->synthese);

								$noeudBilanEleveFinCycle->appendChild($synthese);

								// DEBUG:
								//echo "On a un bilan pour cet élève.<br />";
								// On a à la fois les 8 domaines du socle et la synthèse
								$on_a_un_BilanFinCycle_pour_cet_eleve=TRUE;
							}

						}
						elseif(isset($socle)) {
							unset($socle);
						}

						if ($on_a_un_BilanFinCycle_pour_cet_eleve) {

							// Il faut aussi les Enseignements de complément
							/*
							    <enseignement-complement code="LCA" positionnement="2" />
							ou si on n'a pas d'enseignement de complément à remonter:
							    <enseignement-complement code="AUC" />
							*/
							if(($tableau_des_cycles_pour_lesquels_generer_bilan[$loop_cycle]==4)&&($niveau_eleve_courant==3)) {
								// 20181119
								$sql_restriction_date_enseignements_complement_CHK_et_LCE='';
								if($date_creation<'2019-05-01') {
									$sql_restriction_date_enseignements_complement_CHK_et_LCE=" AND jgec.code!='CHK' AND jgec.code!='LCE' ";
								}

								// Il ne faut retenir qu'un enseignement de complément
								$sql="SELECT seec.*, jgec.code FROM j_groupes_enseignements_complement jgec, 
											socle_eleves_enseignements_complements seec 
										WHERE jgec.id_groupe=seec.id_groupe AND 
											(seec.positionnement='1' OR seec.positionnement='2') AND 
											seec.ine='".$eleve->no_gep."' ".$sql_restriction_date_enseignements_complement_CHK_et_LCE."
										ORDER BY seec.positionnement DESC LIMIT 1;";
								//echo "$sql<br />";
								$res_seec=mysqli_query($mysqli, $sql);
								if(mysqli_num_rows($res_seec)>0) {
									while($lig_seec=mysqli_fetch_object($res_seec)) {
										$noeudEnseignementComplement=$xml->createElement('enseignement-complement');

										// Ca ne devrait pas arriver
										if($lig_seec->code=="AUC") {
											$attributsEnseignementComplement = array('code'=>"AUC");
										}
										else {
											$attributsEnseignementComplement = array('code'=>$lig_seec->code , 'positionnement'=>$lig_seec->positionnement);
										}
										foreach ($attributsEnseignementComplement as $cle=>$valeur) {
											$attsEC = $xml->createAttribute($cle);
											$attsEC->value = $valeur;
											$noeudEnseignementComplement->appendChild($attsEC);
										}
										$noeudBilanEleveFinCycle->appendChild($noeudEnseignementComplement);
									}
								}
								else {
									$noeudEnseignementComplement=$xml->createElement('enseignement-complement');

									$attributsEnseignementComplement = array('code'=>"AUC");
									foreach ($attributsEnseignementComplement as $cle=>$valeur) {
										$attsEC = $xml->createAttribute($cle);
										$attsEC->value = $valeur;
										$noeudEnseignementComplement->appendChild($attsEC);
									}
									$noeudBilanEleveFinCycle->appendChild($noeudEnseignementComplement);
								}

							}

							if (getSettingValue("LSU_Donnees_responsables") != "n") {
								$noeudResponsables = $xml->createElement('responsables');
								// non obligatoire
								$responsablesEleve = getResponsableEleve($eleve->ele_id);
								while ($responsable = $responsablesEleve->fetch_object()) {
									//echo $responsable->pers_id.' '.$responsable->civilite.' '.$responsable->nom.' '.$responsable->prenom.' '.$responsable->resp_legal.' ';
									//echo $responsable->adr1.' '.$responsable->adr2.' '.$responsable->adr3.' '.$responsable->adr4.' '.$responsable->cp.' '.$responsable->pays.' '.$responsable->commune;
									//echo "<br>";
									$legal1 = $responsable->resp_legal == 1 ? 1 : 0;
									$legal2 = $responsable->resp_legal == 2 ? 1 : 0;
									$respElv = $xml->createElement('responsable');
									$attributsResponsable = array('civilite'=>$responsable->civilite , 'nom'=>$responsable->nom, 'prenom'=>$responsable->prenom, 'legal1'=>$legal1, 'legal2'=>$legal2);
									foreach ($attributsResponsable as $cle=>$valeur) {
										$attsResp = $xml->createAttribute($cle);
										$attsResp->value = $valeur;
										$respElv->appendChild($attsResp);
						
									}
					
									if (trim($responsable->adr1) && $responsable->cp && $responsable->commune) {
										$noeudAdresse = $xml->createElement('adresse');
										$responsableAdr1 = trim($responsable->adr1) ? trim($responsable->adr1) : "-";
										$attributsAdresse = array('ligne1'=>$responsableAdr1, 'code-postal'=>$responsable->cp, 'commune'=>$responsable->commune);
										if (trim($responsable->adr2) != "") {$attributsAdresse['ligne2'] = $responsable->adr2;}
										if (trim($responsable->adr3) != "") {$attributsAdresse['ligne3'] = $responsable->adr3;}
										if (trim($responsable->adr4) != "") {$attributsAdresse['ligne4'] = $responsable->adr4;}
										foreach ($attributsAdresse as $cle=>$valeur) {
											if (!$valeur) {continue ;}
											$attAdresse = $xml->createAttribute($cle);
											$attAdresse->value = $valeur;
											$noeudAdresse->appendChild($attAdresse);
										}
										$respElv->appendChild($noeudAdresse);
									}
					
									$noeudResponsables->appendChild($respElv);
								}
								//echo "<br>";

								if ($responsablesEleve->num_rows) {
									$noeudBilanEleveFinCycle->appendChild($noeudResponsables);
								}
							}

							if($LSUN_version_xsd!=20171009) {
								if(($tableau_des_cycles_pour_lesquels_generer_bilan[$loop_cycle]==4)&&($niveau_eleve_courant==3)) {
									// <langue-culture-regionale code="BAS" niveau-atteint="true"/>
									if(getSettingAOui('langue_vivante_regionale')) {
										$sql="SELECT DISTINCT jgl.code, sel.positionnement FROM socle_eleves_lvr sel, 
													j_groupes_lvr jgl, 
													groupes g 
												WHERE sel.ine='".$eleve->no_gep."' AND 
													sel.positionnement>'0' AND 
													jgl.id_groupe=sel.id_groupe AND 
													g.id=jgl.id_groupe 
												ORDER BY sel.positionnement DESC 
												LIMIT 1;";
										//echo "$sql<br />";
										$res_lvr=mysqli_query($mysqli, $sql);
										if(mysqli_num_rows($res_lvr)>0) {
											while($lig_lvr=mysqli_fetch_object($res_lvr)) {
												$noeudLVR=$xml->createElement('langue-culture-regionale');

												/*
												Entier compris entre 1 et 2
												1 : Objectif non atteint
												2 : Objectif atteint
												*/

												$attributsLVR = array('code'=>$lig_lvr->code , 
																'positionnement'=>$lig_lvr->positionnement);
												foreach ($attributsLVR as $cle=>$valeur) {
													$attsEleveLVR = $xml->createAttribute($cle);
													$attsEleveLVR->value = $valeur;

													$noeudLVR->appendChild($attsEleveLVR);
												}

												$noeudBilanEleveFinCycle->appendChild($noeudLVR);
											}
										}
									}
								}
							}

							$bilansFinCycle->appendChild($noeudBilanEleveFinCycle);
							$on_a_des_BilansFinCycle=TRUE;
						}
					}
				}
			}

			if ($on_a_des_BilansFinCycle) {
				$donnees->appendChild($bilansFinCycle);
			}
		}


