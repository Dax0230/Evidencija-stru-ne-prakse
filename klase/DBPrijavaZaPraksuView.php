<?php
class DBPrijavaZaPraksuView extends Tabela {

public function DajFiltriranPregled($IdKorisnika, $BrojPrijave, $SifraStatusa, $DatumOd, $DatumDo)
{
    $Uslovi = array();

    if ($IdKorisnika !== null) {
        $Uslovi[] = "IDKORISNIKA=" . (int)$IdKorisnika;
    }
    if (!empty($BrojPrijave)) {
        $Uslovi[] = "BROJPRIJAVE LIKE '%" . $this->Escapiraj($BrojPrijave) . "%'";
    }
    if (!empty($SifraStatusa)) {
        $Uslovi[] = "SIFRASTATUSA='" . $this->Escapiraj($SifraStatusa) . "'";
    }
    if (!empty($DatumOd)) {
        $Uslovi[] = "DATUMPRIJAVE>='" . $this->Escapiraj($DatumOd) . "'";
    }
    if (!empty($DatumDo)) {
        $Uslovi[] = "DATUMPRIJAVE<='" . $this->Escapiraj($DatumDo) . "'";
    }

    $SQL = "select * from `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "`";
    if (count($Uslovi) > 0) {
        $SQL .= " WHERE " . implode(" AND ", $Uslovi);
    }
    $SQL .= " ORDER BY DATUMPRIJAVE DESC";

    $this->UcitajSvePoUpitu($SQL);
    return $this->PrebaciKolekcijuUAsocijativnuListu($this->Kolekcija);
}

}
