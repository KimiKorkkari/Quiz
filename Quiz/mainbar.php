<?php

// Kirjautumattomat pääsevät kirjautumaan
if (!isset($_SESSION['app1_islogged']) || $_SESSION['app1_islogged'] !== true) {
   echo "<a href='login.php'><button id='nappi0'>Kirjaudu sisään</button></a><br>";
   //echo "<a href='#'><button id='nappi0' onclick='toivotaOnnea(this.id)'>Pelaa kirjautumatta</button></a><br>";
} 

else { // ja kirjautuneet uloskirjautumaan
   echo "<a href='#'><button id='nappi0' onclick='toivotaOnnea(this.id)'>Pelaa</button></a><br>";
   echo "<a href='logout.php'><button id='nappi0'>Kirjaudu ulos</button></a><br>";
}


