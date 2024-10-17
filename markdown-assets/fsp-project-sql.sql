-- MySQL Workbench Synchronization
-- Generated: 2024-10-17 14:33
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: hdinata

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `esport` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `esport`.`member` (
  `idmember` INT(11) NOT NULL AUTO_INCREMENT,
  `fname` VARCHAR(45) NULL DEFAULT NULL,
  `lname` VARCHAR(45) NULL DEFAULT NULL,
  `username` VARCHAR(45) NULL DEFAULT NULL,
  `password` VARCHAR(100) NULL DEFAULT NULL,
  `profile` ENUM('admin', 'member') NULL DEFAULT NULL,
  PRIMARY KEY (`idmember`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `esport`.`team` (
  `idteam` INT(11) NOT NULL AUTO_INCREMENT,
  `idgame` INT(11) NOT NULL,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idteam`),
  INDEX `fk_team_game1_idx` (`idgame` ASC),
  CONSTRAINT `fk_team_game1`
    FOREIGN KEY (`idgame`)
    REFERENCES `esport`.`game` (`idgame`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `esport`.`game` (
  `idgame` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  `description` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`idgame`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `esport`.`team_members` (
  `idteam` INT(11) NOT NULL,
  `idmember` INT(11) NOT NULL,
  `description` VARCHAR(75) NULL DEFAULT NULL,
  PRIMARY KEY (`idteam`, `idmember`),
  INDEX `fk_team_has_member_member1_idx` (`idmember` ASC),
  INDEX `fk_team_has_member_team_idx` (`idteam` ASC),
  CONSTRAINT `fk_team_has_member_team`
    FOREIGN KEY (`idteam`)
    REFERENCES `esport`.`team` (`idteam`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_team_has_member_member1`
    FOREIGN KEY (`idmember`)
    REFERENCES `esport`.`member` (`idmember`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `esport`.`event` (
  `idevent` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  `date` DATE NULL DEFAULT NULL,
  `description` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`idevent`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `esport`.`event_teams` (
  `idevent` INT(11) NOT NULL,
  `idteam` INT(11) NOT NULL,
  PRIMARY KEY (`idevent`, `idteam`),
  INDEX `fk_event_has_team_team1_idx` (`idteam` ASC),
  INDEX `fk_event_has_team_event1_idx` (`idevent` ASC),
  CONSTRAINT `fk_event_has_team_event1`
    FOREIGN KEY (`idevent`)
    REFERENCES `esport`.`event` (`idevent`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_event_has_team_team1`
    FOREIGN KEY (`idteam`)
    REFERENCES `esport`.`team` (`idteam`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `esport`.`achievement` (
  `idachievement` INT(11) NOT NULL AUTO_INCREMENT,
  `idteam` INT(11) NOT NULL,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  `date` DATE NULL DEFAULT NULL,
  `description` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`idachievement`),
  INDEX `fk_achievement_team1_idx` (`idteam` ASC),
  CONSTRAINT `fk_achievement_team1`
    FOREIGN KEY (`idteam`)
    REFERENCES `esport`.`team` (`idteam`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `esport`.`join_proposal` (
  `idjoin_proposal` INT(11) NOT NULL AUTO_INCREMENT,
  `idmember` INT(11) NOT NULL,
  `idteam` INT(11) NOT NULL,
  `description` VARCHAR(100) NULL DEFAULT 'role preference: support, attacker, dll',
  `status` ENUM('waiting', 'approved', 'rejected') NULL DEFAULT NULL,
  PRIMARY KEY (`idjoin_proposal`),
  INDEX `fk_join_proposal_member1_idx` (`idmember` ASC),
  INDEX `fk_join_proposal_team1_idx` (`idteam` ASC),
  CONSTRAINT `fk_join_proposal_member1`
    FOREIGN KEY (`idmember`)
    REFERENCES `esport`.`member` (`idmember`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_join_proposal_team1`
    FOREIGN KEY (`idteam`)
    REFERENCES `esport`.`team` (`idteam`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
