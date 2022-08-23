<!DOCTYPE html>
<html>
<?php session_start();

$_SESSION['pisteet'] += $_GET['summa'];
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
<?php header("Location: kokeile.php");?>            
</body>
</html>
