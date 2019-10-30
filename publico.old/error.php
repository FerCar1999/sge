<html>
<head>
    <title>Diario Pedagógico</title>
    <!--INCLUYO UN ARCHIVO MAESTRO-->
    <link type="text/css" rel="stylesheet" href="/utils/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="/utils/sweetalert.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="/utils/fonts/material-icons.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="/utils/materializeclockpicker.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="/publico/css/content.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta charset="utf-8">
</head>
<!--INCLUYO UN ARCHIVO MAESTRO-->
<body>
    <div class="container">
        <div class="row">
            <div class="col s12 center-align" style="margin-top:5em;">
                <h3>Error: página inexistente o incorrecta</h3>
                <br>
                <img src="/media/img/error-flat.png">
                <br>
                <br>
                <br>
                <?php
                $ninguno = true; 
                session_start();
                if (isset($_SESSION["id_personal"])) {
                echo '<a class="btn grey darken-1" onclick="location.href = \'login\'">VOLVER A LA PÁGINA DE INICIO</a>';
                $ninguno = false;
            }
            /*if (isset($_SESSION)) {
            echo '<a class="btn grey darken-1" onclick="location.href = \'inicio\'">VOLVER A LA PÁGINA DE INICIO</a>';*/
            //}else{
            if (isset($_SESSION["id_estudiante"])) {
            echo '<a class="btn grey darken-1" onclick="location.href = \'estudiante\'">VOLVER A LA PÁGINA DE INICIO</a>';
            $ninguno = false;
        }
        if ($ninguno) {
        echo '<a class="btn grey darken-1" onclick="location.href = \'inicio\'">VOLVER A LA PÁGINA DE INICIO</a>';
    }
    //}
    ?>
</div>
</div>
</div>
</body>
</html>