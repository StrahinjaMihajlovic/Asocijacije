DROP PROCEDURE IF EXISTS popuni_pojmove_asocijacije;
CREATE PROCEDURE popuni_pojmove_asocijacije(IN param1 INT, IN param2 INT)
BEGIN
    UPDATE asocijacija
    SET pojmovi_ids = CONCAT(pojmovi_ids, CONCAT(", ", param1))
    WHERE id = param2;  
END; 

DROP TRIGGER IF EXISTS pojmovi_asocijacije_trigger;
CREATE TRIGGER pojmovi_asocijacije_trigger BEFORE INSERT ON asocijacija_pojam
FOR EACH row 
BEGIN
 CALL popuni_pojmove_asocijacije(NEW.pojam_id, NEW.asocijacija_id);
END;

