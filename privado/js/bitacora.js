var url_cargar_funciones = "/privado/php/bitacora/funciones.php",
	url_listar_personal = "/privado/php/bitacora/listarPersonal.php",
	url_listar_bitacora_total = "/privado/php/bitacora/bitacoraTotal.php";
var autocompletePersonal = {};
var idFuncion = 0, fechaInicio = "", fechaFin = "", pkPersonal = "";

$(document).ready(function () {
	$('.modal').modal();
    $('select').material_select();
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
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy/mm/dd',
        
        selectMonths: true,
        selectYears: 100,

        closeOnSelect: true
    });
    getPersonal();
    $('input.autocomplete').autocomplete({
		data: autocompletePersonal
	});
	paginador = $(".pagination");
	// cantidad de items por pagina
	var items = 10, numeros =3;  
	// inicia el paginador
	init_paginator(paginador,items,numeros);
	// manda la peticion ajax que ejecutara como callback
	set_callback(get_data_callback_bitacora); 
});

function get_data_callback_bitacora(){
	$.ajax({
	  	data:{
	  		limit: itemsPorPagina,              
	  		offset: desde,
			busqueda: "",
			funcion: idFuncion,
			inicio: fechaInicio,
			fin: fechaFin,
			personal: pkPersonal, 		
	  	},
	  	type:"POST",
	  	url: url_listar_bitacora_total
	}).done(function(data,textStatus,jqXHR){
  	// obtiene la clave lista del json data
  	var lista = data.lista;
  	$(".table").html("");
  	// si es necesario actualiza la cantidad de paginas del paginador
  	if(pagina==0){
		creaPaginador(data.cantidad);
  	}
  	// genera el cuerpo de la tabla
  	$.each(lista, function(ind, elem){
		$('<tr id="+elem.id+">'+                      
			'<td>'+elem.fecha+'</td>'+
			'<td>'+elem.nombre+'</td>'+
			'<td>'+elem.funcion+'</td>'+
			'<td>'+elem.descripcion+' | <a onclick="openModal(\''+ elem.detalle +'\')">Ver detalle</a></td>'+
		'</tr>').appendTo($(".table"));
		$('.tooltipped').tooltip();
  	});     
	}).fail(function(jqXHR,textStatus,textError){
	  	alert("Error al realizar la petición dame".textError);
	});
}

function openModal(detalle){
	$('#modalDetalle').modal('open');
	$("#detalle").text(detalle);
}

$('#verBitacora').click(function(e){
	if(!$("#todos[type='checkbox']").is(':checked')){
		pkPersonal = $("#autocomplete-input").val().substring(0,4);
	}
	if(!$("#fechas[type='checkbox']").is(':checked')){
		fechaInicio = $("#date_inicio").val();
		fechaFin = $("#date_fin").val();
	}
	idFuncion = $("#selectFuncion").val();
	set_callback(get_data_callback_bitacora);
	cargaPagina(0);
});

$("#todos[type='checkbox']").on('change', function(){
    if ($(this).is(':checked')){
		$('input.autocomplete').attr({'disabled' : 'disabled'});
		pkPersonal = "";
	}
    else{
		$('input.autocomplete').removeAttr('disabled');
	}
});

$("#fechas[type='checkbox']").on('change', function(){
    if ($(this).is(':checked')){
		$('input.datepicker').attr({'disabled' : 'disabled'});
		fechaInicio = "";
		fechaFin = "";
	}
    else{
		$('input.datepicker').removeAttr('disabled');
	}
});

$("#selectTipo").change(function(){
    getFunciones();
});

$("#selectFuncion").change(function(){
	if($("#selectFuncion").val() >= 0)
		$("a#verBitacora").removeAttr('disabled');
	else
		$("a#verBitacora").attr({'disabled' : 'disabled'});
});

function getFunciones(){
	$.ajax({ 		 		
		data:{			
			id: $("#selectTipo").val()
		},
		type:"POST",
		url: url_cargar_funciones
	}).done(function(data,textStatus,jqXHR){

		// obtiene la clave lista del json data
		var lista = data.lista;
		$("#selectFuncion").html("");
        $('#selectFuncion').append($('<option>', { 
            value: -1,
            text : "Elija una función" 
		}));
		$('#selectFuncion').append($('<option>', { 
            value: 0,
            text : "Todas las funciones" 
        }));
		// genera el cuerpo de la tabla
		$.each(lista, function (i, item) {

			$('#selectFuncion').append($('<option>', { 
				value: item.id,
				text : item.nombre 
			}));
			$('select').material_select();
		});
	}).fail(function(jqXHR,textStatus,textError){
		alert("Error al realizar la petición ".textError);
	});
}

function getPersonal() {
	$.ajaxSetup({async: false});
	$.ajax({
		type:'POST',
		url: url_listar_personal,
		data:{
		    
		},
		dataType: "json",
		success: function(valores){
			var resp = eval(valores);
			$.each(resp, function(ind,elem) {
				var persona = elem.persona;
				var foto = elem.foto;
				autocompletePersonal[persona] = foto;
			});
			return false;
		}
	});
	return false;	
}