DROP TABLE IF EXISTS korisnik;
CREATE TABLE IF NOT EXISTS korisnik(
    id SERIAL NOT NULL PRIMARY KEY,
    korisnicko_ime VARCHAR(30) NOT NULL,
    lozinka VARCHAR(60) NOT NULL,
    email VARCHAR(30) NOT NULL UNIQUE,
    reset_kod TEXT NULL,
    auth_key TEXT
)ENGINE = INNODB;

