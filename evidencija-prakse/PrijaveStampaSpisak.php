<?php
session_start();
if (!isset($_SESSION['korisnik'])) { header('Location: PrijavaNaSistem.php'); exit; }
require 'delovi/pomocne.php';
$jeAdmin = ($_SESSION['uloga'] === 'administrator');

$fBroj = isset($_GET['broj_prijave']) ? trim($_GET['broj_prijave']) : '';
$fStatus = isset($_GET['status']) ? trim($_GET['status']) : '';
$fDatumOd = isset($_GET['datum_od']) ? trim($_GET['datum_od']) : '';
$fDatumDo = isset($_GET['datum_do']) ? trim($_GET['datum_do']) : '';

require_once 'klase/Konekcija.php';
require_once 'klase/Tabela.php';
require_once 'klase/DBPrijavaZaPraksuView.php';
require_once 'klase/DBStavkaPrijave.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

$ViewObjekat = new DBPrijavaZaPraksuView($KonekcijaObjekat, 'PregledPrijava');
$idKorisnikaFilter = $jeAdmin ? null : (int)$_SESSION['idkorisnika'];
$prijave = $ViewObjekat->DajFiltriranPregled($idKorisnikaFilter, $fBroj, $fStatus, $fDatumOd, $fDatumDo);

$StavkaObjekat = new DBStavkaPrijave($KonekcijaObjekat, 'STAVKAPRIJAVE');
$obrasci = array();
foreach ($prijave as $p) {
    $stavkeRedovi = $StavkaObjekat->UcitajStavkeZaPrijavu((int)$p['IDPRIJAVE']);
    $obrasci[] = [
        'BrojPrijave'      => $p['BROJPRIJAVE'],
        'DatumPrijave'     => $p['DATUMPRIJAVE'],
        'Ime'              => $p['IME'],
        'Prezime'          => $p['PREZIME'],
        'BrojIndeksa'      => $p['BROJINDEKSA'],
        'Fakultet'         => $p['FAKULTET'],
        'StudijskiProgram' => $p['STUDIJSKIPROGRAM'],
        'Telefon'          => $p['TELEFON'],
        'Email'            => $p['EMAIL'],
        'NazivStatusa'     => $p['NAZIVSTATUSA'],
        'Stavke'           => $stavkeRedovi,
    ];
}

$KonekcijaObjekat->disconnect();
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Spisak prijava - štampa</title>
<?php include 'css/stil.php'; ?>
</head>
<body onload="window.print()">
<div style="padding:1.5rem;">
    <?php if (count($obrasci) === 0): ?>
        <p>Nema prijava koje odgovaraju zadatim filterima.</p>
    <?php endif; ?>
    <?php foreach ($obrasci as $i => $obrazac): ?>
        <?php if ($i > 0): ?><div class="obrazac-odvajac"></div><?php endif; ?>
        <?php include 'delovi/obrazac_prijave.php'; ?>
    <?php endforeach; ?>
</div>
</body>
</html>
