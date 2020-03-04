// variable para almacenar las url
var url_cargar_grupos= "/privado/php/horarios/listar_grupos.php",
url_cargar_asignaturas= "/privado/php/horarios/cargar_asignaturas.php",
url_guardar_clase= "/privado/php/horarios/agregar_clase.php",
url_quitar_clase= "/privado/php/horarios/quitar_clase.php",
url_cargar_secciones = "/privado/php/horarios/cargar_secciones.php",
url_get_modules = "/privado/php/horarios/listar_modulos.php";
// utils para detectar niveles,tipos y grupos escolares
var tipo="academico",nivel="",grupo="",modulo="NO",grupo_tecnico_completo="NO",tab_dia="Lunes";
var pk_grado=0, pk_tipo_asignatura=0;
var autocompleteAsginaturas = {};
var asignaturasID = [];

$(document).ready(function() {
	// carga los calendarios con textos en español
	$('.datepicker').pickadate({
		// Strings and translations
		monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
		weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb'],

		// Buttons
		today: 'Hoy',
		clear: 'Limpiar',
		close: 'Cerrar',

		// Accessibility labels
		labelMonthNext: 'Adelante',
		labelMonthPrev: 'Atras',
		labelMonthSelect: 'Selecciona un mes',
		labelYearSelect: 'Selecciona un año',

		// Formats
		format: 'dd !de mmmm !de yyyy',
		formatSubmit: 'yyyy/mm/dd',
		
		selectMonths: true,
		selectYears: 100,

		closeOnSelect: true
	});	
	// habilita los componentes de materialize
	$('ul.tabs').tabs();
	$('select').material_select(); 	
	//peticiones para cargar los combobox
	pk_grado = $( "#select_grado").val();
	pk_tipo_asignatura = $( "#select_tipo_asignatura").val();
	cargar_grupos();
	cargar_secciones();
	cargar_asignaturas();
	setTimeout(function(){ obtenerValorPrevios(); }, 3000);
});

function obtenerValorPrevios(){
	let prevTipo = localStorage.getItem('tipo') == 'Academico' ? 2 : 1;
	$("#select_grado").val(localStorage.getItem('id_grado'));
	$("#select_tipo_asignatura").val(prevTipo).trigger("change");
	$("#select_especialidad").val(localStorage.getItem('id_especialidad')).trigger("change");
	$("#select_secciones").val(localStorage.getItem('id_seccion')).trigger("change");

	$("#select_hora").val(localStorage.getItem('id_tiempo'));
	$("#select_local").val(localStorage.getItem('id_local'));		
	$("#date_inicio").pickadate('picker').set('select', localStorage.getItem('inicio'), { format: 'yyyy/mm/dd' }).trigger("change");
	$("#date_fin").pickadate('picker').set('select', localStorage.getItem('fin'), { format: 'yyyy/mm/dd' }).trigger("change");
	
	
	
	
	
}
function storageSelectedData(){
	localStorage.setItem('tipo',tipo);
	localStorage.setItem('id_local',$("#select_local").val());
	localStorage.setItem('id_grupo',$("#select_grupos").val());
	localStorage.setItem('id_seccion',$("#select_secciones").val());
	localStorage.setItem('id_grado',$("#select_grado").val());
	localStorage.setItem('id_asignatura',$("#select_asignaturas").val());
	localStorage.setItem('id_tiempo',$("#select_hora").val());
	localStorage.setItem('id_especialidad',$("#select_especialidad").val());
	localStorage.setItem('inicio',$("#date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ));
	localStorage.setItem('fin',$("#date_fin").pickadate("picker").get( 'select', 'yyyy/mm/dd' ));
}

// cargar grupos academicos o tecnicos
function cargar_grupos(){
	$.ajax({ 		 		
		data:{
			id: pk_tipo_asignatura, 		
		},
		type:"POST",
		url:url_cargar_grupos
	}).done(function(data,textStatus,jqXHR){

		// obtiene la clave lista del json data
		var lista = data.lista;
		$("#select_grupos").html("");
		
		// si es necesario actualiza la cantidad de paginas del paginador

		// genera el cuerpo de la tabla
		$.each(lista, function (i, item) {
			tipo=item.tipo;
			$('#select_grupos').append($('<option>', { 
				value: item.id,
				text : item.nombre,
				grupo: item.tipo, 
			}));
			$('select').material_select();
		});
	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
	});

}
// carga las secciones
function cargar_secciones(){
	$.ajax({ 		 		
		data:{
			id: pk_grado, 		
		},
		type:"POST",
		url:url_cargar_secciones
	}).done(function(data,textStatus,jqXHR){

		// obtiene la clave lista del json data
		var lista = data.lista;
		$("#select_secciones").html("");
		
		// si es necesario actualiza la cantidad de paginas del paginador

		// genera el cuerpo de la tabla
		$.each(lista, function (i, item) {
			nivel = item.nivel;
			if(item.nivel=="Tercer Ciclo"){
				$('#select_grupos').prop('disabled', 'disabled');
				$('#select_grupos').val('1');
			} 
				else if(item.nivel=="Bachillerato") $('#select_grupos').removeAttr('disabled');

					$('#select_secciones').append($('<option>', { 
						value: item.id,
						text : item.nombre 
					}));
					$('select').material_select();
				});
	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
	});
}
// carga las asignaturas
function cargar_asignaturas(){
	
	$.ajaxSetup({async: false});
	$.ajax({ 		 		
		data:{
			id: pk_grado,
			id_tipo_asignatura: pk_tipo_asignatura, 	 		
		},
		type:"POST",
		url:url_cargar_asignaturas
	}).done(function(data){

		// obtiene la clave lista del json data
		var lista = data.lista;								
		autocompleteAsginaturas = {}
		$.each(lista, function (i, item) {
				let asignatura = (i+1)+": "+item.nombre;
				asignaturasID[i] = item.id;
				autocompleteAsginaturas[asignatura] = null;
		});
		$(".autocomplete-content").remove();
		// Autocomplete Asignaturas		
		$('input.autocomplete').autocomplete({
			data: autocompleteAsginaturas	
		});
	});
	
}
function forever(){
	if(tipo=="Academico") $('#select_especialidad').prop('disabled', 'disabled');
		else $('#select_especialidad').removeAttr('disabled');
	}

	// actualizar informacion de combobox al cambiar alguno
	$( "#select_grado" ).change(function() {	
		pk_grado = $( "#select_grado").val();
		cargar_secciones();
		cargar_asignaturas();
		Materialize.toast('Seleccione la especialidad.', 3000, 'rounded');

	});

	$( "#select_tipo_asignatura" ).change(function() {	
		pk_tipo_asignatura = $( "#select_tipo_asignatura").val();
		cargar_grupos();	
		cargar_asignaturas();
		if($("#select_tipo_asignatura option:selected").text()!="Técnica"){
			Materialize.toast('Ignore el campo especialidad.', 3000, 'rounded');
			$( "#select_especialidad").prop('disabled', 'disabled');
		}else{
			$( "#select_especialidad").removeAttr('disabled');		
		}
	});
	$('#check_modulo').change(function() {
		if($(this).is(":checked")) {
			swal("Alerta","Indique la duración en la que se impartira el modulo.");
			modulo = "SI";
			$(this).attr("checked", true);		
		}else{
			modulo = "NO";
		}
		$('#check_modulo').val($(this).is(':checked'));                
		//modulo=$('#check_modulo').prop("checked")
	});

	$('#grupo_completo').change(function() {
		if($(this).is(":checked")) {			
			grupo_tecnico_completo = "SI";
			$(this).attr("checked", true);			
			Materialize.toast('Se ha activado el grupo técnico, ignore el campo sección.', 3000, 'rounded');		
			
		}else{
			grupo_tecnico_completo = "NO";			
		}
		$('#grupo_completo').val($(this).is(':checked'));                		
	});

	function tab_dias(dia){
		tab_dia=dia;
	}
	$('#agregar_clase').click(function(e){
		if($("#select_asignaturas").val() != "") {
			let numero_asignatura= $("#select_asignaturas").val().substring(0,$("#select_asignaturas").val().indexOf(":"));
		let id_asignatura = asignaturasID[parseInt(numero_asignatura)-1];
		$.post(
			url_guardar_clase,
			{
				id_local:$("#select_local").val(),
				id_grupo:$("#select_grupos").val(),
				tipo:tipo,
				id_seccion:$("#select_secciones").val(),
				id_grado:$("#select_grado").val(),
				id_asignatura:id_asignatura,
				id_tiempo:$("#select_hora").val(),
				id_especialidad:$("#select_especialidad").val(),
				inicio : $("#date_inicio").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
				fin : $("#date_fin").pickadate("picker").get( 'select', 'yyyy/mm/dd' ),
				dia: tab_dia,
				modulo:modulo,
				grupo_tecnico_completo:grupo_tecnico_completo,


			},function(resp){
				if(resp !="true"){
					storageSelectedData();

					Materialize.toast(resp, 5000,'',function(){
						location.reload();
					});
				}else location.reload();		

			});
		}else {
			swal("Alerta","Seleciona una Materia","warning");
		}
		
	});

	function detalles(pk,pk_tiempo,dia,row){
		$('.modal').modal();	
		$('#modal1').modal('open');
		$.ajax({
			data:{
				id: pk_tiempo,
				dia: dia,
			},
			type:"POST",
			url: url_get_modules
		}).done(function(data,textStatus,jqXHR){

			// obtiene la clave lista del json data
			var lista = data.lista;
			$(".table_modulos").html("")

			// genera el cuerpo de la tabla
			$.each(lista, function(ind, elem){
				$('<tr>'+                      
					'<td>'+elem.materia+'</td>'+
					'<td>'+elem.inicio+'</td>'+
					'<td>'+elem.final+'</td>'+
					'<td><a onclick="remove_clase2('+elem.id+',$(this))" class="btn-floating teal tooltipped" data-position="left" data-delay="50" data-tooltip="Remover"><i class="material-icons">clear</i></a></td>'+
					'</tr>').appendTo($(".table_modulos"));	
				$('.tooltipped').tooltip();
			});     
		}).fail(function(jqXHR,textStatus,textError){
			alert("Error al realizar la peticion dame".textError);

		});
	}

	function remove_clase2(pk,row){
		
		row.closest('tr').remove();
		$.post(
			url_quitar_clase,
			{			
				id:pk
			},function(resp){

			});
	}
	function remove_clase(pk,row){
		swal({
			title: "Estas Seguro?",
			text: "La clase se eliminará?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "SI",
			cancelButtonText: "NO",
			closeOnConfirm: true,
			closeOnCancel: true
		},
		function(isConfirm){
			if (isConfirm) {

				$.post(
					url_quitar_clase,
					{			
						id:pk
					},function(resp){
						if(resp!="Exito"){
							Materialize.toast(resp, 3000);
						}else row.closest('tr').remove();
					});
			} 
		});

	}