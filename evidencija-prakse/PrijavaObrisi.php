<?php
session_start();

if (!isset($_SESSION['korisnik'])) {
    header('Location: PrijavaNaSistem.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

require_once 'klase/Konekcija.php';
require_once 'klase/Tabela.php';
require_once 'klase/DBPrijavaZaPraksu.php';
require_once 'klase/Transakcija.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

$PrijavaObjekat = new DBPrijavaZaPraksu($KonekcijaObjekat, 'PRIJAVAZAPRAKSU');
$PrijavaObjekat->UcitajPrijavuPoId($id);

if ($PrijavaObjekat->BrojZapisa > 0) {
    $red = $PrijavaObjekat->PrebaciKolekcijuUAsocijativnuListu($PrijavaObjekat->Kolekcija)[0];
    $jeAdmin = $_SESSION['uloga'] === 'administrator';
    $jeVlasnik = (int)$red['IDKORISNIKA'] === (int)$_SESSION['idkorisnika'];

    if ($jeAdmin || $jeVlasnik) {
        $TransakcijaObjekat = new Transakcija($KonekcijaObjekat);
        $TransakcijaObjekat->ZapocniTransakciju();

        $greska = $PrijavaObjekat->ObrisiPrijavu($id);

        $TransakcijaObjekat->ZavrsiTransakciju($greska);
    }
}

$KonekcijaObjekat->disconnect();
header('Location: PrijaveLista.php?poruka=obrisano');
exit;
