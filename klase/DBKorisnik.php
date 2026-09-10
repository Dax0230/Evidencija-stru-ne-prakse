<?php
class DBKorisnik extends Tabela {

public $IDKorisnika;
public $Prezime;
public $Ime;
public $BrojIndeksa;
public $Fakultet;
public $StudijskiProgram;
public $Telefon;
public $Email;
public $Sifra;
public $Uloga;

public function DaLiPostojiKorisnikSaEmailom($EmailParametar)
{
    $EmailEsc = $this->Escapiraj($EmailParametar);
    $Kriterijum = "EMAIL='" . $EmailEsc . "'";
    return $this->PostojiZapis($Kriterijum);
}

public function UcitajKorisnikaPoEmailu($EmailParametar)
{
    $EmailEsc = $this->Escapiraj($EmailParametar);
    $SQL = "SELECT * FROM `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` WHERE EMAIL='" . $EmailEsc . "'";
    $this->UcitajSvePoUpitu($SQL);

    if ($this->BrojZapisa > 0) {
        $Lista = $this->PrebaciKolekcijuUAsocijativnuListu($this->Kolekcija);
        return $Lista[0];
    }
    return null;
}

public function UcitajKorisnikaPoId($IdParametar)
{
    $SQL = "SELECT * FROM `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "` WHERE IDKORISNIKA=" . (int)$IdParametar;
    $this->UcitajSvePoUpitu($SQL);

    if ($this->BrojZapisa > 0) {
        $Lista = $this->PrebaciKolekcijuUAsocijativnuListu($this->Kolekcija);
        return $Lista[0];
    }
    return null;
}

public function RegistrujKorisnika()
{
    $PrezimeEsc = $this->Escapiraj($this->Prezime);
    $ImeEsc = $this->Escapiraj($this->Ime);
    $BrojIndeksaEsc = $this->Escapiraj($this->BrojIndeksa);
    $FakultetEsc = $this->Escapiraj($this->Fakultet);
    $StudProgEsc = $this->Escapiraj($this->StudijskiProgram);
    $TelefonEsc = $this->Escapiraj($this->Telefon);
    $EmailEsc = $this->Escapiraj($this->Email);
    $SifraEsc = $this->Escapiraj($this->Sifra);

    $SQL = "INSERT INTO `" . $this->NazivBazePodataka . "`.`" . $this->NazivTabele . "`
            (PREZIME, IME, BROJINDEKSA, FAKULTET, STUDIJSKIPROGRAM, TELEFON, EMAIL, SIFRA, ULOGA)
            VALUES ('$PrezimeEsc', '$ImeEsc', '$BrojIndeksaEsc', '$FakultetEsc', '$StudProgEsc', '$TelefonEsc', '$EmailEsc', '$SifraEsc', 'student')";
    return $this->IzvrsiAktivanSQLUpit($SQL);
}

}
