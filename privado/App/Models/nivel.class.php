<?php
class ComboReporte{
    public function getNiveles(){
        $sql = "SELECT `id_nivel`, `nombre`FROM `niveles`";
        $params = array(null);
        return Database::getRows($sql,$params);
    }
//CAMBIO EN CONSULTA
    public function getGrado(){
        $sql = "SELECT `id_grado`, `nombre` FROM `grados` ORDER BY id_grado ASC";
        $params = array(null);
        return Database::getRows($sql,$params);
    }
    public function getGradoEsp(){
        $sql = "SELECT `id_grado`, `nombre` FROM `grados` WHERE id_nivel = 2 ORDER BY id_grado ASC";
        $params = array(null);
        return Database::getRows($sql,$params);
    }
//FINAL CAMBIO EN CONSULTA

    public function getSecciones($grado){
        $sql = "SELECT E.id_seccion, S.nombre FROM estudiantes E INNER JOIN secciones S USING(id_seccion) WHERE E.id_grado = ? GROUP BY S.id_seccion";
        $params = array($grado);
        return Database::getRows($sql,$params);
    }

    public function getEspecilidades($grado){
        $sql ="SELECT E.id_especialidad, Es.nombre FROM estudiantes E INNER JOIN especialidades Es USING(id_especialidad) WHERE  E.id_grado = ? GROUP BY Es.id_especialidad";
        $params = array($grado);
        return Database::getRows($sql,$params);
    }
    
    public function getSeccionEsp($esp, $grTec){
        $sql ="SELECT E.id_seccion, S.nombre FROM estudiantes E INNER JOIN secciones S USING(id_seccion) WHERE  E.id_especialidad = ? AND E.id_grupo_tecnico = ? GROUP BY S.id_seccion";
        $params = array($esp,$grTec);
        return Database::getRows($sql,$params);
    }

    public function getGrTec($esp){
        $sql="SELECT E.id_grupo_tecnico, G.nombre FROM estudiantes E INNER JOIN grupos_tecnicos G USING(id_grupo_tecnico) WHERE  E.id_especialidad = ? GROUP BY G.id_grupo_tecnico";
        $params = array($esp);
        return Database::getRows($sql,$params);
    }
    public function getGrAc($esp){
        $sql="SELECT E.id_grupo_academico, G.nombre FROM estudiantes E INNER JOIN grupos_academicos G USING(id_grupo_academico) WHERE  E.id_seccion = ? GROUP BY G.id_grupo_academico";
        $params = array($esp);
        return Database::getRows($sql,$params);
    }

}
?>
