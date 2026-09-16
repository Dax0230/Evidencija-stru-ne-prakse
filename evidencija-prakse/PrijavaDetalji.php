<?php
session_start();
$naslovStranice = 'Detalji prijave';
require 'delovi/pocetak.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

require_once 'klase/Konekcija.php';
require_once 'klase/Tabela.php';
require_once 'klase/DBPrijavaZaPraksu.php';
require_once 'klase/DBKorisnik.php';

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

$k = null;
if ($jeAdmin) {
    $KorisnikObjekat = new DBKorisnik($KonekcijaObjekat, 'KORISNIK');
    $k = $KorisnikObjekat->UcitajKorisnikaPoId($prijava->IDKorisnika);
}

$KonekcijaObjekat->disconnect();
?>

<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.6rem;">
    <h1>Prijava br. <?= h($prijava->BrojPrijave) ?></h1>
    <div class="bez-stampe">
        <a href="PrijavaStampaDokument.php?id=<?= $prijava->IDPrijave ?>" class="dugme dugme-sekundarno" target="_blank">Štampaj</a>
        <?php if ($jeAdmin || $prijava->IDKorisnika === (int)$_SESSION['idkorisnika']): ?>
            <a href="PrijavaIzmeniForm.php?id=<?= $prijava->IDPrijave ?>" class="dugme">Izmeni</a>
        <?php endif; ?>
        <a href="PrijaveLista.php" class="dugme dugme-sekundarno">Nazad</a>
    </div>
</div>

<table class="podaci" style="margin-bottom:1.5rem;">
    <tr><th style="width:200px;">Broj prijave</th><td><?= h($prijava->BrojPrijave) ?></td></tr>
    <tr><th>Datum prijave</th><td><?= h($prijava->DatumPrijave) ?></td></tr>
    <tr><th>Status</th><td><span class="oznaka-status"><?= h($prijava->Status->Naziv) ?></span></td></tr>
    <?php if ($jeAdmin && $k): ?>
        <tr><th>Student</th><td><?= h($k['IME'] . ' ' . $k['PREZIME'] . ' (' . $k['BROJINDEKSA'] . ')') ?></td></tr>
        <tr><th>Fakultet</th><td><?= h($k['FAKULTET']) ?></td></tr>
        <tr><th>Studijski program</th><td><?= h($k['STUDIJSKIPROGRAM']) ?></td></tr>
        <tr><th>Telefon</th><td><?= h($k['TELEFON']) ?></td></tr>
        <tr><th>E-mail</th><td><?= h($k['EMAIL']) ?></td></tr>
    <?php endif; ?>
</table>

<h2>Kompanije i pozicije u ovoj prijavi</h2>
<table class="podaci">
<thead><tr><th>Kompanija</th><th>Pozicija</th><th>Trajanje prakse</th><th>Napomena</th></tr></thead>
<tbody>
<?php foreach ($prijava->Stavke as $deo): ?>
    <tr>
        <td><?= h($deo->Kompanija) ?></td>
        <td><?= h($deo->Pozicija) ?></td>
        <td><?= h($deo->TrajanjePrakse) ?></td>
        <td><?= h($deo->Napomena) ?></td>
    </tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require 'delovi/kraj.php'; ?>
