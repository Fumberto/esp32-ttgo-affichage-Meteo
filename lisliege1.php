<?php
$jo=$_GET['jo'];
$file = file_get_contents("http://api.openweathermap.org/data/2.5/forecast/?q=liege&mode=xml&APPID=CODEAPI&units=metric");
if (!$file) {
  echo "<p>Erreur\n";
  exit;
}

$cie[200]="Orage avec pluie legere";
$cie[201]="Orage avec pluie";
$cie[202]="Orage avec fortes pluies";
$cie[210]="Orage leger";
$cie[211]="Orage";
$cie[212]="Orage violent";
$cie[221]="Orage en lambeaux";
$cie[230]="Orage avec bruine legere";
$cie[231]="Orage avec bruine";
$cie[232]="Orage avec forte bruine";
$cie[300]="Bruine d'intensite lumineuse";
$cie[301]="Bruine";
$cie[302]="Bruine intense";
$cie[310]="intensite legere bruine pluie";
$cie[311]="pluie fine";
$cie[312]="pluie forte bruine intense";
$cie[313]="pluie et bruine sous la pluie";
$cie[314]="fortes averses de pluie et bruine";
$cie[321]="bruine de douche";
$cie[500]="pluie legere";
$cie[501]="pluie moderee";
$cie[502]="pluie forte intensite";
$cie[503]="tres forte pluie";
$cie[504]="pluie extreme";
$cie[511]="pluie verglacante";
$cie[520]="pluie legere";
$cie[521]="pluie de pluie";
$cie[522]="pluie forte pluie";
$cie[531]="pluie de pluie en lambeaux";
$cie[600]="neige legere";
$cie[601]="Neige";
$cie[602]="Beaucoup de neige";
$cie[611]="Neige fondue";
$cie[612]="Douche legere";
$cie[613]="Douche de douche";
$cie[615]="Faible pluie et neige";
$cie[616]="Pluie et neige";
$cie[620]="Faible averse de neige";
$cie[621]="Averse de neige";
$cie[622]="Fortes averses de neige";
$cie[701]="brouillard";
$cie[711]="Fumee";
$cie[721]="Brume";
$cie[731]="tourbillons de sable/poussiere";
$cie[741]="brouillard";
$cie[751]="le sable";
$cie[761]="poussiere";
$cie[762]="cendre volcanique";
$cie[771]="grains";
$cie[781]="tornade";
$cie[800]="Ciel clair";
$cie[801]="quelques nuages: 11-25%";
$cie[802]="nuages ​​epars: 25-50%";
$cie[803]="nuages ​​epars: 51-84%";
$cie[804]="nuages ​​couverts: 85-100%";


		function skip_accents($varMaChaine)
		{
			$search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
			//Préférez str_replace à strtr car strtr travaille directement sur les octets, ce qui pose problème en UTF-8
			$replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');

//			$varMaChaine = str_replace($search, $replace, $varMaChaine);
			return str_replace($search, $replace, $varMaChaine);// $varMaChaine; //On retourne le résultat
		}
function trouvedonne($scpr)
{
	global $file,$nbj,$lafin;
	$ledecal=strlen($scpr);
	$pos=strpos($file,$scpr,0);
	if ($nbj>0)
	{
		$pos=1;
		for ($i=0;$i<=$nbj;$i++)
		{
			$pos=strpos($file,$scpr,($pos+$ledecal));
		}
	}


	$pos1=strpos($file." ",$lafin,($pos+$ledecal));
	$scpr= substr($file,$pos+$ledecal,($pos1-($pos+$ledecal)));
	return $scpr;
}
$nbj=0;
$lafin='<';
echo chr(36)."Ville: #".skip_accents(trouvedonne('<name>'))." ( ".$jo." )*";
$lafin='"';
echo chr(36)."Lat: #".trouvedonne('latitude="')." : ";//."*";
echo chr(36)."Lon: #".trouvedonne('longitude="')."*";
$nbj=0+$jo;
echo chr(36)."Dat: #".str_replace("T"," ",trouvedonne('time from="'));//."*";
echo chr(36)." a #".substr(str_replace("T"," ",trouvedonne(' to="')),11)."*";
$nbj=0;
echo chr(36)."SoLev: #".substr(str_replace("T"," ",trouvedonne('rise="')),11)." ";//."*";
echo chr(36)."SoCou: #".substr(str_replace("T"," ",trouvedonne('set="')),11)."*";
$nbj=0+$jo;

$lafin='" var=';
$donn=substr(trouvedonne('<symbol number="'),0,3);
$leciel= $cie[$donn];
echo chr(36)."Ciel: #".$leciel."*";

$lafin='"></temp';
$donn=trouvedonne('sius" value="');
$donn=str_replace('" min="'," ".chr(36)."Min= #",$donn);
$donn=str_replace('" max="'," ".chr(36)."Max= #",$donn);
echo chr(36)."Temp: #".$donn."*";

$lafin='"></windS';
$donn=trouvedonne('peed mps="');
$donn=str_replace('" unit="m/s" name="'," ",$donn);
$donn=str_replace('Moderate breeze',"Brise legere",$donn);


$lafin='"></pr';
$donn=trouvedonne('unit="hPa"');
$donn=str_replace('value="',"",$donn)." hPa";
echo chr(36)."Pres: #".$donn."*";

$lafin='" unit="m/s"';
$donn=trouvedonne('windSpeed mps="');
$donn=$donn." m/s";
echo chr(36)."Vent: #".$donn." ";

$lafin='"></windDirection';
$donn=trouvedonne('windDirection deg="');
$donn=str_replace('" code="'," ",$donn);

$pos=strpos($donn,' name="',0);
echo " ".substr($donn,0,($pos-1));
echo "$$";
?>
