<!DOCTYPE html>
<?php
session_start();

?>

<html>
<head lang="en">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="js/pikavisa.js"></script>
	<link rel="stylesheet" href="tyyli/styles.css">
</head>
<body>
    <div class="leiska">
		<span id="ylapisteet"><p style="margin:0;">Pisteesi yhteensä: <?php echo $_SESSION['pisteet'];?></p></span>
		<input type="hidden" id="haloo" value="0" />
        <div id="quiz">
            <h1>Oppivisa</h1>
            <hr style="margin-bottom: 20px">
			<div id="section">
			<div id="kysymysosa">
            <p><span style="font-size:30px;"id="tilanne"></span><span id="kysymys"></span></p>
			</div>
            <div class="napit">
                <button id="nappi0"><span id="valinta0"></span></button><br>
                <button id="nappi1"><span id="valinta1"></span></button><br>
                <button id="nappi2"><span id="valinta2"></span></button><br>
				<button id="nappi3"><span id="valinta3"></span></button><br>
				<!-- kierrätetään tämän kautta pisteet pikavisa.js:sään-->
				<input type="hidden" id="session" value="<?php echo $_SESSION['pisteet']?>" />
            </div>
			</div>
										
			<span id="canva"></span>
            <hr style="margin-top: 20px">
            <footer>
				<p id="points"></p>
            </footer>
        </div>
    </div>
</body>

</html>

