let urlBitacora = "/api/v1/bitacora";

$(document).ready(function () {
 	$(".button-collapse").sideNav();
 	$('.collapsible').collapsible();

 	$(".abrirLista").click(function () {
 		$(this).siblings(".cerrarLista").show();
 		$(this).siblings().show('slow');
 		$(this).hide();
 	});

 	$(".cerrarLista").click(function () {
 		$(this).siblings().hide('slow');
 		$(this).siblings(".abrirLista").show();
 		$(this).hide();
 	});

 });

 // MARK: - Open Menu Options

 function openHorarios(e) {
	e.preventDefault();
	$.post(
	 urlBitacora,
	 {
		 action: 1,
		 description: "Acceso a vista de Horarios",
		 function: 90			
	 },
	 function(data) {		 
		window.location = "/horarios"
	 }); 
 }

