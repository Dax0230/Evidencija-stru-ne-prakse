<?php

?>
<div class="obrazac-prijave">
    <table class="obrazac-zaglavlje">
        <tr>
            <td class="obrazac-logo"><img src="images/univerzitet.png" alt="Znak univerziteta"></td>
            <td class="obrazac-naziv">
                Univerzitet u Novom Sadu<br>
                Tehnički fakultet „Mihajlo Pupin”<br>
                Zrenjanin
            </td>
            <td class="obrazac-logo"><img src="images/fakultet.png" alt="Znak fakulteta"></td>
        </tr>
    </table>

    <p class="obrazac-podnaslov">Prijava za stručnu praksu:</p>

    <table class="obrazac-polja">
        <tr><td class="obrazac-label">Broj prijave:</td><td class="obrazac-vrednost"><?= h($obrazac['BrojPrijave']) ?></td></tr>
        <tr><td class="obrazac-label">Datum prijave:</td><td class="obrazac-vrednost"><?= h($obrazac['DatumPrijave']) ?></td></tr>
        <tr><td class="obrazac-label">Ime i prezime studenta:</td><td class="obrazac-vrednost"><?= h($obrazac['Ime'] . ' ' . $obrazac['Prezime']) ?></td></tr>
        <tr><td class="obrazac-label">Broj indeksa:</td><td class="obrazac-vrednost"><?= h($obrazac['BrojIndeksa']) ?></td></tr>
        <tr><td class="obrazac-label">Fakultet:</td><td class="obrazac-vrednost"><?= h($obrazac['Fakultet']) ?></td></tr>
        <tr><td class="obrazac-label">Studijski program:</td><td class="obrazac-vrednost"><?= h($obrazac['StudijskiProgram']) ?></td></tr>
        <tr><td class="obrazac-label">Telefon:</td><td class="obrazac-vrednost"><?= h($obrazac['Telefon']) ?></td></tr>
        <tr><td class="obrazac-label">E-mail:</td><td class="obrazac-vrednost"><?= h($obrazac['Email']) ?></td></tr>
        <tr><td class="obrazac-label">Status prijave:</td><td class="obrazac-vrednost"><?= h($obrazac['NazivStatusa']) ?></td></tr>
    </table>

    <table class="obrazac-tabela">
        <thead>
            <tr>
                <th style="width:9%;">Redni broj</th>
                <th>Kompanija</th>
                <th>Pozicija</th>
                <th style="width:16%;">Trajanje prakse</th>
                <th style="width:16%;">Status prijave</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($obrazac['Stavke'] as $i => $st): ?>
                <tr>
                    <td style="text-align:center;"><?= $i + 1 ?></td>
                    <td><?= h($st['KOMPANIJA']) ?></td>
                    <td><?= h($st['POZICIJA']) ?></td>
                    <td><?= h($st['TRAJANJEPRAKSE']) ?></td>
                    <td><?= h($obrazac['NazivStatusa']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($obrazac['Stavke']) === 0): ?>
                <tr><td colspan="5">&nbsp;</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p class="obrazac-napomena-naslov">NAPOMENA</p>
    <div class="obrazac-napomena-polje">
        <?php

            $napomeneZaIspis = [];
            foreach ($obrazac['Stavke'] as $i => $st) {
                if (!empty($st['NAPOMENA'])) {
                    $napomeneZaIspis[] = ($i + 1) . '. ' . $st['NAPOMENA'];
                }
            }
        ?>
        <?php if (count($napomeneZaIspis) > 0): ?>
            <?php foreach ($napomeneZaIspis as $red): ?>
                <p class="obrazac-napomena-red"><?= h($red) ?></p>
            <?php endforeach; ?>
        <?php else: ?>
            &nbsp;
        <?php endif; ?>
    </div>
</div>
