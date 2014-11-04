SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `pdbdd` DEFAULT CHARACTER SET utf8 ;
USE `pdbdd` ;

-- -----------------------------------------------------
-- Table `pdbdd`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`usuario` (
  `idusuario` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(300) NOT NULL,
  `apellidos` VARCHAR(500) NOT NULL,
  `email` VARCHAR(254) NOT NULL,
  `pass` VARCHAR(300) NOT NULL,
  `verificado` TINYINT(1) NULL DEFAULT 0,
  `fbid` VARCHAR(100) NULL COMMENT 'userID en facebook.',
  `fbtoken` VARCHAR(300) NULL,
  `fbexpiresin` DECIMAL(10,0) NULL,
  `primer_login` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_cambio` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idusuario`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`grupo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`grupo` (
  `idgrupo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(200) NOT NULL,
  `nmiembros` INT NULL DEFAULT 0,
  `activo` TINYINT(1) NULL DEFAULT 0,
  `descripcion` TEXT NULL,
  PRIMARY KEY (`idgrupo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`votacionsnd`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`votacionsnd` (
  `idvotacionsnd` INT NOT NULL AUTO_INCREMENT,
  `enunciado` TEXT NULL,
  `error` DECIMAL(13,10) NULL DEFAULT 0.5 COMMENT 'Es el error máximo que se puede estar cometiendo con los datos que tenemos de los representantes.',
  `fecha_creacion` DATETIME NULL COMMENT 'Fecha de creación de la votación.',
  `timein` DATETIME NULL COMMENT 'Fecha que se utilizará para marcar cuándo la votación comienza.',
  `checktime` DATETIME NULL COMMENT 'Momento en el que la votación será controlada para ampliarse o terminarse.',
  `timeout` DATETIME NULL COMMENT 'Fecha límite de la votación.',
  `fecha_finalizacion` DATETIME NULL COMMENT 'Fecha en la que se cierra la votación.',
  `ampliaciones` INT NULL DEFAULT 0 COMMENT 'Número de ampliaciones llevadas a cabo en esta votación.',
  `activa` TINYINT(1) NULL DEFAULT 0,
  `finalizada` TINYINT(1) NULL DEFAULT 0,
  `resultado` INT NULL DEFAULT NULL COMMENT 'El resultado de la votación: 0 es abstención, 1 es No, 2 es Depende, 3 es Sí.',
  `votossi` INT NULL DEFAULT 0,
  `votosno` INT NULL DEFAULT 0,
  `votosdep` INT NULL DEFAULT 0,
  `votossirep` INT NULL DEFAULT 0,
  `votosnorep` INT NULL DEFAULT 0,
  `votosdeprep` INT NULL DEFAULT 0,
  `nrepresentantes` INT NULL DEFAULT 0,
  `censo` INT NOT NULL,
  `minimosi` DECIMAL(13,10) NULL DEFAULT 0,
  `minimono` DECIMAL(13,10) NULL DEFAULT 0,
  `minimodep` DECIMAL(13,10) NULL DEFAULT 0,
  `nindividuos` INT NULL DEFAULT 0,
  PRIMARY KEY (`idvotacionsnd`),
  INDEX `fk_votacionsnd_grupo1_idx` (`censo` ASC),
  CONSTRAINT `fk_votacionsnd_grupo1`
    FOREIGN KEY (`censo`)
    REFERENCES `pdbdd`.`grupo` (`idgrupo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`votosnd`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`votosnd` (
  `usuario_idusuario` INT NOT NULL,
  `votacionsnd_idvotacionsnd` INT NOT NULL,
  `representante` TINYINT(1) NULL DEFAULT 0,
  `valor` INT NULL DEFAULT NULL,
  `enunciado_logico_idenunciado_logico` INT NULL,
  PRIMARY KEY (`usuario_idusuario`, `votacionsnd_idvotacionsnd`),
  INDEX `fk_usuario_has_votacionsinodep_votacionsinodep1_idx` (`votacionsnd_idvotacionsnd` ASC),
  INDEX `fk_usuario_has_votacionsinodep_usuario1_idx` (`usuario_idusuario` ASC),
  INDEX `fk_votosnd_enunciado_logico1_idx` (`enunciado_logico_idenunciado_logico` ASC),
  CONSTRAINT `fk_usuario_has_votacionsinodep_usuario1`
    FOREIGN KEY (`usuario_idusuario`)
    REFERENCES `pdbdd`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_votacionsinodep_votacionsinodep1`
    FOREIGN KEY (`votacionsnd_idvotacionsnd`)
    REFERENCES `pdbdd`.`votacionsnd` (`idvotacionsnd`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_votosnd_enunciado_logico1`
    FOREIGN KEY (`enunciado_logico_idenunciado_logico`)
    REFERENCES `pdbdd`.`enunciado_logico` (`idenunciado_logico`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`miembro`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`miembro` (
  `grupo_idgrupo` INT NOT NULL,
  `usuario_idusuario` INT NOT NULL,
  `puntos_participacion` INT NULL DEFAULT 0 COMMENT 'Los puntos de participación que tiene el miembro para este grupo.',
  `voluntad` INT NULL DEFAULT 2 COMMENT 'Indica si el usuario desea participar en el grupo (2), si desea dejarlo (0) o simplemente ser un seguidor(1). Si desea participar y tiene puntos suficientes podrá. Si desea participar pero no tiene puntos suficientes será un seguidor y tendrá que esperar  /* comment truncated */ /*a obtenerlos. Si no desea participar y tiene puntos suficientes podremos borrar la entrada. Si no desea participar pero no tiene puntos suficientes tendremos que esperar a que obtenga los puntos antes de borrar la entrada. Si decide ser un seguidor no importan los puntos que tenga ya que tampoco puede participar.*/',
  `ultima_actualizacion` DATETIME NULL COMMENT 'indica el último momento en el que se ha actualizado la membresía de este usuario. Puntos, baja...',
  PRIMARY KEY (`grupo_idgrupo`, `usuario_idusuario`),
  INDEX `fk_grupo_has_usuario_usuario1_idx` (`usuario_idusuario` ASC),
  INDEX `fk_grupo_has_usuario_grupo1_idx` (`grupo_idgrupo` ASC),
  CONSTRAINT `fk_grupo_has_usuario_grupo1`
    FOREIGN KEY (`grupo_idgrupo`)
    REFERENCES `pdbdd`.`grupo` (`idgrupo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_grupo_has_usuario_usuario1`
    FOREIGN KEY (`usuario_idusuario`)
    REFERENCES `pdbdd`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`subgrupo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`subgrupo` (
  `idgrupo` INT NOT NULL,
  `idsubgrupo` INT NOT NULL,
  PRIMARY KEY (`idgrupo`, `idsubgrupo`),
  INDEX `fk_grupo_has_grupo_grupo2_idx` (`idsubgrupo` ASC),
  INDEX `fk_grupo_has_grupo_grupo1_idx` (`idgrupo` ASC),
  CONSTRAINT `fk_grupo_has_grupo_grupo1`
    FOREIGN KEY (`idgrupo`)
    REFERENCES `pdbdd`.`grupo` (`idgrupo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_grupo_has_grupo_grupo2`
    FOREIGN KEY (`idsubgrupo`)
    REFERENCES `pdbdd`.`grupo` (`idgrupo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`accionsys`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`accionsys` (
  `idaccionsys` INT NOT NULL,
  `nombre` TEXT NULL COMMENT 'Nombre de la acción que realizará el sistema.',
  `descripcion` TEXT NULL COMMENT 'Descripción de la acción que realizará el sistema.',
  PRIMARY KEY (`idaccionsys`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`ejecucionsys`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`ejecucionsys` (
  `idejecucionsys` INT NOT NULL AUTO_INCREMENT,
  `nombre` TEXT NULL,
  `estado` INT NULL DEFAULT 0,
  PRIMARY KEY (`idejecucionsys`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`ejecucionsys_has_accionsys`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`ejecucionsys_has_accionsys` (
  `ejecucionsys_idejecucionsys` INT NOT NULL,
  `accionsys_idaccionsys` INT NOT NULL,
  `orden` INT NOT NULL,
  `parametros` TEXT NULL,
  PRIMARY KEY (`ejecucionsys_idejecucionsys`, `accionsys_idaccionsys`, `orden`),
  INDEX `fk_ejecucionsys_has_accionsys_accionsys1_idx` (`accionsys_idaccionsys` ASC),
  INDEX `fk_ejecucionsys_has_accionsys_ejecucionsys1_idx` (`ejecucionsys_idejecucionsys` ASC),
  CONSTRAINT `fk_ejecucionsys_has_accionsys_ejecucionsys1`
    FOREIGN KEY (`ejecucionsys_idejecucionsys`)
    REFERENCES `pdbdd`.`ejecucionsys` (`idejecucionsys`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ejecucionsys_has_accionsys_accionsys1`
    FOREIGN KEY (`accionsys_idaccionsys`)
    REFERENCES `pdbdd`.`accionsys` (`idaccionsys`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`decisionsnd`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`decisionsnd` (
  `iddecisionsnd` INT NOT NULL AUTO_INCREMENT,
  `votacionsnd_idvotacionsnd` INT NOT NULL,
  `descripcion` TEXT NULL,
  `resultado` INT NULL DEFAULT NULL,
  `grupo_idgrupo` INT NOT NULL,
  `nombre` TEXT NULL,
  `ejecucionsys_idejecucionsys` INT NULL,
  PRIMARY KEY (`iddecisionsnd`),
  INDEX `fk_decisionsnd_votacionsnd1_idx` (`votacionsnd_idvotacionsnd` ASC),
  INDEX `fk_decisionsnd_grupo1_idx` (`grupo_idgrupo` ASC),
  INDEX `fk_decisionsnd_ejecucionsys1_idx` (`ejecucionsys_idejecucionsys` ASC),
  CONSTRAINT `fk_decisionsnd_votacionsnd1`
    FOREIGN KEY (`votacionsnd_idvotacionsnd`)
    REFERENCES `pdbdd`.`votacionsnd` (`idvotacionsnd`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_decisionsnd_grupo1`
    FOREIGN KEY (`grupo_idgrupo`)
    REFERENCES `pdbdd`.`grupo` (`idgrupo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_decisionsnd_ejecucionsys1`
    FOREIGN KEY (`ejecucionsys_idejecucionsys`)
    REFERENCES `pdbdd`.`ejecucionsys` (`idejecucionsys`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pdbdd`.`chatgrupo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pdbdd`.`chatgrupo` (
  `idchatgrupo` INT NOT NULL AUTO_INCREMENT,
  `grupo_idgrupo` INT NOT NULL COMMENT 'El grupo en el que se publica',
  `usuario_idusuario` INT NOT NULL COMMENT 'El usuario que publica',
  `mensaje` TEXT NULL COMMENT 'El texto del mensaje',
  `fecha` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idchatgrupo`),
  INDEX `fk_chatgrupo_grupo1_idx` (`grupo_idgrupo` ASC),
  INDEX `fk_chatgrupo_usuario1_idx` (`usuario_idusuario` ASC),
  CONSTRAINT `fk_chatgrupo_grupo1`
    FOREIGN KEY (`grupo_idgrupo`)
    REFERENCES `pdbdd`.`grupo` (`idgrupo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_chatgrupo_usuario1`
    FOREIGN KEY (`usuario_idusuario`)
    REFERENCES `pdbdd`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `pdbdd`.`accionsys`
-- -----------------------------------------------------
START TRANSACTION;
USE `pdbdd`;
INSERT INTO `pdbdd`.`accionsys` (`idaccionsys`, `nombre`, `descripcion`) VALUES (1, 'UnirGrupo', 'Formar parte de un grupo');
INSERT INTO `pdbdd`.`accionsys` (`idaccionsys`, `nombre`, `descripcion`) VALUES (2, 'DejarGrupo', 'Dejar de formar parte de un grupo');
INSERT INTO `pdbdd`.`accionsys` (`idaccionsys`, `nombre`, `descripcion`) VALUES (3, 'EditDescripcion', 'Modificar la descripción del grupo');
INSERT INTO `pdbdd`.`accionsys` (`idaccionsys`, `nombre`, `descripcion`) VALUES (4, 'AddVariable', 'Crear nueva variable de grupo');
INSERT INTO `pdbdd`.`accionsys` (`idaccionsys`, `nombre`, `descripcion`) VALUES (5, 'EditVariable', 'Editar valor de una variable');
INSERT INTO `pdbdd`.`accionsys` (`idaccionsys`, `nombre`, `descripcion`) VALUES (6, 'AddRule', 'Crear nueva regla');
INSERT INTO `pdbdd`.`accionsys` (`idaccionsys`, `nombre`, `descripcion`) VALUES (7, 'InvalidVotacion', 'Invalidar votación');

COMMIT;

