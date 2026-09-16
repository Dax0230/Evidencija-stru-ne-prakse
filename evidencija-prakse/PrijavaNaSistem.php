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
<title>Prijava na sistem</title>
<?php include 'css/stil.php'; ?>
</head>
<body>

<?php include 'delovi/zaglavlje.php'; ?>

<table class="raspored" style="width:100%;">
<tr>
<td style="width:20%;"></td>
<td style="width:60%; padding:2rem 0;">

    <div class="sadrzaj-glavni" style="max-width:420px; margin:0 auto;">
        <h2>Prijava na sistem</h2>

        <?php if ($greska === 'pogresno'): ?>
            <div class="poruka-greska">Pogrešan e-mail ili lozinka.</div>
        <?php endif; ?>

        <form class="forma" method="post" action="PrijavaNaSistemObrada.php">
            <div class="polje">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="polje">
                <label for="sifra">Lozinka</label>
                <input type="password" id="sifra" name="sifra" required>
            </div>
            <button type="submit" class="dugme">Prijavi se</button>
        </form>
        <p style="margin-top:1rem;">Nemate nalog? <a href="Registracija.php">Registrujte se</a></p>
    </div>

</td>
<td style="width:20%;"></td>
</tr>
</table>

<?php include 'delovi/footer.php'; ?>
</body>
</html>
