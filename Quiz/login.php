<?php
session_start();
// login.php
$kayttajat = [];  

		require_once("/home/N3031/db-config/PelaajatDb.php");
		$pelaajaObj = new PelaajatDb();
		$tyhja_hakusana = '';
		$pelaajat = $pelaajaObj->getPelaajat($tyhja_hakusana);
		
		foreach ($pelaajat as $pelaaja) {
			
		$kayttajat[$pelaaja->nimi] = $pelaaja->salasana;
		}

/*foreach ($kayttajat as $key=>$item){ Tässä jos haluaa ne salasanat nähdä, lopullunen versio olisi toisenlainen :)
    echo "$key => $item <br>";
} 
*/
$errmsg = '';
$login = false;

if (isset($_POST['uid']) AND isset($_POST['passwd'])) {

		foreach ($kayttajat as $key => $value) {
    
        $login = ($key === $_POST['uid'] && $value === $_POST['passwd']) ? true : false;
        if($login) break;
		}
	//Tarkistellaa onko kirjautuja yllapito vai ei, heikohko tapa, mutta menköön nyt näin
    if ($key === $_POST['uid'] && $value === $_POST['passwd'] && $key === "yllapito" && $value === "yllapito") {
        // Kirjautuminen ok, merkintä sessiotauluun
        $_SESSION['app1_islogged'] = true;
        $_SESSION['yllapito'] = true;
         header("Location: http://" . $_SERVER['HTTP_HOST']
                                    . dirname($_SERVER['PHP_SELF']) . '/'
                                    . "yllapito.php");
        exit;
		
    }  elseif($key === $_POST['uid'] && $value === $_POST['passwd'] && $key !== "yllapito" && $value !== "yllapito") {
		$_SESSION['app1_islogged'] = true;
		$_SESSION['uid'] = $_POST['uid'];
		
		require_once("/home/N3031/db-config/PelaajatDb.php");
		$pelaajaObj = new PelaajatDb();
		$tyhja_hakusana = '';
		$pelaajat = $pelaajaObj->getPelaajat($tyhja_hakusana);
		
		foreach ($pelaajat as $pelaaja) {
			if ($pelaaja->nimi == $_SESSION['uid']){
				
				$_SESSION['pisteet'] = $pelaaja->pisteet;	
			};	
		};
			header("Location: http://" . $_SERVER['HTTP_HOST']
                                    . dirname($_SERVER['PHP_SELF']) . '/'
                                    . "main.php");
        exit;
	
	}else {
        $errmsg = '<span style="background: yellow;">Tunnus/Salasana väärin!';
    }
}
?>

<title>Kirjautusmislomake</title>

<?php
if ($errmsg != '') echo $errmsg;
?>

<html>
<head lang="en">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<link rel="stylesheet" href="tyyli/styles.css">

</head>
<body>
    <div class="leiska">
	

        <div id="quiz">
		<input type="button" value="Takaisin" onclick="history.back()">
            <h1>Oppivisa</h1>
            <hr style="margin-bottom: 20px">
			<section>
			<div id="kysymysosa">
            <p><span style="font-size:30px;"id="tilanne"></span><span id="kysymys"></span></p>
			</div>
            <div class="napit">
                <form id="container" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				Tunnus:<br><input type="text" name="uid" size="30"><br>
				Salasana:<br><input type="text" name="passwd" size="30"><br><br>
				<input type='submit' name='action' value='Kirjaudu'>
				<br>
				</form>

            </div>
			</section>
            <hr style="margin-top: 50px">
            <footer>
				<p id="points"></p>
            </footer>
        </div>
    </div>
 
</body>
</html>


