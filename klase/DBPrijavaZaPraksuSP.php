<?php
class DBPrijavaZaPraksuSP extends Tabela {

public $BrojPrijave;
public $DatumPrijave;
public $IDKorisnika;
public $SifraStatusa;
public $NoviIDPrijave;

public function DodajPrijavu()
{
    $BrojEsc = $this->Escapiraj($this->BrojPrijave);
    $DatumEsc = $this->Escapiraj($this->DatumPrijave);
    $StatusEsc = $this->Escapiraj($this->SifraStatusa);
    $IdKorisnika = (int)$this->IDKorisnika;

    $GreskaPar1 = $this->IzvrsiAktivanSQLUpit("SET @BrojPrijaveParametar='$BrojEsc'");
    $GreskaPar2 = $this->IzvrsiAktivanSQLUpit("SET @DatumPrijaveParametar='$DatumEsc'");
    $GreskaPar3 = $this->IzvrsiAktivanSQLUpit("SET @IdKorisnikaParametar=$IdKorisnika");
    $GreskaPar4 = $this->IzvrsiAktivanSQLUpit("SET @SifraStatusaParametar='$StatusEsc'");

    $GreskaCall = $this->IzvrsiAktivanSQLUpit(
        "CALL `DodajPrijavu`(@BrojPrijaveParametar, @DatumPrijaveParametar, @IdKorisnikaParametar, @SifraStatusaParametar, @NoviIdParametar)"
    );

    $this->UcitajSvePoUpitu("SELECT @NoviIdParametar AS NoviId");
    $Red = $this->PrebaciKolekcijuUAsocijativnuListu($this->Kolekcija);
    $this->NoviIDPrijave = $Red[0]['NoviId'] ?? null;

    return $GreskaPar1 . $GreskaPar2 . $GreskaPar3 . $GreskaPar4 . $GreskaCall;
}

public function DodajStavku($IdPrijaveParametar, $KompanijaParametar, $PozicijaParametar, $TrajanjeParametar, $NapomenaParametar)
{
    $KompEsc = $this->Escapiraj($KompanijaParametar);
    $PozEsc = $this->Escapiraj($PozicijaParametar);
    $TrajEsc = $this->Escapiraj($TrajanjeParametar);
    $NapEsc = $this->Escapiraj($NapomenaParametar);

    $this->IzvrsiAktivanSQLUpit("SET @IdPrijaveParametar=" . (int)$IdPrijaveParametar);
    $this->IzvrsiAktivanSQLUpit("SET @KompanijaParametar='$KompEsc'");
    $this->IzvrsiAktivanSQLUpit("SET @PozicijaParametar='$PozEsc'");
    $this->IzvrsiAktivanSQLUpit("SET @TrajanjeParametar='$TrajEsc'");
    $this->IzvrsiAktivanSQLUpit("SET @NapomenaParametar=" . ($NapomenaParametar !== null && $NapomenaParametar !== '' ? "'$NapEsc'" : "NULL"));

    return $this->IzvrsiAktivanSQLUpit(
        "CALL `DodajStavku`(@IdPrijaveParametar, @KompanijaParametar, @PozicijaParametar, @TrajanjeParametar, @NapomenaParametar)"
    );
}

public function ObrisiSveStavkePrijave($IdPrijaveParametar)
{
    $this->IzvrsiAktivanSQLUpit("SET @IdPrijaveParametar=" . (int)$IdPrijaveParametar);
    return $this->IzvrsiAktivanSQLUpit("CALL `ObrisiSveStavkePrijave`(@IdPrijaveParametar)");
}

}
