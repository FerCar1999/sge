var url_reiniciar_datos = "/libs/reiniciar_datos.php";

$("#btnReiniciarDatos").click(function(){
    swal({
        title: "¿Está seguro de reiniciar los datos?",
        text: "Se borraran los datos para iniciar un nuevo año escolar, previamente, se hará una copia de seguridad.",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Sí, reiniciar",
        cancelButtonText: "No, lo haré luego",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {
            confirmarNuevo();
        } else {
            swal("Cancelado", "No se han realizado cambios en el sistema", "error");
        }
      });
});

function confirmarNuevo(){
    swal({
        title: "Se inicirá un nuevo año, ¿está seguro?",
        text: "Una vez confirmada la operación se inciará el proceso para un nuevo año escolar. Esto puede llevar varios minutos,por favor no cierre la ventana hasta que aparezaca un mensaje de confirmación.",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Sí, reiniciar ahora",
        cancelButtonText: "No, en otro momento",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {
            $.post(
                url_reiniciar_datos,
                {
                  
                },function(resp){
                    switch(resp){
                        case 'exito':
                            swal("Datos reiniciados", "Se han reiniciado los datos para un nuevo año escolar.", "success");
                        break;
                        case 'error':
                            swal('Ups! Algo salió mal',"No se pudo realizar el proceso de nuevo año escolar",'error');
                        break;
                        default:
                            swal('Ups! Algo salió mal',"No se pudo realizar el proceso de nuevo año escolar",'error');
                        break;
                    }
            });
        } else {
          swal("Cancelado", "No se han realizado cambios en el sistema", "error");
        }
      });
}