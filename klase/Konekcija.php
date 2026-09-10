<?php
class Konekcija {

public $konekcijaDB;
public $KompletanNazivBazePodataka;

private $PutanjaNazivFajlaXMLParametriKonekcije;
private $host;
private $korisnik;
private $sifra;
private $prefiks_baze_podataka;
private $naziv_baze_podataka;

private function UcitajParametreKonekcije($PutanjaNazivFajlaXMLParametriKonekcije)
{
    $xml = simplexml_load_file($PutanjaNazivFajlaXMLParametriKonekcije)
        or die("Greska: Ne postoji fajl BaznaParametriKonekcije.xml");

    $this->host = (string)$xml->host;
    $this->korisnik = (string)$xml->korisnik;
    $this->sifra = (string)$xml->sifra;
    $this->prefiks_baze_podataka = (string)$xml->prefiks_baze_podataka;
    $this->naziv_baze_podataka = (string)$xml->naziv_baze_podataka;
    $this->KompletanNazivBazePodataka = $this->prefiks_baze_podataka . $this->naziv_baze_podataka;
}

public function __construct($NovaPutanjaNazivFajlaXMLParametriKonekcije)
{
    $this->PutanjaNazivFajlaXMLParametriKonekcije = $NovaPutanjaNazivFajlaXMLParametriKonekcije;
    $this->UcitajParametreKonekcije($NovaPutanjaNazivFajlaXMLParametriKonekcije);
}

public function connect()
{
    $this->konekcijaDB = mysqli_connect($this->host, $this->korisnik, $this->sifra, $this->KompletanNazivBazePodataka);

    if ($this->konekcijaDB) {
        mysqli_set_charset($this->konekcijaDB, "utf8");
    }
}

public function disconnect()
{
    if ($this->konekcijaDB) {
        mysqli_close($this->konekcijaDB);
    }
}

}
