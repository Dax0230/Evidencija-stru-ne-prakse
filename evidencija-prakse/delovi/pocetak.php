<?php

if (!isset($_SESSION['korisnik'])) {
    header('Location: PrijavaNaSistem.php');
    exit;
}

require_once __DIR__ . '/pomocne.php';
$jeAdmin = ($_SESSION['uloga'] === 'administrator');
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title><?= isset($naslovStranice) ? h($naslovStranice) : 'Evidencija stručne prakse' ?></title>
<?php include __DIR__ . '/../css/stil.php'; ?>
</head>
<body>

<?php include __DIR__ . '/zaglavlje.php'; ?>

<table class="raspored" style="width:100%;">
<tr>
<td style="width:2%;"></td>
<td style="width:18%; vertical-align:top; padding:1rem 0;">
    <?php include $jeAdmin ? __DIR__ . '/menilevoadmin.php' : __DIR__ . '/menilevo.php'; ?>
</td>
<td style="width:2%;"></td>
<td style="width:76%; vertical-align:top; padding:1rem 0;">
    <div class="sadrzaj-glavni">
