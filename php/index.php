<?php
require 'db.php';

// Zpracování hledání a filtrace
$search = $_GET['search'] ?? '';
$vydavatel_id = $_GET['vydavatel_id'] ?? '';

// Základní SQL dotaz (Výpis všech her včetně vydavatelství)
$sql = "SELECT HRA.IDH, HRA.nazev_hry, HRA.rok_vydani, HRA.cena, VYDAVATELSTVI.nazev_vydavatelstvi 
        FROM HRA 
        JOIN VYDAVATELSTVI ON HRA.IDV = VYDAVATELSTVI.IDV 
        WHERE 1=1";
$params = [];

// Vyhledávání podle názvu (Bod 3)
if (!empty($search)) {
    $sql .= " AND HRA.nazev_hry LIKE ?";
    $params[] = "%$search%";
}

// Filtrace podle vydavatelství (Bod 5)
if (!empty($vydavatel_id)) {
    $sql .= " AND HRA.IDV = ?";
    $params[] = $vydavatel_id;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$hry = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Získání vydavatelů pro roletku
$vydavatele = $pdo->query("SELECT * FROM VYDAVATELSTVI")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Správa deskových her</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Databáze deskových her</h1>
        
        <div class="search-bar">
            <form method="GET" action="index.php">
                <div class="form-group">
                    <label>Vyhledat hru podle názvu:</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Zadejte název...">
                </div>
                <div class="form-group">
                    <label>Filtrovat podle vydavatelství:</label>
                    <select name="vydavatel_id">
                        <option value="">-- Všechna vydavatelství --</option>
                        <?php foreach($vydavatele as $v): ?>
                            <option value="<?= $v['IDV'] ?>" <?= ($vydavatel_id == $v['IDV']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($v['nazev_vydavatelstvi']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn">Hledat / Filtrovat</button>
                <a href="index.php" class="btn" style="background:#7f8c8d;">Zrušit filtry</a>
                <a href="akce_hra.php" class="btn" style="float:right;">+ Přidat novou hru</a>
            </form>
        </div>

        <h2>Seznam her</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Název hry</th>
                <th>Rok vydání</th>
                <th>Cena (Kč)</th>
                <th>Vydavatelství</th>
                <th>Akce</th>
            </tr>
            <?php foreach($hry as $hra): ?>
            <tr>
                <td><?= $hra['IDH'] ?></td>
                <td><?= htmlspecialchars($hra['nazev_hry']) ?></td>
                <td><?= $hra['rok_vydani'] ?></td>
                <td><?= number_format($hra['cena'], 2, ',', ' ') ?> Kč</td>
                <td><?= htmlspecialchars($hra['nazev_vydavatelstvi']) ?></td>
                <td>
                    <a href="akce_hra.php?edit_id=<?= $hra['IDH'] ?>" class="btn btn-edit">Upravit</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($hry)): ?>
                <tr><td colspan="6">Žádné záznamy nebyly nalezeny.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>