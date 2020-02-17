-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `Asocijacije` CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `Asocijacije`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- CREATE TABLE 'asocijacija' ----------------------------------
CREATE TABLE `asocijacija` ( 
	`id` BIGINT( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`kreator_id` INT( 11 ) NULL,
	`datum_kreiranja` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'igra' -----------------------------------------
CREATE TABLE `igra` ( 
	`id` BIGINT( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`kategorija_id` BIGINT( 20 ) UNSIGNED NOT NULL,
	`kreator_id` INT( 11 ) NULL,
	`naziv` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`opis` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`aktivna` TINYINT( 1 ) NOT NULL DEFAULT 0,
	`broj_igranja` INT( 11 ) NOT NULL DEFAULT 0,
	`sablon_igre_id` BIGINT( 20 ) UNSIGNED NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'igra_asocijacija' -----------------------------
CREATE TABLE `igra_asocijacija` ( 
	`igra_id` BIGINT( 20 ) UNSIGNED NOT NULL,
	`asocijacija_id` BIGINT( 20 ) UNSIGNED NOT NULL,
	PRIMARY KEY ( `igra_id`, `asocijacija_id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB;
-- -------------------------------------------------------------


-- CREATE TABLE 'kategorija' -----------------------------------
CREATE TABLE `kategorija` ( 
	`id` BIGINT( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`naziv` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`roditelj_id` BIGINT( 20 ) UNSIGNED NULL,
	`levo` INT( 11 ) NULL,
	`desno` INT( 11 ) NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'korisnik' -------------------------------------
CREATE TABLE `korisnik` ( 
	`id` INT( 11 ) AUTO_INCREMENT NOT NULL,
	`korisnicko_ime` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`email` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`lozinka` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`reset_kod` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`auth_key` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`aktivan` TINYINT( 1 ) NULL,
	`datum_registrovanja` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`prebivaliste` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`pol` TINYINT( 1 ) NULL,
	`datum_rodjenja` DATE NULL,
	`zanimanje` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`je_admin` TINYINT( 1 ) NULL DEFAULT 0,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'migration' ------------------------------------
CREATE TABLE `migration` ( 
	`version` VARCHAR( 180 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`apply_time` INT( 11 ) NULL,
	PRIMARY KEY ( `version` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB;
-- -------------------------------------------------------------


-- CREATE TABLE 'neprimerene_reci' -----------------------------
CREATE TABLE `neprimerene_reci` ( 
	`id` BIGINT( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`rec` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ),
	CONSTRAINT `rec` UNIQUE( `rec` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'pojam' ----------------------------------------
CREATE TABLE `pojam` ( 
	`id` BIGINT( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`kreator_id` INT( 11 ) NULL,
	`sadrzaj` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ),
	CONSTRAINT `sadrzaj` UNIQUE( `sadrzaj` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'pojam_polje_asocijacija' ----------------------
CREATE TABLE `pojam_polje_asocijacija` ( 
	`id_asocijacije` BIGINT( 20 ) UNSIGNED NOT NULL,
	`id_polja` BIGINT( 20 ) UNSIGNED NOT NULL,
	`id_pojma` BIGINT( 20 ) UNSIGNED NOT NULL,
	PRIMARY KEY ( `id_asocijacije`, `id_polja`),
	CONSTRAINT un_id_asocijacije UNIQUE(id_asocijacije, id_pojma))
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB;
-- -------------------------------------------------------------


-- CREATE TABLE 'pokusaji_logovanja' ---------------------------
CREATE TABLE `pokusaji_logovanja` ( 
	`id` BIGINT( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`korisnik_id` INT( 11 ) NOT NULL,
	`broj_pokusaja` INT( 11 ) NULL,
	`vreme_zadnjeg` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ),
	CONSTRAINT `korisnik_id_2` UNIQUE( `korisnik_id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'polje' ----------------------------------------
CREATE TABLE `polje` ( 
	`id` BIGINT( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`sablon_igre_id` BIGINT( 20 ) UNSIGNED NULL,
	`naziv` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'resena_asocijacija' ---------------------------
CREATE TABLE `resena_asocijacija` ( 
	`asocijacija_id` BIGINT( 20 ) UNSIGNED NOT NULL,
	`korisnik_id` INT( 11 ) NOT NULL,
	`otvorena_polja` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `asocijacija_id`, `korisnik_id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB;
-- -------------------------------------------------------------


-- CREATE TABLE 'resena_igra' ----------------------------------
CREATE TABLE `resena_igra` ( 
	`igra_id` BIGINT( 20 ) UNSIGNED NOT NULL,
	`korisnik_id` INT( 11 ) NOT NULL,
	`resene_asocijacije` INT( 11 ) NULL,
	PRIMARY KEY ( `igra_id`, `korisnik_id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB;
-- -------------------------------------------------------------


-- CREATE TABLE 'sablon_igre' ----------------------------------
CREATE TABLE `sablon_igre` ( 
	`id` BIGINT( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`resenje` BIGINT( 20 ) UNSIGNED NULL,
	`naziv` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ),
	CONSTRAINT `naziv` UNIQUE( `naziv` ),
	CONSTRAINT `resenje` UNIQUE( `resenje` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = INNODB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE INDEX 'idx_kreator_asocijacija' ----------------------
CREATE INDEX `idx_kreator_asocijacija` USING BTREE ON `asocijacija`( `kreator_id` );
-- -------------------------------------------------------------



-- CREATE INDEX 'fk_sablon_igre_igra' --------------------------
CREATE INDEX `fk_sablon_igre_igra` USING BTREE ON `igra`( `sablon_igre_id` );
-- -------------------------------------------------------------


-- CREATE INDEX 'idx_broj_igranja_igra' ------------------------
CREATE INDEX `idx_broj_igranja_igra` USING BTREE ON `igra`( `broj_igranja`, `aktivna` );
-- -------------------------------------------------------------


-- CREATE INDEX 'idx_kategorija_igra' --------------------------
CREATE INDEX `idx_kategorija_igra` USING BTREE ON `igra`( `kategorija_id`, `aktivna` );
-- -------------------------------------------------------------


-- CREATE INDEX 'idx_kreator_igra' -----------------------------
CREATE INDEX `idx_kreator_igra` USING BTREE ON `igra`( `kreator_id`, `aktivna` );
-- -------------------------------------------------------------


-- CREATE INDEX 'idx_asocijacija_igra_asocijacija' -------------
CREATE INDEX `idx_asocijacija_igra_asocijacija` USING BTREE ON `igra_asocijacija`( `asocijacija_id` );
-- -------------------------------------------------------------


-- CREATE INDEX 'idx_roditelj_kategorija' ----------------------
CREATE INDEX `idx_roditelj_kategorija` USING BTREE ON `kategorija`( `roditelj_id` );
-- -------------------------------------------------------------


-- CREATE INDEX 'idx_kreator_pojam' ----------------------------
CREATE INDEX `idx_kreator_pojam` USING BTREE ON `pojam`( `kreator_id` );
-- -------------------------------------------------------------


-- CREATE INDEX 'fk_veza_pojam' --------------------------------
CREATE INDEX `fk_veza_pojam` USING BTREE ON `pojam_polje_asocijacija`( `id_pojma` );
-- -------------------------------------------------------------


-- CREATE INDEX 'fk_veza_polje' --------------------------------
CREATE INDEX `fk_veza_polje` USING BTREE ON `pojam_polje_asocijacija`( `id_polja` );
-- -------------------------------------------------------------


-- CREATE INDEX 'korisnik_id' ----------------------------------
CREATE INDEX `korisnik_id` USING BTREE ON `pokusaji_logovanja`( `korisnik_id` );
-- -------------------------------------------------------------


-- CREATE INDEX 'vreme_zadnjeg' --------------------------------
CREATE INDEX `vreme_zadnjeg` USING BTREE ON `pokusaji_logovanja`( `vreme_zadnjeg` );
-- -------------------------------------------------------------


-- CREATE INDEX 'fk_polje_sablon_igre' -------------------------
CREATE INDEX `fk_polje_sablon_igre` USING BTREE ON `polje`( `sablon_igre_id` );
-- -------------------------------------------------------------


-- CREATE INDEX 'fk_korisnik_resena_asocijacija' ---------------
CREATE INDEX `fk_korisnik_resena_asocijacija` USING BTREE ON `resena_asocijacija`( `korisnik_id` );
-- -------------------------------------------------------------


-- CREATE INDEX 'idx_korisnik_resena_asocijacija' --------------
CREATE INDEX `idx_korisnik_resena_asocijacija` USING BTREE ON `resena_asocijacija`( `asocijacija_id` );
-- -------------------------------------------------------------


-- CREATE INDEX 'idx_korisnik_resena_igra' ---------------------
CREATE INDEX `idx_korisnik_resena_igra` USING BTREE ON `resena_igra`( `korisnik_id` );
-- -------------------------------------------------------------


-- CREATE LINK 'fk_asocijacija_igra_asocijacija' ---------------
ALTER TABLE `igra_asocijacija`
	ADD CONSTRAINT `fk_asocijacija_igra_asocijacija` FOREIGN KEY ( `asocijacija_id` )
	REFERENCES `asocijacija`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_asocijacija_resena_asocijacija' -------------
ALTER TABLE `resena_asocijacija`
	ADD CONSTRAINT `fk_asocijacija_resena_asocijacija` FOREIGN KEY ( `asocijacija_id` )
	REFERENCES `asocijacija`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_igra_igra_asocijacija' ----------------------
ALTER TABLE `igra_asocijacija`
	ADD CONSTRAINT `fk_igra_igra_asocijacija` FOREIGN KEY ( `igra_id` )
	REFERENCES `igra`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_igra_resena_igra' ---------------------------
ALTER TABLE `resena_igra`
	ADD CONSTRAINT `fk_igra_resena_igra` FOREIGN KEY ( `igra_id` )
	REFERENCES `igra`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_kategorija_igra' ----------------------------
ALTER TABLE `igra`
	ADD CONSTRAINT `fk_kategorija_igra` FOREIGN KEY ( `kategorija_id` )
	REFERENCES `kategorija`( `id` )
	ON DELETE RESTRICT
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_korisnik_resena_asocijacija' ----------------
ALTER TABLE `resena_asocijacija`
	ADD CONSTRAINT `fk_korisnik_resena_asocijacija` FOREIGN KEY ( `korisnik_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_korisnik_resena_igra' -----------------------
ALTER TABLE `resena_igra`
	ADD CONSTRAINT `fk_korisnik_resena_igra` FOREIGN KEY ( `korisnik_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_kreator_asocijacija' ------------------------
ALTER TABLE `asocijacija`
	ADD CONSTRAINT `fk_kreator_asocijacija` FOREIGN KEY ( `kreator_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE SET NULL
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_kreator_igra' -------------------------------
ALTER TABLE `igra`
	ADD CONSTRAINT `fk_kreator_igra` FOREIGN KEY ( `kreator_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE SET NULL
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_kreator_pojam' ------------------------------
ALTER TABLE `pojam`
	ADD CONSTRAINT `fk_kreator_pojam` FOREIGN KEY ( `kreator_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE SET NULL
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_polje_sablon_igre' --------------------------
ALTER TABLE `polje`
	ADD CONSTRAINT `fk_polje_sablon_igre` FOREIGN KEY ( `sablon_igre_id` )
	REFERENCES `sablon_igre`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_polje_sablon_igre_resenje' ------------------
ALTER TABLE `sablon_igre`
	ADD CONSTRAINT `fk_polje_sablon_igre_resenje` FOREIGN KEY ( `resenje` )
	REFERENCES `polje`( `id` )
	ON DELETE SET NULL
	ON UPDATE CASCADE;
-- -------------------------------------------------------------



-- CREATE LINK 'fk_roditelj_kategorija' ------------------------
ALTER TABLE `kategorija`
	ADD CONSTRAINT `fk_roditelj_kategorija` FOREIGN KEY ( `roditelj_id` )
	REFERENCES `kategorija`( `id` )
	ON DELETE RESTRICT
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_sablon_igre_igra' ---------------------------
ALTER TABLE `igra`
	ADD CONSTRAINT `fk_sablon_igre_igra` FOREIGN KEY ( `sablon_igre_id` )
	REFERENCES `sablon_igre`( `id` )
	ON DELETE RESTRICT
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_veza_asocijacija' ---------------------------
ALTER TABLE `pojam_polje_asocijacija`
	ADD CONSTRAINT `fk_veza_asocijacija` FOREIGN KEY ( `id_asocijacije` )
	REFERENCES `asocijacija`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_veza_pojam' ---------------------------------
ALTER TABLE `pojam_polje_asocijacija`
	ADD CONSTRAINT `fk_veza_pojam` FOREIGN KEY ( `id_pojma` )
	REFERENCES `pojam`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_veza_polje' ---------------------------------
ALTER TABLE `pojam_polje_asocijacija`
	ADD CONSTRAINT `fk_veza_polje` FOREIGN KEY ( `id_polja` )
	REFERENCES `polje`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------


-- CREATE LINK 'f_korisnik_pokusaji_logovanja' -----------------
ALTER TABLE `pokusaji_logovanja`
	ADD CONSTRAINT `f_korisnik_pokusaji_logovanja` FOREIGN KEY ( `korisnik_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE CASCADE
	ON UPDATE CASCADE;
-- -------------------------------------------------------------

INSERT INTO korisnik(id, korisnicko_ime, email, lozinka, aktivan, je_admin) 
VALUES(1, 'admin', 'admin@admin.com', '$2y$13$STP2UzYtmu44wQLnv6sy7OJZdBq8JR8YhIY20cPvx/JHsY8wPJM1i', 1, 1);
INSERT INTO pokusaji_logovanja(korisnik_id, broj_pokusaja) VALUES(1,0);

INSERT INTO kategorija(naziv)
VALUES('Tehnologija'),('Geografija'),('Biologija'), ('Knjizenvost'),
('Racunarstvo'), ('Politika'), ('Fizika'), 
('Sport'),  ('Psihologija') ,('Izumi');

UPDATE kategorija
SET roditelj_id = 1
WHERE naziv = 'Racunarstvo' OR naziv = 'Izumi';

INSERT INTO sablon_igre(naziv) VALUES('4x4');

INSERT INTO polje(naziv, sablon_igre_id) 
VALUES('Resenje', 1),('A', 1),
('B', 1),('A1', 1),('A2', 1),
('A3', 1),('A4', 1),('B1', 1),
('B2', 1),('B3', 1),('B4', 1),
('C', 1),('C1', 1),('C2', 1),
('C3', 1),('C4', 1),('D', 1),
('D1', 1),('D2', 1),('D3', 1),('D4', 1);

UPDATE sablon_igre
SET resenje = 1
WHERE id=1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------