// url para las peticiones ajax 
var url_data = "/privado/php/personal/perfilpersonal.php",
	url_cambio = "/privado/php/personal/cambiosvalidar.php",
	url_personal = "/privado/php/personal/editarperfil.php";;

// almace el id a modificar o eliminar
var pk_personal,foto_antigua;

function cambios_validar(cambio){
	$.ajax({ 		 		
		data:{			
			p: cambio
		},
		async:false,
		type:"POST",
		url:url_cambio
	}).done(function(data,textStatus,jqXHR){

		// obtiene la clave lista del json data
		var lista = data.lista;

		// genera el cuerpo de la tabla
		$.each(lista, function (i, elem) {
			
			if (elem.resp)
				$("#formPersonalMod").submit();
			else
				swal("Error","Clave incorrecta.","error");
		});
	}).fail(function(jqXHR,textStatus,textError){
		swal("Error al realizar la peticion dame".textError);
	});
}

function cargar_datos_personal(){
	$.ajax({ 		 		
		data:{			
			
		},
		async:false,
		type:"POST",
		url:url_data
	}).done(function(data,textStatus,jqXHR){

		// obtiene la clave lista del json data
		var lista = data.lista;

		// genera el cuerpo de la tabla
		$.each(lista, function (i, elem) {
			$("label").addClass("active");
			pk_personal = elem.id;
			foto_antigua = elem.foto;
			$("#mod_nombre").val(elem.nombre);
		  	$("#mod_apellido").val(elem.apellido);
		  	$("#mod_correo").val(elem.correo);
		  	$('#img_personal_mod').attr('src',elem.foto);
		});
	}).fail(function(jqXHR,textStatus,textError){
		//alert("Error al realizar la peticion dame".textError);
	});
}

$(document).ready(function () {
	cargar_datos_personal();
 });


$(document).ready(function(){
	$("#formPersonalMod").on("submit",function(e){
		e.preventDefault();
		var formData = new FormData(this);
		
		formData.append('id',pk_personal);
		formData.append('nombre', $("#mod_nombre").val());
	  	formData.append('apellido',$("#mod_apellido").val());
	  	formData.append('correo',$("#mod_correo").val());
	  	formData.append('clave',$("#mod_clave").val());
	  	formData.append('claveR',$("#mod_claveR").val());
	  	formData.append('url_foto', foto_antigua);

	  	$.ajaxSetup({async: false});
	  	$.ajax({
	  		url: url_personal,
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			success: function(resp){
				switch (resp){
					case 'Personal modificado.':
						location.reload();
					break;
					default:
						Materialize.toast(resp, 3000, 'rounded');
					break;
				}
			}
	  	});

	});
});

//ACCIONES DE LOS BOTONES
$('#modificar_personal').click(function(e){
	swal({
	  	title: "CLAVE ACTUAL",
	  	text: "Ingrese su clave actual para efectuar los cambios.",
	  	type: "input",
	  	inputType: "password",
	  	showCancelButton: true,
	  	closeOnConfirm: true,
	  	animation: "slide-from-top",
	  	inputPlaceholder: "Clave actual"
	},
	function(inputValue){
	  	if (inputValue === false) return false;	  
	  	if (inputValue === "") {
	    	swal.showInputError("Por favor, ingrese la clave actual.");
	    	return false
	  	}else{
	  		cambios_validar(inputValue);
	  	}
	});
	//
});

$("#cancelar_mod").click(function(e){
  	window.location.href = "/privado/views/dashboard.php";
});

//FIN DE ACCION DE BOTONES

function readURL(input) {
  	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#img_personal_mod').attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
  	}
}

$("#fotoPersonalMod").change(function(){
  	readURL(this);
});