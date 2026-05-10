<?php
require 'db.php';

$edit_mode = false;
$id = $nazev = $rok = $cena = $idv = '';

// Pokud je v URL edit_id, načítáme data pro úpravu (UPDATE)
if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $id = $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM HRA WHERE IDH = ?");
    $stmt->execute([$id]);
    $hra = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($hra) {
        $nazev = $hra['nazev_hry'];
        $rok = $hra['rok_vydani'];
        $cena = $hra['cena'];
        $idv = $hra['IDV'];
    }
}

// Zpracování odeslaného formuláře (INSERT nebo UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazev = $_POST['nazev_hry'];
    $rok = $_POST['rok_vydani'];
    $cena = $_POST['cena'];
    $idv = $_POST['IDV'];

    if (isset($_POST['id_hry']) && !empty($_POST['id_hry'])) {
        // UPDATE (Bod 2)
        $stmt = $pdo->prepare("UPDATE HRA SET nazev_hry = ?, rok_vydani = ?, cena = ?, IDV = ? WHERE IDH = ?");
        $stmt->execute([$nazev, $rok, $cena, $idv, $_POST['id_hry']]);
    } else {
        // INSERT (Bod 1)
        $stmt = $pdo->prepare("INSERT INTO HRA (nazev_hry, rok_vydani, cena, IDV) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nazev, $rok, $cena, $idv]);
    }
    header("Location: index.php"); // Po uložení přesměrujeme zpět na výpis
    exit;
}

$vydavatele = $pdo->query("SELECT * FROM VYDAVATELSTVI")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title><?= $edit_mode ? 'Úprava hry' : 'Přidání hry' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1><?= $edit_mode ? 'Upravit záznam hry' : 'Přidat novou hru' ?></h1>
        <form method="POST" action="akce_hra.php">
            <?php if($edit_mode): ?>
                <input type="hidden" name="id_hry" value="<?= $id ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Název hry:</label>
                <input type="text" name="nazev_hry" required value="<?= htmlspecialchars($nazev) ?>">
            </div>
            <div class="form-group">
                <label>Rok vydání:</label>
                <input type="number" name="rok_vydani" value="<?= htmlspecialchars($rok) ?>">
            </div>
            <div class="form-group">
                <label>Cena (Kč):</label>
                <input type="number" step="0.01" name="cena" value="<?= htmlspecialchars($cena) ?>">
            </div>
            <div class="form-group">
                <label>Vydavatelství:</label>
                <select name="IDV" required>
                    <option value="">-- Vyberte vydavatelství --</option>
                    <?php foreach($vydavatele as $v): ?>
                        <option value="<?= $v['IDV'] ?>" <?= ($idv == $v['IDV']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['nazev_vydavatelstvi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn"><?= $edit_mode ? 'Uložit změny' : 'Přidat hru' ?></button>
            <a href="index.php" class="btn" style="background:#e74c3c;">Zpět</a>
        </form>
    </div>
</body>
</html>