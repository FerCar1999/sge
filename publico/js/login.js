var url = "/publico/php/login.php";
var intentos = 0;

/*document.addEventListener("keydown", function(event) {
    if(event.keyCode == 13)
        loginAjax();
});
*/

$("#btn_login").click(function(e) {
    //Materialize.toast('De momento, el SGE ha quedado deshabilitado', 5000, 'rounded');
    //Hashear password
    var user = $("input#codigo").val().trim();
    var pass = $("input#clave").val().trim();
    $.post(
        url, {
            "user": user,
            "pass": pass
        },
        function(data) {
            if (data == "true") {
                window.location = "/estudiante";
            } else if (data == "Clave incorrecta.") {

                /*intentos++;
                if(parseInt(intentos)==3){
                    //accion=recovery;
                 swal("Demasiados intentos.");
                 return;
                }
                swal(data);*/
                Materialize.toast(data, 5000, 'rounded');
            } else if (data == "El usuario no existe.") {
                Materialize.toast(data, 5000, 'rounded');
            }
        });
});