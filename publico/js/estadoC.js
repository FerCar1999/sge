var urlx = 'fox/index.php';
$(document).ready(function() {
    traerTodosLosPagos();
});

function traerTodosLosPagos() {
    $.ajax({
        type: "POST",
        url: urlx,
        data: {
            accion: 'pagos'
        },
        dataType: "JSON",
        success: function(data) {
            $.each(data, function(x, y) {
                var fecha = calcularFecha(y[20], y[13], y[14]);
                obtenerPagosTabla(y[1], y[4], fecha, x, y[16]);
            });
        }
    });
}

function calcularFecha(anio, mes, dia) {
    var fecha = new Date();
    var aniox = fecha.getFullYear() + anio;
    return dia + "/" + mes + "/" + aniox;
}

function calcularFechaDos(fecha) {
    var dia, mes, anio;
    anio = fecha.charAt(0) + "" + fecha.charAt(1) + "" + fecha.charAt(2) + "" + fecha.charAt(3);
    mes = fecha.charAt(4) + "" + fecha.charAt(5);
    dia = fecha.charAt(6) + "" + fecha.charAt(7);
    return dia + "/" + mes + "/" + anio;
}

function obtenerPagosTabla(tipopago, monto, fecha, posicion, multa) {
    switch (tipopago) {
        case '001':
            obtenerPagosRealizados(tipopago, 'RESERVA DE MATRÍCULA', monto, fecha, posicion, multa);
            break;
        case '002':
            obtenerPagosRealizados(tipopago, 'COMPLEMENTO DE MATRÍCULA', monto, fecha, posicion, multa);
            break;
        default:
            $.ajax({
                type: "POST",
                url: urlx,
                data: {
                    accion: 'obtenerTabla',
                    codigo: tipopago
                },
                dataType: "JSON",
                success: function(data) {
                    obtenerPagosRealizados(tipopago, data[0], monto, fecha, posicion, multa);
                }
            });
            break;
    }
}

function obtenerPagosRealizados(tipopago, nombretipomonto, monto, fecha, posicion, multa) {
    //var elem = new Object();
    $.ajax({
        type: "POST",
        url: urlx,
        data: {
            accion: 'pagosRealizados',
            tipopago: tipopago
        },
        dataType: "JSON",
        success: function(data) {
            switch (data.length) {
                case 0:
                    var anio = new Date().getTime();
                    var fechasplit = fecha.split('/');
                    var fechaP = fechasplit[2] + "-" + fechasplit[1] + "-" + fechasplit[0];
                    var fex = new Date(fechaP).getTime();
                    var diff = fex - anio;
                    if (diff >= 0) {
                        $('#' + posicion).append('<td>' + nombretipomonto + '</td><td></td><td></td><td></td><td></td><td>$' + monto + '</td><td>$0</td><td>$' + monto + '</td><td>' + fecha + '</td>');
                    } else {
                        $('#' + posicion).html('<td style="color:red;">' + nombretipomonto + '</td><td></td><td></td><td></td><td></td><td style="color:red;">$' + monto + '</td><td style="color:red;">$' + (monto * (multa / 100)) + '</td><td style="color:red;">$' + (monto + (monto * (multa / 100))) + '</td><td style="color:red;">' + fecha + '</td>');
                    }
                    break;
                case 1:
                    if ((data[0][2] + data[0][3]) == (monto + data[0][3])) {
                        //ubicaciones[posicion] = '<tr><td>$' + monto + '</td><td>' + nombretipomonto + '</td><td> ' + fecha + '</td><td></td><td></td><td>Cancelado</td></tr>';
                        $('#' + posicion).html('<td>' + nombretipomonto + '</td><td>$' + monto + '</td><td> $' + data[0][3] + '</td><td>$' + (monto + data[0][3]) + '</td><td>' + calcularFechaDos(data[0][5]) + '</td><td></td><td></td><td></td><td></td>');
                    } else {
                        $('#' + posicion).html('<td>' + nombretipomonto + '</td><td></td><td></td><td></td><td></td><td>$' + (data[0][2] - monto) + '</td><td>$' + data[0][3] + '</td><td>$' + ((data[0][2] - monto) + data[0][3]) + '</td><td>' + fecha + '</td>');
                    }
                    break;

                default:
                    var total = 0;
                    for (let i = 0; i < data.length; i++) {
                        total += data[i][2];
                    }
                    if ((total + data[0][3]) == (monto + data[0][3])) {
                        $('#' + posicion).html('<td>' + nombretipomonto + '</td><td>$' + total + '</td><td> $' + data[0][3] + '</td><td>$' + (total + data[0][3]) + '</td><td>' + calcularFechaDos(data[0][5]) + '</td><td></td><td></td><td></td><td></td>');
                    } else {
                        $('#' + posicion).html('<td>' + nombretipomonto + '</td><td></td><td></td><td></td><td></td><td>$' + (monto - total) + '</td><td>$' + data[0][3] + '</td><td>$' + ((monto - total) + data[0][3]) + '</td><td>' + fecha + '</td>');
                    }
                    break;
            }
        }
    });
}

function mostrarEstadoCuenta(nombre, apellido) {
    var fecha = new Date();
    var doc = new jsPDF({
        orientation: 'l',
        unit: 'mm',
        format: 'letter'
    });
    //Agregando imagen 
    var logo = new Image();
    logo.src = '../media/img/logo.png';
    doc.addImage(logo, 'JPG', 15, 15, 25, 25);
    //Agregando Institucion
    doc.setFontSize(18);
    var textWidth = doc.getStringUnitWidth("INSTITUTO TÉCNICO RICALDONE") * doc.internal.getFontSize() / doc.internal.scaleFactor;
    var x = (doc.internal.pageSize.width - textWidth) / 2;
    doc.text(x, 23, "INSTITUTO TÉCNICO RICALDONE");
    //
    doc.setFontSize(15);
    var textWidth = doc.getStringUnitWidth("Estado de Cuenta") * doc.internal.getFontSize() / doc.internal.scaleFactor;
    var x = (doc.internal.pageSize.width - textWidth) / 2;
    doc.text(x, 30, "Estado de Cuenta");
    //Obtener anio lectivo
    doc.setFontSize(10);
    var textWidth = doc.getStringUnitWidth("Año Lectivo: " + fecha.getFullYear()) * doc.internal.getFontSize() / doc.internal.scaleFactor;
    var x = (doc.internal.pageSize.width - textWidth) / 2;
    doc.text(x, 35, "Año Lectivo: " + fecha.getFullYear());
    //Agregar nombre de alumno
    doc.setFontSize(12);
    doc.text(15, 50, "Alumno: " + nombre.trim() + " " + apellido.trim());

    doc.setFontSize(12);
    //Agregando texto explicando que es documento solo informativo
    doc.text(15, 160, "*Este documento es de caracter informativo.");
    doc.setFontSize(12);
    //Agregando texto explicando el proceso de informacion
    doc.text(15, 165, "*Si no encuentra la actualizacion en algún pago, espere 24 horas para verlo en este documento.");
    let finalY = 45;
    doc.autoTable({
        startY: finalY + 10,
        html: '#tabla'
    });
    window.open(doc.output('bloburl', 'estado_de_cuenta.pdf'), '_blank');
}