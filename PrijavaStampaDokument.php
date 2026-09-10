<?php
session_start();
if (!isset($_SESSION['korisnik'])) { header('Location: PrijavaNaSistem.php'); exit; }
require 'delovi/pomocne.php';
$jeAdmin = ($_SESSION['uloga'] === 'administrator');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

require 'klase/Konekcija.php';
require 'klase/Tabela.php';
require 'klase/DBPrijavaZaPraksu.php';
require 'klase/DBKorisnik.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

$prijava = new DBPrijavaZaPraksu($KonekcijaObjekat, 'PRIJAVAZAPRAKSU');
$prijava->UcitajKompletnuPrijavu($id);

if ($prijava->BrojZapisa === 0) {
    $KonekcijaObjekat->disconnect();
    header('Location: PrijaveLista.php');
    exit;
}

if (!$jeAdmin && $prijava->IDKorisnika !== (int)$_SESSION['idkorisnika']) {
    $KonekcijaObjekat->disconnect();
    header('Location: PrijaveLista.php');
    exit;
}

$KorisnikObjekat = new DBKorisnik($KonekcijaObjekat, 'KORISNIK');
$k = $KorisnikObjekat->UcitajKorisnikaPoId($prijava->IDKorisnika);

$KonekcijaObjekat->disconnect();

$obrazac = [
    'BrojPrijave'      => $prijava->BrojPrijave,
    'DatumPrijave'     => $prijava->DatumPrijave,
    'Ime'              => $k['IME'],
    'Prezime'          => $k['PREZIME'],
    'BrojIndeksa'      => $k['BROJINDEKSA'],
    'Fakultet'         => $k['FAKULTET'],
    'StudijskiProgram' => $k['STUDIJSKIPROGRAM'],
    'Telefon'          => $k['TELEFON'],
    'Email'            => $k['EMAIL'],
    'NazivStatusa'     => $prijava->Status->Naziv,
    'Stavke'           => array_map(function ($deo) {
        return [
            'KOMPANIJA'      => $deo->Kompanija,
            'POZICIJA'       => $deo->Pozicija,
            'TRAJANJEPRAKSE' => $deo->TrajanjePrakse,
            'NAPOMENA'       => $deo->Napomena,
        ];
    }, $prijava->Stavke),
];
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Prijava br. <?= h($prijava->BrojPrijave) ?> - štampa</title>
<?php include 'css/stil.php'; ?>
</head>
<body onload="window.print()">
<div style="padding:1.5rem;">
    <?php include 'delovi/obrazac_prijave.php'; ?>
</div>
</body>
</html>
