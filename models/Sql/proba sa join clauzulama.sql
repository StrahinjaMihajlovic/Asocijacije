SELECT pojmovi_ids FROM polje INNER JOIN igra ON polje.sablon_igre_id = igra.sablon_igre_id INNER JOIN 
igra_asocijacija ON igra.id = igra_asocijacija.igra_id INNER JOIN asocijacija ON igra_asocijacija.asocijacija_id = asocijacija.id WHERE polje.id = 1;