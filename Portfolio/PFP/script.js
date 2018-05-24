
function ouvreV(){
	var x = document.getElementById("volet"); 
	x.style.left='0px';
	
}

function fermeV(){
	var x = document.getElementById("volet"); 
	x.style.left='-250px';
	
}




function descL(){
	var x = document.getElementById("logoaccueil"); 
	x.style.top='-3vw';
	var y = document.getElementById("accueil"); 
	y.style.color='#379ec4';}

function monteL(){
	var x = document.getElementById("logoaccueil"); 
	x.style.top='-6vw';
	var y = document.getElementById("accueil"); 
	y.style.color='white';}
	

function ouvreRejoindre(){
	var x = document.getElementById("nousrejoindre"); 
	x.style.top='42vw';	
	var y = document.getElementById("illupage"); 
	y.style.filter= 'grayscale(1) blur(2px)';	}

function fermeRejoindre(){
	var x = document.getElementById("nousrejoindre"); 
	x.style.top='45.5vw';
	var y = document.getElementById("illupage"); 
	y.style.filter= 'grayscale(1)';}

function surligne(champ, erreur)
{
   if(erreur)
      champ.style.backgroundColor = "#fba";
   else
      champ.style.backgroundColor = "";
}

function verifNomPrenom(champ)
{
   if(champ.value.length < 3 || champ.value.length > 25)
   {
      surligne(champ, true);
      return false;
   }
   else
   {
      surligne(champ, false);
      return true;
   }
}

function verifMail(champ)
{
   var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
   if(!regex.test(champ.value))
   {
      surligne(champ, true);
      return false;
   }
   else
   {
      surligne(champ, false);
      return true;
   }
}

	
