
CREATE VIEW `evidencija_prakse`.`PregledPrijava` AS
SELECT
    p.IDPRIJAVE, p.BROJPRIJAVE, p.DATUMPRIJAVE, p.IDKORISNIKA,
    k.PREZIME, k.IME, k.BROJINDEKSA, k.FAKULTET, k.STUDIJSKIPROGRAM, k.TELEFON, k.EMAIL,
    s.SIFRA AS SIFRASTATUSA, s.NAZIV AS NAZIVSTATUSA,
    (SELECT COUNT(*) FROM `evidencija_prakse`.`STAVKAPRIJAVE` sp WHERE sp.IDPRIJAVE = p.IDPRIJAVE) AS BROJSTAVKI
FROM `evidencija_prakse`.`PRIJAVAZAPRAKSU` p
INNER JOIN `evidencija_prakse`.`KORISNIK` k ON k.IDKORISNIKA = p.IDKORISNIKA
INNER JOIN `evidencija_prakse`.`SIFARNIKSTATUSA` s ON s.SIFRA = p.SIFRASTATUSA;

CREATE VIEW `evidencija_prakse`.`PregledStavki` AS
SELECT
    sp.IDSTAVKE, sp.IDPRIJAVE, p.BROJPRIJAVE,
    sp.KOMPANIJA, sp.POZICIJA, sp.TRAJANJEPRAKSE, sp.NAPOMENA
FROM `evidencija_prakse`.`STAVKAPRIJAVE` sp
INNER JOIN `evidencija_prakse`.`PRIJAVAZAPRAKSU` p ON p.IDPRIJAVE = sp.IDPRIJAVE;

USE `evidencija_prakse`;
DROP PROCEDURE IF EXISTS `DodajPrijavu`;
DELIMITER $$
USE `evidencija_prakse`$$
CREATE PROCEDURE `DodajPrijavu` (
    IN BrojPrijaveParametar varchar(30),
    IN DatumPrijaveParametar date,
    IN IdKorisnikaParametar int,
    IN SifraStatusaParametar varchar(10),
    OUT NoviIdParametar int
)
BEGIN
    INSERT INTO `PRIJAVAZAPRAKSU` (BROJPRIJAVE, DATUMPRIJAVE, IDKORISNIKA, SIFRASTATUSA)
    VALUES (BrojPrijaveParametar, DatumPrijaveParametar, IdKorisnikaParametar, SifraStatusaParametar);
    SET NoviIdParametar = LAST_INSERT_ID();
END
$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `IzmeniPrijavu`;
DELIMITER $$
USE `evidencija_prakse`$$
CREATE PROCEDURE `IzmeniPrijavu` (
    IN IdPrijaveParametar int,
    IN BrojPrijaveParametar varchar(30),
    IN DatumPrijaveParametar date,
    IN SifraStatusaParametar varchar(10)
)
BEGIN
    UPDATE `PRIJAVAZAPRAKSU`
       SET BROJPRIJAVE = BrojPrijaveParametar,
           DATUMPRIJAVE = DatumPrijaveParametar,
           SIFRASTATUSA = SifraStatusaParametar,
           DATUMIZMENE = NOW()
     WHERE IDPRIJAVE = IdPrijaveParametar;
END
$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `DodajStavku`;
DELIMITER $$
USE `evidencija_prakse`$$
CREATE PROCEDURE `DodajStavku` (
    IN IdPrijaveParametar int,
    IN KompanijaParametar varchar(100),
    IN PozicijaParametar varchar(100),
    IN TrajanjeParametar varchar(50),
    IN NapomenaParametar varchar(255)
)
BEGIN
    INSERT INTO `STAVKAPRIJAVE` (IDPRIJAVE, KOMPANIJA, POZICIJA, TRAJANJEPRAKSE, NAPOMENA)
    VALUES (IdPrijaveParametar, KompanijaParametar, PozicijaParametar, TrajanjeParametar, NapomenaParametar);
END
$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `ObrisiSveStavkePrijave`;
DELIMITER $$
USE `evidencija_prakse`$$
CREATE PROCEDURE `ObrisiSveStavkePrijave` (
    IN IdPrijaveParametar int
)
BEGIN
    DELETE FROM `STAVKAPRIJAVE` WHERE IDPRIJAVE = IdPrijaveParametar;
END
$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `ObrisiPrijavu`;
DELIMITER $$
USE `evidencija_prakse`$$
CREATE PROCEDURE `ObrisiPrijavu` (
    IN IdPrijaveParametar int
)
BEGIN
    DELETE FROM `STAVKAPRIJAVE` WHERE IDPRIJAVE = IdPrijaveParametar;
    DELETE FROM `PRIJAVAZAPRAKSU` WHERE IDPRIJAVE = IdPrijaveParametar;
END
$$
DELIMITER ;
