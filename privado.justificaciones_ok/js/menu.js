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