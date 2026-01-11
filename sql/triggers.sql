
DROP TRIGGER IF EXISTS AI_upd_rating;
DROP TRIGGER IF EXISTS AU_upd_rating;

DELIMITER $$
CREATE TRIGGER AI_upd_rating AFTER INSERT ON Reservas
FOR EACH ROW
BEGIN
  UPDATE Condutores
  SET aval = (
    SELECT AVG(R.avaliacao)
    FROM Reservas R
    JOIN Viagens V ON R.viagem_id = V.id
    WHERE V.condutor_id = (SELECT V2.condutor_id FROM Viagens V2 WHERE V2.id = NEW.viagem_id)
  )
  WHERE id = (SELECT condutor_id FROM Viagens WHERE id = NEW.viagem_id);
END

DELIMITER $$;

DELIMITER $$
CREATE TRIGGER AU_upd_rating AFTER UPDATE ON Reservas
FOR EACH ROW
BEGIN
    UPDATE Condutores
    SET aval = (
        SELECT AVG(R.avaliacao)
        FROM Reservas R
        JOIN Viagens V ON R.viagem_id = V.id
        WHERE V.condutor_id = (SELECT V2.condutor_id FROM Viagens V2 WHERE V2.id = NEW.viagem_id)
    )
    WHERE id = (SELECT condutor_id FROM Viagens WHERE id = NEW.viagem_id);
END

DELIMITER $$;


DROP TRIGGER IF EXISTS upd_seats;

DELIMITER $$
CREATE TRIGGER upd_seats BEFORE INSERT ON Reservas
FOR EACH ROW
BEGIN
 DECLARE available_seats INT;

 -- Check available seats for the trip
 SELECT lugares INTO available_seats
 FROM Viagens
 WHERE id = NEW.viagem_id;

 -- If no seats are available, signal an error
 IF available_seats <= 0 THEN
     SIGNAL SQLSTATE '45000' -- unhandled user-defined exception
         SET MESSAGE_TEXT = 'No available seats for this trip';
 ELSE
     -- Decrease the number of available seats
     UPDATE Viagens
     SET lugares = lugares - 1
     WHERE id = NEW.viagem_id;
 END IF;
END
