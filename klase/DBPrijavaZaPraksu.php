<?php
require_once __DIR__ . '/DBStavkaPrijave.php';
require_once __DIR__ . '/DBSifarnikStatusa.php';

class DBPrijavaZaPraksu extends Tabela {

public $IDPrijave;
public $BrojPrijave;
public $DatumPrijave;
public $IDKorisnika;
public $SifraStatusa;

public $Status;

public $Stavke = array();

public function UcitajPrijavuPoId($IdParametar)
{
    $SQL = "select * from `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` WHERE IDPRIJAVE=" . (int)$IdParametar;
    $this->UcitajSvePoUpitu($SQL);
}

public function UcitajKompletnuPrijavu($IdParametar)
{
    $this->UcitajPrijavuPoId($IdParametar);
    if ($this->BrojZapisa == 0) {
        return;
    }
    $red = $this->PrebaciKolekcijuUAsocijativnuListu($this->Kolekcija)[0];
    $this->IDPrijave    = (int)$red['IDPRIJAVE'];
    $this->BrojPrijave  = $red['BROJPRIJAVE'];
    $this->DatumPrijave = $red['DATUMPRIJAVE'];
    $this->IDKorisnika  = (int)$red['IDKORISNIKA'];
    $this->SifraStatusa = $red['SIFRASTATUSA'];

    $sifarnikEntitet = new DBSifarnikStatusa($this->OtvorenaKonekcija, 'SIFARNIKSTATUSA');
    foreach ($sifarnikEntitet->UcitajSveStatuse() as $s) {
        if ($s['SIFRA'] === $this->SifraStatusa) {
            $sifarnikEntitet->Sifra = $s['SIFRA'];
            $sifarnikEntitet->Naziv = $s['NAZIV'];
            break;
        }
    }
    $this->Status = $sifarnikEntitet;

    $this->Stavke = array();
    $stavkaEntitet = new DBStavkaPrijave($this->OtvorenaKonekcija, 'STAVKAPRIJAVE');
    foreach ($stavkaEntitet->UcitajStavkeZaPrijavu($this->IDPrijave) as $rs) {
        $deo = new DBStavkaPrijave($this->OtvorenaKonekcija, 'STAVKAPRIJAVE');
        $deo->IDStavke        = (int)$rs['IDSTAVKE'];
        $deo->IDPrijave       = (int)$rs['IDPRIJAVE'];
        $deo->Kompanija       = $rs['KOMPANIJA'];
        $deo->Pozicija        = $rs['POZICIJA'];
        $deo->TrajanjePrakse  = $rs['TRAJANJEPRAKSE'];
        $deo->Napomena        = $rs['NAPOMENA'];
        $this->Stavke[] = $deo;
    }
}

public function DaLiJeBrojPrijaveSlobodan($BrojPrijaveParametar, $IdPrijaveZaIskljucenje = null)
{
    $BrojEsc = $this->Escapiraj($BrojPrijaveParametar);
    $Kriterijum = "BROJPRIJAVE='" . $BrojEsc . "'";
    if ($IdPrijaveZaIskljucenje !== null) {
        $Kriterijum .= " AND IDPRIJAVE<>" . (int)$IdPrijaveZaIskljucenje;
    }
    return !$this->PostojiZapis($Kriterijum);
}

public function IzmeniPrijavu()
{
    $BrojEsc = $this->Escapiraj($this->BrojPrijave);
    $DatumEsc = $this->Escapiraj($this->DatumPrijave);
    $StatusEsc = $this->Escapiraj($this->SifraStatusa);

    $SQL = "UPDATE `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "`
            SET BROJPRIJAVE='$BrojEsc', DATUMPRIJAVE='$DatumEsc', SIFRASTATUSA='$StatusEsc', DATUMIZMENE=NOW()
            WHERE IDPRIJAVE=" . (int)$this->IDPrijave;
    return $this->IzvrsiAktivanSQLUpit($SQL);
}

public function ObrisiPrijavu($IdParametar)
{

    $SQLStavke = "DELETE FROM `" . $this->NazivBazePodataka . "`.`STAVKAPRIJAVE` WHERE IDPRIJAVE=" . (int)$IdParametar;
    $Greska1 = $this->IzvrsiAktivanSQLUpit($SQLStavke);

    $SQLPrijava = "DELETE FROM `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` WHERE IDPRIJAVE=" . (int)$IdParametar;
    $Greska2 = $this->IzvrsiAktivanSQLUpit($SQLPrijava);

    return $Greska1 . $Greska2;
}

}
