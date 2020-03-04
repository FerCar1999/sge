// url para las peticiones ajax 
var url_listar_permisos = "/privado/php/permisos/permisos_listar.php",
url_permisos = "/privado/php/permisos/permisos.php",
url_modulo_permisos="/privado/php/permisos/modulos_permiso.php",
url_listar_modulos="/privado/php/permisos/modulos.php",
url_guardar_acceso="/privado/php/permisos/guardar_acceso.php",
url_eliminar_permiso="/privado/php/permisos/eliminar_permisos.php";

// almace el id a modificar o eliminar
var pk_permiso;

// variables de estado para acitvar o desactivar y ver tabla
var ver_estado_permiso = "Activo";
var estado_permiso = "Inactivo";

$(document).ready(function () {
  paginador = $(".pagination");
    // cantidad de items por pagina
    var items = 3, numeros =3;  
    // inicia el paginador
    init_paginator(paginador,items,numeros);
    // manda la peticion ajax que ejecutara como callback
    set_callback(get_data_callback_permisos); 
    permisos_init();	
    
  });
// buscar. Se usa on porque son elementos html insertados dinamicamente 
$('#buscar_permiso').keyup(function() {  
  permisos_init();
});
// funcion para llenar la tabla
function get_data_callback_permisos(){
  $.ajax({
    data:{
      limit: itemsPorPagina,              
      offset: desde,
      busqueda: $.trim($('input#buscar_permiso').val()),  
      orden: "nombre",
      estado:ver_estado_permiso,                   
    },
    type:"POST",
    url: url_listar_permisos
  }).done(function(data,textStatus,jqXHR){

    // obtiene la clave lista del json data
    var lista = data.lista;
    $(".table").html("")
    
    // si es necesario actualiza la cantidad de paginas del paginador
    if(pagina==0){
      creaPaginador(data.cantidad);
    }
    // genera el cuerpo de la tabla
    $.each(lista, function(ind, elem){
      if(ver_estado_permiso=="Activo"){
        $('<tr id="+elem.id+">'+                      
          '<td>'+elem.nombre+'</td>'+                      
          '<td><a onclick="ver_permiso('+elem.id+',\''+elem.nombre+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar información"><i  class="material-icons">edit</i></a> <a onclick=eliminar_permiso('+elem.id+',\''+elem.nombre+'\') class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar permiso"><i class="material-icons">delete</i></a> <a onclick="acceso_permiso('+elem.id+',\''+elem.nombre+'\')" class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="Administrar módulos del permisos"><i  class="material-icons">lock </i></a></td>'+
          '</tr>').appendTo($(".table"));
        $('.tooltipped').tooltip();
      }
      else {
        $('<tr id="+elem.id+">'+                      
          '<td>'+elem.nombre+'</td>'+                      
          '<td><a onclick=activar_permiso('+elem.id+',\''+elem.nombre+'\') class="btn-floating teal"><i class="material-icons">delete</i></a></td>'+
          '</tr>').appendTo($(".table"));
        $('.tooltipped').tooltip();
      }
    });     
  }).fail(function(jqXHR,textStatus,textError){
    swal("Error al realizar la petición dame".textError);

  });
}
// funcion agregar
$('#agregar_permiso').click(function(e){
	$.post(
		url_permisos,
		{
			id: null,
			nombre : $("#nombre").val(),
			token: $("#dpToken").val(),
		},function(resp){
			swal(resp);
			permisos_init();				
   });
});
// funcion modificar
$('#modificar_permiso').click(function(e){
	$.post(
		url_permisos,
		{
			id: pk_permiso,
			nombre : $("#modificar_nombre").val(),
			token: $("#dpToken").val(),
		},function(resp){
			swal(resp);
			permisos_init();				
   });
});
// funcion eliminar
$('#eliminar_permiso').click(function(e){
  //peticion post
  $.post(
    url_eliminar_permiso,
    {
      id: pk_permiso,
      estado:estado_permiso,  
      token: $("#dpToken").val(),    
    },function(resp){
      permisos_init();  
      swal(resp);
    });
});
// funcion activar
$('#activar_permiso').click(function(e){
  //peticion post
  $.post(
    url_eliminar_permiso,
    {
      id: pk_permiso,
      estado:estado_permiso,
      token: $("#dpToken").val(),      
    },function(resp){
      permisos_init();  
      swal(resp);
    });
});
// funcion guardar acceso
$('#guardar_acceso').click(function(e){
  // obtiene todos los checkbox
  var checkbox = $(".access_check");
  // obtiene la cantidad
  var size = $(".access_check").size();
  var arrayID = [];
  // recorre todos los checkbox
  for (var i=0; i<size; i++) {
      // obtiene el id de los que estan selecionados y los gurda
      if($(checkbox[i]).parent().children().prop('checked')){      
        arrayID[i]=$(checkbox[i]).attr("id");      
      }     
    }
    var permisos_json = JSON.stringify(arrayID);
    $.post(
      url_guardar_acceso,
      {
        id_permiso:fk_permiso,      
        permisos:permisos_json,
        token: $("#dpToken").val(),
      },function(resp){
        location.reload();
      });
    permisos_init();
  // muestra la notificacion
  
  
});
$('#ver_permisos_activos').click(function(e){
  if(ver_estado_permiso!="Activo"){
    ver_estado_permiso= "Activo";
    set_callback(get_data_callback_permisos); 
    permisos_init();        
  }
  
});
$('#ver_permisos_inactivos').click(function(e){
  if(ver_estado_permiso!="Inactivo"){
    ver_estado_permiso= "Inactivo";
    set_callback(get_data_callback_permisos); 
    permisos_init();
  }
});
function ver_permiso(pk,nombre){
	pk_permiso=pk;
	$('.agregar_permiso').hide('fast');
	$('.modificar_permiso').show('fast');
  $('.permiso_modulos').hide('fast');
  $('.eliminar_permiso').hide('fast');
  $("#modificar_nombre").val(nombre);
  $("label").addClass("active");
}
// confirmacion si desea eliminar
function eliminar_permiso(pk, nombre){
  pk_permiso=pk;
  $('.agregar_permiso').hide('fast');
  $('.permiso_modulos').hide('fast');
  $('.modificar_permiso').hide('fast');
  $('.eliminar_permiso').show('fast');
  $('#confirmacion').text("¿Desea desactivar el permiso '"+nombre +"'?");
  estado_permiso ="Inactivo";

}
// confirmacion si desea activar
function activar_permiso(pk, nombre){
  pk_permiso=pk;
  $('.agregar_permiso').hide('fast');
  $('.modificar_permiso').hide('fast');
  $('.eliminar_permiso').hide('fast');
  $('.permiso_modulos').hide('fast');
  $('.activar_permiso').show('fast');
  $('#confirmacion2').text("¿Desea activar el permiso '"+nombre +"'?");
  estado_permiso ="Activo";

}

// mostrar el acceso asignado a este permiso
function acceso_permiso(pk,name){
  fk_permiso = pk;
  $('.agregar_permiso').hide('fast');
  $('.modificar_permiso').hide('fast');
  $('.eliminar_permiso').hide('fast');
  $('.permiso_modulos').show('fast');
  
  
  $("#titulo_acceso").text("Acceso "+name);
  // ajax que obtiene todos los modulos
  $.ajax({
    data:{
      limit: 0,             
      offset: 0,
      busqueda:"",                  
      orden: "nombre",    
    },
    type:"POST",
    url:url_listar_modulos
  }).done(function(data,textStatus,jqXHR){
    
    // obtiene la clave lista del json data
    var lista = data.lista;
    // inserta los modulos
    $("#modulos").html("");
    $.each(lista, function(ind, elem){        
      $('<div class="col s6">'+
        '<input  id='+elem.id+' class="access_check" type="checkbox" >'+
        '<label id='+elem.id+' for='+elem.id+'>'+                
        ''+elem.nombre+'</label></div>').appendTo($("#modulos"));
    });
    // verifica los mdulos
    verificar_acceso();   
  }).fail(function(jqXHR,textStatus,textError){
    swal("Error al realizar la petición dame".textError);

  }); 
}

// funcion que verifica los modulos que estan habilitados 
function verificar_acceso(){
  var checkbox = $(".access_check");
  var size = $(".access_check").size();
  // recorre los modulos y guarda sus ids
  var arrayID = [];
  for (var i=0; i<size; i++) {
    arrayID[i]=$(checkbox[i]).attr("id");      
  }
  // obtiene los modulos habilitados para un permiso
  $.ajax({
    data:{
      id_permiso:fk_permiso,
      token: $("#dpToken").val(),
    },
    type:"POST",
    url:url_modulo_permisos
  }).done(function(data,textStatus,jqXHR){    
    // obtiene la clave lista del json data
    var lista = data.lista;
    // recorre los modulos
    $.each(lista, function(ind, elem){        
      for (var i=0; i<size; i++) {
        // seleciona los checkbox habilitados
        if(parseInt(elem.id) == parseInt(arrayID[i])){
          $(checkbox[i]).attr('checked',true);
        } 
      }
    }); 
  }).fail(function(jqXHR,textStatus,textError){
    swal("Error al realizar la petición dame".textError);

  }); 
}
// funcion para reicinar todo
function permisos_init(){
	cargaPagina(0);  
	$('.modificar_permiso').hide('fast');
  $('.eliminar_permiso').hide('fast');
  $('.activar_permiso').hide('fast');
  $('.permiso_modulos').hide('fast');
  $('.agregar_permiso').show('fast');
}

