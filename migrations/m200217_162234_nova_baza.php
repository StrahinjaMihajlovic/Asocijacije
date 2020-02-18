<?php

use yii\db\Migration;

/**
 * Class m200217_162234_nova_baza
 */
class m200217_162234_nova_baza extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
                    "-- Valentina Studio --
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
	`id` BigInt( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`kreator_id` Int( 11 ) NULL,
	`datum_kreiranja` DateTime NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'igra' -----------------------------------------
CREATE TABLE `igra` ( 
	`id` BigInt( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`kategorija_id` BigInt( 20 ) UNSIGNED NOT NULL,
	`kreator_id` Int( 11 ) NULL,
	`naziv` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`opis` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`aktivna` TinyInt( 1 ) NOT NULL DEFAULT 0,
	`broj_igranja` Int( 11 ) NOT NULL DEFAULT 0,
	`sablon_igre_id` BigInt( 20 ) UNSIGNED NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'igra_asocijacija' -----------------------------
CREATE TABLE `igra_asocijacija` ( 
	`igra_id` BigInt( 20 ) UNSIGNED NOT NULL,
	`asocijacija_id` BigInt( 20 ) UNSIGNED NOT NULL,
	PRIMARY KEY ( `igra_id`, `asocijacija_id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE 'kategorija' -----------------------------------
CREATE TABLE `kategorija` ( 
	`id` BigInt( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`naziv` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`roditelj_id` BigInt( 20 ) UNSIGNED NULL,
	`levo` Int( 11 ) NULL,
	`desno` Int( 11 ) NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'korisnik' -------------------------------------
CREATE TABLE `korisnik` ( 
	`id` Int( 11 ) AUTO_INCREMENT NOT NULL,
	`korisnicko_ime` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`email` VarChar( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`lozinka` VarChar( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`reset_kod` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`auth_key` Text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`aktivan` TinyInt( 1 ) NULL,
	`datum_registrovanja` DateTime NULL DEFAULT CURRENT_TIMESTAMP,
	`prebivaliste` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`pol` TinyInt( 1 ) NULL,
	`datum_rodjenja` Date NULL,
	`zanimanje` VarChar( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`je_admin` TinyInt( 1 ) NULL DEFAULT 0,
	PRIMARY KEY ( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'migration' ------------------------------------
CREATE TABLE `migration` ( 
	`version` VarChar( 180 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`apply_time` Int( 11 ) NULL,
	PRIMARY KEY ( `version` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE 'neprimerene_reci' -----------------------------
CREATE TABLE `neprimerene_reci` ( 
	`id` BigInt( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`rec` VarChar( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ),
	CONSTRAINT `rec` UNIQUE( `rec` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'pojam' ----------------------------------------
CREATE TABLE `pojam` ( 
	`id` BigInt( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`kreator_id` Int( 11 ) NULL,
	`sadrzaj` VarChar( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ),
	CONSTRAINT `sadrzaj` UNIQUE( `sadrzaj` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'pojam_polje_asocijacija' ----------------------
CREATE TABLE `pojam_polje_asocijacija` ( 
	`id_asocijacije` BigInt( 20 ) UNSIGNED NOT NULL,
	`id_polja` BigInt( 20 ) UNSIGNED NOT NULL,
	`id_pojma` BigInt( 20 ) UNSIGNED NOT NULL,
	PRIMARY KEY ( `id_asocijacije`, `id_polja`),
	constraint un_id_asocijacije unique(id_asocijacije, id_pojma))
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE 'pokusaji_logovanja' ---------------------------
CREATE TABLE `pokusaji_logovanja` ( 
	`id` BigInt( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`korisnik_id` Int( 11 ) NOT NULL,
	`broj_pokusaja` Int( 11 ) NULL,
	`vreme_zadnjeg` DateTime NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ),
	CONSTRAINT `korisnik_id_2` UNIQUE( `korisnik_id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'polje' ----------------------------------------
CREATE TABLE `polje` ( 
	`id` BigInt( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`sablon_igre_id` BigInt( 20 ) UNSIGNED NULL,
	`naziv` Text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
AUTO_INCREMENT = 1;
-- -------------------------------------------------------------


-- CREATE TABLE 'resena_asocijacija' ---------------------------
CREATE TABLE `resena_asocijacija` ( 
	`asocijacija_id` BigInt( 20 ) UNSIGNED NOT NULL,
	`korisnik_id` Int( 11 ) NOT NULL,
	`otvorena_polja` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	PRIMARY KEY ( `asocijacija_id`, `korisnik_id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE 'resena_igra' ----------------------------------
CREATE TABLE `resena_igra` ( 
	`igra_id` BigInt( 20 ) UNSIGNED NOT NULL,
	`korisnik_id` Int( 11 ) NOT NULL,
	`resene_asocijacije` Int( 11 ) NULL,
	PRIMARY KEY ( `igra_id`, `korisnik_id` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE 'sablon_igre' ----------------------------------
CREATE TABLE `sablon_igre` ( 
	`id` BigInt( 20 ) UNSIGNED AUTO_INCREMENT NOT NULL,
	`resenje` BigInt( 20 ) UNSIGNED NULL,
	`naziv` VarChar( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	PRIMARY KEY ( `id` ),
	CONSTRAINT `id` UNIQUE( `id` ),
	CONSTRAINT `naziv` UNIQUE( `naziv` ),
	CONSTRAINT `resenje` UNIQUE( `resenje` ) )
CHARACTER SET = utf8
COLLATE = utf8_general_ci
ENGINE = InnoDB
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
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_asocijacija_resena_asocijacija' -------------
ALTER TABLE `resena_asocijacija`
	ADD CONSTRAINT `fk_asocijacija_resena_asocijacija` FOREIGN KEY ( `asocijacija_id` )
	REFERENCES `asocijacija`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_igra_igra_asocijacija' ----------------------
ALTER TABLE `igra_asocijacija`
	ADD CONSTRAINT `fk_igra_igra_asocijacija` FOREIGN KEY ( `igra_id` )
	REFERENCES `igra`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_igra_resena_igra' ---------------------------
ALTER TABLE `resena_igra`
	ADD CONSTRAINT `fk_igra_resena_igra` FOREIGN KEY ( `igra_id` )
	REFERENCES `igra`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_kategorija_igra' ----------------------------
ALTER TABLE `igra`
	ADD CONSTRAINT `fk_kategorija_igra` FOREIGN KEY ( `kategorija_id` )
	REFERENCES `kategorija`( `id` )
	ON DELETE Restrict
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_korisnik_resena_asocijacija' ----------------
ALTER TABLE `resena_asocijacija`
	ADD CONSTRAINT `fk_korisnik_resena_asocijacija` FOREIGN KEY ( `korisnik_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_korisnik_resena_igra' -----------------------
ALTER TABLE `resena_igra`
	ADD CONSTRAINT `fk_korisnik_resena_igra` FOREIGN KEY ( `korisnik_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_kreator_asocijacija' ------------------------
ALTER TABLE `asocijacija`
	ADD CONSTRAINT `fk_kreator_asocijacija` FOREIGN KEY ( `kreator_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE set null
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_kreator_igra' -------------------------------
ALTER TABLE `igra`
	ADD CONSTRAINT `fk_kreator_igra` FOREIGN KEY ( `kreator_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE set null
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_kreator_pojam' ------------------------------
ALTER TABLE `pojam`
	ADD CONSTRAINT `fk_kreator_pojam` FOREIGN KEY ( `kreator_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE set null
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_polje_sablon_igre' --------------------------
ALTER TABLE `polje`
	ADD CONSTRAINT `fk_polje_sablon_igre` FOREIGN KEY ( `sablon_igre_id` )
	REFERENCES `sablon_igre`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_polje_sablon_igre_resenje' ------------------
ALTER TABLE `sablon_igre`
	ADD CONSTRAINT `fk_polje_sablon_igre_resenje` FOREIGN KEY ( `resenje` )
	REFERENCES `polje`( `id` )
	ON DELETE set null
	ON UPDATE Cascade;
-- -------------------------------------------------------------



-- CREATE LINK 'fk_roditelj_kategorija' ------------------------
ALTER TABLE `kategorija`
	ADD CONSTRAINT `fk_roditelj_kategorija` FOREIGN KEY ( `roditelj_id` )
	REFERENCES `kategorija`( `id` )
	ON DELETE restrict
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_sablon_igre_igra' ---------------------------
ALTER TABLE `igra`
	ADD CONSTRAINT `fk_sablon_igre_igra` FOREIGN KEY ( `sablon_igre_id` )
	REFERENCES `sablon_igre`( `id` )
	ON DELETE Restrict
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_veza_asocijacija' ---------------------------
ALTER TABLE `pojam_polje_asocijacija`
	ADD CONSTRAINT `fk_veza_asocijacija` FOREIGN KEY ( `id_asocijacije` )
	REFERENCES `asocijacija`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_veza_pojam' ---------------------------------
ALTER TABLE `pojam_polje_asocijacija`
	ADD CONSTRAINT `fk_veza_pojam` FOREIGN KEY ( `id_pojma` )
	REFERENCES `pojam`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'fk_veza_polje' ---------------------------------
ALTER TABLE `pojam_polje_asocijacija`
	ADD CONSTRAINT `fk_veza_polje` FOREIGN KEY ( `id_polja` )
	REFERENCES `polje`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK 'f_korisnik_pokusaji_logovanja' -----------------
ALTER TABLE `pokusaji_logovanja`
	ADD CONSTRAINT `f_korisnik_pokusaji_logovanja` FOREIGN KEY ( `korisnik_id` )
	REFERENCES `korisnik`( `id` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------






/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


"
                );
                $this->insert('korisnik', ['korisnicko_ime' => 'admin'
                    , 'email' => 'admin@admin.com', 'lozinka' 
                    => yii::$app->security->generatePasswordHash('admin')
                        , 'aktivan' => 1, 'je_admin' => 1]);
                    $this->insert('pokusaji_logovanja', ['korisnik_id' => 1, 'broj_pokusaja' => 0]);
                    $this->execute("insert into kategorija(naziv)"
                            . "values('Tehnologija'),('Geografija'),('Biologija'), ('Knjizenvost'),"
                            . "('Racunarstvo'), ('Politika'), ('Fizika'), "
                            . "('Sport'),  ('Psihologija') ,('Izumi')");
                    $this->update('kategorija', ['roditelj_id' => 1], ['naziv' => 'Racunarstvo']);
                    $this->update('kategorija', ['roditelj_id' => 1], ['naziv' => 'Izumi']);
                    
                    $this->insert('sablon_igre', ['naziv'=>'4x4']);
                    $this->execute("insert into polje(naziv, sablon_igre_id) "
                            . "values('Resenje', 1),('A', 1),"
                            . "('B', 1),('A1', 1),('A2', 1),"
                            . "('A3', 1),('A4', 1),('B1', 1),"
                            . "('B2', 1),('B3', 1),('B4', 1),"
                            . "('C', 1),('C1', 1),('C2', 1),"
                            . "('C3', 1),('C4', 1),('D', 1),"
                            . "('D1', 1),('D2', 1),('D3', 1),('D4', 1)");
                    $this->update('sablon_igre', ['resenje' => 1], ['id' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("drop database proba");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200217_162234_nova_baza cannot be reverted.\n";

        return false;
    }
    */
}
