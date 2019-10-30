var intentos=0;
document.addEventListener("keydown", function(event) {
    if(event.keyCode == 13)
        loginAjax();
});
function loginAjax(){
    // Hashear password
    var user = $("input#codigo").val().trim();
    var pass = $("input#clave").val().trim();    
    $.post(
        "/privado/php/login.php",
        {
            "user" : user,
            "pass" : pass
        },
        function(data) {
            if(data == "true"){
                window.location = "/dashboard";
            }else if(data == "pass"){
                window.location = "/cambiarclave";
            }
            else if (data == "cambio") {
                window.location = "/cambiarclave";
            }
            else if (data == "Clave incorrecta."){

                /*intentos++;
                if(parseInt(intentos)==3){
                    //accion=recovery;
                    swal("Demasiados intentos.");
                    return;
                }
                swal(data);*/
                Materialize.toast(data, 5000, 'rounded');
            }
            else if (data == "El usuario no existe."){
                //swal(data);
                Materialize.toast(data, 5000, 'rounded');
            }
        });    
}
$("#olvidepass").click(function () {
    var href = $(this).attr('href',"/recuperar-clave");
    window.location.href = href;
});