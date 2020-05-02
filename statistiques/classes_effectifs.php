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

$sql="SELECT 1=1 FROM droits WHERE id='/statistiques/classes_effectifs.php';";
$test=mysqli_query($GLOBALS["mysqli"], $sql);
if(mysqli_num_rows($test)==0) {
$sql="INSERT INTO droits SET id='/statistiques/classes_effectifs.php',
administrateur='V',
professeur='V',
cpe='V',
scolarite='V',
eleve='F',
responsable='F',
secours='F',
autre='F',
description='Effectifs des classes',
statut='';";
$insert=mysqli_query($GLOBALS["mysqli"], $sql);
}

if (!checkAccess()) {
	header("Location: ../logout.php?auto=2");
	die();
}


$sql="SELECT DISTINCT id, classe FROM classes ORDER BY classe;";
//echo "$sql<br />\n";
$res_classes=mysqli_query($GLOBALS["mysqli"], $sql);
$nb_classes=mysqli_num_rows($res_classes);
if($nb_classes>0) {
	$tab_classe=array();
	$cpt=0;
	while($lig_classe=mysqli_fetch_object($res_classes)) {
		$tab_classe[$cpt]=array();
		$tab_classe[$cpt]['id']=$lig_classe->id;
		$tab_classe[$cpt]['classe']=$lig_classe->classe;
		$cpt++;
	}
}

if(isset($_GET['export_csv'])) {
	$num_periode=isset($_GET['num_periode']) ? $_GET['num_periode'] : 1;

	if($_GET['export_csv']=='effectifs') {
		$nom_fic = "export_classes_effectifs_periode_".$num_periode."_".date("Ymd_His").".csv";

		$csv="Classes;Effectifs;\r\n";
		for($i=0;$i<count($tab_classe);$i++) {
			$csv.=$tab_classe[$i]['classe'].";";
			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='".$tab_classe[$i]['id']."' AND e.login=jec.login AND jec.periode='$num_periode';";
			$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
			$csv.=mysqli_num_rows($res_eff).";\r\n";
		}

		$csv.="Total;";
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$num_periode';";
		//echo "$sql<br />";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		$csv.=mysqli_num_rows($res_eff).";\r\n";

		send_file_download_headers('text/x-csv',$nom_fic);
		//echo $csv;
		echo echo_csv_encoded($csv);
		die();
	}
	elseif($_GET['export_csv']=='effectifs_grp') {
		$nom_fic = "export_regroupements_effectifs_periode_".$num_periode."_".date("Ymd_His").".csv";

		$tab_grp=array();
		//$csv="Groupe;Effectifs;\r\n";
		$csv="Groupe;Classes;Effectif;\r\n";
		$sql="SELECT distinct id_groupe, count(id_classe) FROM j_groupes_classes jgc, classes c WHERE jgc.id_classe=c.id group by id_groupe HAVING COUNT(id_classe)>1 order by c.classe;";
		$res_grp=mysqli_query($GLOBALS["mysqli"], $sql);
		if(mysqli_num_rows($res_grp)>0) {
			while($lig_grp=mysqli_fetch_object($res_grp)) {
				$tab_grp[]=$lig_grp->id_groupe;
			}

			for($i=0;$i<count($tab_grp);$i++) {
				//$csv.=get_info_grp($tab_grp[$i], array('classes')).";";
				$csv.=get_info_grp($tab_grp[$i], array('')).";";

				$tmp_tab=get_classes_from_id_groupe($tab_grp[$i]);
				$csv.=$tmp_tab['classlist_string'].";";

				$sql="SELECT e.login FROM j_eleves_groupes jeg, eleves e WHERE jeg.id_groupe='".$tab_grp[$i]."' AND e.login=jeg.login AND jeg.periode='$num_periode';";
				$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
				$csv.=mysqli_num_rows($res_eff).";\r\n";
			}
			/*
			$csv.="Total;";
			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$num_periode';";
			//echo "$sql<br />";
			$res_eff=mysql_query($sql);
			$csv.=mysql_num_rows($res_eff).";\r\n";
			*/
		}

		send_file_download_headers('text/x-csv',$nom_fic);
		//echo $csv;
		echo echo_csv_encoded($csv);
		die();
	}
	elseif($_GET['export_csv']=='effectifs_sous_grp') {
		$nom_fic = "export_sous_groupes_effectifs_periode_".$num_periode."_".date("Ymd_His").".csv";

		$tab_nom_classe=array();
		$csv="Groupe;Classe;Effectif groupe;Effectif classe;\r\n";

		//$sql="SELECT DISTINCT jgc.id_groupe, COUNT(jeg.login) AS eff_ele_grp FROM j_groupes_classes jgc, j_eleves_groupes jeg WHERE jgc.id_groupe=jeg.id_groupe AND jeg.periode='$num_periode' GROUP BY id_groupe HAVING COUNT(id_classe)='1';";
		$sql="SELECT DISTINCT jgc.id_groupe, jgc.id_classe FROM j_groupes_classes jgc GROUP BY id_groupe HAVING COUNT(id_classe)='1';";
		//echo "$sql<br />";
		$res_grp=mysqli_query($GLOBALS["mysqli"], $sql);
		if(mysqli_num_rows($res_grp)>0) {
			while($lig_grp=mysqli_fetch_object($res_grp)) {
				$sql="SELECT * FROM j_eleves_groupes WHERE id_groupe='".$lig_grp->id_groupe."' AND periode='".$num_periode."';";
				//echo "$sql<br />";
				$res_eff_grp=mysqli_query($GLOBALS["mysqli"], $sql);

				$sql="SELECT * FROM j_eleves_classes WHERE id_classe='".$lig_grp->id_classe."' AND periode='".$num_periode."';";
				//echo "$sql<br />";
				$res_eff_clas=mysqli_query($GLOBALS["mysqli"], $sql);

				if(mysqli_num_rows($res_eff_grp)!=mysqli_num_rows($res_eff_clas)) {
					//$csv.=get_info_grp($lig_grp->id_groupe, array('classes')).";";
					$csv.=get_info_grp($lig_grp->id_groupe, array('')).";";
					if(!isset($tab_nom_classe[$lig_grp->id_classe])) {
						$tab_nom_classe[$lig_grp->id_classe]=get_nom_classe($lig_grp->id_classe);
					}
					$csv.=$tab_nom_classe[$lig_grp->id_classe].";";
					$csv.=mysqli_num_rows($res_eff_grp).";";
					$csv.=mysqli_num_rows($res_eff_clas).";";
					$csv.="\r\n";
				}
			}
		}

		send_file_download_headers('text/x-csv',$nom_fic);
		//echo $csv;
		echo echo_csv_encoded($csv);
		die();
	}
	elseif($_GET['export_csv']=='effectifs_sexe') {

		$nom_fic = "export_classes_effectifs_sexe_periode_".$num_periode."_".date("Ymd_His").".csv";
		$csv="Classes;Garçons;Filles;Total;\r\n";
		for($i=0;$i<count($tab_classe);$i++) {
			$csv.=$tab_classe[$i]['classe'].";";

			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='".$tab_classe[$i]['id']."' AND e.login=jec.login AND jec.periode='$num_periode' AND e.sexe='M';";
			//echo "$sql<br />\n";
			$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
			$csv.=mysqli_num_rows($res_eff).";";
		
			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='".$tab_classe[$i]['id']."' AND e.login=jec.login AND jec.periode='$num_periode' AND e.sexe='F';";
			//echo "$sql<br />\n";
			$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
			$csv.=mysqli_num_rows($res_eff).";";
		
			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='".$tab_classe[$i]['id']."' AND e.login=jec.login AND jec.periode='$num_periode';";
			//echo "$sql<br />\n";
			$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
			$csv.=mysqli_num_rows($res_eff).";\r\n";
		}

		$csv.="Total;";

		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$num_periode' AND e.sexe='M';";
		//echo "$sql<br />\n";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		$csv.=mysqli_num_rows($res_eff).";";
	
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$num_periode' AND e.sexe='F';";
		//echo "$sql<br />\n";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		$csv.=mysqli_num_rows($res_eff).";";
	
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$num_periode';";
		//echo "$sql<br />\n";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		$csv.=mysqli_num_rows($res_eff).";\r\n";

		send_file_download_headers('text/x-csv',$nom_fic);
		//echo $csv;
		echo echo_csv_encoded($csv);
		die();
	}
	elseif($_GET['export_csv']=='mef') {
		$nom_fic = "export_mef_effectifs_periode_".$num_periode."_".date("Ymd_His").".csv";

		$csv="MEF;Classes;Effectifs;\r\n";

		$tab_mef=get_tab_mef();

		$sql="SELECT m.*, COUNT(e.login) AS effectif FROM eleves e, mef m WHERE e.mef_code=m.mef_code AND e.login in (SELECT login FROM j_eleves_classes WHERE periode='".$num_periode."') GROUP BY mef_code ORDER BY m.libelle_court, m.libelle_long, m.libelle_edition, m.mef_code;";
		$res_mef=mysqli_query($mysqli, $sql);
		while($lig_mef=mysqli_fetch_object($res_mef)) {
			$liste_classes='';
			$sql="SELECT DISTINCT c.classe, jec.id_classe FROM eleves e, 
													j_eleves_classes jec, 
													classes c 
												WHERE e.mef_code='".$lig_mef->mef_code."' AND 
													e.login=jec.login AND 
													periode='".$num_periode."' AND 
													jec.id_classe=c.id 
												ORDER BY c.classe;";
			$res_mef_classe=mysqli_query($mysqli, $sql);
			$cpt_mef_classe=0;
			while($lig_mef_classe=mysqli_fetch_object($res_mef_classe)) {
				if($cpt_mef_classe>0) {$liste_classes.=', ';}
				$liste_classes.=$lig_mef_classe->classe;
				$cpt_mef_classe++;
			}
			$csv.=$tab_mef[$lig_mef->mef_code]['designation_courte'].";".$liste_classes.";".$lig_mef->effectif.";\r\n";
		}

		// MEF VIDE
		$sql="SELECT COUNT(e.login) AS effectif FROM eleves e, 
									j_eleves_classes jec 
								WHERE e.login=jec.login AND 
									jec.periode='".$num_periode."' AND 
									(e.mef_code='' OR e.mef_code='-1' OR e.mef_code IS NULL OR e.mef_code NOT IN (SELECT mef_code FROM mef WHERE mef_code!=''));";
		//echo "$sql<br />";
		$res_mef=mysqli_query($mysqli, $sql);
		while($lig_mef=mysqli_fetch_object($res_mef)) {
			$liste_classes='';
			$sql="SELECT DISTINCT c.classe, jec.id_classe FROM eleves e, 
													j_eleves_classes jec, 
													classes c 
												WHERE (e.mef_code='' OR e.mef_code='-1' OR e.mef_code IS NULL OR e.mef_code NOT IN (SELECT mef_code FROM mef WHERE mef_code!='')) AND 
													e.login=jec.login AND 
													periode='".$num_periode."' AND 
													jec.id_classe=c.id 
												ORDER BY c.classe;";
			$res_mef_classe=mysqli_query($mysqli, $sql);
			$cpt_mef_classe=0;
			while($lig_mef_classe=mysqli_fetch_object($res_mef_classe)) {
				if($cpt_mef_classe>0) {$liste_classes.=', ';}
				$liste_classes.=$lig_mef_classe->classe;
				$cpt_mef_classe++;
			}
			$csv.="MEF VIDE;".$liste_classes.";".$lig_mef->effectif.";\r\n";
		}

		$csv.="Total;;";
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$num_periode';";
		//echo "$sql<br />";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		$csv.=mysqli_num_rows($res_eff).";\r\n";

		send_file_download_headers('text/x-csv',$nom_fic);
		//echo $csv;
		echo echo_csv_encoded($csv);
		die();
	}
	elseif($_GET['export_csv']=='mef_rattachement') {
		$nom_fic = "export_mef_rattachement_periode_".$num_periode."_effectifs_".date("Ymd_His").".csv";

		$csv="MEF;Classes;Effectifs;\r\n";

		$tab_mef=get_tab_mef();
		/*
		echo "<pre>";
		print_r($tab_mef);
		echo "</pre>";
		*/

		$sql="SELECT m.*, COUNT(e.login) AS effectif FROM eleves e, mef m WHERE e.mef_code=m.mef_code AND e.login in (SELECT login FROM j_eleves_classes WHERE periode='".$num_periode."') GROUP BY mef_rattachement ORDER BY m.libelle_court, m.libelle_long, m.libelle_edition, m.mef_code;";
		//echo "$sql<br />";
		$res_mef=mysqli_query($mysqli, $sql);
		while($lig_mef=mysqli_fetch_object($res_mef)) {
			$liste_classes='';
			$sql="SELECT DISTINCT c.classe, jec.id_classe FROM eleves e, 
													j_eleves_classes jec, 
													classes c 
												WHERE e.mef_code='".$lig_mef->mef_code."' AND 
													e.login=jec.login AND 
													periode='".$num_periode."' AND 
													jec.id_classe=c.id 
												ORDER BY c.classe;";
			//echo "$sql<br />";
			$res_mef_classe=mysqli_query($mysqli, $sql);
			$cpt_mef_classe=0;
			while($lig_mef_classe=mysqli_fetch_object($res_mef_classe)) {
				if($cpt_mef_classe>0) {$liste_classes.=', ';}
				$liste_classes.=$lig_mef_classe->classe;
				$cpt_mef_classe++;
			}
			if(isset($tab_mef[$lig_mef->mef_rattachement]['designation_courte'])) {
				$csv.=$tab_mef[$lig_mef->mef_rattachement]['designation_courte'].";".$liste_classes.";".$lig_mef->effectif.";\r\n";
			}
			elseif(isset($tab_mef[$lig_mef->mef_code]['designation_courte'])) {
				$csv.=$tab_mef[$lig_mef->mef_code]['designation_courte'].";".$liste_classes.";".$lig_mef->effectif.";\r\n";
			}
			else {
				$csv.=$lig_mef->rattachement.";".$liste_classes.";".$lig_mef->effectif.";\r\n";
			}
		}

		// MEF VIDE
		$sql="SELECT COUNT(e.login) AS effectif FROM eleves e, 
									j_eleves_classes jec 
								WHERE e.login=jec.login AND 
									jec.periode='".$num_periode."' AND 
									e.mef_code='';";
		//echo "$sql<br />";
		$res_mef=mysqli_query($mysqli, $sql);
		while($lig_mef=mysqli_fetch_object($res_mef)) {
			$liste_classes='';
			$sql="SELECT DISTINCT c.classe, jec.id_classe FROM eleves e, 
													j_eleves_classes jec, 
													classes c 
												WHERE e.mef_code='' AND 
													e.login=jec.login AND 
													periode='".$num_periode."' AND 
													jec.id_classe=c.id 
												ORDER BY c.classe;";
			$res_mef_classe=mysqli_query($mysqli, $sql);
			$cpt_mef_classe=0;
			while($lig_mef_classe=mysqli_fetch_object($res_mef_classe)) {
				if($cpt_mef_classe>0) {$liste_classes.=', ';}
				$liste_classes.=$lig_mef_classe->classe;
				$cpt_mef_classe++;
			}
			$csv.="MEF VIDE;".$liste_classes.";".$lig_mef->effectif.";\r\n";
		}

		$csv.="Total;;";
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$num_periode';";
		//echo "$sql<br />";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		$csv.=mysqli_num_rows($res_eff).";\r\n";

		send_file_download_headers('text/x-csv',$nom_fic);
		//echo $csv;
		echo echo_csv_encoded($csv);
		die();
	}
}

$javascript_specifique[] = "lib/tablekit";
$utilisation_tablekit="ok";
// ===================== entete Gepi ======================================//
$titre_page = "Classes, effectifs,...";
require_once("../lib/header.inc.php");
// ===================== fin entete =======================================//

//debug_var();

echo "<p class='bold'><a href='index.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>

<h2>Effectifs des classes</h2>";

if($nb_classes==0) {
	echo "<p style='color:red'>Aucune classe n'existe encore.</p>\n";

	require_once("../lib/footer.inc.php");
	die();
}

$sql="SELECT num_periode FROM periodes p, classes c WHERE p.id_classe=c.id ORDER BY num_periode DESC LIMIT 1";
$res_per=mysqli_query($GLOBALS["mysqli"], $sql);
if(mysqli_num_rows($res_per)==0) {
	echo "<p style='color:red'>Aucune classe avec périodes n'a été trouvée.</p>\n";
	require_once("../lib/footer.inc.php");
	die();
}
else {
	echo "<p class='bold' style='margin-bottom:1em;margin-top:1em;'>$nb_classes classe(s) sont définies.</p>
<p>Choisissez dans la page&nbsp;:</p>
<ul>
	<li><a href='#eff_classes'>Effectifs des classes par périodes</a></li>
	<li><a href='#eff_grp'>Effectifs des enseignements multi-classes <em>(regroupements)</em> par périodes</a></li>
	<li><a href='#eff_grp_clas'>Effectifs des enseignements sous-groupes et mono-classe par rapport aux effectifs des classes par périodes</a></li>
	<li><a href='#eff_classes_sexes'>Effectifs des classes par sexes par périodes</a></li>
	<li><a href='#eff_mef' title=\"Effectifs par MEF (Modules élémentaires de formation)\">Effectifs par MEF</a></li>
	<li><a href='#eff_mef_rattachement' title=\"Effectifs par MEF rattachement (Modules élémentaires de formation)\">Effectifs par MEF rattachement</a></li>
</ul>

<a name='eff_classes'></a>";

	$tab_eff_classe=array();
	$max_per=old_mysql_result($res_per, 0, "num_periode");
	for($loop=1;$loop<=$max_per;$loop++) {
		echo "<div style='float:left; width:15em;margin:3px;'>\n";
		echo "<p class='bold'>Effectifs en période $loop&nbsp;: <a href='".$_SERVER['PHP_SELF']."?export_csv=effectifs&amp;num_periode=$loop'>Export CSV</a></p>\n";
		echo "<table class='boireaus resizable sortable'
	<thead>
		<tr>
			<th>Classes</th>
			<th>Effectifs</th>
		</tr>
	</thead>
	<tbody>\n";
		$alt=1;
		for($i=0;$i<count($tab_classe);$i++) {
			$alt=$alt*(-1);
			echo "<tr class='lig$alt white_hover'>\n";
			echo "<td>".$tab_classe[$i]['classe']."</td>\n";

			echo "<td>";
			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='".$tab_classe[$i]['id']."' AND e.login=jec.login AND jec.periode='$loop';";
			//echo "$sql<br />\n";
			$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
			$tab_eff_classe[$tab_classe[$i]['id']][$loop]=mysqli_num_rows($res_eff);
			echo $tab_eff_classe[$tab_classe[$i]['id']][$loop];
			echo "</td>\n";
			echo "</tr>\n";
		}

		echo "
	</tbody>
	<tfoot>
		<tr>
			<th>Total</th>
			<th>";
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$loop';";
		//echo "$sql<br />\n";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		echo mysqli_num_rows($res_eff);
		echo "
			</th>
		</tr>
	</tfoot>
</table>\n";
		echo "</div>\n";
	}
	echo "<div style='clear:both;'>&nbsp;</div>\n";
	echo "<p><br /></p>\n";

	//=======================================================

	echo "<a name='eff_grp'></a>\n";
	//$sql="SELECT distinct id_groupe, count(id_classe) FROM j_groupes_classes group by id_groupe HAVING COUNT(id_classe)>1;";
	$sql="SELECT distinct id_groupe, count(id_classe) FROM j_groupes_classes jgc, classes c WHERE jgc.id_classe=c.id group by id_groupe HAVING COUNT(id_classe)>1 order by c.classe;";
	//echo "$sql<br />";
	$res_grp=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res_grp)==0) {
		echo "<p style='color:red; margin-top:1em; margin-bottom:2em;'>Aucun enseignement multi-classe n'est présent.</p>";
	}
	else {
		$tab_grp=array();
		while($lig_grp=mysqli_fetch_object($res_grp)) {
			$tab_grp[]=$lig_grp->id_groupe;
		}

		for($loop=1;$loop<=$max_per;$loop++) {
			echo "<div style='float:left; width:25em;margin:3px;'>\n";
			echo "<p class='bold'>Effectifs en période $loop&nbsp;: <a href='".$_SERVER['PHP_SELF']."?export_csv=effectifs_grp&amp;num_periode=$loop'>Export CSV</a></p>\n";
			echo "<table class='boireaus resizable sortable'>\n";
			echo "<tr>\n";
			echo "<th>Regroupements</th>\n";
			echo "<th>Classes</th>\n";
			echo "<th>Effectifs</th>\n";
			echo "</tr>\n";
			$alt=1;
			for($i=0;$i<count($tab_grp);$i++) {
				$alt=$alt*(-1);
				echo "<tr class='lig$alt white_hover'>\n";
				echo "<td>";
				//echo get_info_grp($tab_grp[$i], array('classes'));
				echo get_info_grp($tab_grp[$i], array());
				echo "</td>\n";
				echo "<td>";
				$tmp_tab=get_classes_from_id_groupe($tab_grp[$i]);
				echo $tmp_tab['classlist_string'];
				echo "</td>\n";

				echo "<td>";
				$sql="SELECT e.login FROM j_eleves_groupes jeg, eleves e WHERE jeg.id_groupe='".$tab_grp[$i]."' AND e.login=jeg.login AND jeg.periode='$loop';";
				//echo "$sql<br />\n";
				$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
				echo mysqli_num_rows($res_eff);
				echo "</td>\n";
				echo "</tr>\n";
			}

			/*
			echo "<tr>\n";
			echo "<th>Total</th>\n";

			echo "<th>";
			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$loop';";
			//echo "$sql<br />\n";
			$res_eff=mysql_query($sql);
			echo mysql_num_rows($res_eff);
			echo "</th>\n";
			echo "</tr>\n";
			*/

			echo "</table>\n";
			echo "</div>\n";
		}
		echo "<div style='clear:both;'>&nbsp;</div>\n";
		echo "<p><br /></p>\n";
	}

	echo "<a name='eff_grp_clas'></a>\n";
	//=======================================================
	$tab_sous_grp=array();
	$tab_nom_classe=array();
	$temoin_au_moins_un_groupe=0;
	$sql="SELECT DISTINCT jgc.id_groupe, jgc.id_classe FROM j_groupes_classes jgc GROUP BY id_groupe HAVING COUNT(id_classe)='1';";
	//echo "$sql<br />";
	$res_grp=mysqli_query($GLOBALS["mysqli"], $sql);
	if(mysqli_num_rows($res_grp)>0) {
		while($lig_grp=mysqli_fetch_object($res_grp)) {
			for($loop=1;$loop<=$max_per;$loop++) {
				$sql="SELECT * FROM j_eleves_groupes WHERE id_groupe='".$lig_grp->id_groupe."' AND periode='".$loop."';";
				//echo "$sql<br />";
				$res_eff_grp=mysqli_query($GLOBALS["mysqli"], $sql);

				$sql="SELECT * FROM j_eleves_classes WHERE id_classe='".$lig_grp->id_classe."' AND periode='".$loop."';";
				//echo "$sql<br />";
				$res_eff_clas=mysqli_query($GLOBALS["mysqli"], $sql);

				if(mysqli_num_rows($res_eff_grp)!=mysqli_num_rows($res_eff_clas)) {
					$tab_sous_grp[$loop]['id_groupe'][]=$lig_grp->id_groupe;
					$tab_sous_grp[$loop]['id_classe'][]=$lig_grp->id_classe;
					if(!isset($tab_nom_classe[$lig_grp->id_classe])) {
						$tab_nom_classe[$lig_grp->id_classe]=get_nom_classe($lig_grp->id_classe);
					}
					$tab_sous_grp[$loop]['classe'][]=$tab_nom_classe[$lig_grp->id_classe];
					$temoin_au_moins_un_groupe++;
				}
			}
		}

		if($temoin_au_moins_un_groupe>0) {
			for($loop=1;$loop<=$max_per;$loop++) {
				if(isset($tab_sous_grp[$loop]['id_groupe'])) {
					echo "<div style='float:left; width:30em; margin:3px;'>\n";
					echo "<p class='bold'>Effectifs en période $loop&nbsp;: <a href='".$_SERVER['PHP_SELF']."?export_csv=effectifs_sous_grp&amp;num_periode=$loop'>Export CSV</a></p>\n";
					echo "<table class='boireaus boireaus_alt resizable sortable'>\n";
					echo "<tr>\n";
					echo "<th class=text'>Sous-groupes</th>\n";
					echo "<th class=text'>Classes</th>\n";
					echo "<th class=number'>Effectifs<br />groupes</th>\n";
					echo "<th class=number'>Effectifs<br />classes</th>\n";
					echo "</tr>\n";
					$alt=1;
					for($i=0;$i<count($tab_sous_grp[$loop]['id_groupe']);$i++) {
						$alt=$alt*(-1);
						echo "<tr class='lig$alt white_hover'>\n";
						echo "<td>";
						//echo get_info_grp($tab_sous_grp[$loop][$i], array('classes'));
						echo get_info_grp($tab_sous_grp[$loop]['id_groupe'][$i], array());
						echo "</td>\n";
						echo "<td>";
						echo $tab_sous_grp[$loop]['classe'][$i];
						echo "</td>\n";

						echo "<td>";
						$sql="SELECT e.login FROM j_eleves_groupes jeg, eleves e WHERE jeg.id_groupe='".$tab_sous_grp[$loop]['id_groupe'][$i]."' AND e.login=jeg.login AND jeg.periode='$loop';";
						//echo "$sql<br />\n";
						$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
						echo mysqli_num_rows($res_eff);
						echo "</td>\n";
						echo "<td>";
						echo $tab_eff_classe[$tab_sous_grp[$loop]['id_classe'][$i]][$loop];
						echo "</td>\n";
						echo "</tr>\n";
					}
					echo "</table>\n";
					echo "</div>\n";
				}
			}
			echo "<div style='clear:both;'>&nbsp;</div>\n";
			echo "<p><br /></p>\n";
		}
		else {
			echo "<p style='color:red; margin-top:1em; margin-bottom:2em;'>Aucun enseignement sous-groupe d'une classe n'est présent.</p>";
		}
	}

	//=======================================================

	echo "<a name='eff_classes_sexes'></a>\n";
	for($loop=1;$loop<=$max_per;$loop++) {
		echo "<div style='float:left; width:40em;margin:3px;'>\n";
		echo "<p class='bold'>Effectifs par sexe en période $loop&nbsp;: <a href='".$_SERVER['PHP_SELF']."?export_csv=effectifs_sexe&amp;num_periode=$loop'>Export CSV</a></p>\n";
		echo "<table class='boireaus resizable sortable'>\n";
		echo "<tr>\n";
		echo "<th>Classes</th>\n";
		echo "<th>Effectifs garçons</th>\n";
		echo "<th>Effectifs filles</th>\n";
		echo "<th>Effectifs totaux</th>\n";
		echo "</tr>\n";
		$alt=1;
		for($i=0;$i<count($tab_classe);$i++) {
			$alt=$alt*(-1);
			echo "<tr class='lig$alt white_hover'>\n";
			echo "<td>".$tab_classe[$i]['classe']."</td>\n";

			echo "<td>";
			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='".$tab_classe[$i]['id']."' AND e.login=jec.login AND jec.periode='$loop' AND e.sexe='M';";
			//echo "$sql<br />\n";
			$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
			echo mysqli_num_rows($res_eff);
			echo "</td>\n";

			echo "<td>";
			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='".$tab_classe[$i]['id']."' AND e.login=jec.login AND jec.periode='$loop' AND e.sexe='F';";
			//echo "$sql<br />\n";
			$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
			echo mysqli_num_rows($res_eff);
			echo "</td>\n";

			echo "<td>";
			$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='".$tab_classe[$i]['id']."' AND e.login=jec.login AND jec.periode='$loop';";
			//echo "$sql<br />\n";
			$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
			echo mysqli_num_rows($res_eff);
			echo "</td>\n";

			echo "</tr>\n";
		}
		echo "<tr>
	<th>Total</th>
	<th>";
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$loop' AND e.sexe='M';";
		//echo "$sql<br />\n";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		echo mysqli_num_rows($res_eff);
		echo "</th>
	<th>";
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$loop' AND e.sexe='F';";
		//echo "$sql<br />\n";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		echo mysqli_num_rows($res_eff);
		echo "</th>
	<th>";
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$loop';";
		//echo "$sql<br />\n";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		echo mysqli_num_rows($res_eff);
		echo "</th>
</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}
	echo "<div style='clear:both;'>&nbsp;</div>\n";
	echo "<p><br /></p>\n";
}
$tab_mef=get_tab_mef();
/*
echo "<pre>";
print_r($tab_mef);
echo "</pre>";
*/
echo "
<a name='eff_mef'></a>";
	$tab_eff_classe=array();
	//$max_per=old_mysql_result($res_per, 0, "num_periode");
	for($loop=1;$loop<=$max_per;$loop++) {
		echo "<div style='float:left; width:30em;margin:3px;'>\n";
		echo "<p class='bold'>Effectifs par MEF en période $loop&nbsp;: <a href='".$_SERVER['PHP_SELF']."?export_csv=mef&amp;num_periode=$loop'>Export CSV</a></p>\n";
		echo "<table class='boireaus boireaus_alt resizable sortable'
	<thead>
		<tr>
			<th>MEF</th>
			<th>Classes</th>
			<th>Effectifs</th>
		</tr>
	</thead>
	<tbody>\n";
		foreach($tab_mef as $code_mef => $mef_courant) {
			// Récupérer la liste des classes associées et effectifs associés
			// N'afficher que si l'effectif est non nul
		}

		$sql="SELECT m.*, COUNT(e.login) AS effectif FROM eleves e, mef m WHERE e.mef_code=m.mef_code AND e.login in (SELECT login FROM j_eleves_classes WHERE periode='".$loop."') GROUP BY mef_code ORDER BY m.libelle_court, m.libelle_long, m.libelle_edition, m.mef_code;";
		//echo "$sql<br />";
		$res_mef=mysqli_query($mysqli, $sql);
		while($lig_mef=mysqli_fetch_object($res_mef)) {
			$liste_classes='';
			$sql="SELECT DISTINCT c.classe, jec.id_classe FROM eleves e, 
													j_eleves_classes jec, 
													classes c 
												WHERE e.mef_code='".$lig_mef->mef_code."' AND 
													e.login=jec.login AND periode='".$loop."' AND 
													jec.id_classe=c.id 
												ORDER BY c.classe;";
			//echo "$sql<br />";
			$res_mef_classe=mysqli_query($mysqli, $sql);
			$cpt_mef_classe=0;
			while($lig_mef_classe=mysqli_fetch_object($res_mef_classe)) {
				if($cpt_mef_classe>0) {$liste_classes.=', ';}
				$liste_classes.=$lig_mef_classe->classe;
				$cpt_mef_classe++;
			}
			echo "
		<tr>
			<td>".$tab_mef[$lig_mef->mef_code]['designation_courte']."</td>
			<td>".$liste_classes."</td>
			<td>".$lig_mef->effectif."</td>
		</tr>";
		}

		// MEF VIDE
		$sql="SELECT COUNT(e.login) AS effectif FROM eleves e, 
									j_eleves_classes jec 
								WHERE e.login=jec.login AND 
									jec.periode='".$loop."' AND 
									(e.mef_code='' OR e.mef_code='-1' OR e.mef_code IS NULL OR e.mef_code NOT IN (SELECT mef_code FROM mef WHERE mef_code!=''));";
		//echo "$sql<br />";
		$res_mef=mysqli_query($mysqli, $sql);
		while($lig_mef=mysqli_fetch_object($res_mef)) {
			$liste_classes='';
			$sql="SELECT DISTINCT c.classe, jec.id_classe FROM eleves e, 
													j_eleves_classes jec, 
													classes c 
												WHERE (e.mef_code='' OR e.mef_code='-1' OR e.mef_code IS NULL OR e.mef_code NOT IN (SELECT mef_code FROM mef WHERE mef_code!='')) AND 
													e.login=jec.login AND 
													periode='".$loop."' AND 
													jec.id_classe=c.id 
												ORDER BY c.classe;";
			//echo "$sql<br />";
			$res_mef_classe=mysqli_query($mysqli, $sql);
			$cpt_mef_classe=0;
			while($lig_mef_classe=mysqli_fetch_object($res_mef_classe)) {
				if($cpt_mef_classe>0) {$liste_classes.=', ';}
				$liste_classes.=$lig_mef_classe->classe;
				$cpt_mef_classe++;
			}
			echo "
		<tr>
			<td style='color:red' title=\"MEF non renseigné\">Aucun</td>
			<td>".$liste_classes."</td>
			<td>".$lig_mef->effectif."</td>
		</tr>";
		}

		echo "
	</tbody>
	<tfoot>
		<tr>
			<th>Total</th>
			<th></th>
			<th>";
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$loop';";
		//echo "$sql<br />\n";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		echo mysqli_num_rows($res_eff);
		echo "
			</th>
		</tr>
	</tfoot>
</table>\n";
		echo "</div>\n";
	}
	echo "<div style='clear:both;'>&nbsp;</div>\n";
	echo "<p><br /></p>\n";

echo "
<a name='eff_mef_rattachement'></a>";
	$tab_eff_classe=array();
	//$max_per=old_mysql_result($res_per, 0, "num_periode");
	for($loop=1;$loop<=$max_per;$loop++) {
		echo "<div style='float:left; width:30em;margin:3px;'>\n";
		echo "<p class='bold'>Effectifs par MEF_rattachement en période $loop&nbsp;: <a href='".$_SERVER['PHP_SELF']."?export_csv=mef_rattachement&amp;num_periode=$loop'>Export CSV</a></p>\n";
		echo "<table class='boireaus boireaus_alt resizable sortable'
	<thead>
		<tr>
			<th>MEF rattachement</th>
			<th>Classes</th>
			<th>Effectifs</th>
		</tr>
	</thead>
	<tbody>\n";
		foreach($tab_mef as $code_mef => $mef_courant) {
			// Récupérer la liste des classes associées et effectifs associés
			// N'afficher que si l'effectif est non nul
		}

		$sql="SELECT m.*, COUNT(e.login) AS effectif FROM eleves e, mef m WHERE e.mef_code=m.mef_code AND e.login in (SELECT login FROM j_eleves_classes WHERE periode='".$loop."') GROUP BY mef_rattachement ORDER BY m.libelle_court, m.libelle_long, m.libelle_edition, m.mef_code;";
		$res_mef=mysqli_query($mysqli, $sql);
		while($lig_mef=mysqli_fetch_object($res_mef)) {
			$liste_classes='';
			$sql="SELECT DISTINCT c.classe, jec.id_classe FROM eleves e, 
													j_eleves_classes jec, 
													classes c 
												WHERE e.mef_code='".$lig_mef->mef_code."' AND 
													e.login=jec.login AND periode='".$loop."' AND 
													jec.id_classe=c.id 
												ORDER BY c.classe;";
			$res_mef_classe=mysqli_query($mysqli, $sql);
			$cpt_mef_classe=0;
			while($lig_mef_classe=mysqli_fetch_object($res_mef_classe)) {
				if($cpt_mef_classe>0) {$liste_classes.=', ';}
				$liste_classes.=$lig_mef_classe->classe;
				$cpt_mef_classe++;
			}
			echo "
		<tr>
			<td>".(isset($tab_mef[$lig_mef->mef_rattachement]['designation_courte']) ? $tab_mef[$lig_mef->mef_rattachement]['designation_courte'] : "<span title=\"MEF_rattachement inconnu ? Contrôlez les MEF.\" style='color:red'>???</span>")."</td>
			<td>".$liste_classes."</td>
			<td>".$lig_mef->effectif."</td>
		</tr>";
		}

		// MEF VIDE
		$sql="SELECT COUNT(e.login) AS effectif FROM eleves e, 
									j_eleves_classes jec 
								WHERE e.login=jec.login AND 
									jec.periode='".$loop."' AND 
									(e.mef_code='' OR e.mef_code='-1' OR e.mef_code IS NULL OR e.mef_code NOT IN (SELECT mef_code FROM mef WHERE mef_code!=''));";
		//echo "$sql<br />";
		$res_mef=mysqli_query($mysqli, $sql);
		while($lig_mef=mysqli_fetch_object($res_mef)) {
			$liste_classes='';
			$sql="SELECT DISTINCT c.classe, jec.id_classe FROM eleves e, 
													j_eleves_classes jec, 
													classes c 
												WHERE (e.mef_code='' OR e.mef_code='-1' OR e.mef_code IS NULL OR e.mef_code NOT IN (SELECT mef_code FROM mef WHERE mef_code!='')) AND 
													e.login=jec.login AND 
													periode='".$loop."' AND 
													jec.id_classe=c.id 
												ORDER BY c.classe;";
			//echo "$sql<br />";
			$res_mef_classe=mysqli_query($mysqli, $sql);
			$cpt_mef_classe=0;
			while($lig_mef_classe=mysqli_fetch_object($res_mef_classe)) {
				if($cpt_mef_classe>0) {$liste_classes.=', ';}
				$liste_classes.=$lig_mef_classe->classe;
				$cpt_mef_classe++;
			}
			echo "
		<tr>
			<td style='color:red' title=\"MEF non renseigné\">Aucun</td>
			<td>".$liste_classes."</td>
			<td>".$lig_mef->effectif."</td>
		</tr>";
		}

		echo "
	</tbody>
	<tfoot>
		<tr>
			<th>Total</th>
			<th></th>
			<th>";
		$sql="SELECT e.login FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND jec.periode='$loop';";
		//echo "$sql<br />\n";
		$res_eff=mysqli_query($GLOBALS["mysqli"], $sql);
		echo mysqli_num_rows($res_eff);
		echo "
			</th>
		</tr>
	</tfoot>
</table>\n";
		echo "</div>\n";
	}
	echo "<div style='clear:both;'>&nbsp;</div>\n";
	echo "<p><br /></p>\n";

/*
$sql="SELECT COUNT(e.login) AS nb_filles FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='XXX' AND e.login=jec.login AND e.sexe='F';";
echo "$sql<br />\n";

$sql="SELECT COUNT(e.login) AS nb_garcons FROM j_eleves_classes jec, eleves e WHERE jec.id_classe='XXX' AND e.login=jec.login AND e.sexe='M';";
echo "$sql<br />\n";

$sql="SELECT COUNT(e.login) AS nb_filles FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND e.sexe='F';";
echo "$sql<br />\n";

$sql="SELECT COUNT(e.login) AS nb_garcons FROM j_eleves_classes jec, eleves e WHERE e.login=jec.login AND e.sexe='M';";
echo "$sql<br />\n";
*/
// Pour afficher au-dessus du photocopieur:...

echo "<p><em>NOTES&nbsp;:</em> Certains de ces tableaux peuvent par exemple servir pour un affichage des effectifs au-dessus du photocopieur.</p>\n";

require_once("../lib/footer.inc.php");
?>
