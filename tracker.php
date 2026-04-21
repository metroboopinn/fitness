<?php
include 'includes/functions.php';
include 'includes/header.php';

$plik_json = 'data/kalorie.json';

if (!file_exists($plik_json)) {
    $start_data = ["dzisiejsze_kalorie" => 0, "ostatni_update" => "", "historia" => []];
    file_put_contents($plik_json, json_encode($start_data));
}

$data = json_decode(file_get_contents($plik_json), true);
$dzisiaj = date('Y-m-d');

if ($data['ostatni_update'] !== $dzisiaj) {
    $data['dzisiejsze_kalorie'] = 0;
    $data['ostatni_update'] = $dzisiaj;
    $data['historia'] = [];
    file_put_contents($plik_json, json_encode($data));
}

if (isset($_POST['dodaj'])) {
    $kcal = (int)$_POST['kcal'];
    $opis = htmlspecialchars($_POST['opis']);
    
    $data['dzisiejsze_kalorie'] += $kcal;
    $data['historia'][] = ["opis" => $opis, "kcal" => $kcal, "godzina" => date('H:i')];
    
    file_put_contents($plik_json, json_encode($data));
    header("Location: tracker.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Licznik Kalorii</title>
</head>
<body>
    <section id="calorie-tracker">
        <h2>Licznik Kalorii (Dziś: <?php echo $dzisiaj; ?>)</h2>
    
        <div class="summary">
            <h3>Suma: <?php echo $data['dzisiejsze_kalorie']; ?> kcal</h3>
        </div>

        <form action="tracker.php" method="POST">
            <input type="text" name="opis" required>
            <input type="number" name="kcal" required>
            <button type="submit" name="dodaj">Dodaj</button>
        </form>

        <ul>
            <?php foreach ($data['historia'] as $wpis): ?>
                <li>
                    <strong><?php echo $wpis['godzina']; ?></strong> - 
                    <?php echo $wpis['opis']; ?>: 
                    <?php echo $wpis['kcal']; ?> kcal
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</body>
</html>