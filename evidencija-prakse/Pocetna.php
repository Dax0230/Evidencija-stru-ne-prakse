<?php
session_start();
$naslovStranice = 'Početna';
require 'delovi/pocetak.php';
?>

<h1>Dobrodošli, <?= h($_SESSION['korisnik']) ?></h1>

<?php if ($jeAdmin): ?>
    <p>Prijavljeni ste kao <strong>administrator</strong>. Ovde možete pregledati sve prijave studenata za stručnu praksu.</p>
    <p><a href="PrijaveLista.php" class="dugme">Pregled svih prijava</a></p>
<?php else: ?>
    <p>Prijavljeni ste kao <strong>student</strong>. Ovde možete podneti novu prijavu za stručnu praksu ili pregledati svoje postojeće prijave.</p>
    <p>
        <a href="PrijavaUnosForm.php" class="dugme">Nova prijava</a>
        <a href="PrijaveLista.php" class="dugme dugme-sekundarno">Moje prijave</a>
    </p>
<?php endif; ?>

<?php require 'delovi/kraj.php'; ?>
