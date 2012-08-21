SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `$dbname` ;

-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_players`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_players` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_players` (
  `playerID` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(16) NOT NULL ,
  `online` INT(11) NOT NULL DEFAULT 0 ,
  `expPerc` INT(3) UNSIGNED NOT NULL DEFAULT 0 ,
  `expTotal` SMALLINT(5) UNSIGNED NULL DEFAULT 0 ,
  `level` SMALLINT(5) UNSIGNED NULL DEFAULT 1 ,
  `foodlevel` TINYINT(2) UNSIGNED NULL DEFAULT NULL ,
  `health` TINYINT(2) UNSIGNED NULL DEFAULT NULL ,
  `first_login` INT(11) NULL ,
  `logins` INT(11) NULL ,
  PRIMARY KEY (`playerID`) );


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_players_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_players_log` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_players_log` (
  `logID` INT NOT NULL AUTO_INCREMENT ,
  `playerID` INT NOT NULL ,
  `logged_in` INT(11) NOT NULL ,
  `logged_out` INT(11) NOT NULL DEFAULT -1 ,
  `world` VARCHAR(255) NULL ,
  `x` INT NULL ,
  `y` INT NULL ,
  `z` INT NULL ,
  PRIMARY KEY (`logID`) ,
  INDEX `fk_player_log_player` (`playerID` ASC) ,
  CONSTRAINT `fk_player_log_player`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_players_distance`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_players_distance` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_players_distance` (
  `distID` INT NOT NULL AUTO_INCREMENT ,
  `playerID` INT NULL ,
  `foot` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0 ,
  `boat` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0 ,
  `minecart` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0 ,
  `pig` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`distID`) ,
  INDEX `fk_player_distances_player1` (`playerID` ASC) ,
  CONSTRAINT `fk_player_distances_player1`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_creatures`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_creatures` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_creatures` (
  `creatureType` VARCHAR(45) NOT NULL ,
  `name` VARCHAR(100) NULL ,
  PRIMARY KEY (`creatureType`) );


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_items` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_items` (
  `itemID` SMALLINT(5) UNSIGNED NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`itemID`) );


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_pvp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_pvp` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_pvp` (
  `pvpID` INT NOT NULL AUTO_INCREMENT ,
  `playerID` INT NOT NULL ,
  `victimID` INT NOT NULL ,
  `itemID` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0 ,
  `cause` VARCHAR(45) NULL ,
  `world` VARCHAR(255) NULL ,
  `x` INT NULL ,
  `y` INT NULL ,
  `z` INT NULL ,
  `time` INT(11) NULL ,
  PRIMARY KEY (`pvpID`) ,
  INDEX `fk_pvp_player1` (`playerID` ASC) ,
  INDEX `fk_pvp_item1` (`itemID` ASC) ,
  INDEX `fk_pvp_player2` (`victimID` ASC) ,
  CONSTRAINT `fk_pvp_player1`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pvp_item1`
    FOREIGN KEY (`itemID` )
    REFERENCES `$dbname`.`$prefix_items` (`itemID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pvp_player2`
    FOREIGN KEY (`victimID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_blocks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_blocks` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_blocks` (
  `blockID` TINYINT(3) UNSIGNED NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`blockID`) );


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_evp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_evp` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_evp` (
  `evpID` INT NOT NULL AUTO_INCREMENT ,
  `creatureType` VARCHAR(45) NOT NULL ,
  `playerID` INT NOT NULL ,
  `itemID` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0 ,
  `cause` VARCHAR(45) NULL ,
  `world` VARCHAR(255) NULL ,
  `x` INT NULL ,
  `y` INT NULL ,
  `z` INT NULL ,
  `time` INT(11) NULL ,
  PRIMARY KEY (`evpID`) ,
  INDEX `fk_evp_creature1` (`creatureType` ASC) ,
  INDEX `fk_evp_player1` (`playerID` ASC) ,
  INDEX `fk_evp_item1` (`itemID` ASC) ,
  CONSTRAINT `fk_evp_creature1`
    FOREIGN KEY (`creatureType` )
    REFERENCES `$dbname`.`$prefix_creatures` (`creatureType` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_evp_player1`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_evp_item1`
    FOREIGN KEY (`itemID` )
    REFERENCES `$dbname`.`$prefix_items` (`itemID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_players_death`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_players_death` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_players_death` (
  `pdeathID` INT NOT NULL AUTO_INCREMENT ,
  `playerID` INT NOT NULL ,
  `cause` VARCHAR(45) NOT NULL ,
  `world` VARCHAR(255) NULL ,
  `x` INT NULL ,
  `y` INT NULL ,
  `z` INT NULL ,
  PRIMARY KEY (`pdeathID`) ,
  INDEX `fk_player_death_player1` (`playerID` ASC) ,
  CONSTRAINT `fk_player_death_player1`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_blocks_placed`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_blocks_placed` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_blocks_placed` (
  `bplacedID` INT NOT NULL AUTO_INCREMENT ,
  `blockID` TINYINT(3) UNSIGNED NOT NULL ,
  `playerID` INT NOT NULL ,
  `world` VARCHAR(255) NULL ,
  `x` INT NULL ,
  `y` INT NULL ,
  `z` INT NULL ,
  `time` INT(11) NULL ,
  PRIMARY KEY (`bplacedID`) ,
  INDEX `fk_block_placed_block1` (`blockID` ASC) ,
  INDEX `fk_block_placed_player1` (`playerID` ASC) ,
  CONSTRAINT `fk_block_placed_block1`
    FOREIGN KEY (`blockID` )
    REFERENCES `$dbname`.`$prefix_blocks` (`blockID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_block_placed_player1`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_blocks_destroyed`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_blocks_destroyed` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_blocks_destroyed` (
  `bdestroyedID` INT NOT NULL AUTO_INCREMENT ,
  `blockID` TINYINT(3) UNSIGNED NOT NULL ,
  `playerID` INT NOT NULL ,
  `world` VARCHAR(255) NULL ,
  `x` INT NULL ,
  `y` INT NULL ,
  `z` INT NULL ,
  `time` INT(11) NULL ,
  PRIMARY KEY (`bdestroyedID`) ,
  INDEX `fk_block_placed_block1` (`blockID` ASC) ,
  INDEX `fk_block_placed_player1` (`playerID` ASC) ,
  CONSTRAINT `fk_block_placed_block10`
    FOREIGN KEY (`blockID` )
    REFERENCES `$dbname`.`$prefix_blocks` (`blockID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_block_placed_player10`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_items_drop`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_items_drop` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_items_drop` (
  `idropID` INT NOT NULL AUTO_INCREMENT ,
  `itemID` SMALLINT(5) UNSIGNED NOT NULL ,
  `playerID` INT NOT NULL ,
  `world` VARCHAR(255) NULL ,
  `x` INT NULL ,
  `y` INT NULL ,
  `z` INT NULL ,
  `time` INT(11) NULL ,
  PRIMARY KEY (`idropID`) ,
  INDEX `fk_item_drop_item1` (`itemID` ASC) ,
  INDEX `fk_item_drop_player1` (`playerID` ASC) ,
  CONSTRAINT `fk_item_drop_item1`
    FOREIGN KEY (`itemID` )
    REFERENCES `$dbname`.`$prefix_items` (`itemID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_drop_player1`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_items_pickup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_items_pickup` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_items_pickup` (
  `ipickupID` INT NOT NULL AUTO_INCREMENT ,
  `itemID` SMALLINT(5) UNSIGNED NOT NULL ,
  `playerID` INT NOT NULL ,
  `world` VARCHAR(255) NULL ,
  `x` INT NULL ,
  `y` INT NULL ,
  `z` INT NULL ,
  `time` INT(11) NULL ,
  PRIMARY KEY (`ipickupID`) ,
  INDEX `fk_item_drop_item1` (`itemID` ASC) ,
  INDEX `fk_item_drop_player1` (`playerID` ASC) ,
  CONSTRAINT `fk_item_drop_item10`
    FOREIGN KEY (`itemID` )
    REFERENCES `$dbname`.`$prefix_items` (`itemID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_drop_player10`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_settings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_settings` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_settings` (
  `key` VARCHAR(64) NOT NULL ,
  `value` TEXT NOT NULL ,
  PRIMARY KEY (`key`) );


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_items_use`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_items_use` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_items_use` (
  `iuseID` INT NOT NULL AUTO_INCREMENT ,
  `itemID` SMALLINT(5) UNSIGNED NOT NULL ,
  `playerID` INT NOT NULL ,
  `times` INT(10) NOT NULL ,
  PRIMARY KEY (`iuseID`) ,
  INDEX `fk_item_use_item1` (`itemID` ASC) ,
  INDEX `fk_item_use_player1` (`playerID` ASC) ,
  CONSTRAINT `fk_item_use_item1`
    FOREIGN KEY (`itemID` )
    REFERENCES `$dbname`.`$prefix_items` (`itemID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_use_player1`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `$dbname`.`$prefix_pve`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `$dbname`.`$prefix_pve` ;

CREATE  TABLE IF NOT EXISTS `$dbname`.`$prefix_pve` (
  `pveID` INT NOT NULL AUTO_INCREMENT ,
  `playerID` INT NOT NULL ,
  `creatureType` VARCHAR(45) NOT NULL ,
  `itemID` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0 ,
  `cause` VARCHAR(45) NULL ,
  `world` VARCHAR(255) NULL ,
  `x` INT NULL ,
  `y` INT NULL ,
  `z` INT NULL ,
  `time` INT(11) NULL ,
  PRIMARY KEY (`pveID`) ,
  INDEX `fk_player_killed_player1` (`playerID` ASC) ,
  INDEX `fk_player_killed_item1` (`itemID` ASC) ,
  INDEX `fk_$prefix_player_killed_$prefix_player1` (`creatureType` ASC) ,
  CONSTRAINT `fk_pve_player1`
    FOREIGN KEY (`playerID` )
    REFERENCES `$dbname`.`$prefix_players` (`playerID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pve_item1`
    FOREIGN KEY (`itemID` )
    REFERENCES `$dbname`.`$prefix_items` (`itemID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pve_creature1`
    FOREIGN KEY (`creatureType` )
    REFERENCES `$dbname`.`$prefix_creatures` (`creatureType` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
