var listar_suspendidos_pendiente = "/privado/php/suspendidos/suspendidos_pendientes.php";
var listar_suspendidos = "/privado/php/suspendidos/suspendidos_listar.php";
var url_guardar_suspendido ="/privado/php/suspendidos/suspender.php";
var url_eliminar_pre_suspendido ="/privado/php/suspendidos/eliminar_pre_suspencion.php";
var url_finalizar_suspencion ="/privado/php/suspendidos/finalizar.php";
var pk_suspendido = 0;

$(document).ready(function() {
	cargar_suspendidos_pendientes();
	cargar_suspendidos();
});

function cargar_suspendidos_pendientes(){
	$.ajax({
		data:{},
		type:"POST",
		url: listar_suspendidos_pendiente
	}).done(function(data,textStatus,jqXHR){
		
	// obtiene la clave lista del json data
	var lista = data.lista;
	$("#table1").html("")
	
	// si es necesario actualiza la cantidad de paginas del paginador	
	// genera el cuerpo de la tabla
	$.each(lista, function(ind, elem){	
		$('<tr id="+elem.id+">'+                      
			'<td>'+elem.codigo+'</td>'+
			'<td>'+elem.nombre+'</td>'+
			'<td>'+elem.grado+'</td>'+
			'<td>'+elem.espe+'</td>'+ 
			'<td><a onclick="suspender_alummno('+elem.id+',\''+elem.codigo+'\',\''+elem.nombre+'\',\''+elem.grado+'\',\''+elem.espe+'\',\''+elem.url+'\',\''+elem.observacion+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Suspender alumno"><i  class="material-icons">gavel</i></a> <a onclick="delete_pre_suspendidos('+elem.id+',\''+elem.codigo+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Remover"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($("#table1"));
		$('.tooltipped').tooltip();
	});     
}).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

});
}
function suspender_alummno(pk,codigo,nombre,grado,espe,url,observacion){
	$('.modal').modal();
	$('.modal').modal('open');
	$("#codigo").text(codigo);
	$("#nombre").text(nombre);
	$("#foto").attr("src",url);
	$("#codigoOb").text(codigo);
	$("#nombreOb").text(nombre);
	$("#observacion_text").text(observacion);
	
	$("#fotoOb").attr("src",url);
	pk_suspendido = pk;
	$("label").addClass("active");
	
}
function guardar_suspencion(){
	
	$.post(
		url_guardar_suspendido,
		{
			id:pk_suspendido,
			inicio:$("#date1").val(),
			fin:$("#date2").val(),
			observaion:$("#observacion_text").val(),           
		},
		function(data) {
			location.reload();
		});    
}
// elimiar de la presuspencion
function delete_pre_suspendidos(pk,codigo){
	swal({
	  title: "¿Desea eliminar al estudiante con carnet "+codigo+"?",
	  text: "El estudiante se va eliminar del registro para suspencion.",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: "OK",
	  closeOnConfirm: false
	},
	function(){	  
	$.post(
		url_eliminar_pre_suspendido,
		{
			id:pk,			
		},
		function(data) {
			location.reload();
		});   
	});
}
// finalizar suspencion 
function finalizar_suspencion(pk,codigo){
	swal({
	  title: "¿Desea finalizar la suspención del estudiante con carnet "+codigo+"?",
	  text: "El estudiante finalazara la suspención antes del tiempo previsto.",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: "OK",
	  closeOnConfirm: false
	},
	function(){	  
	$.post(
		url_finalizar_suspencion,
		{
			id:pk,			
		},
		function(data) {
			location.reload();
		});   
	});
}
function cargar_suspendidos(){
	$.ajax({
		data:{},
		type:"POST",
		url: listar_suspendidos
	}).done(function(data,textStatus,jqXHR){
		
	// obtiene la clave lista del json data
	var lista = data.lista;
	$("#table2").html("")
	
	// si es necesario actualiza la cantidad de paginas del paginador	
	// genera el cuerpo de la tabla
	$.each(lista, function(ind, elem){	
		$('<tr id="+elem.id+">'+                      
			'<td>'+elem.codigo+'</td>'+
			'<td>'+elem.nombre+'</td>'+
			'<td>'+elem.grado+'</td>'+
			'<td>'+elem.espe+'</td>'+
			'<td>'+elem.inicio+'</td>'+
			'<td>'+elem.fin+'</td>'+
			'<td><a onclick="finalizar_suspencion('+elem.id+',\''+elem.codigo+'\')" class="btn-floating green"><i class="material-icons">delete</i></a></td>'+
			'</tr>').appendTo($("#table2"));
		
	});     
}).fail(function(jqXHR,textStatus,textError){
	alert("Error al realizar la peticion dame".textError);

});
}