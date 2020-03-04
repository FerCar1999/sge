$(document).ready(function() {  
    // carga los calendarios con textos en español
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
   $('select').material_select();
});

function descargarExelClases(){	
   $("#clases_accion").val(2);
   $("#clases_inicio").val($("#date_inicio_just_clase").pickadate("picker").get( 'select', 'dd-mm-yyyy' ));
   $("#clases_fin").val($("#date_fin_just_clase").pickadate("picker").get( 'select', 'dd-mm-yyyy' ));
   $("#clases_nivel").val($("#selectNivel_just_clase").val());	
   $("#descargarExcelClases").submit();
}
function descargarExelClases2(){	
   $("#clases_accion").val(1);
   $("#clases_inicio").val($("#date_inicio_injust_clase").pickadate("picker").get( 'select', 'dd-mm-yyyy' ));
   $("#clases_fin").val($("#date_fin_injust_clase").pickadate("picker").get( 'select', 'dd-mm-yyyy' ));
   $("#clases_nivel").val($("#selectNivel_injust_clase").val());	
   $("#descargarExcelClases").submit();
}
function descargarExelClases3(){	
   $("#clases_accion").val(4);
   $("#clases_inicio").val($("#date_inicio_just_total").pickadate("picker").get( 'select', 'dd-mm-yyyy' ));
   $("#clases_fin").val($("#date_fin_just_total").pickadate("picker").get( 'select', 'dd-mm-yyyy' ));
   $("#clases_nivel").val($("#selectNivel_just_total").val());	
   $("#descargarExcelClases").submit();
}

function descargarExelClases4(){	
   $("#clases_accion").val(3);
   $("#clases_inicio").val($("#date_inicio_injust_total").pickadate("picker").get( 'select', 'dd-mm-yyyy' ));
   $("#clases_fin").val($("#date_fin_injust_total").pickadate("picker").get( 'select', 'dd-mm-yyyy' ));
   $("#clases_nivel").val($("#selectNivel_injust_total").val());	
   $("#descargarExcelClases").submit();
}

