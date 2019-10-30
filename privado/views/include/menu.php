<?php     
    $total_notificaciones = 0;
    if(!isset($_SESSION['id_personal'])){
        header("location: /login");
        
    }
    $now = time();

    if ($now > $_SESSION['expire']) {
        session_destroy();
        header("location: /login");
        
    }else {
            // le sumamos otros 10 minutos
            $_SESSION['expire'] = $now + (50 * 60); 
     }
    //if ($_SESSION["permiso"] == "Docente Guía") {
        require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/notificaciones/notificaciones.php");
        $notificaciones = new notificaciones();
        $data = $notificaciones->countNotificaciones($_SESSION['id_personal']);
        if ($data[0]>0)$total_notificaciones = $data[0];    
    //}
    require_once($_SERVER['DOCUMENT_ROOT']."/privado/php/functions/modulos.php");
    $modulos = get_modules();
?>
<ul id="navfixed" class="side-nav <?php if(!isset($nonavfixed)) echo 'fixed';  ?>">
    <li>
        <div class="userView">
            <div class="background"></div>
            <a href="#!user"><img class="circle" src="<?php echo $_SESSION['foto']; ?>"></a>
            <a href="#!name"><span class="white-text name"><?php echo $_SESSION["nombre"].' '.$_SESSION["apellido"]; ?></span></a>
            <a href="#!email"><span class="white-text email"><?php echo $_SESSION["permiso"]; ?></span></a>
        </div>
    </li>


    <!--ul class="optionsMenu">
        <li class="abrirLista"><a href="#"><i class="material-icons">people</i>Prueba</a></li>
        <li class="cerrarLista"><a href="#"><i class="material-icons">close</i>Prueba</a></li>
        <li class="opcion"><a href="#"><i class="material-icons">people</i>Opción 1</a></li>
    </ul>

    <ul class="optionsMenu">
        <li class="abrirLista"><a href="#"><i class="material-icons">people</i>Prueba 2</a></li>
        <li class="cerrarLista"><a href="#"><i class="material-icons">close</i>Prueba 2</a></li>
        <li class="opcion"><a href="#"><i class="material-icons">people</i>Opción 1</a></li>
      </ul-->


    <?php 
    // verifica si administrador
    if(isset($modulos['Permisos'])){
        $_SESSION["isAdmin"] = true;
    }
    echo '<li><a href="dashboard"><i class="material-icons">dashboard</i>Dashboard</a></li>';
    if(isset($modulos['Personal']) || isset($modulos['Estudiantes'])){
        
        echo '
        <ul class="optionsMenu">
            <li class="abrirLista"><a href="#"><i class="material-icons">people</i>Usuarios</a></li>
            <li class="cerrarLista"><a href="#"><i class="material-icons">close</i>Usuarios</a></li>
            <li class="opcion"><a href="personal" class="submenu-text" ><i class="material-icons submenu">people</i>Personal</a></li>
            <li class="opcion"><a href="alumnos" class="submenu-text" ><i class="material-icons submenu">face</i>Estudiantes</a></li>
            <li class="opcion"><a href="historial-estudiante" class="submenu-text" ><i class="material-icons submenu">insert_drive_file</i>Historial Estudiante</a></li>
            <li class="opcion"><a href="ingreso-estudiantes" class="submenu-text" ><i class="material-icons submenu">fingerprint</i>Ingresos Estudiante</a></li>
        </ul>';
    }    
    if(isset($modulos['Grados']) || isset($modulos['Tiempos']) || isset($modulos['Niveles']) || isset($modulos['Asignaturas']) || isset($modulos['Secciones']) || isset($modulos['Especialidades'])){
        echo 
            '<ul class="optionsMenu">
                <li class="abrirLista"><a href="#"><i class="material-icons">book</i>Gestión académica</a></li>
                <li class="cerrarLista"><a href="#"><i class="material-icons">close</i>Gestión académica</a></li>';

        if(isset($modulos['Tiempos'])){
            echo '<li class="opcion"><a href="tiempos" class="submenu-text"><i class="material-icons submenu">schedule</i>Tiempos</a></li>';    
        }
        if(isset($modulos['Grados'])){
            echo '<li class="opcion"><a href="grados" class="submenu-text"><i class="material-icons submenu">lightbulb_outline</i>Grados</a></li>';
        }               
        if(isset($modulos['Etapas'])){
            echo '<li class="opcion"><a href="etapas" class="submenu-text"><i class="material-icons submenu">trending_up</i>Etapas</a></li>';
        }
        if(isset($modulos['Asignaturas'])){
            echo '
                <li class="opcion"><a href="tipos-asignaturas" class="submenu-text"><i class="material-icons submenu">bookmark</i>Tipos de asignatura</a></li>
                <li class="opcion"><a href="asignaturas" class="submenu-text"><i class="material-icons submenu">bookmark_border</i>Asignaturas</a></li>';
        }
        if(isset($modulos['Secciones'])){
            echo '<li class="opcion"><a href="secciones" class="submenu-text"><i class="material-icons submenu">work</i>Secciones</a></li>';
        }
        if(isset($modulos['Grupos'])){
            echo '<li class="opcion"><a href="grupos" class="submenu-text"><i class="material-icons submenu">local_library</i>Grupos</a></li>';
        }
        if(isset($modulos['Especialidades'])){
            echo '<li class="opcion"><a href="especialidades" class="submenu-text"><i class="material-icons submenu">extension</i>Especialidades</a></li>'; 
        }
        if(isset($modulos['Asuetos'])){
            echo '<li class="opcion"><a href="asuetos" class="submenu-text"><i class="material-icons submenu">location_off</i>Asuetos</a></li>';    
        }
        if (isset($modulos['Códigos'])) {
            echo '<li class="opcion"><a href="tipos-codigos" class="submenu-text"><i class="material-icons submenu">announcement</i>Tipos códigos</a></li>
            <li class="opcion"><a href="codigos" class="submenu-text"><i class="material-icons submenu">gavel</i>Códigos</a></li>';
        }
        if(isset($modulos['Reiniciar datos'])){
            echo '<li class="opcion"><a href="reiniciar-datos" class="submenu-text"><i class="material-icons submenu">backup</i>Nuevo año escolar</a></li>';            }
        echo '</ul>';

    }
    if(isset($modulos['Horario'])){
        echo '<li><a href="#" onclick="openHorarios(event);" ><i class="material-icons">date_range</i>Horario</a></li>';
    }
    if(isset($modulos['Enfermeria'])){
        echo '<li><a href="enfermeria"><i class="material-icons">healing</i>Enfermeria</a></li>';
    }
    if(isset($modulos['Asistencia'])){
        echo '<li><a href="asistencia"><i class="material-icons">beenhere</i>Asistencia</a></li>';
    }
    if(isset($modulos['Asis. Administrador'])){
        echo '<li><a href="ver-asistencias-administrador"><i class="material-icons">beenhere</i>Asistencia</a></li>';
    }
    
    if(isset($modulos['Asistencia'])){
        echo '<li><a href="asistencia-diferida"><i class="material-icons">beenhere</i>Asistencia Diferida</a></li>';
    }
    if(isset($modulos['Permisos'])){
        echo '<li><a href="permisos"><i class="material-icons">lock</i>Permisos</a></li>';
    }

    if(isset($modulos['Códigos']) || isset($modulos['Códigos exaula'])){
        echo '<ul class="optionsMenu">
        <li class="abrirLista"><a href="#"><i class="material-icons">security</i>Disciplina</a></li>
        <li class="cerrarLista"><a href="#"><i class="material-icons">close</i>Disciplina</a></li>';        
        if(isset($modulos['Códigos exaula'])){
            echo '<li class="opcion"><a href="codigos-exaula" class="submenu-text"><i class="material-icons submenu">face</i>Alumnos</a></li>';
        }
        if(isset($modulos['Observaciones'])){
            echo '<li class="opcion"><a href="observaciones" class="submenu-text"><i class="material-icons submenu">book</i>Observaciones</a></li>';
        }
        
        if(isset($modulos['Control Disciplinario'])){
            echo '<li class="opcion"><a href="control-diciplinario" class="submenu-text"><i class="material-icons submenu">history</i>Control disciplinario</a></li>';
        }
        if(isset($modulos['Suspendidos'])){
            echo '<li class="opcion"><a href="suspendidos" class="submenu-text"><i class="material-icons submenu">warning</i>Suspendidos</a></li>';
        }
        if(isset($modulos['Llegadas Tarde'])){
            echo '<li class="opcion"><a href="llegadas-tarde" class="submenu-text"><i class="material-icons submenu">alarm_off</i>Llegadas Tarde</a></li>';
        }
        if(isset($modulos['Control Inasistencias'])){
            echo '<li><a href="control-llegadas-tarde"><i class="material-icons">pan_tool</i>Control Llegadas Tarde</a></li>';
        }
    echo '</ul>';
    }
    if(isset($modulos['Locales'])){
        echo '
            <ul class="optionsMenu">
                <li class="abrirLista"><a href="#"><i class="material-icons">place</i>Locales</a></li>
                <li class="cerrarLista"><a href="#"><i class="material-icons">close</i>Locales</a></li>
                <li class="opcion"><a href="tipos-locales" class="submenu-text"><i class="material-icons submenu">subway</i>Tipos de locales</a></li>
                <li class="opcion"><a href="locales" class="submenu-text"><i class="material-icons submenu">explore</i>Locales</a></li>
            </ul>';
        }
    if(isset($modulos['Justificaciones'])){
        echo '<li><a href="permisos-inasistencias"><i class="material-icons">check_circle</i>Permisos Anticipados</a></li>';
        echo '
            <ul class="optionsMenu">
                <li class="abrirLista"><a href="#"><i class="material-icons">send</i>Justificaciones</a></li>
                <li class="cerrarLista"><a href="#"><i class="material-icons">close</i>Justificaciones</a></li>
                <li class="opcion"><a href="justificacion-llegadas-tarde" class="submenu-text"><i class="material-icons submenu">near_me</i>Impuntualidades</a></li>
                <li class="opcion"><a href="justificaciones-individual" class="submenu-text"><i class="material-icons submenu">person</i>Inasistencias Individual</a></li>
                <li class="opcion"><a href="justificar-grupo" class="submenu-text"><i class="material-icons submenu">people</i>Inasistencias Grupal</a></li>           
                <li class="opcion"><a href="eliminar-inasistencia-total" class="submenu-text"><i class="material-icons submenu">delete</i>Inasistencia Total</a></li>
     
            </ul>';
        }
        if(isset($modulos['Reportes'])){
        echo '<li><a href="reportes"><i class="material-icons">picture_as_pdf</i>Reportes</a></li>';
        
    }    
    if(isset($modulos['Uso Sistema'])){
        echo '<li><a href="/clases-sin-marcar-asistencia"><i class="material-icons">assessment</i>Uso del Sistema</a></li>';
    }
    if(isset($modulos['Bitacora'])){
        echo '<li><a href="/bitacora"><i class="material-icons">find_in_page</i>Bitácora</a></li>';
    }
    

    ?>
</ul>
<div id="<?php if(isset($nonavfixed)) echo 'menu-full'; else echo 'menu'; ?>" class="z-depth-3">
    <div class="nav-wrapper">
        <ul class="left <?php if(!isset($nonavfixed)) echo 'hide'?> hide-on-med-and-down" id="options"> 
            <li class="tooltipped" data-position="right" data-delay="50" data-tooltip="Abrir el menu">
                <a href="#" data-activates="navfixed" class="button-collapse"><i class="material-icons">menu</i></a>
            </li>
        </ul>
        <ul id="options" class="right">
            <?php  
                $tooltipText = "";
                $spanText = "";
                if ($total_notificaciones > 0) {
                    if ($total_notificaciones == 1){
                        $tooltipText = "Tienes ". $total_notificaciones . " notificación";
                        $spanText = "notificación";
                    }
                    else{
                        $tooltipText = "Tienes ". $total_notificaciones . " notificaciones";
                        $spanText = "notificaciones";
                    }
                    echo '
                <li class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="'.$tooltipText.'"><a href="/notificaciones"><span class="new badge amber darken-4" data-badge-caption="'.$spanText.'">'.$total_notificaciones.'</span><i class="material-icons">notifications_active</i></a></li>';
                }else{
                    echo '<li class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Sin notificaciones"><a href="notificaciones"><span class="new badge grey" data-badge-caption="Sin notificaciones"></span><i class="material-icons">notifications_none</i></a></li>';
                }
            ?>
            <li class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Editar perfil"><a href="/perfil"><i class="material-icons">settings</i></a></li>
            <li class="tooltipped" data-position="left" data-delay="50" data-tooltip="Cerrar sesión"><a href="/privado/php/logout.php"><i class="material-icons">power_settings_new</i></a></li>
            <!--<li><a href="#">Cerrar sesión</a></li>-->
        </ul>
    </div>
    <a href="#" data-activates="navfixed" class="button-collapse"><i class="material-icons menu-button">menu</i></a>
</div>