<?php
// logout.php
session_start();
$_SESSION['pisteet'] += $_GET['summa'];
if (isset($_SESSION['app1_islogged'])) {
	$_SESSION['app1_islogged'] === false;
    unset($_SESSION['app1_islogged']);
	
	
	require_once("/home/N3031/db-config/PelaajatDb.php");
	$pelaajaObj = new PelaajatDb();
	$pelaajat = $pelaajaObj->getPelaajat($tyhja_hakusana);
		
		foreach ($pelaajat as $pelaaja) {
			if ($pelaaja->nimi == $_SESSION['uid']){
				
			$pelaajaObj->updatePelaaja($pelaaja->id, $_SESSION['pisteet']);
			};	
		};
	session_destroy();
}

header("Location: http://" . $_SERVER['HTTP_HOST']
                           . dirname($_SERVER['PHP_SELF']) . '/'
                           . "main.php");
?>