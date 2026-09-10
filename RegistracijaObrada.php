<?php
session_start();

$ime = isset($_POST['ime']) ? trim($_POST['ime']) : '';
$prezime = isset($_POST['prezime']) ? trim($_POST['prezime']) : '';
$brojIndeksa = isset($_POST['brojIndeksa']) ? trim($_POST['brojIndeksa']) : '';
$fakultet = isset($_POST['fakultet']) ? trim($_POST['fakultet']) : '';
$studijskiProgram = isset($_POST['studijskiProgram']) ? trim($_POST['studijskiProgram']) : '';
$telefon = isset($_POST['telefon']) ? trim($_POST['telefon']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$sifra = isset($_POST['sifra']) ? $_POST['sifra'] : '';

$greska = '';
if ($ime === '' || $prezime === '' || $brojIndeksa === '' || $fakultet === '' || $studijskiProgram === '' || $telefon === '' || $email === '' || $sifra === '') {
    $greska = 'Sva polja su obavezna.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $greska = 'E-mail adresa nije ispravnog formata.';
} elseif (mb_strlen($sifra) < 6) {
    $greska = 'Lozinka mora imati bar 6 karaktera.';
}

require 'klase/Konekcija.php';
require 'klase/Tabela.php';
require 'klase/DBKorisnik.php';

$KonekcijaObjekat = new Konekcija('klase/BaznaParametriKonekcije.xml');
$KonekcijaObjekat->connect();

if ($greska === '' && $KonekcijaObjekat->konekcijaDB) {
    $KorisnikObjekat = new DBKorisnik($KonekcijaObjekat, 'KORISNIK');

    if ($KorisnikObjekat->DaLiPostojiKorisnikSaEmailom($email)) {
        $greska = 'Nalog sa ovom e-mail adresom već postoji.';
    } else {
        $KorisnikObjekat->Ime = $ime;
        $KorisnikObjekat->Prezime = $prezime;
        $KorisnikObjekat->BrojIndeksa = $brojIndeksa;
        $KorisnikObjekat->Fakultet = $fakultet;
        $KorisnikObjekat->StudijskiProgram = $studijskiProgram;
        $KorisnikObjekat->Telefon = $telefon;
        $KorisnikObjekat->Email = $email;
        $KorisnikObjekat->Sifra = password_hash($sifra, PASSWORD_DEFAULT);
        $greskaSQL = $KorisnikObjekat->RegistrujKorisnika();
        if (!empty($greskaSQL)) {
            $greska = 'Greška prilikom snimanja: ' . $greskaSQL;
        }
    }
}

$KonekcijaObjekat->disconnect();

if ($greska !== '') {
    header('Location: Registracija.php?greska=' . urlencode($greska));
} else {
    header('Location: Registracija.php?uspeh=1');
}
exit;
