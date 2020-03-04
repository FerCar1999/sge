var url_pass = "/privado/php/recuperar_clave/recuperar_clave.php"
$("#formRecuperar").on("submit",function(e){
	e.preventDefault();
	var formData = new FormData(this);
	
	formData.append('codigo',$("#codigo").val());

	$.ajax({
		url: url_pass,
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function(resp){
			switch (resp){
  				case 'success':
    					Materialize.toast('Revise su correo electrónico y siga los pasos.', 5000, 'rounded');
    					setTimeout(function(){window.location="/privado"},5000);
 				break;
 				case 'error':
    					Materialize.toast('Ha ocurrido un error en su solicitud.', 10000, 'rounded');
 				break;
				default:
					Materialize.toast(resp, 5000, 'rounded');
				break;
			}
		}
	});

});