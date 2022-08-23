//Pelin loputtua mahdollisuus palata napista main-sivulle ja urlin kautta pisteet mukana...

function main(){
	//Napataan uudet pisteet inputin valuesta
	let pojot = $("#haloo").val();
	let summa = parseInt(pojot);
	console.log(summa);
	//Viedään urlin kautta gettiin ja lisätään sessioniin
	window.top.location="logout.php?summa="+summa;
	
}
function valilataus(){
	
	//Napataan uudet pisteet inputin valuesta
	let pojot = $("#haloo").val();
	let summa = parseInt(pojot);
	console.log(summa);
	//Viedään urlin kautta gettiin ja lisätään sessioniin
	window.top.location="valilataus.php?summa="+summa;
	
}

$.ajax({
           url: 'json/kysymykset.json'
    }).fail(function() {
            console.log("fail!");
    }).done(function(data) {
		
		//Arvotaan 10 kpl kysymyksien ID:t, (kaikista kysymyksistä) jotka poimitaan jsonista.
		let numerot = [];
        let IDs = [];
		$.each(data, function(index, kysymys) {	
				IDs.push(kysymys.ID);		
		});	
		//Tarkistetaan että kysymyslistassa on tarpeeksi kysymyksiä
		if (IDs.length >= 10) {
		do {
			let numero = IDs[Math.floor(Math.random() * IDs.length)];
			numerot.push(numero);
			numerot = numerot.filter((item, index) => {
			return numerot.indexOf(item) === index
			});
			} while (numerot.length < 10);
		//Luodaan taulukko johon tuodaan kysymykset, ID:n (numerot-taulukosta) mukaan
		let json = [];
		for (let i = 0; i < 10; ++i){
        $.each(data, function(index, kysymys) {	
			if (kysymys.ID == numerot[i]){
				json.push(kysymys);	
			}
		});	
		};
		//Käynnistetään peli, viedään kysymykset mukana
			start(json);
		}else {console.log("Tarkista kysymyslistan pituus!"); window.top.location="main.php";}
     });

function start(json){
//Laitetaan aikalimitti pelaamiselle
//Kysymysolio, joka sisältää kysymyksen, vastausvaihtoehdot ja oiken vastauksen
function Kysymys(kyssari, vaihtoehdot, oikea) {
    this.kyssari = kyssari;
    this.vaihtoehdot = vaihtoehdot;
    this.oikea = oikea;
}
//Siirretään kysymykset json-taulukosta olioon mallia: (kysymys, [vastaus vaihtoehdot], oikea vastaus)
let kysymykset = [
    new Kysymys(json[0].kysymys, [json[0].vastaukset.a, json[0].vastaukset.b,json[0].vastaukset.c, json[0].vastaukset.d], json[0].oikea),
    new Kysymys(json[1].kysymys, [json[1].vastaukset.a, json[1].vastaukset.b,json[1].vastaukset.c, json[1].vastaukset.d], json[1].oikea),
    new Kysymys(json[2].kysymys, [json[2].vastaukset.a, json[2].vastaukset.b,json[2].vastaukset.c, json[2].vastaukset.d], json[2].oikea),
	new Kysymys(json[3].kysymys, [json[3].vastaukset.a, json[3].vastaukset.b,json[3].vastaukset.c, json[3].vastaukset.d], json[3].oikea),
    new Kysymys(json[4].kysymys, [json[4].vastaukset.a, json[4].vastaukset.b,json[4].vastaukset.c, json[4].vastaukset.d], json[4].oikea),
    new Kysymys(json[5].kysymys, [json[5].vastaukset.a, json[5].vastaukset.b,json[5].vastaukset.c, json[5].vastaukset.d], json[5].oikea),
	new Kysymys(json[6].kysymys, [json[6].vastaukset.a, json[6].vastaukset.b,json[6].vastaukset.c, json[6].vastaukset.d], json[6].oikea),
	new Kysymys(json[7].kysymys, [json[7].vastaukset.a, json[7].vastaukset.b,json[7].vastaukset.c, json[7].vastaukset.d], json[7].oikea),
    new Kysymys(json[8].kysymys, [json[8].vastaukset.a, json[8].vastaukset.b,json[8].vastaukset.c, json[8].vastaukset.d], json[8].oikea),
    new Kysymys(json[9].kysymys, [json[9].vastaukset.a, json[9].vastaukset.b,json[9].vastaukset.c, json[9].vastaukset.d], json[9].oikea)
]; 

//Peli-olio
function Visa(kysymykset) {
    this.pisteet = 0;
    this.kysymykset = kysymykset;
    this.kysymysIndex = 0;
}
// Uusi olio-visa kysymyksineen
let visa = new Visa(kysymykset);	

// Poimii oikean kysymyksen vuorollaan kysymyksista
Visa.prototype.getKysymysIndex = function() {
    return this.kysymykset[this.kysymysIndex];
}
//Tarkistaa onko onko kysymys oikea
Visa.prototype.arvaus = function(arvaus) {
	
	//Tässä on oikea vastaus -> this.getKysymysIndex().oikea
	let right = "kuvat/r.png";
	let wrong = "kuvat/w.png";
	
	let points = document.getElementById("points");
	
	//Tarkisteaan onko kysymys oikea. Oikeasta arvaukseta r.png kuva, väärästä w.png
		if(this.getKysymysIndex().oikea == arvaus) {
			for(let i = 0; i < 4; i++) {
			//Tämä siksi, että kun vastauskien välillä on pieni viive, ei voi vastailla tuleviin kysymyksiin...
			document.getElementById("nappi" + i).disabled = true;
        }
			
        this.pisteet++;
		points.innerHTML +="<img src='"+right+"'>" + " "
		
    } else {
		
		for(let i = 0; i < 4; i++) {
            let element = document.getElementById("valinta" + i);
			//Tämä siksi, että kun oikea vastaus näytetään ja pieni viive, ei voi vastailla tuleviin kysymyksiin...
			document.getElementById("nappi" + i).disabled = true;
            if (element.innerHTML == this.getKysymysIndex().oikea){element.parentNode.style.background = "#FF0000";}
        }
		
		points.innerHTML +="<img src='"+wrong+"'>"+ " ";}

    this.kysymysIndex++;
}
//Kun kysytään viimeinen kysymys, kierto-funktion if visa.loppu() = true ja peli loppuu
Visa.prototype.loppu = function() {
    return this.kysymysIndex === 10;
}

 //Pelin kierto
function kierto(ohitus) {
    if(visa.loppu()) {
		//Pysäytetään aikalaskuri
		clearTimeout(kello);
		//Kun peli on lopussa, odotellaan hetki, että viimeinenkin oikein- tai väärinkuva näkyy
        setTimeout(naytaPisteet, 500);
    }
	//Jos peli jatkuu
    else {
        let element = document.getElementById("kysymys");
		//Vastaus vaihtoehdot
        let vaihtoehdot = visa.getKysymysIndex().vaihtoehdot;
		//Sekoitetaan vastausvaihtoehdot, koska alkuperäisessä ajaxin tuomassa datassa, oikea vastaus on aina ensimmäinen.
		function shuffle(arr){
		arr.sort(() => Math.random() - 0.5);
		}
		shuffle(vaihtoehdot);
		let delay = 0;
		for (delay = 1; delay <= 100; ++delay) {	
		setDelay(delay);
		}
	
		function setDelay(delay) {
		setTimeout(function(){
		//Asetaan viive kysymyksien välille -> jos väärä vastaus, oikea vastaus kerkee näkyä napin eri värisyytenä
		if (delay == 100 || ohitus == 0) {
				//Välitetään kysymys
				element.innerHTML = visa.getKysymysIndex().kyssari;
				naytaTilanne();
			
			for(let i = 0; i < 4; i++) {
			
            let element = document.getElementById("valinta" + i);
            element.innerHTML = vaihtoehdot[i];
			//Tämä antaa taas mahdollisuuden vastailla
			document.getElementById("nappi" + i).disabled = false;
			element.parentNode.style.background = "#00e64d";
			//Funktio joka käynnistää kysymyksen tarkistuksen sekä seuraavan kysymyksen
            valinta("nappi" + i, vaihtoehdot[i]);
        }
				
		}
		}, 10 * delay);
		}
        
    }
};
//funktio joka näyttää monesko kysymys on menossa
function naytaTilanne() {
    let menossa = visa.kysymysIndex + 1;
    let element = document.getElementById("tilanne");
    element.innerHTML = menossa+". ";
};

function valinta(id, arvaus) {
	//Lisätään puttoniin vastausvaihtoehto-nappulat...
    let button = document.getElementById(id);
	//..., ja klikkauksesta napataan niitä yksi tarkastukseen
    button.onclick = function() {
		//Välitetään arvaus testiin ja kun testi suoritettu, käynnistetään uusi kysymys
        visa.arvaus(arvaus);
		let jatko = 1;
        kierto(jatko);
    }
};
//Tällänen kikkailu-toteutustapa, "piirtää" aikajanaa pelin ajalle.
function timer() {
	let elementti = document.getElementById("canva");
	let i = 1;
	
	for (i = 1; i <= 1000; ++i) {	
		setDelay(i);
	}
	
	function setDelay(i) {
	setTimeout(function(){
	elementti.innerHTML = '<canvas style="background-color:red; margin-top: 40px;" id="chart" width="'+i*0.6+'" height="10"></canvas>'
	}, 40 * i);
	}
};

//Ajan loputtua ei anneta pisteitä
function aikaLoppu(){
	
	let yla = document.getElementById("ylapisteet");
	yla.style.display = "none";
	let pisteet = (2 * visa.pisteet -(10 -visa.pisteet));
	//Haetaan session-muuttujan pisteet ja lisätään uudet pisteet ja lähetetään takasin...varmaan ois järkevämpikin tapa kikkailla
	let sessio = $("#session").val();
	let sessiopisteet = parseInt(sessio) + pisteet;
	//Työnnetään uudet pisteet inputin valueen.
	document.getElementById("haloo").value = pisteet;
	
	let gameOverHTML = "Pisteesi yhteensä: "+sessiopisteet+"<br><h1>Aika loppui!</h1>";
    gameOverHTML += "<h3 id='score'> " + pisteet + " pistettä</h3><br><br><button id='restart' onClick='valilataus()'>Pelaa uudestaan?</button><br>";
	gameOverHTML += "<br><button id='restart' onClick='main()'>Lopeta</button>";
    let element = document.getElementById("quiz");
    element.innerHTML = gameOverHTML;
};

//Pelin loputtua näytetään pisteet ja napit.
function naytaPisteet() {
	
	let yla = document.getElementById("ylapisteet");
	yla.style.display = "none";
	let pisteet = (2 * visa.pisteet -(10 -visa.pisteet));
	//Haetaan session-muuttujan pisteet ja lisätään uudet pisteet ja lähetetään takasin...varmaan ois järkevämpikin tapa kikkailla
	let sessio = $("#session").val();
	let sessiopisteet = parseInt(sessio) + pisteet;
	//Työnnetään uudet pisteet inputin valueen.
	document.getElementById("haloo").value = pisteet;
	
    let gameOverHTML = "Pisteesi yhteensä: "+sessiopisteet+"<br><h1>Tulos</h1>";
    gameOverHTML += "<h3 id='score'>" + pisteet + " pistetä</h3><br><br><button id='restart' onClick='valilataus()'>Pelaa uudestaan?</button><br>";
	gameOverHTML += "<br><button id='restart' onClick='main()'>Lopeta ja kirjaudu ulos</button>";
    let element = document.getElementById("quiz");
    element.innerHTML = gameOverHTML;
	
};
// Peli käyntiin
let kello = setTimeout(aikaLoppu, 40000);
timer();
let ohitus = 0;
kierto(ohitus);
};




//Täällä päivitetään pisteet tietokantaan ja luetaan naytapisteet osiossa.