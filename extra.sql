create table validar_justificacion(
	id int primary key auto_increment,
	id_estudiante int not null,
	id_horario int,
	fecha date not null,
	tipo enum('Permiso','Clase',"Justifiación Total",'Justificación ITR'),
	foreign key(id_estudiante) references estudiantes(id_estudiante),
	foreign key(id_horario) references horarios(id_horario)
);


alter table inasistencias_clases
add tipo varchar(100) default 'normal';

alter table tipos_codigos
add browseable char(2) default 'NO';


update tipos_codigos set browseable = "SI" where id_tipo_codigo = 1;
update tipos_codigos set browseable = "SI" where id_tipo_codigo = 2;

create table maestros_lista_inasistencias(
	id_lista int primary key auto_increment,
	id_horario int not null,
	fecha date not null,
	foreign key(id_horario) references horarios (id_horario)	
);

create table inasistencias_totales(
 id_inasistencia int primary key auto_increment,
 fecha	date not null,
 id_estudiante int not null,
 estado enum("Justificada","Injustificada") default "Injustificada",
 foreign key(id_estudiante) references estudiantes(id_estudiante)
)

create view vw_get_codigos_today as
select count(*) from asistencias where date(fecha_hora) = CURDATE() group by id_estudiante


alter table horarios
add tipo enum ("Seccion","Grupo") default 'Seccion';

create table ausencias_justificadas( 
	id_ausencia int primary key auto_increment,
	id_estudiante int not null,
	inicio date not null,
	fin date not null,
	motivo varchar(500) not null,
	foreign key(id_estudiante) references estudiantes(id_estudiante)
);

create table impuntualidad_procesados(
	id_procesado int primary key auto_increment,
	id_estudiante int not null,
	fecha_procesado date ,
	foreign key(id_estudiante) references estudiantes(id_estudiante)
);
create view vwCantidadLLegadasTardeProcesados as 
select count(*) as cantidad,e.codigo, e.apellidos,e.nombres,g.nombre as grado ,es.nombre as especialidad, gt.nombre as gt,ga.nombre as ga,sec.nombre as seccion,ip.fecha_procesado as fecha from impuntualidad i, impuntualidad_procesados ip,estudiantes e,grupos_academicos ga,grupos_tecnicos gt,secciones s, grados g, especialidades es, secciones sec where ip.id_estudiante = i.id_estudiante and i.id_estudiante = e.id_estudiante and date(i.fecha_hora) >= ip.fecha_procesado and i.tipo="Institución" and e.id_seccion=s.id_seccion and e.id_grupo_academico = ga.id_grupo_academico and e.id_grupo_tecnico = gt.id_grupo_tecnico and e.id_grado=g.id_grado and e.id_especialidad=es.id_especialidad  and e.id_seccion = sec.id_seccion and i.estado="Injustificada"  group by e.codigo order by cantidad desc



create view vwCantidadLLegadasTardes as
select count(*) as cantidad,e.codigo, e.apellidos,e.nombres,g.nombre as grado,es.nombre as especialidad, gt.nombre as gt,ga.nombre as ga,sec.nombre as seccion from impuntualidad i,estudiantes e,grupos_academicos ga,grupos_tecnicos gt,secciones s, grados g, especialidades es , secciones sec where i.id_estudiante = e.id_estudiante  and i.tipo="Institución" and e.id_seccion=s.id_seccion and e.id_grupo_academico = ga.id_grupo_academico and e.id_grupo_tecnico = gt.id_grupo_tecnico and e.id_grado=g.id_grado and e.id_seccion = sec.id_seccion and e.id_especialidad=es.id_especialidad and i.estado="Injustificada" group by e.codigo order by cantidad desc