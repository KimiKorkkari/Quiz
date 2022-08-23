<?php
/******************************************  funktiot   ******************************** *****/

//Funktio joka poistaa halutun kysymyksen tiedostosta

function deleteJson($otsikkotarkistus) {

    $jsonString = file_get_contents('json/'.$otsikkotarkistus.'.json');
	$data = json_decode($jsonString, true);
	$luku = sizeof($data);

	foreach ($data as $key => $entry) {
	if ($entry['ID'] == $_GET['valinta']){
	unset($data[$key]);}
	$newJsonString = json_encode($data);
	file_put_contents('json/'.$otsikkotarkistus.'.json', $newJsonString);
	header("Location: yllapito.php");
    }
}
//Funktio joka lisää kysymyksen tiedostoon mallia json
function addJson($otsikkotarkistus) {
	
	
    $jsonString = file_get_contents('json/'.$otsikkotarkistus.'.json');
	$data = json_decode($jsonString, true);
	$luku = sizeof($data);
	
	$lastID = 0;
	
	//Katotaan viimeisen kysymyksen ID, jotta saadaan uuden kysymyksen ID selville
	foreach ($data as $key => $entry) {
	$lastID = $entry['ID'];
    }

	$data[$luku]['ID'] = $lastID +1;
	$data[$luku]['kysymys'] = $_GET['kysymys'];
	$data[$luku]['vastaukset']['a'] = $_GET['vaihtoa'];
	$data[$luku]['vastaukset']['b'] = $_GET['vaihtob'];
	$data[$luku]['vastaukset']['c'] = $_GET['vaihtoc'];
	$data[$luku]['vastaukset']['d'] = $_GET['vaihtod'];
	$data[$luku]['oikea'] = $_GET['vaihto'.$_GET['oikea']];
	
	$newJsonString = json_encode($data);
	file_put_contents('json/'.$otsikkotarkistus.'.json', $newJsonString);
	$_SESSION['otsikko'] = "";
	header("Location: yllapito.php");
}

//Funktio joka päivittää kysymyksen tiedostosta
function updateJson($otsikkotarkistus) {

    $jsonString = file_get_contents('json/'.$otsikkotarkistus.'.json');
	$data = json_decode($jsonString, true);

	//Katotaan viimeisen kysymyksen ID, jotta saadaan uuden kysymyksen ID selville
	foreach ($data as $key => $entry) {
		
		if ($entry['ID'] == $_GET['muokkausID']) {
			$data[$entry['ID']]['ID'] = $entry['ID'];
			$data[$entry['ID']]['kysymys'] = $_GET['kysymys'];
			$data[$entry['ID']]['vastaukset']['a'] = $_GET['vaihtoa'];
			$data[$entry['ID']]['vastaukset']['b'] = $_GET['vaihtob'];
			$data[$entry['ID']]['vastaukset']['c'] = $_GET['vaihtoc'];
			$data[$entry['ID']]['vastaukset']['d'] = $_GET['vaihtod'];
			$data[$entry['ID']]['oikea'] = $_GET['vaihto'.$_GET['oikea']];
			
			$newJsonString = json_encode($data);
			file_put_contents('json/'.$otsikkotarkistus.'.json', $newJsonString);
			header("Location: yllapito.php");
		
		}
    }
}

//Funktio joka lisää uuden kysymyslistan tiedostoon mallia json
function newJson($listanimi) {
	
    $jsonString = file_get_contents('json/tyhja.json');
	$data = json_decode($jsonString, true);

	$data[0]['ID'] = 0;
	$data[0]['kysymys'] = $_GET['kysymys'];
	$data[0]['vastaukset']['a'] = $_GET['vaihtoa'];
	$data[0]['vastaukset']['b'] = $_GET['vaihtob'];
	$data[0]['vastaukset']['c'] = $_GET['vaihtoc'];
	$data[0]['vastaukset']['d'] = $_GET['vaihtod'];
	$data[0]['oikea'] = $_GET['vaihto'.$_GET['oikea']];
	
	$newJsonString = json_encode($data);
	file_put_contents('json/'.$listanimi.'.json', $newJsonString);
	
	
	require_once("/home/N3031/db-config/PelaajatDb.php");
	$listaObj = new PelaajatDb();

    $errMsg = '';
    $success = 0;


    // Alkeellista tarkistusta lisäystä varten
    if (strlen($listanimi)>=1) {
        $success = $listaObj->addLista($listanimi);
    }

    if ($success) {
		//$_SESSION['app1_islogged'] = true; Tämä jos haluaa pysyä rekisteröinnin jälkeen kirjautuneena
        $_SESSION['uid'] = $_POST['uid']; // Tässä mukavuussyistä, voidaan tulostella yms.
         header("Location: http://" . $_SERVER['HTTP_HOST']
                                    . dirname($_SERVER['PHP_SELF']) . '/'
                                    . "yllapito.php");
        exit;
    } else {
        $errMsg = "<p>Tallentamisessa jotain ongelmaa</p>";
        return $errMsg;
    }

}
//Ladataan lista tietovisaan ja muokkaustilaan
function lataaLista(){
	require_once("/home/N3031/db-config/PelaajatDb.php");
	$listaObj = new PelaajatDb();
	$tyhja_hakusana = '';
	$listat = $listaObj->getListat($tyhja_hakusana);
	foreach ($listat as $lista) {	
	//valinta on se id joka halutaan vaihtaa, se saadaan optionista, johon se kysymyslistaa ladatessa laitettiin talteen.
	//Jos valinta on 1, eli tyhjälista:
		if ($_GET['valinta'] == "1"){
			
			//ladataan kansiosta oikean niminen kysymyslista ja palautetaan sen kysymykset.json
			//kysymykset.json on sen nimi, jonka ajax lataa tietovisaan kysymykseski
			//Joten vaihdettava kysymyslista "lipastetaan" tietovisaan
			$jsonString = file_get_contents('json/'.$lista->lista.'.json');
			file_put_contents('json/kysymykset.json', $jsonString);
		
			echo '<style type="text/css">

					#tyhjalista  {
					display:inline;
					}
					#kysymyksienotsikko {
					display:none;
					}

					</style>';
			$_SESSION['tyhja'] = $lista->lista;
			break;
			}
			
		elseif ($_GET['valinta'] == $lista->id){
			$jsonString = file_get_contents('json/'.$lista->lista.'.json');
			file_put_contents('json/kysymykset.json', $jsonString);
			$_SESSION['otsikko'] = $lista->lista;
		}
	}
}		
?>