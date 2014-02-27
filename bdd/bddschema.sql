SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `pdbdd` ;
CREATE SCHEMA IF NOT EXISTS `pdbdd` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `pdbdd` ;

-- -----------------------------------------------------
-- Table `pdbdd`.`usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`usuarios` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`usuarios` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(100) NULL,
  `pass` VARCHAR(200) NOT NULL,
  `ultimo_acceso` DATETIME NULL,
  `nombre` TEXT NULL,
  `email` VARCHAR(200) NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`permisos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`permisos` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`permisos` (
  `id_permiso` INT NOT NULL AUTO_INCREMENT,
  `nombre` TEXT NULL,
  PRIMARY KEY (`id_permiso`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`usuarios_has_permisos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`usuarios_has_permisos` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`usuarios_has_permisos` (
  `usuarios_id_usuario` INT NOT NULL,
  `permisos_id_permiso` INT NOT NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `permisos_id_permiso`),
  INDEX `fk_usuarios_has_permisos_permisos1_idx` (`permisos_id_permiso` ASC),
  INDEX `fk_usuarios_has_permisos_usuarios1_idx` (`usuarios_id_usuario` ASC),
  CONSTRAINT `fk_usuarios_has_permisos_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `pdbdd`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_permisos_permisos1`
    FOREIGN KEY (`permisos_id_permiso`)
    REFERENCES `pdbdd`.`permisos` (`id_permiso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`documentos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`documentos` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`documentos` (
  `id_documento` INT NOT NULL AUTO_INCREMENT,
  `ruta` TEXT NULL,
  `nombre_documento` TEXT NULL,
  `tipo_contenido` TEXT NULL,
  PRIMARY KEY (`id_documento`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`configuraciones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`configuraciones` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`configuraciones` (
  `id_configuracion` INT NOT NULL AUTO_INCREMENT,
  `clave` VARCHAR(200) NOT NULL,
  `valor` TEXT NULL,
  PRIMARY KEY (`id_configuracion`),
  UNIQUE INDEX `clave_UNIQUE` (`clave` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`estados_objetivo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`estados_objetivo` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`estados_objetivo` (
  `id_estado_objetivo` INT NOT NULL,
  `nombre` TEXT NOT NULL,
  `proceso` TEXT NULL,
  PRIMARY KEY (`id_estado_objetivo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`objetivos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`objetivos` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`objetivos` (
  `id_objetivo` INT NOT NULL AUTO_INCREMENT,
  `descripcion` TEXT NULL,
  `estado_objetivo` INT NOT NULL,
  PRIMARY KEY (`id_objetivo`),
  INDEX `fk_objetivos_estados_objetivo1_idx` (`estado_objetivo` ASC),
  CONSTRAINT `fk_objetivos_estados_objetivo1`
    FOREIGN KEY (`estado_objetivo`)
    REFERENCES `pdbdd`.`estados_objetivo` (`id_estado_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`grupos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`grupos` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`grupos` (
  `id_grupo` INT NOT NULL AUTO_INCREMENT,
  `nombre` TEXT NULL,
  PRIMARY KEY (`id_grupo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`votos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`votos` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`votos` (
  `usuarios_id_usuario` INT NOT NULL,
  `objetivos_id_objetivo` INT NOT NULL,
  `voto` INT NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `objetivos_id_objetivo`),
  INDEX `fk_usuarios_has_objetivos_objetivos1_idx` (`objetivos_id_objetivo` ASC),
  INDEX `fk_usuarios_has_objetivos_usuarios1_idx` (`usuarios_id_usuario` ASC),
  CONSTRAINT `fk_usuarios_has_objetivos_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `pdbdd`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_objetivos_objetivos1`
    FOREIGN KEY (`objetivos_id_objetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`responsables`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`responsables` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`responsables` (
  `usuarios_id_usuario` INT NOT NULL,
  `objetivos_id_objetivo` INT NOT NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `objetivos_id_objetivo`),
  INDEX `fk_usuarios_has_objetivos_objetivos2_idx` (`objetivos_id_objetivo` ASC),
  INDEX `fk_usuarios_has_objetivos_usuarios2_idx` (`usuarios_id_usuario` ASC),
  CONSTRAINT `fk_usuarios_has_objetivos_usuarios2`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `pdbdd`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_objetivos_objetivos2`
    FOREIGN KEY (`objetivos_id_objetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`colaboradores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`colaboradores` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`colaboradores` (
  `usuarios_id_usuario` INT NOT NULL,
  `objetivos_id_objetivo` INT NOT NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `objetivos_id_objetivo`),
  INDEX `fk_usuarios_has_objetivos_objetivos3_idx` (`objetivos_id_objetivo` ASC),
  INDEX `fk_usuarios_has_objetivos_usuarios3_idx` (`usuarios_id_usuario` ASC),
  CONSTRAINT `fk_usuarios_has_objetivos_usuarios3`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `pdbdd`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_objetivos_objetivos3`
    FOREIGN KEY (`objetivos_id_objetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`estimaciones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`estimaciones` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`estimaciones` (
  `usuarios_id_usuario` INT NOT NULL,
  `objetivos_id_objetivo` INT NOT NULL,
  `estimacion` DECIMAL(10,0) NOT NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `objetivos_id_objetivo`),
  INDEX `fk_usuarios_has_objetivos_objetivos4_idx` (`objetivos_id_objetivo` ASC),
  INDEX `fk_usuarios_has_objetivos_usuarios4_idx` (`usuarios_id_usuario` ASC),
  CONSTRAINT `fk_usuarios_has_objetivos_usuarios4`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `pdbdd`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_objetivos_objetivos4`
    FOREIGN KEY (`objetivos_id_objetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`aptitudes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`aptitudes` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`aptitudes` (
  `id_aptitud` INT NOT NULL AUTO_INCREMENT,
  `nombre` TEXT NULL,
  PRIMARY KEY (`id_aptitud`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`aprobadores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`aprobadores` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`aprobadores` (
  `usuarios_id_usuario` INT NOT NULL,
  `objetivos_id_objetivo` INT NOT NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `objetivos_id_objetivo`),
  INDEX `fk_usuarios_has_objetivos_objetivos5_idx` (`objetivos_id_objetivo` ASC),
  INDEX `fk_usuarios_has_objetivos_usuarios5_idx` (`usuarios_id_usuario` ASC),
  CONSTRAINT `fk_usuarios_has_objetivos_usuarios5`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `pdbdd`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_objetivos_objetivos5`
    FOREIGN KEY (`objetivos_id_objetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`estimadores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`estimadores` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`estimadores` (
  `usuarios_id_usuario` INT NOT NULL,
  `objetivos_id_objetivo` INT NOT NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `objetivos_id_objetivo`),
  INDEX `fk_usuarios_has_objetivos_objetivos6_idx` (`objetivos_id_objetivo` ASC),
  INDEX `fk_usuarios_has_objetivos_usuarios6_idx` (`usuarios_id_usuario` ASC),
  CONSTRAINT `fk_usuarios_has_objetivos_usuarios6`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `pdbdd`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_objetivos_objetivos6`
    FOREIGN KEY (`objetivos_id_objetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`evaluadores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`evaluadores` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`evaluadores` (
  `usuarios_id_usuario` INT NOT NULL,
  `objetivos_id_objetivo` INT NOT NULL,
  `evaluacion` DECIMAL(10,0) NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `objetivos_id_objetivo`),
  INDEX `fk_usuarios_has_objetivos_objetivos7_idx` (`objetivos_id_objetivo` ASC),
  INDEX `fk_usuarios_has_objetivos_usuarios7_idx` (`usuarios_id_usuario` ASC),
  CONSTRAINT `fk_usuarios_has_objetivos_usuarios7`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `pdbdd`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_objetivos_objetivos7`
    FOREIGN KEY (`objetivos_id_objetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`objetivos_requieren_aptitudes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`objetivos_requieren_aptitudes` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`objetivos_requieren_aptitudes` (
  `objetivos_id_objetivo` INT NOT NULL,
  `aptitudes_id_aptitud` INT NOT NULL,
  `nivel` DECIMAL(10,0) NULL,
  PRIMARY KEY (`objetivos_id_objetivo`, `aptitudes_id_aptitud`),
  INDEX `fk_objetivos_has_aptitudes_aptitudes1_idx` (`aptitudes_id_aptitud` ASC),
  INDEX `fk_objetivos_has_aptitudes_objetivos1_idx` (`objetivos_id_objetivo` ASC),
  CONSTRAINT `fk_objetivos_has_aptitudes_objetivos1`
    FOREIGN KEY (`objetivos_id_objetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_objetivos_has_aptitudes_aptitudes1`
    FOREIGN KEY (`aptitudes_id_aptitud`)
    REFERENCES `pdbdd`.`aptitudes` (`id_aptitud`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`usuarios_tienen_aptitudes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`usuarios_tienen_aptitudes` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`usuarios_tienen_aptitudes` (
  `usuarios_id_usuario` INT NOT NULL,
  `aptitudes_id_aptitud` INT NOT NULL,
  `nivel` DECIMAL(10,0) NULL,
  PRIMARY KEY (`usuarios_id_usuario`, `aptitudes_id_aptitud`),
  INDEX `fk_usuarios_has_aptitudes_aptitudes1_idx` (`aptitudes_id_aptitud` ASC),
  INDEX `fk_usuarios_has_aptitudes_usuarios1_idx` (`usuarios_id_usuario` ASC),
  CONSTRAINT `fk_usuarios_has_aptitudes_usuarios1`
    FOREIGN KEY (`usuarios_id_usuario`)
    REFERENCES `pdbdd`.`usuarios` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarios_has_aptitudes_aptitudes1`
    FOREIGN KEY (`aptitudes_id_aptitud`)
    REFERENCES `pdbdd`.`aptitudes` (`id_aptitud`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`subobjetivos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`subobjetivos` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`subobjetivos` (
  `objetivo` INT NOT NULL,
  `subobjetivo` INT NOT NULL,
  PRIMARY KEY (`objetivo`, `subobjetivo`),
  INDEX `fk_objetivos_has_objetivos_objetivos2_idx` (`subobjetivo` ASC),
  INDEX `fk_objetivos_has_objetivos_objetivos1_idx` (`objetivo` ASC),
  CONSTRAINT `fk_objetivos_has_objetivos_objetivos1`
    FOREIGN KEY (`objetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_objetivos_has_objetivos_objetivos2`
    FOREIGN KEY (`subobjetivo`)
    REFERENCES `pdbdd`.`objetivos` (`id_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`estado_siguiente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pdbdd`.`estado_siguiente` ;

CREATE TABLE IF NOT EXISTS `pdbdd`.`estado_siguiente` (
  `estado_origen` INT NOT NULL,
  `estado_destino` INT NOT NULL,
  PRIMARY KEY (`estado_origen`, `estado_destino`),
  INDEX `fk_estados_objetivo_has_estados_objetivo_estados_objetivo2_idx` (`estado_destino` ASC),
  INDEX `fk_estados_objetivo_has_estados_objetivo_estados_objetivo1_idx` (`estado_origen` ASC),
  CONSTRAINT `fk_estados_objetivo_has_estados_objetivo_estados_objetivo1`
    FOREIGN KEY (`estado_origen`)
    REFERENCES `pdbdd`.`estados_objetivo` (`id_estado_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_estados_objetivo_has_estados_objetivo_estados_objetivo2`
    FOREIGN KEY (`estado_destino`)
    REFERENCES `pdbdd`.`estados_objetivo` (`id_estado_objetivo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `pdbdd`.`usuarios`
-- -----------------------------------------------------
START TRANSACTION;
USE `pdbdd`;
INSERT INTO `pdbdd`.`usuarios` (`id_usuario`, `login`, `pass`, `ultimo_acceso`, `nombre`, `email`) VALUES (1, 'test', '8cb2237d0679ca88db6464eac60da96345513964', NULL, 'TestMan', NULL);
INSERT INTO `pdbdd`.`usuarios` (`id_usuario`, `login`, `pass`, `ultimo_acceso`, `nombre`, `email`) VALUES (2, 'kalati', '8cb2237d0679ca88db6464eac60da96345513964', NULL, 'Kalati', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `pdbdd`.`permisos`
-- -----------------------------------------------------
START TRANSACTION;
USE `pdbdd`;
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (1, 'ver_aseguradoras');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (2, 'ver_cobros');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (3, 'ver_configuraciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (4, 'ver_documentos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (5, 'ver_documentos_expediente');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (6, 'ver_entidades');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (7, 'ver_estados_reparaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (8, 'ver_expedientes');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (9, 'ver_facturas');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (10, 'ver_grupos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (11, 'ver_grupos_facturacion');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (12, 'ver_info_reparaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (13, 'ver_ordenes_de_trabajo');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (14, 'ver_peritos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (15, 'ver_perjuicios');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (16, 'ver_permisos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (17, 'ver_poblaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (18, 'ver_presupuestos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (19, 'ver_proveedores');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (20, 'ver_reparaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (21, 'ver_servicios_facturas');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (22, 'ver_servicios_presupuesto');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (23, 'ver_tipos_siniestro');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (24, 'ver_usuarios');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (25, 'add_aseguradoras');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (26, 'add_cobros');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (27, 'add_configuraciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (28, 'add_documentos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (29, 'add_documentos_expediente');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (30, 'add_entidades');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (31, 'add_estados_reparaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (32, 'add_expedientes');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (33, 'add_facturas');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (34, 'add_grupos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (35, 'add_grupos_facturacion');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (36, 'add_info_reparaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (37, 'add_ordenes_de_trabajo');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (38, 'add_peritos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (39, 'add_perjuicios');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (40, 'add_permisos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (41, 'add_poblaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (42, 'add_presupuestos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (43, 'add_proveedores');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (44, 'add_reparaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (45, 'add_servicios_facturas');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (46, 'add_servicios_presupuesto');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (47, 'add_tipos_siniestro');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (48, 'add_usuarios');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (49, 'edit_aseguradoras');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (50, 'edit_cobros');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (51, 'edit_configuraciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (52, 'edit_documentos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (53, 'edit_documentos_expediente');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (54, 'edit_entidades');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (55, 'edit_estados_reparaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (56, 'edit_expedientes');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (57, 'edit_facturas');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (58, 'edit_grupos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (59, 'edit_grupos_facturacion');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (60, 'edit_info_reparaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (61, 'edit_ordenes_de_trabajo');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (62, 'edit_peritos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (63, 'edit_perjuicios');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (64, 'edit_permisos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (65, 'edit_poblaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (66, 'edit_presupuestos');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (67, 'edit_proveedores');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (68, 'edit_reparaciones');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (69, 'edit_servicios_facturas');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (70, 'edit_servicios_presupuesto');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (71, 'edit_tipos_siniestro');
INSERT INTO `pdbdd`.`permisos` (`id_permiso`, `nombre`) VALUES (72, 'edit_usuarios');

COMMIT;


-- -----------------------------------------------------
-- Data for table `pdbdd`.`configuraciones`
-- -----------------------------------------------------
START TRANSACTION;
USE `pdbdd`;
INSERT INTO `pdbdd`.`configuraciones` (`id_configuracion`, `clave`, `valor`) VALUES (1, 'ruta_principal', '../media');
INSERT INTO `pdbdd`.`configuraciones` (`id_configuracion`, `clave`, `valor`) VALUES (2, 'iva_por_defecto', '21');
INSERT INTO `pdbdd`.`configuraciones` (`id_configuracion`, `clave`, `valor`) VALUES (3, 'vida_maxima_expediente_dias', '35');
INSERT INTO `pdbdd`.`configuraciones` (`id_configuracion`, `clave`, `valor`) VALUES (4, 'vida_maxima_factura_dias', '35');
INSERT INTO `pdbdd`.`configuraciones` (`id_configuracion`, `clave`, `valor`) VALUES (5, 'vida_maxima_albaran_dias', '35');

COMMIT;


-- -----------------------------------------------------
-- Data for table `pdbdd`.`estados_objetivo`
-- -----------------------------------------------------
START TRANSACTION;
USE `pdbdd`;
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (0, 'borrador', 'composición');
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (1, 'propuesto', 'aprobación');
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (2, 'rechazado', 'ninguno');
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (3, 'aprobado', 'estimación');
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (4, 'estimado', 'división');
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (5, 'estimado', 'asignación');
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (6, 'asignado', 'ejecución');
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (7, 'finalizado', 'evaluación');
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (8, 'completado', 'ninguno');
INSERT INTO `pdbdd`.`estados_objetivo` (`id_estado_objetivo`, `nombre`, `proceso`) VALUES (9, 'fallido', 'ninguno');

COMMIT;


-- -----------------------------------------------------
-- Data for table `pdbdd`.`subobjetivos`
-- -----------------------------------------------------
START TRANSACTION;
USE `pdbdd`;
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (0, 1);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (1, 2);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (1, 3);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (2, 0);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (3, 4);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (3, 5);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (4, 5);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (5, 6);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (6, 7);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (7, 8);
INSERT INTO `pdbdd`.`subobjetivos` (`objetivo`, `subobjetivo`) VALUES (7, 9);

COMMIT;

