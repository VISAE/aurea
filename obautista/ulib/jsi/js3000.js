// JavaScript Document
// --- © Omar Augusto Bautista Mora - UNAD - 2024 ---
// --- omar.bautista@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 1.0 jueves, 7 de noviembre de 2024
function cambiacanal(){
	let iCanal = parseInt(document.getElementById('saiucanal').value);
	let sCanal = 'saiupresencial';
	switch(iCanal) {
		case 1: sCanal='saiupresencial';
		break;
		case 2: sCanal='saiutelefonico';
		break;
		case 3: sCanal='saiuchat';
		break;
		case 4: sCanal='saiucorreo';
		break;
		case 5: sCanal='saiusolusuario';
		break;
	}
	location.href = './' + sCanal + '.php';
}