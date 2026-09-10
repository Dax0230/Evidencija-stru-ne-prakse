<?php
class DBStavkaPrijave extends Tabela {

public $IDStavke;
public $IDPrijave;
public $Kompanija;
public $Pozicija;
public $TrajanjePrakse;
public $Napomena;

public function UcitajStavkeZaPrijavu($IdPrijaveParametar)
{
    $SQL = "select * from `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` WHERE IDPRIJAVE=" . (int)$IdPrijaveParametar . " ORDER BY IDSTAVKE";
    $this->UcitajSvePoUpitu($SQL);
    return $this->PrebaciKolekcijuUAsocijativnuListu($this->Kolekcija);
}

public function DodajStavku()
{
    $KompEsc = $this->Escapiraj($this->Kompanija);
    $PozEsc = $this->Escapiraj($this->Pozicija);
    $TrajEsc = $this->Escapiraj($this->TrajanjePrakse);
    $NapVrednost = ($this->Napomena !== null && $this->Napomena !== '') ? "'" . $this->Escapiraj($this->Napomena) . "'" : "NULL";

    $SQL = "INSERT INTO `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "`
            (IDPRIJAVE, KOMPANIJA, POZICIJA, TRAJANJEPRAKSE, NAPOMENA)
            VALUES (" . (int)$this->IDPrijave . ", '$KompEsc', '$PozEsc', '$TrajEsc', $NapVrednost)";
    return $this->IzvrsiAktivanSQLUpit($SQL);
}

public function ObrisiStavkeZaPrijavu($IdPrijaveParametar)
{
    $SQL = "DELETE FROM `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` WHERE IDPRIJAVE=" . (int)$IdPrijaveParametar;
    return $this->IzvrsiAktivanSQLUpit($SQL);
}

}
