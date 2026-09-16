<?php
session_start();
$naslovStranice = 'Izmena prijave';
require 'delovi/pocetak.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

require_once 'klase/Konekcija.php';
require_once 'klase/Tabela.php';
require_once 'klase/DBPrijavaZaPraksu.php';
require_once 'klase/DBStavkaPrijave.php';
require_once 'klase/DBSifarnikStatusa.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

$PrijavaObjekat = new DBPrijavaZaPraksu($KonekcijaObjekat, 'PRIJAVAZAPRAKSU');
$PrijavaObjekat->UcitajPrijavuPoId($id);

if ($PrijavaObjekat->BrojZapisa === 0) {
    $KonekcijaObjekat->disconnect();
    header('Location: PrijaveLista.php');
    exit;
}

$prijava = $PrijavaObjekat->PrebaciKolekcijuUAsocijativnuListu($PrijavaObjekat->Kolekcija)[0];

if (!$jeAdmin && (int)$prijava['IDKORISNIKA'] !== (int)$_SESSION['idkorisnika']) {
    $KonekcijaObjekat->disconnect();
    header('Location: PrijaveLista.php');
    exit;
}

$StavkaObjekat = new DBStavkaPrijave($KonekcijaObjekat, 'STAVKAPRIJAVE');
$stavke = $StavkaObjekat->UcitajStavkeZaPrijavu($id);

$SifarnikObjekat = new DBSifarnikStatusa($KonekcijaObjekat, 'SIFARNIKSTATUSA');
$sviStatusi = $SifarnikObjekat->UcitajSveStatuse();

$KonekcijaObjekat->disconnect();

$greska = isset($_GET['greska']) ? $_GET['greska'] : '';
?>

<h1>Izmena prijave br. <?= h($prijava['BROJPRIJAVE']) ?></h1>

<?php if ($greska): ?>
    <div class="poruka-greska"><?= h($greska) ?></div>
<?php endif; ?>

<form class="forma" method="post" action="PrijavaIzmeniObrada.php" data-validiraj novalidate>
    <input type="hidden" name="id" value="<?= (int)$prijava['IDPRIJAVE'] ?>">

    <h2>Osnovni podaci</h2>
    <table><tr>
        <td style="padding-right:1.5rem;">
            <div class="polje">
                <label for="broj_prijave">Broj prijave</label>
                <input type="text" id="broj_prijave" name="broj_prijave" maxlength="30" required value="<?= h($prijava['BROJPRIJAVE']) ?>">
            </div>
        </td>
        <td style="padding-right:1.5rem;">
            <div class="polje">
                <label for="datum_prijave">Datum prijave</label>
                <input type="date" id="datum_prijave" name="datum_prijave" required value="<?= h($prijava['DATUMPRIJAVE']) ?>">
            </div>
        </td>
        <td>
            <div class="polje">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <?php foreach ($sviStatusi as $s): ?>
                        <option value="<?= h($s['SIFRA']) ?>" <?= $prijava['SIFRASTATUSA'] === $s['SIFRA'] ? 'selected' : '' ?>><?= h($s['NAZIV']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr></table>

    <h2>Kompanije i pozicije</h2>
    <div id="stavke-lista">
        <?php foreach ($stavke as $i => $st): ?>
        <div class="stavka-red">
            <table><tr>
                <td><label>Kompanija</label><br><input type="text" name="stavke[<?= $i ?>][kompanija]" maxlength="100" required value="<?= h($st['KOMPANIJA']) ?>"></td>
                <td><label>Pozicija</label><br><input type="text" name="stavke[<?= $i ?>][pozicija]" maxlength="100" required value="<?= h($st['POZICIJA']) ?>"></td>
                <td><label>Trajanje prakse</label><br><input type="text" name="stavke[<?= $i ?>][trajanje]" maxlength="50" required value="<?= h($st['TRAJANJEPRAKSE']) ?>"></td>
                <td><label>Napomena</label><br><input type="text" name="stavke[<?= $i ?>][napomena]" maxlength="255" value="<?= h($st['NAPOMENA']) ?>"></td>
                <td style="vertical-align:bottom;"><button type="button" class="dugme dugme-opasnost ukloni-stavku">&times;</button></td>
            </tr></table>
        </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="dodaj-stavku" class="dugme dugme-sekundarno" style="margin-bottom:1.2rem;">+ Dodaj stavku</button>

    <div>
        <button type="submit" class="dugme">Sačuvaj izmene</button>
        <a href="PrijaveLista.php" class="dugme dugme-sekundarno">Otkaži</a>
    </div>
</form>

<script src="js/validacija.js"></script>

<?php require 'delovi/kraj.php'; ?>
