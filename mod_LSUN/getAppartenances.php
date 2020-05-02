<?php

/*
*
* Copyright 2016-2019 Régis Bouguin, Stéphane Boireau
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

//$_SESSION['fichier_sts_emp'] = filter_input(INPUT_POST, 'fichier_sts_emp');
// echo $_FILES['fichier_sts_emp']['name'];
$_SESSION['fichier_sts_emp'] = isset($_FILES['fichier_sts_emp']['name']) ? $_FILES['fichier_sts_emp']['name'] : (isset($_SESSION['fichier_sts_emp']) ? $_SESSION['fichier_sts_emp'] : NULL);

if(isset($_FILES['fichier_sts_emp']['tmp_name'])) {
	unset($xml);

	/*
	echo "<pre>";
	print_r($_FILES);
	echo "</pre>";
	*/

	if(!isset($_FILES['fichier_sts_emp']['error']) || is_array($_FILES['fichier_sts_emp']['error'])) {
		$msg="Erreur sur l'upload du XML STS_EMP.<br />";
	}
	elseif($_FILES['fichier_sts_emp']['error']==UPLOAD_ERR_OK) {
		if((isset($_FILES['fichier_sts_emp']['type']))&&(!preg_match('/xml/i', $_FILES['fichier_sts_emp']['type']))) {
			$msg="Le fichier n'est pas un fichier XML&nbsp;: ".$_FILES['fichier_sts_emp']['type']."<br />";
		}
		else {
			libxml_use_internal_errors(true);
			$xml = simplexml_load_file($_FILES['fichier_sts_emp']['tmp_name']);
			if(!$xml) {
				$msg="Echec de l'ouverture du fichier&nbsp;: Est-ce bien un fichier XML&nbsp;?<br />";
				unset($xml);
			}
		}
	}
	else {
		$msg="Erreur sur l'upload du XML STS_EMP.<br />";
	}
}

if (filter_input(INPUT_POST, 'corrigeMEF')) {
	//debug_var();

	// On enregistre les MEF
	$classeBase = filter_input(INPUT_POST, 'classeBase', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	//print_r($classeBase);
	//var_dump($classeBase);
	$nom_completBase = filter_input(INPUT_POST, 'nom_completBase', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	$codeMefBase = filter_input(INPUT_POST, 'codeMefBase', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	$classeFichier = filter_input(INPUT_POST, 'classeFichier', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	$nom_completFichier = filter_input(INPUT_POST, 'nom_completFichier', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	$codeMefFichier = filter_input(INPUT_POST, 'codeMefFichier', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

	$classeXML = filter_input(INPUT_POST, 'classeXML', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	$classeBase2 = filter_input(INPUT_POST, 'classeBase2', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	$nom_completBase2 = filter_input(INPUT_POST, 'nom_completBase2', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	$mefAppartenance2=filter_input(INPUT_POST, 'mefAppartenance2', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	$nomXMLclasse=filter_input(INPUT_POST, 'nomXMLclasse', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

	// $key est l'id_classe (champ classes.id)
	if(is_array($classeBase)) {
		foreach ($classeBase as $key=>$classeActuelle) {
			//echo $key." ".$classeActuelle." ".$nom_completBase[$key].'<br>';
			$sql = "UPDATE classes SET mef_code = '$codeMefFichier[$key]' WHERE classe = '$classeActuelle' AND nom_complet = '$nom_completBase[$key]';";
			//echo $sql.'<br>';
			$mysqli->query($sql);

			$sql="SELECT * FROM classes_param WHERE id_classe='".$key."';";
			//echo $sql.'<br>';
			$test=mysqli_query($GLOBALS['mysqli'], $sql);
			if(mysqli_num_rows($test)==0) {
				$sql="INSERT INTO classes_param SET name='nom_classe_sts', value='".$classeXML[$key]."', id_classe='".$key."';";
				//echo $sql.'<br>';
				$insert=mysqli_query($GLOBALS['mysqli'], $sql);
			}
			else {
				$sql="UPDATE classes_param SET name='nom_classe_sts', value='".$classeXML[$key]."' WHERE id_classe='".$key."';";
				//echo $sql.'<br>';
				$update=mysqli_query($GLOBALS['mysqli'], $sql);
			}
		}
	}

	if(is_array($classeBase2)) {
		foreach ($classeBase2 as $key=>$classeActuelle) {
			if($nomXMLclasse[$key]=='classe_non_sconet_sans_remontee_LSU') {
				saveParamClasse($key, 'type_classe', 'non_sconet');
			}
			else {
				//echo $key." ".$classeActuelle." ".$nom_completBase[$key].'<br>';
				$sql = "UPDATE classes SET mef_code = '$mefAppartenance2[$key]' WHERE classe = '$classeActuelle' AND nom_complet = '$nom_completBase2[$key]';";
				//echo $sql.'<br>';
				$mysqli->query($sql);

				if(isset($nomXMLclasse[$key])) {
					$sql="SELECT * FROM classes_param WHERE id_classe='".$key."';";
					//echo $sql.'<br>';
					$test=mysqli_query($GLOBALS['mysqli'], $sql);
					if(mysqli_num_rows($test)==0) {
						$sql="INSERT INTO classes_param SET name='nom_classe_sts', value='".$nomXMLclasse[$key]."', id_classe='".$key."';";
						//echo $sql.'<br>';
						$insert=mysqli_query($GLOBALS['mysqli'], $sql);
					}
					else {
						$sql="UPDATE classes_param SET name='nom_classe_sts', value='".$nomXMLclasse[$key]."' WHERE id_classe='".$key."';";
						//echo $sql.'<br>';
						$update=mysqli_query($GLOBALS['mysqli'], $sql);
					}
				}
			}
		}
	}
}

$tbs_CSS_spe[] = array('rel'=>"stylesheet", 'type'=>"text/css", 'fichier'=>"lib/style.css", 'media'=>"screen");
$titre_page = "AP - EPI - parcours";
if (!suivi_ariane($_SERVER['PHP_SELF'],'AP-EPI')) {
	echo "erreur lors de la création du fil d'ariane";
}
require_once("../lib/header.inc.php");
//**************** FIN EN-TETE *****************

//debug_var();
?>
<!--
<form action="index.php" method="post" enctype="multipart/form-data" id="formFichier">
	<h2>Rapprochement des noms de classes et renseignement des MEFs</h2>
	<p class="center">
		<label for="fichier_sts_emp">Choisissez le fichier sts_emp :</label>
		<input type="file" name="fichier_sts_emp" id="fichier_sts_emp" />
		<button>valider</button>
	</p>
</form>
-->
<form action="index.php" method="post" enctype="multipart/form-data" id="formFichier">
	<h2>Rapprochement des noms de classes et renseignement des MEFs</h2>
	<p class="center">
		<label for="fichier_sts_emp">Choisissez le fichier sts_emp :</label>
		<input type="file" name="fichier_sts_emp" id="fichier_sts_emp" />
		<input type='submit' id='input_submit' value='Valider' />
		<input type='button' id='input_button' value='Valider' style='display:none;' onclick="check_champ_file()" />
	</p>

	<script type='text/javascript'>
		document.getElementById('input_submit').style.display='none';
		document.getElementById('input_button').style.display='';

		function check_champ_file() {
			fichier=document.getElementById('fichier_sts_emp').value;
			//alert(fichier);
			if(fichier=='') {
				alert('Vous n\'avez pas sélectionné de fichier XML à envoyer.');
			}
			else {
				document.getElementById('formFichier').submit();
			}
		}
	</script>
</form>

<?php
if(isset($xml)) {
	/*
	echo "<pre>";
	print_r($xml);
	echo "</pre>";
	*/
	
	$debug_import="n";
	$cpt=0;
	$tab_xml=array();
	$tab_indices_maj_classes_xml=array();
	$tab_indices_maj_unaccent_classes_xml=array();
	if((!$xml->DONNEES)||(!$xml->DONNEES->STRUCTURE)||(!$xml->DONNEES->STRUCTURE->DIVISIONS)) {
		echo "<p style='color:red'><strong>ERREUR&nbsp;:</strong> Fichier XML non valide.<br />
		Il devrait avoir une structure DONNEES-&gt;STRUCTURE-&gt;DIVISIONS.</p>";
		require_once("../lib/footer.inc.php");
		die();
	}
	$objet_structures=($xml->DONNEES->STRUCTURE->DIVISIONS);
	foreach ($objet_structures->children() as $current_division) {
		//echo("<p><b>Structure</b><br />");

/*
<DIVISION CODE="3 A">
<LIBELLE_LONG/>
<EFFECTIF_PREVU>16</EFFECTIF_PREVU>
<EFFECTIF_CALCULE>14</EFFECTIF_CALCULE>
<MEFS_APPARTENANCE>
<MEF_APPARTENANCE CODE="16710002110">
<EFFECTIF_PREVU>16</EFFECTIF_PREVU>
<EFFECTIF_CALCULE>14</EFFECTIF_CALCULE>
</MEF_APPARTENANCE>
</MEFS_APPARTENANCE>
*/
		foreach($current_division->attributes() as $key => $value) {
			//echo("$key=".$value."<br />");

			if(mb_strtoupper($key)=='CODE') {
				//$nom_classe=casse_mot($value, "maj");
				$nom_classe=$value;
				$tab_xml["$nom_classe"]=array();
				$tab_indices_maj_classes_xml[]=casse_mot($nom_classe, "maj");
				$tab_indices_maj_unaccent_classes_xml[]=remplace_accents(preg_replace("/[ _.-]/", "", casse_mot($nom_classe, "maj")), "all");

				foreach($current_division->children() as $key2 => $value2) {
					$tab_xml["$nom_classe"][$key2]=$value2;
				}

				if($debug_import=='y') {
					echo "<pre style='color:green;'><b>Tableau \$tab_xml[\"$nom_classe\"]&nbsp;:</b>";
					print_r($tab_xml["$nom_classe"]);
					echo "</pre>";
				}
			}
		}
	}

?>
<form action="index.php" method="post"  id="formCodeMef">
<table class='boireaus_alt'>
	<thead>
	<tr>
		<th colspan="3" style='background-color:lightgreen'>Base GEPI</th>
		<th colspan="3" style='background-color:plum;'><?php echo $_SESSION['fichier_sts_emp']; ?></th>
	</tr>
	<tr>
		<th style='background-color:lightgreen'>classe</th>
		<th style='background-color:lightgreen'>nom complet</th>
		<th style='background-color:lightgreen'>code mef</th>
		<th style='background-color:plum;'>classe</th>
		<th style='background-color:plum;'>nom complet</th>
		<th style='background-color:plum;'>code mef</th>
	</tr>
	</thead>
<?php 
$listeClasse = getClasses();
$cpt = 0;
while ($classe = $listeClasse->fetch_object()) {

	$nom_classe_xml="";
	if(array_key_exists($classe->classe, $tab_xml)) {
		$nom_classe_xml=$classe->classe;
	}
	elseif(array_key_exists(casse_mot($classe->classe, "maj"), $tab_xml)) {
		foreach($tab_xml as $current_nom_div => $current_div) {
			if(casse_mot($classe->classe, "maj")==$current_nom_div) {
				$nom_classe_xml=$current_nom_div;
				break;
			}
		}
	}
	elseif(in_array(casse_mot($classe->classe, "maj"), $tab_indices_maj_classes_xml)) {
		foreach($tab_xml as $current_nom_div => $current_div) {
			if(casse_mot($classe->classe, "maj")==casse_mot($current_nom_div, "maj")) {
				$nom_classe_xml=$current_nom_div;
				break;
			}
		}
	}
	elseif(in_array(remplace_accents(preg_replace("/[ _.-]/","",casse_mot($classe->classe, "maj")), "all"), $tab_indices_maj_unaccent_classes_xml)) {
		foreach($tab_xml as $current_nom_div => $current_div) {
			if(remplace_accents(preg_replace("/[ _.-]/","",casse_mot($classe->classe, "maj")), "all")==remplace_accents(preg_replace("/[ _.-]/","",casse_mot($current_nom_div, "maj")),"all")) {
				$nom_classe_xml=$current_nom_div;
				break;
			}
		}
	}

	if($nom_classe_xml!="") {
		$result = $xml->xpath("/STS_EDT/DONNEES/STRUCTURE/DIVISIONS/DIVISION[@CODE='".$nom_classe_xml."']");
		echo "
	<tr class='lig".(($cpt%2)-1)."'>
		<td>
			<input type='hidden' name='classeBase[".$classe->id."]' value=\"".$classe->classe."\" />
			<a href='../classes/modify_nom_class.php?id_classe=".$classe->id."#type_de_classe' target='_blank' title='Voir/modifier les paramètres de cette classe dans un nouvel onglet.'>".$classe->classe."</a>
		</td>
		<td>
			<input type='hidden' name='nom_completBase[".$classe->id."]' value=\"".$classe->nom_complet."\" />
			".$classe->nom_complet."
		</td>
		<td>
			".$classe->mef_code."
		</td>
		<td>
			".$nom_classe_xml."
			<input type='hidden' name='classeXML[".$classe->id."]' value=\"".$nom_classe_xml."\" />
		</td>
		<td>
			".(isset($tab_xml["$nom_classe_xml"]["LIBELLE_LONG"]) ? $tab_xml["$nom_classe_xml"]["LIBELLE_LONG"] : "")."
		</td>
		<td>
			<!--input type='text' name='mefAppartenance[".$classe->id."]' value=\"".$result[0]->MEFS_APPARTENANCE->MEF_APPARTENANCE['CODE']."\" /-->
			<input type='text' name='codeMefFichier[".$classe->id."]' value=\"".$result[0]->MEFS_APPARTENANCE->MEF_APPARTENANCE['CODE']."\" />
		</td>
	</tr>";
	}
	else {
		echo "
	<tr class='lig".(($cpt%2)-1)."'>
		<td>
			<input type='hidden' name='classeBase2[".$classe->id."]' value=\"".$classe->classe."\" />
			<a href='../classes/modify_nom_class.php?id_classe=".$classe->id."#type_de_classe' target='_blank' title='Voir/modifier les paramètres de cette classe dans un nouvel onglet.'>".$classe->classe."</a>
		</td>
		<td>
			<input type='hidden' name='nom_completBase2[".$classe->id."]' value=\"".$classe->nom_complet."\" />
			".$classe->nom_complet."
		</td>
		<td>
			".$classe->mef_code."
		</td>
		<td colspan='2' style='text-align:left;'>
			<select name='nomXMLclasse[".$classe->id."]'>
				<option value=''>---</option>";
		foreach($tab_xml as $current_nom_div => $current_div) {
			echo "
				<option value='".$current_nom_div."'>$current_nom_div</option>";
		}
		echo "
				<option value='classe_non_sconet_sans_remontee_LSU'>Hors Sconet (pas de remontée LSU)</option>
			</select>
		</td>
		<td>
			<input type='text' name='mefAppartenance2[".$classe->id."]' value=\"\" />
		</td>
	</tr>";
	}
$cpt ++;
}

?>
</table>

	<p class="center">
		<button name="corrigeMEF" value="y">Enregistrer les MEF</button>
	</p>
</form>

<?php 
}

echo "
<p style='margin-top:1em;'><em>NOTES&nbsp;:</em></p>
<ul>
	<li>
		<p>Vous allez rapprocher ici les noms Sconet des classes et les noms que vous avez éventuellement modifiés dans Gepi<br />
		Vous devrez également préciser les MEF des classes <em>(information en principe présente dans le fichier XML et donc récupérée autmatiquement par import)</em>.</p>
	</li>
	<li style='margin-top:1em;'>
		<p>Si certaines classes n'existent pas dans Sconet, parce que correspondant à des usages particuliers, vous devez le déclarer dans <strong>Gestion des bases/Gestion des classes/&lt;Telle classe&gt; Paramètres</strong> à la rubrique <strong>Type de classe</strong> pour indiquer qu'il s'agit d'une classe <strong>classe non standard <em>(hors Sconet,...)</em></strong>.<br />
	Elle ne sera alors pas remontée vers LSU et ne vous bloquera pas dans la présente page.</p>
	</li>
</ul>
<!-- Pour avoir un peu de place en bas de page -->
<p><br /></p>";

//debug_var();
//**************** Pied de page *****************
require_once("../lib/footer.inc.php");
//**************** Fin de pied de page *****************
