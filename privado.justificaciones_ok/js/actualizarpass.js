var url_newpass = "/privado/php/newpass.php";

$("#newPass").click(function () {
	$.post(
		url_newpass,
		{
			clave: $("#clave").val(),
			claveR: $("#claveR").val(),
		},function(resp){								
			switch (resp){
				case 'success':
					Materialize.toast('Contraseña modificada exitosamente.', 3000, 'rounded');
					setTimeout(function(){window.location = "/privado/views/dashboard.php";},3000);
				break;
				case 'Las claves no coinciden.':
					Materialize.toast(resp, 3000, 'rounded');
				break;
				default:
					Materialize.toast(resp, 3000, 'rounded');
				break;
			}
		}
	);
});