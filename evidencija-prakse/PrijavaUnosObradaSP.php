<?php
session_start();

if (!isset($_SESSION['korisnik']) || $_SESSION['uloga'] !== 'student') {
    header('Location: PrijavaNaSistem.php');
    exit;
}

$brojPrijave = isset($_POST['broj_prijave']) ? trim($_POST['broj_prijave']) : '';
$datumPrijave = isset($_POST['datum_prijave']) ? trim($_POST['datum_prijave']) : '';
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$stavkeUnos = isset($_POST['stavke']) ? $_POST['stavke'] : array();

$greske = array();

if ($brojPrijave === '') { $greske[] = 'Broj prijave je obavezan.'; }

$d = DateTime::createFromFormat('Y-m-d', $datumPrijave);
if (!$d || $d->format('Y-m-d') !== $datumPrijave) { $greske[] = 'Datum prijave nije ispravan.'; }

if ($status === '') { $greske[] = 'Status mora biti izabran.'; }

$stavke = array();
foreach ($stavkeUnos as $red) {
    $kompanija = trim($red['kompanija'] ?? '');
    $pozicija = trim($red['pozicija'] ?? '');
    $trajanje = trim($red['trajanje'] ?? '');
    $napomena = trim($red['napomena'] ?? '');
    if ($kompanija !== '' || $pozicija !== '' || $trajanje !== '') {
        if ($kompanija === '') { $greske[] = 'Naziv kompanije je obavezan za svaku stavku.'; }
        if ($pozicija === '') { $greske[] = 'Pozicija je obavezna za svaku stavku.'; }
        if ($trajanje === '') { $greske[] = 'Trajanje prakse je obavezno za svaku stavku.'; }
        $stavke[] = array('kompanija' => $kompanija, 'pozicija' => $pozicija, 'trajanje' => $trajanje, 'napomena' => $napomena);
    }
}
if (count($stavke) === 0) { $greske[] = 'Prijava mora imati bar jednu stavku (kompanija/pozicija).'; }

require_once 'klase/Konekcija.php';
require_once 'klase/Tabela.php';
require_once 'klase/DBPrijavaZaPraksu.php';
require_once 'klase/DBPrijavaZaPraksuSP.php';
require_once 'klase/Transakcija.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

if (count($greske) === 0 && $KonekcijaObjekat->konekcijaDB) {
    $ProveraObjekat = new DBPrijavaZaPraksu($KonekcijaObjekat, 'PRIJAVAZAPRAKSU');
    if (!$ProveraObjekat->DaLiJeBrojPrijaveSlobodan($brojPrijave)) {
        $greske[] = 'Prijava sa ovim brojem već postoji.';
    }
}

if (count($greske) === 0) {

    $TransakcijaObjekat = new Transakcija($KonekcijaObjekat);
    $TransakcijaObjekat->ZapocniTransakciju();

    $PrijavaSPObjekat = new DBPrijavaZaPraksuSP($KonekcijaObjekat, 'PRIJAVAZAPRAKSU');
    $PrijavaSPObjekat->BrojPrijave = $brojPrijave;
    $PrijavaSPObjekat->DatumPrijave = $datumPrijave;
    $PrijavaSPObjekat->IDKorisnika = (int)$_SESSION['idkorisnika'];
    $PrijavaSPObjekat->SifraStatusa = $status;

    $greskaSQL = $PrijavaSPObjekat->DodajPrijavu();
    $noviId = $PrijavaSPObjekat->NoviIDPrijave;

    foreach ($stavke as $stavka) {
        $greskaSQL .= $PrijavaSPObjekat->DodajStavku(
            $noviId, $stavka['kompanija'], $stavka['pozicija'], $stavka['trajanje'],
            $stavka['napomena'] !== '' ? $stavka['napomena'] : null
        );
    }

    $TransakcijaObjekat->ZavrsiTransakciju($greskaSQL);

    $KonekcijaObjekat->disconnect();

    if (!empty($greskaSQL)) {
        header('Location: PrijavaUnosForm.php?greska=' . urlencode('Greška prilikom snimanja: ' . $greskaSQL));
        exit;
    }

    header('Location: PrijaveLista.php?poruka=sacuvano');
    exit;

} else {
    $KonekcijaObjekat->disconnect();
    header('Location: PrijavaUnosForm.php?greska=' . urlencode(implode(' ', $greske)));
    exit;
}
