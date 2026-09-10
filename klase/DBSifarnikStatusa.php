<?php
class DBSifarnikStatusa extends Tabela {

public $Sifra;
public $Naziv;

public function UcitajSveStatuse()
{
    $SQL = "select * from `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` ORDER BY NAZIV";
    $this->UcitajSvePoUpitu($SQL);
    return $this->PrebaciKolekcijuUAsocijativnuListu($this->Kolekcija);
}

}
