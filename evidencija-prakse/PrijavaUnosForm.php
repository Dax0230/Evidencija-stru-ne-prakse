<?php
session_start();
$naslovStranice = 'Nova prijava';
require 'delovi/pocetak.php';

if ($jeAdmin) {
    header('Location: PrijaveLista.php');
    exit;
}

require_once 'klase/Konekcija.php';
require_once 'klase/Tabela.php';
require_once 'klase/DBSifarnikStatusa.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();
$SifarnikObjekat = new DBSifarnikStatusa($KonekcijaObjekat, 'SIFARNIKSTATUSA');
$sviStatusi = $SifarnikObjekat->UcitajSveStatuse();
$KonekcijaObjekat->disconnect();

$greska = isset($_GET['greska']) ? $_GET['greska'] : '';
?>

<h1>Nova prijava za stručnu praksu</h1>

<?php if ($greska): ?>
    <div class="poruka-greska"><?= h($greska) ?></div>
<?php endif; ?>

<form class="forma" method="post" action="PrijavaUnosObradaSP.php" data-validiraj novalidate>

    <h2>Osnovni podaci</h2>
    <table><tr>
        <td style="padding-right:1.5rem;">
            <div class="polje">
                <label for="broj_prijave">Broj prijave</label>
                <input type="text" id="broj_prijave" name="broj_prijave" maxlength="30" required>
            </div>
        </td>
        <td style="padding-right:1.5rem;">
            <div class="polje">
                <label for="datum_prijave">Datum prijave</label>
                <input type="date" id="datum_prijave" name="datum_prijave" required>
            </div>
        </td>
        <td>
            <div class="polje">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="">-- izaberite --</option>
                    <?php foreach ($sviStatusi as $s): ?>
                        <option value="<?= h($s['SIFRA']) ?>"><?= h($s['NAZIV']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr></table>

    <h2>Kompanije i pozicije</h2>
    <div id="stavke-lista">
        <div class="stavka-red">
            <table><tr>
                <td><label>Kompanija</label><br><input type="text" name="stavke[0][kompanija]" maxlength="100" required></td>
                <td><label>Pozicija</label><br><input type="text" name="stavke[0][pozicija]" maxlength="100" required></td>
                <td><label>Trajanje prakse</label><br><input type="text" name="stavke[0][trajanje]" maxlength="50" placeholder="npr. 3 meseca" required></td>
                <td><label>Napomena</label><br><input type="text" name="stavke[0][napomena]" maxlength="255"></td>
                <td></td>
            </tr></table>
        </div>
    </div>
    <button type="button" id="dodaj-stavku" class="dugme dugme-sekundarno" style="margin-bottom:1.2rem;">+ Dodaj stavku</button>

    <div>
        <button type="submit" class="dugme">Sačuvaj prijavu</button>
        <a href="PrijaveLista.php" class="dugme dugme-sekundarno">Otkaži</a>
    </div>
</form>

<script src="js/validacija.js"></script>

<?php require 'delovi/kraj.php'; ?>
