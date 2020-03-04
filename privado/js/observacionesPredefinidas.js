// url para las peticiones ajax 
var url_listar_codigos = "/privado/php/codigos/obsPredefinidasListar.php",
url_codigos = "/privado/php/codigos/codigos.php",
url_eliminar_codigos = "/privado/php/codigos/eliminarCodigos.php";

// almace el id a modificar o eliminar
var pk_obs, estado_obs = "Inactivo", ver_estado_obs = "Activo";

$(document).ready(function () {
paginador = $(".pagination");
// cantidad de items por pagina
var items = 10, numeros =3;  
// inicia el paginador
init_paginator(paginador,items,numeros);
// manda la peticion ajax que ejecutara como callback
set_callback(get_data_callback_obs); 
obs_init();
$('select').material_select();
});

function get_data_callback_obs(){
$.ajax({
data:{
limit: itemsPorPagina,
busqueda: $.trim($('input#buscar_obs').val()),
offset: desde,
estado: ver_estado_obs
},
type:"POST",
url: url_listar_codigos
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
    if (ver_estado_obs == 'Activo') {
        $('<tr id="+elem.id+">'+                      
          '<td>'+elem.descripcion+'</td>'+
          '<td><a onclick="ver_obs('+elem.id+',\''+elem.nombre+'\',\''+elem.descripcion+'\','+elem.idTipoCodigo+')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Actualizar" ><i  class="material-icons">edit</i></a> <a onclick="eliminar_obs('+elem.id+',\''+elem.descripcion+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Desactivar"><i class="material-icons">delete</i></a></td>'+
        '</tr>').appendTo($(".table"));
        $('.tooltipped').tooltip();
    }else{
        $('<tr id="+elem.id+">'+                      
          '<td>'+elem.descripcion+'</td>'+
          '<td><a onclick="activar_obs('+elem.id+',\''+elem.descripcion+'\')" class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="Reactivar" ><i  class="material-icons">autorenew</i></a></td>'+
        '</tr>').appendTo($(".table"));
        $('.tooltipped').tooltip();
    }
});     
}).fail(function(jqXHR,textStatus,textError){
alert("Error al realizar la peticion dame".textError);

});
}

$('#ver_obs_activos').click(function(e){
  if(ver_estado_obs != "Activo"){
    ver_estado_obs = "Activo";
    set_callback(get_data_callback_obs); 
    obs_init();    
  }

});

$('#ver_obs_inactivos').click(function(e){
  if(ver_estado_obs != "Inactivo"){
    ver_estado_obs = "Inactivo";
    set_callback(get_data_callback_obs); 
    obs_init();
  }
});

$('#buscar_codigo').keyup(function() {  
obs_init();
});

function guidGenerator() {
    var S4 = function() {
       return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    };
    return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}

//ACCIONES DE LOS BOTONES
$('#agregar_obs').click(function(e){
  $.post(
    url_codigos,
    {
      nombre: $("#nombre").val() + "" + guidGenerator(),
      descripcion: $("#descripcion").val(),
      token: $("#dpToken").val(),
      idTipoCodigo: $("#selectTipoCodigo").val()
    },function(resp){
        switch(resp){
            case 'agregado':
                swal('Éxito','Observación predefinida agregado.','success');
                obs_init();
                limpiarCampos();
                break;
            case 'camposFalta':
                swal('Error',resp,'info');
                break;
            default:
                swal('Error',resp,'error');
        }
});
});
$('#modificar_obs').click(function(e){
swal({
      title: "¿Editar observación?",
      text: "Se editará la observación",
      type: "warning",
      showCancelButton: true,
    confirmButtonColor: "#bbdefb",
      confirmButtonText: "Sí, editar",
    cancelButtonText: "Cancelar",
      closeOnConfirm: true,
      closeOnCancel: true
},
function(isConfirm){
      if (isConfirm) {
        $.post(
            url_codigos,
            {
                id: pk_obs,
                  nombre: $("#mod_nombre").val(),
                  token: $("#dpToken").val(),
                  descripcion: $("#mod_descripcion").val(),
                  idTipoCodigo: $("#selectTipoCodigoMod").val()
            },function(resp){
                switch(resp){
                    case 'modificado':
                        swal('Éxito','Observación modificada.','success');
                        obs_init();
                        limpiarCampos();
                        break;
                    case 'camposFalta':
                        swal('Error',resp,'info');
                        break;
                    case 'existente':
                        swal('Error','Observación existente.','error');
                        break;
                    default:
                        swal('Error',resp,'error');
                }
        });
      } else {
        Materialize.toast("No se editó el código", 3000, "rounded");
      }
});
});
// funcion eliminar
$('#eliminar_obs').click(function(e){
  //peticion post
  $.post(
    url_eliminar_codigos,
    
    {
      id: pk_obs,
      estado:estado_obs,      
      token: $("#dpToken").val(),
    },function(resp){
      if (resp != "") {
        obs_init();
          swal("",resp,"info");    
    }
});
});
$("#activar_obs").click(function(e){
//peticion post
  $.post(
    url_eliminar_codigos,
    {
      id: pk_obs,
      estado:estado_obs,   
      token: $("#dpToken").val(),   
    },function(resp){
        if (resp != "") {
            obs_init();
              swal("",resp,"info");    
        }
});
});

$("#cancelar_mod").click(function(e){
  limpiarCampos();
  $('#formAgregarObs').show();
$('#formModObs').hide();
$('#formElimObs').hide();
  $('#formActObs').hide();
});

$("#cancelar_eliminar").click(function(e){
limpiarCampos();
  $('#formAgregarObs').show();
$('#formModObs').hide();
$('#formElimObs').hide();
  $('#formActObs').hide();
});

$("#cancelar_activar").click(function(e){
limpiarCampos();
  $('#formAgregarObs').show();
$('#formModObs').hide();
$('#formElimObs').hide();
  $('#formActObs').hide();
});

//FIN DE ACCION DE BOTONES


function ver_obs(pk,nombre,descripcion,tipoCodigo){
pk_obs = pk;

$('#formAgregarObs').hide();
$('#formModObs').show();
$('#formElimObs').hide();
  $('#formActObs').hide();

  $("label").addClass("active");
  
$("#mod_nombre").val(nombre);
  $("#mod_descripcion").val(descripcion);
  
  $('#selectTipoCodigoMod').find('option[value="'+tipoCodigo+'"]').prop('selected', true);
$("#selectTipoCodigoMod").material_select();

}

// confirmacion si desea eliminar
function eliminar_obs(pk, nombre){
  pk_obs=pk;
  $('#formElimObs').show();
  $('#formActObs').hide();
$('#formModObs').hide();
$('#formAgregarObs').hide();
  $('#confirmacion').text("¿Desea desactivar la observación " + nombre + " ?");
  estado_obs ="Inactivo";

}

//Funcion para reactivar al personal
function activar_obs(pk, nombre){
pk_obs = pk;
$('#formActObs').show();
$('#formElimObs').hide();
$('#formModObs').hide();
$('#formAgregarObs').hide();
estado_obs ="Activo";
$("#confirmacion_activar").text("¿Desea activar la observación predefinida " + nombre + " ?");
}

// funcion para reicinar todo
function obs_init(){
cargaPagina(0);  
  $('#formElimObs').hide();
$('#formModObs').hide();
$('#formActObs').hide();
$('#formAgregarObs').show();
}

//Funcion para limpar los campos del formulario
function limpiarCampos(){

$("#nombre").val("");
  $("#descripcion").val("");

  $("label").removeClass("active");
  $("input").removeClass("valid");
  $("input").removeClass("invalid");

$("#mod_nombre").val("");
  $("#mod_descripcion").val("");
}