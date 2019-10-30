// variable para las peticiones ajax
var url_llegadas_tarde = "/privado/php/estadisticas/charts-llegadas-tarde.php",
    url_codigos = "/privado/php/estadisticas/charts-codigos-mensuales.php";
$(function () {
    load_chart();
    load_chart2();
    // estas no existen!! todavia ... se crean dinamicamente
    load_chart3();
    load_chart4();
    load_chart5();
    load_chart6();
});

// cargar todos los graficos
function load_chart2(){
    // arreglos para las series el grafico
    var predata1 = [];    
    $.ajax({
        data:{},
        type:"POST",
        async: true,
        dataType: "json",
        url:url_codigos,
        success: function (data) {
            // obtiene los resultados json 
            var lista1 = data.lista1;  
            
            for (var i = 0;i < 12; i++) {                
                predata1[i] = parseInt(lista1[i]);                
            }            
        // genera el grafico
        chart2(predata1);
    }
  });
}
function load_chart(){
    // arreglos para las series el grafico
    var predata1 = [] ,predata2 = [];    
    $.ajax({
        data:{},
        type:"POST",
        async: true,
        dataType: "json",
        url:url_llegadas_tarde,
        success: function (data) {
            // obtiene los resultados json 
            var lista1 = data.lista1;  
            var lista2 = data.lista2;            
            for (var i = 0;i < 12; i++) {                
                predata1[i] = parseInt(lista1[i]);
                predata2[i] = parseInt(lista2[i]);
            }            
        // genera el grafico
        chart(predata1,predata2);
    }
  });
}
// grafico llegadas tarde
function chart(data1,data2){    
    Highcharts.chart('chart1', {

        title: {
            text: 'LLEGADAS TARDE'
        },

        yAxis: {
            tickInterval: 10,
            breaks: [{
                from: 31,
                to: 110,
                breakSize: 5
            }]
        },
        // valores de los labels
         xAxis: {
            categories: ['Ene', 'Feb', 'Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
        },
        yAxis: {
            title: {
                text: 'Cantidad estudiantes'
            }
        },
        // los valores de las columnas
        series: [{
            type: 'column',
            name:"Institución",
            data: data1
        },{
            type: 'column',
            name: "Salón",
            data: data2
        }]
    });    
}

// grafico llegadas tarde
function chart2(data1){    
    Highcharts.chart('chart2', {

        title: {
            text: 'CODIGOS ASIGNADOS'
        },

        yAxis: {
            tickInterval: 10,
            breaks: [{
                from: 31,
                to: 110,
                breakSize: 5
            }]
        },
        // valores de los labels
         xAxis: {
            categories: ['Ene', 'Feb', 'Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
        },
        yAxis: {
            title: {
                text: 'Cantidad códigos'
            }
        },
        // los valores de las columnas
        series: [{
            type: 'column',
            name:"Códigos",
            data: data1
        }]
    });    
}
