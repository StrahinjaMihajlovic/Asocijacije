/*ALTER TABLE resena_igra
DROP COLUMN resene_asocijacije;*/

ALTER TABLE resena_igra
ADD resene_asocijacije VARCHAR(255);

/*DROP TRIGGER resena_igra_popuna;*/

CREATE TRIGGER resena_igra_popuna
BEFORE INSERT ON resena_igra
FOR EACH row
BEGIN

SET @prom = (SELECT COUNT(asocijacija_id) FROM igra_asocijacija WHERE igra_id = 1);
SET new.resene_asocijacije = lpad('0',@prom, '0'); /*popunjavamo nulama onoliko koliko ima asocijacija u datoj igri */

END