/* url utilizadas para realizar las peticiones en ajax */
var url_listar_alumnos="/privado/php/matriculados/alumnos_listar.php",
	url_listar_alumnos_draft="/privado/php/matriculados/listar_draft.php",
	url_remover_alumnos="/privado/php/matriculados/remover.php",
	url_aceptar_alumnos="/privado/php/matriculados/aceptar_draft.php",
	url_draft_alumnos="/privado/php/matriculados/draft.php";

// seccion almacena el id de la seccion seleciondad
var seccion="noset";
// almacena el id del estudiante
var pk_alumno;

$(document).ready(function () {
 	$(".button-collapse").sideNav("hide");
 	$("#navfixed").removeClass("fixed");
 	$('ul.tabs').tabs();
 	$('select').material_select();
 	listar_estudiantes();	
 	agregar_tablas_draft();
});

// obtiene las tablas de las secciones y las llena segun han sido sorteados
function agregar_tablas_draft(){
	var tablas = $(".tablas-selecionados");
	var cantidad = $(".tablas-selecionados").size();
	// envia cada peticion cuando id_seccion = ?
	for (var i =0; i<cantidad; i++) {
		listar_seccion_draft($(tablas[i]).attr("alias"));
	};
}
// obtiene los alumnos sorteados de una seccion en especifico
function listar_seccion_draft(seccion_pk){	
	$.ajax({
		data:{
		id:seccion_pk,
		id_grupo:$("#select_grupos").val(),
		},
		type:"POST",
		url:url_listar_alumnos_draft
	}).done(function(data,textStatus,jqXHR){
		
		// obtiene la clave lista del json data
		var lista = data.lista;
		$("."+seccion_pk).html("");
				
		// genera el cuerpo de la tabla
		$.each(lista, function(ind, elem){
			$('<tr class="codigos" codigo="'+elem.id+'">'+
 		'<td>'+elem.nombre+'</td>'+
 		'<td>'+elem.apellido+'</td>'+
 		'<td>'+elem.codigo+'</td>'+
 		'<td>'+elem.grupo+'</td>'+ 		
 		'<td><a onclick="remove_alumno('+elem.id+',$(this));" '+
 		'class="btn-floating teal"><i class="material-icons">clear</i></a></td></tr>').appendTo($("."+seccion_pk));
		});			
	}).fail(function(jqXHR,textStatus,textError){
		alert("Error al realizar la peticion dame".textError);

	});
}
// peticion ajax para cargar los estudiantes que no han sido asignados
function listar_estudiantes(){
	$.ajax({
		data:{
		busqueda: $.trim($('input#buscar_estudiante').val()),			
		},
		type:"POST",
		url:url_listar_alumnos
	}).done(function(data,textStatus,jqXHR){
		
		// obtiene la clave lista del json data
		var lista = data.lista;
		$("#table-estudiantes").html("");
				
		// genera el cuerpo de la tabla
		$.each(lista, function(ind, elem){
			$('<tr>'+			
			'<td>'+elem.nombre+'</td>'+
			'<td>'+elem.apellido+'</td>'+
			'<td>'+elem.codigo+'</td>'+
			'<td><a  onclick ="add_alumno($(this),'+elem.id+',\''+elem.nombre+'\','+'\''+elem.apellido+'\','+'\''+elem.codigo+'\');" class="btn-floating teal"><i class="material-icons">add</i></a></a></td>'+
			'</tr>').appendTo($("#table-estudiantes"));			
		});			
	}).fail(function(jqXHR,textStatus,textError){
		alert("Error al realizar la peticion dame".textError);

	});

}
$('#buscar_estudiante').keyup(function() {  
  listar_estudiantes();
});
// function para asignar un alumno a una seccion
function draft(){
	$.post(
		url_draft_alumnos,
		{
			id: pk_alumno,
			id_especialidad : $("#select_especialidad").val(),
			id_grado : $("#select_grado").val(),
			id_grupo : $("#select_grupos").val(),			
			id_seccion:seccion,
		},function(resp){
			//alert(resp);
	});
}
// elimina un alumno de una seccion y vuelve a no selecionados
function remover(){
	$.post(
		url_remover_alumnos,
		{
			id: pk_alumno,			
		},function(resp){			
			listar_estudiantes();
	});

}
// funciona para agregar a un alumno a seccion
 function add_alumno(row,pk,nombre,apellido,codigo,grupo){
 	if(seccion=="noset") {
 		swal('Escoja una sección');
 		return;
 	}
 	pk_alumno=pk;
 	$('<tr class="codigos" codigo="'+pk+'">'+
 		'<td>'+nombre+'</td>'+
 		'<td>'+apellido+'</td>'+
 		'<td>'+codigo+'</td>'+
 		'<td>'+$("#select_grupos :selected").text()+'</td>'+ 		
 		'<td><a onclick="remove_alumno('+pk+',$(this));" '+
 		'class="btn-floating teal"><i class="material-icons">clear</i></a></td></tr>').appendTo($("."+seccion));
 		row.closest('tr').remove();
 	draft();
 }
// al dar click en los tabs se seleciona la seccion
function selecionar_seccion(pk,table){	
	seccion=pk;	
}

// aceptar los grupos formados por el docente
function aceptar_draft(){
	$.post(
		url_aceptar_alumnos,
		{			
			id_especialidad : $("#select_especialidad").val(),
			id_grado : $("#select_grado").val(),
			id_grupo : $("#select_grupos").val(),						
		},function(resp){
			swal(resp);
			agregar_tablas_draft();
	});
	
}

// eliminar alumno de la tabla
function remove_alumno(pk,row){
	 pk_alumno=pk;
	 row.closest('tr').remove();
	 // lo devuelve en la base
	 remover();
}
// cambia las seciones dependiendo del grupo
$( "#select_grupos" ).change(function() {	
	agregar_tablas_draft();
});
