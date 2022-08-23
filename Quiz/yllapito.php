<?php
session_start();

include 'yllapitofunktiot.php';

if(!isset($_SESSION['otsikko'])) $_GET['summa'] = ""; 
if(!isset($_SESSION['tyhja'])) $_GET['summa'] = ""; 
if(!isset($_GET['uusilista'])) $_GET['summa'] = ""; 
if(!isset($_SESSION['otsikko'])) $_SESSION['otsikko'] = ""; 


$otsikko ="";
$sisalto ="";
$tarkastus ="";
$save = 0;
$errmsg = '';

$muokkausKysymys ="";
$muokkausVaihtoa =""; 
$muokkausVaihtob ="";			
$muokkausVaihtoc =""; 
$muokkausVaihtod =""; 

$chka ='';
$chkb ='';
$chkc ='';
$chkd ='';

echo '<style type="text/css">
        #list {
            display:none;
        }
		#muokkaukset {
			display:none;
		}
		#tallenna {
			display:none;
		}
		#peruuta {
			display:none;
		}
		#poistapelaaja {
			display:none;
		}
		#vaihalista  {
			display:none;
		}
		#kysymyksienotsikko  {
			display:none;
		}
		#tyhjalista  {
			display:none;
		}
		#poistalista  {
			display:none;
		}
		#listaa  {
			display:none;
		}
		#valmis  {
			display:none;
		}
		#nollaa {
            display:none;
        }

        </style>';

/******************************************  issiet  ******************************** *****/

//Lomakkeen listaa-painiketta painattessa, lisätään $sisalto-muuttujaan kysymykset

if (isset($_GET['lista'])){
	
	echo '<style type="text/css">
        #list {
            display:block;
        }
		#muokkaukset {
            display:inline;
        }
		#listaa {
            display:none;
        }
		#poistapisteet {
            display:none;
        }
		
        </style>';
	
	$jsonString = file_get_contents('json/kysymykset.json');
	$data = json_decode($jsonString, true);
	
	foreach ($data as $key => $entry) {
		$id = (string)$entry['ID'];
		$sisalto .= "<option value=".$id.">".$entry['kysymys']."</option>";
    }
}

//Listaa kysymyslistat, eli kaikki ne json-tiedostot jotka ovat olemassa
if (isset($_GET['listaakysymyslista'])){
	
	echo '<style type="text/css">
        #list {
            display:block;
        }
		#muokkaukset {
            display:none;
        }
		#listaapelaajat {
            display:none;
        }
		#listaa {
            display:none;
			
        }#listaakysymyslista {
            display:none;
        }
		#poistalista  {
			display:inline;
		}
		#vaihalista  {
			display:block;
		}
		#poistapisteet  {
			display:none;
		}
        </style>';
		
		$kysymystaulukko = [];
		//Otetaan listat tietokannasta ja asetetaan niiden nimet $sisaltoon, joka echotaan optiooniin
		require_once("/home/N3031/db-config/PelaajatDb.php");
		$listaObj = new PelaajatDb();
		$tyhja_hakusana = '';
		$listat = $listaObj->getListat($tyhja_hakusana);
		
		foreach ($listat as $lista) {	
		$kysymystaulukko[$lista->id] = $lista->lista;

		}
		//Key on id, value on listan nimi
		foreach ($kysymystaulukko as $key => $value) {
		$sisalto .= "<option id='tarkastelu' value=".$key.">".$value."</option>";
		}
}

//Vaihetaan valittu lista "kysymyslistaksi", eli siihen joka pyörii tietovisassa

if (isset($_GET['vaihalista'])){
	
	echo '<style type="text/css">
        #list {
            display:none;
        }
		#muokkaukset {
            display:none;
        }
		#listaa {
            display:inline;
        }
		#listaakysymyslista {
            display:none;
        }
		#valmis  {
			display:inline;
		}
		#listaapelaajat {
            display:none;	
        }
		#kysymyksienotsikko {
            display:inline;
        }}
		
        </style>';
	lataaLista();
}

//Listaa pelaajat
if (isset($_GET['pelaaja'])){
	
		echo '<style type="text/css">
        #list {
            display:block;
        }
		#poistapelaaja {
            display:inline;
        }
		#listaapelaajat {
            display:none;
        }		
		#listaa {
            display:none;
        }
		#nollaa {
            display:inline;
        }
        </style>';
	
		require_once("/home/N3031/db-config/PelaajatDb.php");
		$pelaajaObj = new PelaajatDb();
		$tyhja_hakusana = '';
		$pelaajat = $pelaajaObj->getPelaajat($tyhja_hakusana);
		
		foreach ($pelaajat as $pelaaja) {
			if ($pelaaja->nimi == "yllapito"){
				continue;
			}
				$nimi = $pelaaja->nimi;
				$pisteet = $pelaaja->pisteet;
				$id = $pelaaja->id;
				$sisalto .= "<option id='tarkastelu'value=".$id.">".$nimi." - Pisteet: ".$pisteet. " </option>";			
		}
}

//Lomakkeen lisää-painiketta painattessa (kun lisätään uusi kysymys tai lista)
if (isset($_GET['painike'])){
	
	if($_SESSION['otsikko'] == ""){
		$kysymys = isset($_GET['kysymys']) ? $_GET['kysymys'] : '';
		$vaihtoa = isset($_GET['vaihtoa']) ? $_GET['vaihtoa'] : '';
		$vaihtob = isset($_GET['vaihtob']) ? $_GET['vaihtob'] : '';
		$vaihtoc = isset($_GET['vaihtoc']) ? $_GET['vaihtoc'] : '';
		$vaihtod = isset($_GET['vaihtod']) ? $_GET['vaihtod'] : '';
		$oikea = isset($_GET['oikea']) ? $_GET['oikea'] : '';
		$listanimi = isset($_GET['uusilista']) ? $_GET['uusilista'] : '';
		
		if ($kysymys == "" || $vaihtoa == "" || $vaihtoa == "" || $vaihtoa == "" || $vaihtoa == "" || $oikea == "" || $listanimi == ""){
		$errmsg = '<span style="background: yellow;">Toimintoa ei voitu suoristaa. Tarkista että kaikki kentät on täytetty!';
		
		}else {

			newJson($listanimi);
		}
	}
	//Tämä jos lisätään vanhaan listaan uusi kysymys, nyt toimii lisätessä vanhaan listaan
	elseif ($_SESSION['otsikko'] != ""){
	$otsikkotarkistus = $_SESSION['otsikko'];
	$_SESSION['otsikko'] = "";
	$kysymys = isset($_GET['kysymys']) ? $_GET['kysymys'] : '';
	$vaihtoa = isset($_GET['vaihtoa']) ? $_GET['vaihtoa'] : '';
	$vaihtob = isset($_GET['vaihtob']) ? $_GET['vaihtob'] : '';
	$vaihtoc = isset($_GET['vaihtoc']) ? $_GET['vaihtoc'] : '';
	$vaihtod = isset($_GET['vaihtod']) ? $_GET['vaihtod'] : '';
	$oikea = isset($_GET['oikea']) ? $_GET['oikea'] : '';
	
	if ($kysymys == "" || $vaihtoa == "" || $vaihtoa == "" || $vaihtoa == "" || $vaihtoa == "" || $oikea == ""){
		$errmsg = '<span style="background: yellow;">Toimintoa ei voitu suoristaa. Tarkista että kaikki kentät on täytetty!';
	}
	else {
		
	addJson($otsikkotarkistus);
	}
	}
	
}

//Lomakkeen peruuta-painiketta painattessa (kun poistetaan valittu kysymys)
if (isset($_GET['peruuta'])){
	$_SESSION['otsikko']= "";
	header("Location: yllapito.php");
}
//Painetaan valmis, reloudataan sivu
if (isset($_GET['valmis'])){
	$_SESSION['otsikko']= "";
	header("Location: yllapito.php");
}

//Jos poistutaan
if (isset($_GET['poistu'])){
		header("Location: logout.php");
}
//Jos nollataan pelaajien pisteet
if (isset($_GET['nollaa'])){
	
	require_once("/home/N3031/db-config/PelaajatDb.php");
		$pelaajaObj = new PelaajatDb();
		$tyhja_hakusana = '';
		$pelaajaObj->nollaaPisteet($tyhja_hakusana);
		$_SESSION['otsikko']= "";
		header("Location: yllapito.php");
}
//Reg
if (isset($_GET['reg'])){
		session_destroy();
		header("Location: reg.php");
}
//Lomakkeen poista-painiketta painattessa (kun poistetaan valittu kysymys)
if (isset($_GET['poista'])){
	$otsikkotarkistus = $_SESSION['otsikko'];
	$_SESSION['otsikko'] = "";
	deleteJson($otsikkotarkistus);
	header("Location: yllapito.php");
}

//Lomakkeen poista-painiketta painattessa (kun poistetaan valittu lista kysymys)
if (isset($_GET['poistalista'])){
	
	if ($_GET['valinta'] > 1){
	require_once("/home/N3031/db-config/PelaajatDb.php");
	$listaObj = new PelaajatDb();
	$listaObj->deletelista($_GET['valinta']);
	}
	$_SESSION['otsikko']= "";
	header("Location: yllapito.php");
}

//lataa jasonista kysymykset listaan

if (isset($_GET['listaa'])){

	echo '<style type="text/css">
        #list {
            display:block;
        }#muokkaukset {
            display:inline;
        }
		#listaakysymyslista {
            display:none;
        }
		#listaa {
            display:none;	
        }
		#listaapelaajat {
            display:none;
        }
		#vaihalista  {
			display:none;
		}
		#poistapisteet  {
			display:none;
		}
		#kysymyksienotsikko {
            display:inline;
        }#valmis {
            display:inline;
        }
        </style>';	

	$jsonString = file_get_contents('json/kysymykset.json');
	$data = json_decode($jsonString, true);

	foreach ($data as $key => $entry) {
		$id = (string)$entry['ID'];
		$sisalto .= "<option value=".$id.">".$entry['kysymys']."</option>";
	}
}
//Lomakkeen siirrä-muokattavaksi-painiketta painattessa kysymys siirtyy lomakkeeseen
if (isset($_GET['muokattavaksi'])){
	
	echo '<style type="text/css">
        #lisaa {
            display: none;
        }
		#list {
            display:block;
        }
		#listaa {
            display:inline;
        }
		#tallenna {
            display: block;
        }
		#peruuta {
            display: inline;
        }
		#muokkaukset {
            display:none;
        }
		#listaapelaajat {
            display:none;
        }
		#vaihalista {
            display:none;
        }
		#listaakysymyslista {
            display:none;
        }
        </style>';

	
	
	$jsonString = file_get_contents('json/kysymykset.json');
	$data = json_decode($jsonString, true);
	
	foreach ($data as $key => $entry) {
		if ($entry['ID'] == $_GET['valinta']) {
			$muokkausID = $entry['ID'];
			$muokkausKysymys = $entry['kysymys'];
			$muokkausVaihtoa = $entry['vastaukset']['a'];
			$muokkausVaihtob = $entry['vastaukset']['b'];
			$muokkausVaihtoc = $entry['vastaukset']['c'];
			$muokkausVaihtod = $entry['vastaukset']['d'];
			$muokkausOikea = $entry['oikea'];
			
			if ($muokkausOikea == $muokkausVaihtoa){$chka = 'checked';}
			else if ($muokkausOikea == $muokkausVaihtob){$chkb = 'checked';}
			else if ($muokkausOikea == $muokkausVaihtoc){$chkc = 'checked';}
			else if ($muokkausOikea == $muokkausVaihtod){$chkd = 'checked';}	
			
		}
    }
}
if (isset($_GET['poistapelaaja'])){
	echo '<style type="text/css">
        #ysect {
            display: none;
        }
		
        </style>';
	require_once("/home/N3031/db-config/PelaajatDb.php");
		$pelaajaObj = new PelaajatDb();
		$tyhja_hakusana = '';
		$pelaajaObj->deletepelaaja($_GET['valinta']);

	echo '<style type="text/css">
        #ysect {
            display: block;
        }
		
        </style>';
		$_SESSION['otsikko']= "";
}
//Lomakkeen lisää-painiketta painattessa (kun lisätään uusi kysymys)
if (isset($_GET['muokkaa'])){
	
	$kysymys = isset($_GET['kysymys']) ? $_GET['kysymys'] : '';
	$vaihtoa = isset($_GET['vaihtoa']) ? $_GET['vaihtoa'] : '';
	$vaihtob = isset($_GET['vaihtob']) ? $_GET['vaihtob'] : '';
	$vaihtoc = isset($_GET['vaihtoc']) ? $_GET['vaihtoc'] : '';
	$vaihtod = isset($_GET['vaihtod']) ? $_GET['vaihtod'] : '';
	$oikea = isset($_GET['oikea']) ? $_GET['oikea'] : '';
		
	if($kysymys == "" || $vaihtoa == "" || $vaihtoa == "" || $vaihtoa == "" || $vaihtoa == "" || $oikea == ""){
		$errmsg = '<span style="background: yellow;">Toimintoa ei voitu suoristaa. Tarkista että kaikki kentät on täytetty!';
		
	}
	else {
		$otsikkotarkistus = $_SESSION['otsikko'];
		updateJson($otsikkotarkistus);
		$_SESSION['otsikko']= "";
	}
}


?>

<title>Lisaa kysymys</title>

<html>
<head lang="en">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<link rel="stylesheet" href="tyyli/styles.css">

</head>
<body>
    <div class="yllapito">
        <div id="quiz">
		<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<span><input type="submit" name="poistu" value="Poistu"> 
		<input type="button" value="Takaisin" onclick="history.back()">
		<input type="submit" name="reg" value="Lisää uusi pelaaja">
		</span>
            <h1>Yllapito</h1>
			<?php echo $tarkastus;?>
			<div id="ysect">
			<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<span id="list"><select name='valinta' size="1" style="width:100%;" onmousedown="if(this.options.length>8){this.size=6;}"  onchange='this.size=0;' onblur="this.size=0;"><?php echo $sisalto;?></select></span><br>
			<input id="vaihalista" type="submit" name="vaihalista" value="Lataa lista"><br> 
		    <input id="listaa" type="submit" name="listaa" value="Listaa kysymykset"> 
			<input id="listaakysymyslista" type="submit" name="listaakysymyslista" value="Listaa kysymyslistat">
			<span id="pelaajat"><input type="submit" id="listaapelaajat" name="pelaaja" value="Listaa pelaajat">  
			<input type="submit" id="poistapelaaja" name="poistapelaaja" value="Poista pelaaja">
			<input type="submit" id="nollaa" name="nollaa" value="Nollaa pisteet">
			<!--<input type="submit" id="poistapisteet" name="poistapisteet" value="Nollaa pisteet">--></span>
			<span id="muokkaukset"> <input type="submit" name="poista" value="Poista">	
			<input type="submit" name="muokattavaksi" value="Siirrä muokattavaksi"><br><br></span>
			<input id ="poistalista" type="submit" name="poistalista" value="Poista kysymyslista">				
			</form>
			<?php if ($errmsg != '') echo $errmsg;?>
			</div>
			<div id="alapuoli"
			<div id="ykysymysosa">
            <p><span style="font-size:30px;"id="tilanne"></span><span id="ykysymys"></span></p>
			</div>
			
            <div class="napit">
                <form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
				Lisää/muokkaa kysymys       <span style="padding-left: 160px; "id="kysymyksienotsikko">Kysymyslista: <?php if ($_SESSION['otsikko'] != '') echo $_SESSION['otsikko'];?></span>
				<span style="padding-left: 340px;"id="tyhjalista"><input type="text" name="uusilista" id="uusi" value=" <?php if ($_SESSION['tyhja'] != '') echo $_SESSION['tyhja'];?>"></span>
				<br><textarea cols="70" rows="4" type="text" size="79px" name="kysymys" ><?php echo $muokkausKysymys;?></textarea><br><br>
				Kirjoita vastausvaihtoehdot ja valitse oikea vastaus täppäämällä täplää <br><br>
				Vastaus vaihtoehto a:<br><input type="text" name="vaihtoa" value="<?php echo $muokkausVaihtoa;?>" size="30"><input type="radio" name="oikea" value="a" <?php echo $chka;?>><br>
				Vastaus vaihtoehto b:<br><input type="text" name="vaihtob" value="<?php echo $muokkausVaihtob;?>" size="30"><input type="radio" name="oikea" value="b" <?php echo $chkb;?>><br>
				Vastaus vaihtoehto c:<br><input type="text" name="vaihtoc" value="<?php echo $muokkausVaihtoc;?>" size="30"><input type="radio" name="oikea" value="c" <?php echo $chkc;?>><br>
				Vastaus vaihtoehto d:<br><input type="text" name="vaihtod" value="<?php echo $muokkausVaihtod;?>" size="30"><input type="radio" name="oikea" value="d" <?php echo $chkd;?>><br><br>
				<input type="hidden" name="muokkausID" value=<?php echo $muokkausID;?>><br>
				<span id="lisaa"> <input type="submit" name="painike" value="Lisää"></span>
				<input type="submit" id="valmis" name="valmis" value="Valmis">
				<div id="tallenna"> <input type="submit" name="muokkaa" value="Tallenna">  <input type="submit" id="peruuta" name="peruuta" value="Peruuta"></div>
				
				<br>
				</form>
			</div>
            </div>
			</div>
        </div>
    </div>
</body>
</html>
