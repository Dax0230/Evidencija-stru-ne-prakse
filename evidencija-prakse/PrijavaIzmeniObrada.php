<?php
session_start();

if (!isset($_SESSION['korisnik'])) {
    header('Location: PrijavaNaSistem.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
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
if (count($stavke) === 0) { $greske[] = 'Prijava mora imati bar jednu stavku.'; }

require_once 'klase/Konekcija.php';
require_once 'klase/Tabela.php';
require_once 'klase/DBPrijavaZaPraksu.php';
require_once 'klase/DBStavkaPrijave.php';
require_once 'klase/Transakcija.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

$PrijavaProveraObjekat = new DBPrijavaZaPraksu($KonekcijaObjekat, 'PRIJAVAZAPRAKSU');
$PrijavaProveraObjekat->UcitajPrijavuPoId($id);
if ($PrijavaProveraObjekat->BrojZapisa === 0) {
    $KonekcijaObjekat->disconnect();
    header('Location: PrijaveLista.php');
    exit;
}
$postojeca = $PrijavaProveraObjekat->PrebaciKolekcijuUAsocijativnuListu($PrijavaProveraObjekat->Kolekcija)[0];

if ($_SESSION['uloga'] !== 'administrator' && (int)$postojeca['IDKORISNIKA'] !== (int)$_SESSION['idkorisnika']) {
    $KonekcijaObjekat->disconnect();
    header('Location: PrijaveLista.php');
    exit;
}

if (count($greske) === 0 && !$PrijavaProveraObjekat->DaLiJeBrojPrijaveSlobodan($brojPrijave, $id)) {
    $greske[] = 'Prijava sa ovim brojem već postoji.';
}

if (count($greske) > 0) {
    $KonekcijaObjekat->disconnect();
    header('Location: PrijavaIzmeniForm.php?id=' . $id . '&greska=' . urlencode(implode(' ', $greske)));
    exit;
}

$TransakcijaObjekat = new Transakcija($KonekcijaObjekat);
$TransakcijaObjekat->ZapocniTransakciju();

$PrijavaObjekat = new DBPrijavaZaPraksu($KonekcijaObjekat, 'PRIJAVAZAPRAKSU');
$PrijavaObjekat->IDPrijave = $id;
$PrijavaObjekat->BrojPrijave = $brojPrijave;
$PrijavaObjekat->DatumPrijave = $datumPrijave;
$PrijavaObjekat->SifraStatusa = $status;
$greskaSQL = $PrijavaObjekat->IzmeniPrijavu();

$StavkaObjekat = new DBStavkaPrijave($KonekcijaObjekat, 'STAVKAPRIJAVE');
$greskaSQL .= $StavkaObjekat->ObrisiStavkeZaPrijavu($id);

foreach ($stavke as $st) {
    $StavkaObjekat->IDPrijave = $id;
    $StavkaObjekat->Kompanija = $st['kompanija'];
    $StavkaObjekat->Pozicija = $st['pozicija'];
    $StavkaObjekat->TrajanjePrakse = $st['trajanje'];
    $StavkaObjekat->Napomena = $st['napomena'] !== '' ? $st['napomena'] : null;
    $greskaSQL .= $StavkaObjekat->DodajStavku();
}

$TransakcijaObjekat->ZavrsiTransakciju($greskaSQL);
$KonekcijaObjekat->disconnect();

if (!empty($greskaSQL)) {
    header('Location: PrijavaIzmeniForm.php?id=' . $id . '&greska=' . urlencode('Greška prilikom snimanja: ' . $greskaSQL));
    exit;
}

header('Location: PrijaveLista.php?poruka=sacuvano');
exit;
