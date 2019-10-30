<?php 
    $date = date('Y');
    $actual = $date - 1;
    $filename='diario_pedagogico_'. $actual .'.sql';
    $nameDB = 'diario_pedagogico_' . $actual;
    //REALIZANDO EL BACKUP
    exec('mysqldump --user=root --password=DPUser@123 --host=database diario_pedagogico > /var/www/html/public/backups/'.$filename, $output);
    exec('mysql --user=root  --password=DPUser@123 --host=database -e "create database ' . $nameDB . '"');
    //LIMPIANDO LOS REGISTROS DE LA BASE DE DATOS
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' < /var/www/html/public/backups/'.$filename);
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`asistencias_diferidas\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`ausencias_justificadas\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`bloques_justificados\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`disciplina\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`enfermeria\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`impuntualidad\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`impuntualidad_procesados\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`inasistencias_clases\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`inasistencias_totales\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`ingreso_estudiante\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`maestros_lista_inasistencia\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`observaciones\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`asistencias\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`validar_justificacion\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "truncate table \`diario_pedagogico\`.\`bitacora_alumno\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "delete from \`diario_pedagogico\`.\`estudiantes\`"');
    exec('mysql --user=root --password=DPUser@123 --host=database '. $nameDB .' -e "ALTER TABLE \`diario_pedagogico\`.\`estudiantes\` AUTO_INCREMENT = 1"');
    if($output==''){
        echo('error');
    }
    else {
        echo('exito');
    }
?>