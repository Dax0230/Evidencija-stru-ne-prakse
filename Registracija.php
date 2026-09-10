<?php
session_start();
require 'delovi/pomocne.php';

if (isset($_SESSION['korisnik'])) {
    header('Location: Pocetna.php');
    exit;
}

$greska = isset($_GET['greska']) ? $_GET['greska'] : '';
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Registracija</title>
<?php include 'css/stil.php'; ?>
</head>
<body>

<?php include 'delovi/zaglavlje.php'; ?>

<table class="raspored" style="width:100%;">
<tr>
<td style="width:20%;"></td>
<td style="width:60%; padding:2rem 0;">

    <div class="sadrzaj-glavni" style="max-width:420px; margin:0 auto;">
        <h2>Registracija studenta</h2>

        <?php if ($greska): ?>
            <div class="poruka-greska"><?= h($greska) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['uspeh'])): ?>
            <div class="poruka-uspeh">Registracija uspešna. Možete se <a href="PrijavaNaSistem.php">prijaviti</a>.</div>
        <?php else: ?>
        <form class="forma" method="post" action="RegistracijaObrada.php">
            <div class="polje">
                <label for="ime">Ime</label>
                <input type="text" id="ime" name="ime" required>
            </div>
            <div class="polje">
                <label for="prezime">Prezime</label>
                <input type="text" id="prezime" name="prezime" required>
            </div>
            <div class="polje">
                <label for="brojIndeksa">Broj indeksa</label>
                <input type="text" id="brojIndeksa" name="brojIndeksa" required>
            </div>
            <div class="polje">
                <label for="fakultet">Fakultet</label>
                <input type="text" id="fakultet" name="fakultet" required>
            </div>
            <div class="polje">
                <label for="studijskiProgram">Studijski program</label>
                <input type="text" id="studijskiProgram" name="studijskiProgram" required>
            </div>
            <div class="polje">
                <label for="telefon">Telefon</label>
                <input type="text" id="telefon" name="telefon" required>
            </div>
            <div class="polje">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="polje">
                <label for="sifra">Lozinka</label>
                <input type="password" id="sifra" name="sifra" required minlength="6">
            </div>
            <button type="submit" class="dugme">Registruj se</button>
        </form>
        <p style="margin-top:1rem;">Već imate nalog? <a href="PrijavaNaSistem.php">Prijavite se</a></p>
        <?php endif; ?>
    </div>

</td>
<td style="width:20%;"></td>
</tr>
</table>

<?php include 'delovi/footer.php'; ?>
</body>
</html>
