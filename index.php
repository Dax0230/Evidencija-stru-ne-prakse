<?php
session_start();
if (isset($_SESSION['korisnik'])) {
    header('Location: Pocetna.php');
} else {
    header('Location: PrijavaNaSistem.php');
}
exit;
