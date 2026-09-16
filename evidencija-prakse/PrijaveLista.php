<?php
session_start();
$naslovStranice = 'Prijave';
require 'delovi/pocetak.php';

$fBroj = isset($_GET['broj_prijave']) ? trim($_GET['broj_prijave']) : '';
$fStatus = isset($_GET['status']) ? trim($_GET['status']) : '';
$fDatumOd = isset($_GET['datum_od']) ? trim($_GET['datum_od']) : '';
$fDatumDo = isset($_GET['datum_do']) ? trim($_GET['datum_do']) : '';

require_once 'klase/Konekcija.php';
require_once 'klase/Tabela.php';
require_once 'klase/DBSifarnikStatusa.php';
require_once 'klase/DBPrijavaZaPraksuView.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

$SifarnikObjekat = new DBSifarnikStatusa($KonekcijaObjekat, 'SIFARNIKSTATUSA');
$sviStatusi = $SifarnikObjekat->UcitajSveStatuse();

$ViewObjekat = new DBPrijavaZaPraksuView($KonekcijaObjekat, 'PregledPrijava');
$idKorisnikaFilter = $jeAdmin ? null : (int)$_SESSION['idkorisnika'];
$prijave = $ViewObjekat->DajFiltriranPregled($idKorisnikaFilter, $fBroj, $fStatus, $fDatumOd, $fDatumDo);

$KonekcijaObjekat->disconnect();

$poruka = isset($_GET['poruka']) ? $_GET['poruka'] : '';
?>

<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.6rem;">
    <h1><?= $jeAdmin ? 'Sve prijave za stručnu praksu' : 'Moje prijave za stručnu praksu' ?></h1>
    <div class="bez-stampe">
        <?php if (!$jeAdmin): ?>
            <a href="PrijavaUnosForm.php" class="dugme">+ Nova prijava</a>
        <?php endif; ?>
        <a href="PrijaveStampaSpisak.php?<?= h($_SERVER['QUERY_STRING']) ?>" class="dugme dugme-sekundarno" target="_blank">Štampaj spisak</a>
    </div>
</div>

<?php if ($poruka === 'sacuvano'): ?>
    <div class="poruka-uspeh">Prijava je uspešno sačuvana.</div>
<?php elseif ($poruka === 'obrisano'): ?>
    <div class="poruka-uspeh">Prijava je uspešno obrisana.</div>
<?php endif; ?>

<form method="get" class="forma bez-stampe" style="background:#f9fbfc; border:1px solid #e2e6ea; border-radius:6px; padding:1rem; margin-bottom:1.2rem;">
<table>
<tr>
<td style="padding-right:1rem;">
    <div class="polje" style="margin-bottom:0;">
        <label for="broj_prijave">Broj prijave</label>
        <input type="text" id="broj_prijave" name="broj_prijave" value="<?= h($fBroj) ?>">
    </div>
</td>
<td style="padding-right:1rem;">
    <div class="polje" style="margin-bottom:0;">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Svi statusi</option>
            <?php foreach ($sviStatusi as $s): ?>
                <option value="<?= h($s['SIFRA']) ?>" <?= $fStatus === $s['SIFRA'] ? 'selected' : '' ?>><?= h($s['NAZIV']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</td>
<td style="padding-right:1rem;">
    <div class="polje" style="margin-bottom:0;">
        <label for="datum_od">Datum od</label>
        <input type="date" id="datum_od" name="datum_od" value="<?= h($fDatumOd) ?>">
    </div>
</td>
<td style="padding-right:1rem;">
    <div class="polje" style="margin-bottom:0;">
        <label for="datum_do">Datum do</label>
        <input type="date" id="datum_do" name="datum_do" value="<?= h($fDatumDo) ?>">
    </div>
</td>
<td style="vertical-align:bottom; padding-bottom:0.1rem;">
    <button type="submit" class="dugme">Filtriraj</button>
    <a href="PrijaveLista.php" class="dugme dugme-sekundarno">Poništi</a>
</td>
</tr>
</table>
</form>

<table class="podaci">
<thead>
<tr>
    <th>Broj prijave</th>
    <th>Datum</th>
    <?php if ($jeAdmin): ?><th>Student</th><?php endif; ?>
    <th>Broj stavki</th>
    <th>Status</th>
    <th class="bez-stampe">Akcije</th>
</tr>
</thead>
<tbody>
<?php if (count($prijave) === 0): ?>
    <tr><td colspan="6">Nema prijava koje odgovaraju zadatim filterima.</td></tr>
<?php endif; ?>
<?php foreach ($prijave as $p): ?>
    <tr>
        <td><?= h($p['BROJPRIJAVE']) ?></td>
        <td><?= h($p['DATUMPRIJAVE']) ?></td>
        <?php if ($jeAdmin): ?>
            <td><?= h($p['IME'] . ' ' . $p['PREZIME']) ?> (<?= h($p['BROJINDEKSA']) ?>)</td>
        <?php endif; ?>
        <td><?= (int)$p['BROJSTAVKI'] ?></td>
        <td><span class="oznaka-status"><?= h($p['NAZIVSTATUSA']) ?></span></td>
        <td class="bez-stampe">
            <a href="PrijavaDetalji.php?id=<?= (int)$p['IDPRIJAVE'] ?>" class="dugme dugme-sekundarno">Detalji</a>
            <?php if ($jeAdmin || (int)$p['IDKORISNIKA'] === (int)$_SESSION['idkorisnika']): ?>
                <a href="PrijavaIzmeniForm.php?id=<?= (int)$p['IDPRIJAVE'] ?>" class="dugme">Izmeni</a>
                <a href="PrijavaObrisi.php?id=<?= (int)$p['IDPRIJAVE'] ?>" class="dugme dugme-opasnost"
                   onclick="return confirm('Obrisati ovu prijavu?');">Obriši</a>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require 'delovi/kraj.php'; ?>
