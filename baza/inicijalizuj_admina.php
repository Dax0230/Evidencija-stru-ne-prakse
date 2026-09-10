<?php

require_once __DIR__ . '/../klase/Konekcija.php';
require_once __DIR__ . '/../klase/Tabela.php';
require_once __DIR__ . '/../klase/DBKorisnik.php';

$KonekcijaObjekat = new Konekcija(__DIR__ . '/../klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

if (!$KonekcijaObjekat->konekcijaDB) {
    die('Neuspesna konekcija na bazu podataka.');
}

$KorisnikObjekat = new DBKorisnik($KonekcijaObjekat, 'KORISNIK');

if ($KorisnikObjekat->DaLiPostojiKorisnikSaEmailom('admin@fakultet.rs')) {
    echo 'Administratorski nalog vec postoji.';
    exit;
}

$hash = password_hash('admin123', PASSWORD_DEFAULT);
$hashEsc = $KorisnikObjekat->Escapiraj($hash);

$SQL = "INSERT INTO `" . $KonekcijaObjekat->KompletanNazivBazePodataka . "`.`KORISNIK`
        (PREZIME, IME, BROJINDEKSA, EMAIL, SIFRA, ULOGA)
        VALUES ('Administrator', 'Admin', NULL, 'admin@fakultet.rs', '$hashEsc', 'administrator')";
$greska = $KorisnikObjekat->IzvrsiAktivanSQLUpit($SQL);

$KonekcijaObjekat->disconnect();

if ($greska) {
    echo 'Greska: ' . htmlspecialchars($greska);
} else {
    echo 'Administratorski nalog je uspesno kreiran.<br>Email: admin@fakultet.rs<br>Lozinka: admin123<br>';
    echo '<strong>Obavezno obrisite ovaj fajl sa servera.</strong>';
}
