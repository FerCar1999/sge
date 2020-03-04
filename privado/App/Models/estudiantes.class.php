<?php
class estudiantes {
//DECLARACION DE VARIABLES
private $grado = null;
private $anio = null;
private $especialidad = null;
private $grupoTecnico = null;
private $seccion = null;
private $grupoAcademico = null;
private $inicio = null;
private $fin = null;
private $idSeccion = null;
//SOBRECARGA DE PROPIEDADES SET AND GET
public function setGrado($value){
    $this->grado = $value;
    return true;
    
}
public function getGrado(){
 return $this->grado;
}

public function setAnio($value){
    $this->anio = $value;
    return true;
}
public function getAnio(){
 return $this->anio;
}

public function setEspecialidad($value){
    $this->especialidad = $value;
    return true;
}
public function getEspecialidad(){
 return $this->especialidad;
}

public function setGrupotecnico($value){
    $this->grupoTecnico = $value;
    return true;
}
public function getGrupotecnico(){
 return $this->grupoTecnico;
}

public function setSeccion($value){
    $this->seccion = $value;
    return true;
}
public function getSeccion(){
 return $this->seccion;
}

public function setGrupoacademico($value){
    $this->grupoAcademico = $value;
    return true;
}
public function getGrupoacademico(){
 return $this->grupoAcademico;
}

public function setInicio($value)
{
    $this->inicio = $value;
    return true;
    
}
public function getInicio()
{
    return $this->inicio;
}

public function setFin($value)
{
    $this->fin = $value;
    return true;
    
}
public function getFin()
{
    return $this->fin;
}

public function setIdSeccion($value){
    $this->idSeccion = $value;
    return true;
}
public function getIdSeccion(){
 return $this->idSeccion;
}
//CONSULTAS

public function getExcelTabla(){
    $sql = "SELECT DISTINCTROW(i.id_estudiante),i.id_inasistencia,i.fecha_hora,i.estado, a.motivo 
    FROM inasistencias_clases i, ausencias_justificadas a 
    INNER JOIN estudiantes USING(id_estudiante) where i.id_estudiante=a.id_estudiante limit 20";
    $params = array(null);
    return Database::getRows($sql,$params);
}
public function getExcelTercerCiclo(){
    $sql = "SELECT DISTINCTROW
	(st.codigo),
	sc.nombre as seccion,
    gt.nombre as gtecnico,
    DATE(a.fecha_hora) AS fecha,
    'NO' AS falto
FROM
    asistencias a
INNER JOIN estudiantes st USING(id_estudiante)
INNER JOIN grados gd USING(id_grado)
INNER JOIN secciones sc USING(id_seccion)
INNER JOIN especialidades es USING(id_especialidad)
INNER JOIN grupos_tecnicos gt USING(id_grupo_tecnico)
INNER JOIN grupos_academicos ga USING(id_grupo_academico)
WHERE
st.id_grado = ? AND st.id_seccion = ? AND st.id_grupo_academico = ? AND st.id_grupo_tecnico = ? AND st.id_especialidad = ? AND DATE(fecha_hora) BETWEEN ? AND ?
UNION ALL
SELECT DISTINCTROW
	(st.codigo),
	sc.nombre as seccion,
    gt.nombre as gtecnico,
    DATE(a.fecha_hora) AS fecha,
    CASE
    WHEN a.estado='Injustificada' THEN 'SI'
    WHEN a.estado='Justificada' THEN 'SI'
END
FROM
    inasistencias_clases a
INNER JOIN estudiantes st USING(id_estudiante)
INNER JOIN grados gd USING(id_grado)
INNER JOIN secciones sc USING(id_seccion)
INNER JOIN especialidades es USING(id_especialidad)
INNER JOIN grupos_tecnicos gt USING(id_grupo_tecnico)
INNER JOIN grupos_academicos ga USING(id_grupo_academico)
WHERE
st.id_grado = ? AND st.id_seccion = ? AND st.id_grupo_academico = ? AND st.id_grupo_tecnico = ? AND st.id_especialidad = ? AND DATE(fecha_hora) BETWEEN ? AND ?
ORDER BY
    fecha";
    $params = array($this->anio,$this->seccion,$this->grupoAcademico,$this->grupoTecnico,$this->especialidad,$this->inicio,$this->fin,$this->anio,$this->seccion,$this->grupoAcademico,$this->grupoTecnico,$this->especialidad,$this->inicio,$this->fin);
    return Database::getRows($sql,$params);
}
//2 CONSULTA
public function getExcelSeccion(){
    $sql = "SELECT DISTINCTROW
	(st.codigo),
	sc.nombre as seccion,
    gt.nombre as gtecnico,
    DATE(a.fecha_hora) AS fecha,
    'NO' AS falto
FROM
    asistencias a
INNER JOIN estudiantes st USING(id_estudiante)
INNER JOIN grados gd USING(id_grado)
INNER JOIN secciones sc USING(id_seccion)
INNER JOIN especialidades es USING(id_especialidad)
INNER JOIN grupos_tecnicos gt USING(id_grupo_tecnico)
INNER JOIN grupos_academicos ga USING(id_grupo_academico)
WHERE
st.id_grado = ? AND st.id_seccion = ? AND st.id_grupo_academico = ?   AND DATE(fecha_hora) BETWEEN ? AND ?
UNION ALL
SELECT DISTINCTROW
	(st.codigo),
	sc.nombre as seccion,
    gt.nombre as gtecnico,
    DATE(a.fecha_hora) AS fecha,
    CASE
    WHEN a.estado='Injustificada' THEN 'SI'
    WHEN a.estado='Justificada' THEN 'SI'
END
FROM
    inasistencias_clases a
INNER JOIN estudiantes st USING(id_estudiante)
INNER JOIN grados gd USING(id_grado)
INNER JOIN secciones sc USING(id_seccion)
INNER JOIN especialidades es USING(id_especialidad)
INNER JOIN grupos_tecnicos gt USING(id_grupo_tecnico)
INNER JOIN grupos_academicos ga USING(id_grupo_academico)
WHERE
st.id_grado = ? AND st.id_seccion = ? AND st.id_grupo_academico = ?   AND DATE(fecha_hora) BETWEEN ? AND ?
ORDER BY
    fecha";
    $params = array($this->anio,$this->seccion,$this->grupoAcademico,$this->inicio,$this->fin,$this->anio,$this->seccion,$this->grupoAcademico,$this->inicio,$this->fin);
    return Database::getRows($sql,$params);
}
//Tercera Consulta
public function getExcelEspecialidad(){
    $sql = "SELECT DISTINCTROW
	(st.codigo),
	sc.nombre as seccion,
    gt.nombre as gtecnico,
    DATE(a.fecha_hora) AS fecha,
    'NO' AS falto
FROM
    asistencias a
INNER JOIN estudiantes st USING(id_estudiante)
INNER JOIN grados gd USING(id_grado)
INNER JOIN secciones sc USING(id_seccion)
INNER JOIN especialidades es USING(id_especialidad)
INNER JOIN grupos_tecnicos gt USING(id_grupo_tecnico)
INNER JOIN grupos_academicos ga USING(id_grupo_academico)
WHERE
    st.id_grado = ? AND st.id_especialidad = ? AND st.id_grupo_tecnico = ?  AND DATE(a.fecha_hora) BETWEEN ? AND ?
UNION ALL
SELECT DISTINCTROW
	(st.codigo),
    sc.nombre as seccion,
    gt.nombre as gtecnico,
    DATE(a.fecha_hora) AS fecha,
    CASE
    WHEN a.estado='Injustificada' THEN 'SI'
    WHEN a.estado='Justificada' THEN 'SI'
END
FROM
    inasistencias_clases a
INNER JOIN estudiantes st USING(id_estudiante)
INNER JOIN grados gd USING(id_grado)
INNER JOIN secciones sc USING(id_seccion)
INNER JOIN especialidades es USING(id_especialidad)
INNER JOIN grupos_tecnicos gt USING(id_grupo_tecnico)
INNER JOIN grupos_academicos ga USING(id_grupo_academico)
WHERE
    st.id_grado = ? AND st.id_especialidad = ? AND st.id_grupo_tecnico = ?  AND DATE(a.fecha_hora) BETWEEN ? AND ?
ORDER BY
    fecha";
    $params = array($this->anio,$this->especialidad,$this->grupoTecnico,$this->inicio,$this->fin,$this->anio,$this->especialidad,$this->grupoTecnico,$this->inicio,$this->fin);
    return Database::getRows($sql,$params);
}

public function getConsultarEsp(){
    $sql = "SELECT DISTINCT `id_especialidad`,`id_grupo_tecnico` FROM `estudiantes`  WHERE `id_grado` = ? and `id_grupo_academico` = ? and `id_seccion` = ?";
    $params = array($this->anio,$this->grupoAcademico,$this->seccion);
    $datos = Database::getRow($sql, $params);
    if($datos){
        $this->especialidad = $datos['id_especialidad'];
        $this->grupoTecnico = $datos['id_grupo_tecnico'];
        return true;
    }else{
        return null;
    }
}

public function getJustificaciones(){
    $sql = "SELECT
    st.codigo,
    aj.inicio,
    aj.fin,
    aj.motivo,
    aj.permiso
FROM
    `ausencias_justificadas` aj
INNER JOIN estudiantes st USING(id_estudiante)
WHERE
    DATE(aj.inicio)>=? AND DATE(aj.fin)<=? AND  st.id_grado = ? AND st.id_especialidad = ? AND st.id_grupo_tecnico = ?";
    $params = array($this->inicio,$this->fin,$this->anio,$this->especialidad,$this->grupoTecnico);
    return Database::getRows($sql,$params);
}
public function getJustificacionesTercerCiclo(){
    $sql = "SELECT
    st.codigo,
    aj.inicio,
    aj.fin,
    aj.motivo,
    aj.permiso
FROM
    `ausencias_justificadas` aj
INNER JOIN estudiantes st USING(id_estudiante)
WHERE
    DATE(aj.inicio)>=? AND DATE(aj.fin)<=? AND  st.id_grado = ?  AND st.id_seccion = ?";
    $params = array($this->inicio,$this->fin,$this->anio,$this->seccion);
    return Database::getRows($sql,$params);
}
}

?>
