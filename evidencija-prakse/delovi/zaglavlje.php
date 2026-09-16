<div class="baner">
    <h1>Evidencija prijava studenata za stručnu praksu</h1>
    <?php if (isset($_SESSION['korisnik'])): ?>
        <div class="korisnik-info">
            Prijavljen: <?= htmlspecialchars($_SESSION['korisnik']) ?> (<?= htmlspecialchars($_SESSION['uloga']) ?>)
            &nbsp;|&nbsp; <a href="Odjava.php" style="color:#fff;">Odjava</a>
        </div>
    <?php endif; ?>
</div>
