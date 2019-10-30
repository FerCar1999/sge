var url_notificaciones = "/privado/php/notificaciones/notificacionesVistas.php";
var url_eliminar_notificaciones = "/privado/php/notificaciones/eliminarNotificacion.php";
var total;

$(document).ready(function (argument) {
	$('.tooltipped').tooltip();
	total = $(".totalNL").text();
});

function marcarNotificacion(){
	$("#vista").css("color","#0288d1");
	$("#vista").text("done_all");
	$("#vista").attr("data-tooltip","Leída");
	$('.tooltipped').tooltip();
}

function ajaxNotification(pk_notificacion){
	$.post(
		url_notificaciones,
		{
	  	pk_notificacion: pk_notificacion,
		},function(resp){
			switch (resp){
				case 'success':
					Materialize.toast('Notificación marcada como leída.', 3000, 'rounded');
				break;
				default:
					Materialize.toast("Ha ocurrido un error al marcar la notificación.", 5000, 'rounded');
				break;
			}
	});
}

$("#vista").click(function(){
	marcarNotificacion();
});

function eliminarNotificacion(idPanel){
	$("div.card-panel."+idPanel).hide('fast');
	$.post(
		url_eliminar_notificaciones,
		{
	  	pk_notificacion: idPanel,
		},function(resp){
			switch (resp){
				case 'success':
					Materialize.toast('Notificación eliminada.', 3000, 'rounded');
					total = total - 1;
					if (total == 0) {$(".leidas").hide();}
				break;
				default:
					Materialize.toast("Ha ocurrido un error al eliminar la notificación.", 5000, 'rounded');
				break;
			}
	});
}