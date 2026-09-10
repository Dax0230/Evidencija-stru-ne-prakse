<?php

class Tabela {

public $OtvorenaKonekcija;
public $NazivBazePodataka;
public $NazivTabele;

public $Kolekcija;
public $BrojZapisa;
public $ListaZapisa;

public function __construct($NovaOtvorenaKonekcija, $NoviNazivTabele)
{
    $this->OtvorenaKonekcija = $NovaOtvorenaKonekcija;
    $this->NazivBazePodataka = $NovaOtvorenaKonekcija->KompletanNazivBazePodataka;
    $this->NazivTabele = $NoviNazivTabele;
}

public function UcitajSve($KriterijumSortiranja)
{
    $SQL = "select * from `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` ORDER BY " . $KriterijumSortiranja;
    $this->Kolekcija = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $SQL);
    $this->BrojZapisa = mysqli_num_rows($this->Kolekcija);
}

public function UcitajSvePoUpitu($Upit)
{
    $this->Kolekcija = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $Upit);
    $this->BrojZapisa = ($this->Kolekcija) ? mysqli_num_rows($this->Kolekcija) : 0;
}

public function UcitajSvaPoljaFiltrirano($KriterijumFiltriranja, $KriterijumSortiranja)
{
    $SQL = "select * from `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` WHERE " . $KriterijumFiltriranja . " ORDER BY " . $KriterijumSortiranja;
    $this->Kolekcija = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $SQL);
    $this->BrojZapisa = ($this->Kolekcija) ? mysqli_num_rows($this->Kolekcija) : 0;
}

public function DajVrednostJednogPoljaPrvogZapisa($NazivTrazenogPolja, $KriterijumFiltriranja, $KriterijumSortiranja)
{
    $SQL = "select " . $NazivTrazenogPolja . " from `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` WHERE " . $KriterijumFiltriranja . " ORDER BY " . $KriterijumSortiranja;
    $Kolekcija = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $SQL);
    $row = mysqli_fetch_array($Kolekcija, MYSQLI_NUM);
    return $row[0];
}

public function PrebaciKolekcijuUAsocijativnuListu($Kolekcija)
{
    $Lista = array();
    while ($RedZapisa = mysqli_fetch_assoc($Kolekcija)) {
        $Lista[] = $RedZapisa;
    }
    return $Lista;
}

public function PostojiZapis($KriterijumFiltriranja)
{
    $SQL = "SELECT * FROM `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` WHERE " . $KriterijumFiltriranja;
    $KolekcijaLokalna = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $SQL);
    $BrojZapisaLokalna = $KolekcijaLokalna ? mysqli_num_rows($KolekcijaLokalna) : 0;
    return $BrojZapisaLokalna > 0;
}

public function IzvrsiAktivanSQLUpit($AktivanSQLUpit)
{
    $retval = mysqli_query($this->OtvorenaKonekcija->konekcijaDB, $AktivanSQLUpit);
    $Greska = mysqli_error($this->OtvorenaKonekcija->konekcijaDB);
    return $Greska;
}

public function Escapiraj($Tekst)
{
    return mysqli_real_escape_string($this->OtvorenaKonekcija->konekcijaDB, $Tekst);
}

}
