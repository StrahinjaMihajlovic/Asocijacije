ALTER TABLE resena_asocijacija
DROP COLUMN otvorena_polja;

ALTER TABLE resena_asocijacija
ADD otvorena_polja VARCHAR(255);

/*otvorena_polja je verovatno bolje preko php-a popuniti, zbog manipulacije stringovima */

/*DROP TRIGGER resena_igra_popuna;

CREATE TRIGGER resena_asocijacija_popuna
BEFORE INSERT ON resena_asocijacija
FOR EACH row
BEGIN

set @prom = (SELECT COUNT(asocijacija_id) FROM igra_asocijacija WHERE igra_id = 1);
SET new.resene_asocijacije = lpad('0',@prom, '0'); /*popunjavamo nulama onoliko koliko ima asocijacija u datoj igri 
END 
*/