CREATE TABLE IF NOT EXISTS pojam(
    id SERIAL PRIMARY KEY,
    kreator_id INT NOT NULL,
    sadrzaj VARCHAR(50) NOT NULL UNIQUE,
    CONSTRAINT fk_kreator_pojam
        FOREIGN KEY (kreator_id)
        REFERENCES korisnik(id) 
        ON DELETE RESTRICT
);
CREATE INDEX idx_kreator_pojam ON pojam(kreator_id);

CREATE TABLE IF NOT EXISTS polje(
    id SERIAL PRIMARY KEY,
    sablon_igre_id BIGINT UNSIGNED DEFAULT NULL, -- privremeno, ukloniti kasnije defualt null
    naziv TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS sablon_igre(
    id SERIAL PRIMARY KEY,
    naziv VARCHAR(10) NOT NULL UNIQUE,
    resenje BIGINT UNSIGNED NOT NULL UNIQUE, -- 1:1 odnos
   -- polje_id INTEGER[] NOT NULL,  -- broj ostalih polja koja opisuju resenje
    CONSTRAINT fk_polje_sablon_igre_resenje
        FOREIGN KEY(resenje)
        REFERENCES polje(id)
        ON DELETE RESTRICT
);

ALTER TABLE polje 
    ADD CONSTRAINT fk_polje_sablon_igre
        FOREIGN KEY(sablon_igre_id)
        REFERENCES sablon_igre(id)
        ON DELETE SET NULL;

-- TODO Dodati trigger koji ce popuniti resenje iz tabele asocijacija


-- imutable. Svaki update kreira novu asocijaciju.
CREATE TABLE IF NOT EXISTS asocijacija(
    id SERIAL PRIMARY KEY,
    resenje_id BIGINT UNSIGNED NOT NULL,
    kreator_id INT NOT NULL,
    -- TODO kreirati triger koji popunjava pojam prilikom inserta
    pojmovi_ids VARCHAR(255) NOT NULL UNIQUE, -- Prvi id je id resenja. Nakon toga ide zapeta, pa sortirana lista id pojmova odvojena zapetama, 
                                    -- cilj je da se osigura jedinstvenost asocijacije. 
    CONSTRAINT fk_kreator_asocijacija
        FOREIGN KEY (kreator_id)
        REFERENCES korisnik(id) 
        ON DELETE RESTRICT,        
    CONSTRAINT fk_resenje_asocijacija
        FOREIGN KEY (resenje_id)
        REFERENCES pojam(id) 
        ON DELETE RESTRICT
);
CREATE INDEX idx_resenje_asocijacija ON asocijacija(resenje_id);
CREATE INDEX idx_kreator_asocijacija ON asocijacija(kreator_id);


CREATE TABLE IF NOT EXISTS asocijacija_pojam(
    asocijacija_id BIGINT UNSIGNED NOT NULL,
    pojam_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (asocijacija_id, pojam_id),
    CONSTRAINT fk_asocijacija_asocijacija_pojam
        FOREIGN KEY (asocijacija_id)
        REFERENCES asocijacija(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_pojam_asocijacija_pojam
        FOREIGN KEY (pojam_id)
        REFERENCES pojam(id) 
        ON DELETE CASCADE    
);
CREATE INDEX idx_pojam_asocijacija_pojam ON asocijacija_pojam(pojam_id);


CREATE TABLE IF NOT EXISTS kategorija(
    id SERIAL PRIMARY KEY,
    naziv VARCHAR(50) NOT NULL,
    roditelj_id BIGINT UNSIGNED, -- koristi se prilikom rekonstrukcije stabla (vrednosti levo i desno)
    levo INT, -- struktura stabla je definisana preko ugnjezdenih skupova (nested set)
    desno INT,
    CONSTRAINT fk_roditelj_kategorija
        FOREIGN KEY (roditelj_id)
        REFERENCES kategorija(id) 
        ON DELETE CASCADE
);
CREATE INDEX idx_roditelj_kategorija ON kategorija(roditelj_id);

-- igra sadrzi niz asocijacija
CREATE TABLE IF NOT EXISTS igra(
    id SERIAL PRIMARY KEY,
    kategorija_id BIGINT UNSIGNED NOT NULL,
    kreator_id INT NOT NULL,
    
    naziv VARCHAR(100) NOT NULL,
    opis TEXT,
    aktivna BOOLEAN NOT NULL DEFAULT FALSE,
    meta JSON NOT NULL,
    broj_igranja INT NOT NULL DEFAULT 0,
    sablon_igre_id BIGINT UNSIGNED, -- Dozvoljeno null sve dok se ne implementiraju pravilno sabloni igre
    CONSTRAINT fk_kategorija_igra
        FOREIGN KEY (kategorija_id)
        REFERENCES kategorija(id) 
        ON DELETE RESTRICT,
    CONSTRAINT fk_kreator_igra
        FOREIGN KEY (kreator_id)
        REFERENCES korisnik(id) 
        ON DELETE RESTRICT,
    CONSTRAINT fk_sablon_igre_igra
        FOREIGN KEY(sablon_igre_id)
        REFERENCES sablon_igre(id)
        ON DELETE SET NULL
);
CREATE INDEX idx_kategorija_igra ON igra(kategorija_id, aktivna);
CREATE INDEX idx_kreator_igra ON igra(kreator_id, aktivna);
CREATE INDEX idx_broj_igranja_igra ON igra(broj_igranja, aktivna);


CREATE TABLE IF NOT EXISTS igra_asocijacija(
    igra_id BIGINT UNSIGNED NOT NULL,
    asocijacija_id BIGINT UNSIGNED NOT NULL,
    
    PRIMARY KEY(igra_id, asocijacija_id),
    CONSTRAINT fk_igra_alijas
        FOREIGN KEY (alijas_id)
        REFERENCES alijas(id) 
        ON DELETE SET NULL,
    CONSTRAINT fk_igra_igra_asocijacija
        FOREIGN KEY (igra_id)
        REFERENCES igra(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_asocijacija_igra_asocijacija
        FOREIGN KEY (asocijacija_id)
        REFERENCES asocijacija(id) 
        ON DELETE CASCADE
);
CREATE INDEX idx_asocijacija_igra_asocijacija ON igra_asocijacija(asocijacija_id);

CREATE TABLE IF NOT EXISTS resena_igra(
    igra_id BIGINT UNSIGNED NOT NULL,
    korisnik_id INT NOT NULL,
    PRIMARY KEY(igra_id, korisnik_id),
    CONSTRAINT fk_igra_resena_igra
        FOREIGN KEY (igra_id)
        REFERENCES igra(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_korisnik_resena_igra
        FOREIGN KEY (korisnik_id)
        REFERENCES korisnik(id) 
        ON DELETE RESTRICT
);
CREATE INDEX idx_korisnik_resena_igra ON resena_igra(korisnik_id);

CREATE TABLE IF NOT EXISTS resena_asocijacija(
    asocijacija_id BIGINT UNSIGNED NOT NULL,
    korisnik_id INT NOT NULL,
    PRIMARY KEY (asocijacija_id, korisnik_id),
    CONSTRAINT fk_asocijacija_resena_asocijacija
        FOREIGN KEY (asocijacija_id)
        REFERENCES asocijacija(id) 
        ON DELETE CASCADE,
    CONSTRAINT fk_korisnik_resena_asocijacija
        FOREIGN KEY (korisnik_id)
        REFERENCES korisnik(id) 
        ON DELETE RESTRICT
);
CREATE INDEX idx_korisnik_resena_asocijacija ON resena_asocijacija(asocijacija_id);

CREATE TABLE IF NOT EXISTS neprimerene_reci(
    id SERIAL PRIMARY KEY,
    rec VARCHAR(100) NOT NULL UNIQUE    
);