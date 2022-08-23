<?php
session_start();
// login.php

$errmsg = '';
$login = false;
if (isset($_POST['uid']) AND isset($_POST['passwd'])) {
		require_once("/home/N3031/db-config/PelaajatDb.php");
	$pelaajaObj = new PelaajatDb();

    $errMsg = '';
    $success = 0;
	$pisteet = 0;
	
    $nimi       = isset($_POST['uid'])       ? $_POST['uid']       : '';
    $salasana = isset($_POST['passwd']) ? $_POST['passwd'] : '';

    if (strlen($nimi)>=1 AND strlen($salasana)>=1) {
        $success = $pelaajaObj->addPelaaja($nimi, $salasana, $pisteet);
    }

    if ($success) {
		//$_SESSION['app1_islogged'] = true; Tämä jos haluaa pysyä rekisteröinnin jälkeen kirjautuneena
        $_SESSION['uid'] = $_POST['uid'];
         header("Location: http://" . $_SERVER['HTTP_HOST']
                                    . dirname($_SERVER['PHP_SELF']) . '/'
                                    . "yllapito.php");
        exit;
    } else {
        $errMsg = "<p>Tallentamisessa jotain ongelmaa</p>";
        return $errMsg;
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
				Salasana:<br><input type="text" placeholder="Enintään 8 merkkiä" required maxlength="8" name="passwd" size="30"><br><br>
				<input type='submit' name='action' value='Rekisteröi'>
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