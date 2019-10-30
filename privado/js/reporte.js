     $(document).ready(function(){
	console.log("pasaaa");
    //Cargar el combo para el nivel que tendria
    consultaGrados();
    consultaGradosEsp();
      //Creamos el evento change en el combo Grados
    $('#idComboGrados').change(function(){
        limpiarComboSecciones();
        limpiarComGrAc();
        consultaSeccion();
    });
    $('#idComboGradosEsp').change(function(){
        limpiarComGrTec();
        limpiarComboEsp();
       consultaEspecialidades();
    });

    //Creamos el evento del cambio del combo Especialidades
    $('#idComboEspecialidad').change(function(){
      //Consultamos el grupo tecnico de la especialidad seleccionada
      limpiarComGrTec();
      consultasGrTec();
      });
      //Creamos el evento change del combo de grupo tecnico
    $('#idComboSeccion').change(function(){
      if(document.getElementById("idComboGrados").value >= 4){
       limpiarComGrAc();
        consultasGrAc();
      }
      //Consultamos la seccion academica por especialidad y grupo tecnico
    });
 });
//URL DE LOS CONTROLADORES
var controller_nivel = "/privado/App/Controllers/nivel_controller.php";

  function consultaGrados(){
    limpiarLista();
    //Seleccionamos los parametros de grados
   /* var parametros = {
      "accion" : "grado",
      "nivel" : document.getElementById("selectGrados").value
    };*/
    $.ajax({
      type: "POST",
      url: controller_nivel,
      data: "accion=grado",
      dataType:"json",
      success:function(data){
        //Recolectamos los regustros y se los anidamos al combo de grados
        $.each(data, function(key, registro){
          $('#idComboGrados').append('<option value='+registro.id_grado+'>'+registro.nombre+'</option>');
          $('select').material_select();
        });
      },
      //Error de ajax
      error: function(data){
        alert("Error");
      }
    });   
  }
  function consultaGradosEsp(){
    limpiarListaEsp();
    $.ajax({
      type: "POST",
      url: controller_nivel,
      data: "accion=gradoEsp",
      dataType:"json",
      success:function(data){
        //Recolectamos los regustros y se los anidamos al combo de grados
        $.each(data, function(key, registro){
          $('#idComboGradosEsp').append('<option value='+registro.id_grado+'>'+registro.nombre+'</option>');
          $('select').material_select();
        });
      },
      //Error de ajax
      error: function(data){
        alert("Error");
      }
    });   
  }

  function consultaSeccion(){
    limpiarComboSecciones();
    //Recolectamos los parametros necesarios 
    var parametros = {
      "accion" : "seccion",
      "grado" : document.getElementById("idComboGrados").value
    };
    $.ajax({
      type: "POST",
      url: controller_nivel,
      data: parametros,
      dataType:"json",
      success:function(data){
        //Recolectamos los regustros y se los anidamos al combo de grados
        $.each(data, function(key, registro){
          $('#idComboSeccion').append('<option value='+registro.id_seccion+'>'+registro.nombre+'</option>');
          $('select').material_select();
        });
      },
      error: function(data){
        alert("Error");
      }
    });   
  }

  function consultaEspecialidades(){
    limpiarComboEsp();
    var parametros = {
      "accion" : "especialidad",
      "grado" : document.getElementById("idComboGradosEsp").value
    };
    $.ajax({
      type: "POST",
      url: controller_nivel,
      data: parametros,
      dataType:"json",
      success:function(data){
        $.each(data, function(key, registro){
          $('#idComboEspecialidad').append('<option value='+registro.id_especialidad+'>'+registro.nombre+'</option>');
          $('select').material_select();
        });
      },
      error: function(data){
        alert("Error");
      }
    });   
  }

  function consultaSeccionEsp(){
    limpiarComboSecciones();
    var parametros = {
      "accion" : "seccionEsp",
      "esp" : document.getElementById("idComboEspecialidad").value,
      "grTec" : document.getElementById("idComboGrTec").value
    };
    $.ajax({
      type: "POST",
      url: controller_nivel,
      data: parametros,
      dataType:"json",
      success:function(data){
        $.each(data, function(key, registro){
          $('#idComboSeccion').append('<option value='+registro.id_seccion+'>'+registro.nombre+'</option>');
          $('select').material_select();
        });
      },
      error: function(data){
        alert("Error");
      }
    });   
  }
  function consultasGrTec(){
    limpiarComGrTec();
    var parametros = {
      "accion" : "grTec",
      "esp" : document.getElementById("idComboEspecialidad").value
    };
    $.ajax({
      type: "POST",
      url: controller_nivel,
      data: parametros,
      dataType:"json",
      success:function(data){
        $.each(data, function(key, registro){
          $('#idComboGrTec').append('<option value='+registro.id_grupo_tecnico+'>'+registro.nombre+'</option>');
          $('select').material_select();
        });
      },
      error: function(data){
        alert("Error");
      }
    });   
  }
  function consultasGrAc(){
    limpiarComGrAc();
    var parametros = {
      "accion" : "grAc",
      "esp" : document.getElementById("idComboSeccion").value
    };
    $.ajax({
      type: "POST",
      url: controller_nivel,
      data: parametros,
      dataType:"json",
      success:function(data){
        $.each(data, function(key, registro){
          $('#idComboGrAc').append('<option value='+registro.id_grupo_academico+'>'+registro.nombre+'</option>');
          $('select').material_select();
        });
      },
      error: function(data){
        alert("Error");
      }
    });   
  }

//SECCION DE LIMPIEZA
  function limpiarLista(){
    document.getElementById("idComboGrados").innerHTML="";
  }
  function limpiarListaEsp(){
    document.getElementById("idComboGradosEsp").innerHTML="";
  }
  function limpiarComboSecciones(){
    document.getElementById("idComboSeccion").innerHTML="";
  }
  function limpiarComboEsp(){
    document.getElementById("idComboEspecialidad").innerHTML="";
  }
  function limpiarComGrTec(){
    document.getElementById("idComboGrTec").innerHTML="";
  }
  function limpiarComGrAc(){
    document.getElementById("idComboGrAc").innerHTML="";
  }
