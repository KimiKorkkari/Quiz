<!DOCTYPE html>
<html>
<?php session_start();
if(!isset($_GET['summa'])) $_GET['summa'] = 0; 
if(!isset($_SESSION['pisteet'])) $_SESSION['pisteet'] = ""; 
if(!isset($_SESSION['app1_islogged'])) $_SESSION['app1_islogged'] = false; 
if($_SESSION['pisteet'] != ""){
	
	$_SESSION['pisteet'] += $_GET['summa']; 
}

//Tallennetaan urlin mukana pisteitä. Tähän on varmasti järkevämpikin tapa.
?> 
<head lang="en">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
	<link rel="stylesheet" href="tyyli/styles.css">
	<script src="js/teksti.js"></script>
</head>
<body>
    <div class="leiska">
		<p style="margin:0;"> <?php if ($_SESSION['app1_islogged'] === true) echo 'Pisteesi yhteensä: ' .$_SESSION['pisteet']?></p>
        <div id="quiz">
            <h1>Oppivisa</h1>
            <hr style="margin-bottom: 20px">
			<section>
			<div id="kysymysosamain">
			<h2 class="ml1">
				<span class="text-wrapper">
				<span class="line line1"></span>
				<span class="letters">Onnea peliin!</span>
				<span class="line line2"></span>
				</span>
			</h2>
			</div>
			<div id="aloitus">
            <div class="napit">
			<?php include('mainbar.php');?> 
            </div>
			</div>
			</section>
        </div>
    </div>
 <script>
 </script>
</body>
</html>
