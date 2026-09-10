<?php
session_start();

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$sifra = isset($_POST['sifra']) ? $_POST['sifra'] : '';

require 'klase/Konekcija.php';
require 'klase/Tabela.php';
require 'klase/DBKorisnik.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

$prijavljen = false;

if ($KonekcijaObjekat->konekcijaDB) {
    $KorisnikObjekat = new DBKorisnik($KonekcijaObjekat, 'KORISNIK');
    $Red = $KorisnikObjekat->UcitajKorisnikaPoEmailu($email);

    if ($Red !== null && password_verify($sifra, $Red['SIFRA'])) {
        $_SESSION['idkorisnika'] = $Red['IDKORISNIKA'];
        $_SESSION['korisnik'] = $Red['IME'] . ' ' . $Red['PREZIME'];
        $_SESSION['uloga'] = $Red['ULOGA'];
        $prijavljen = true;
    }
}

$KonekcijaObjekat->disconnect();

if ($prijavljen) {
    header('Location: Pocetna.php');
} else {
    header('Location: PrijavaNaSistem.php?greska=pogresno');
}
exit;
