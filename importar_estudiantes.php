<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Importar estudiantes</title>
</head>
<body>
<?php
/*
Para vaciar la tabla estudiantes:
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE estudiantes;
SET FOREIGN_KEY_CHECKS=1;
*/
if(!empty($_POST))
{
	if($_FILES['cvs']['name'] != null)
	{
		if (substr($_FILES['cvs']['name'], -3) == "csv")
		{
			try
			{
				include("/var/www/html/public/libs/database.php");//Dirección del archivo donde esta la conexion
				$ruta 	= "tmp_import/".$_FILES['cvs']['name'];
				move_uploaded_file($_FILES['cvs']['tmp_name'], $ruta);
				$num = 0;
				$file = file($ruta);
				set_time_limit(800);
				foreach ($file as $line) {
					$data = str_getcsv($line);
					$sql="INSERT INTO estudiantes(apellidos, nombres, codigo, correo, clave, foto, id_especialidad, id_grado, id_grupo_academico, id_grupo_tecnico, id_seccion, estado, id_personal, procesado, fecha_procesado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$params = array(utf8_encode($data[0]), utf8_encode($data[1]), $data[2], $data[3], password_hash($data[4], PASSWORD_DEFAULT), $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $data[13], $data[14]);
					if (Database::executeRow($sql, $params))
					{
						$num++;
					}
				}
				/*$file = fopen ($ruta, "r");
				while ($data = fgetcsv($file, 150, ","))
				{
					$sql="INSERT INTO estudiantes(apellidos, nombres, codigo, correo, clave, foto, id_especialidad, id_grado, id_grupo_academico, id_grupo_tecnico, id_seccion, estado, id_personal, procesado, fecha_procesado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					$params = array(utf8_encode($data[0]), utf8_encode($data[1]), $data[2], $data[3], password_hash($data[4], PASSWORD_DEFAULT), $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $data[13], $data[14]);
					if (Database::executeRow($sql, $params))
					{
						$num++;
					}
				}
				fclose ($file);
				*/
				echo "<div>La importación fue satisfactoria. Cantidad de registros: $num</div>";
				exit;
			}
			catch(Exception $error)
			{
				echo "<div>Error: ".$error->getMessage().". Se importaron $num registros.</div>";
				exit;
			}
		}
		else 
		{
			echo "<div>Debe seleccionar un archivo valido.</div>";
		}
	}
	else 
	{
		echo "<div>Debe seleccionar un archivo.</div>";
	}
}
?>
<article>
	<section>
		<h1>Importar estudiantes</h1>
		<form method="post" enctype="multipart/form-data">
		  <p>Subir archivo CSV</p>
		  <p><input type="file" name="cvs"/></p>
		  <p><input type="submit" name="subir" value="Subir"/></p>
		</form>
	</section>
</article>
</body>
</html>
