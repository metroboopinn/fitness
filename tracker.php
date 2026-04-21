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
    
    $nowy_wpis = [
        "id" => uniqid(),
        "opis" => $opis,
        "kcal" => $kcal,
        "godzina" => date('H:i')
    ];
    
    $data['dzisiejsze_kalorie'] += $kcal;
    $data['historia'][] = $nowy_wpis;
    
    file_put_contents($plik_json, json_encode($data));
    header("Location: tracker.php");
    exit;
}

if (isset($_GET['delete_meal'])) {
    $target_id = $_GET['delete_meal'];
    
    foreach ($data['historia'] as $klucz => $wpis) {
        if ($wpis['id'] === $target_id) {
            $data['dzisiejsze_kalorie'] -= $wpis['kcal'];
            unset($data['historia'][$klucz]);
            break;
        }
    }
    
    $data['historia'] = array_values($data['historia']);
    file_put_contents($plik_json, json_encode($data));
    header("Location: tracker.php");
    exit;
}

if (isset($_POST['reset_manual'])) {
    $data['dzisiejsze_kalorie'] = 0;
    $data['historia'] = [];
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
            <form action="tracker.php" method="POST" style="display:inline;">
                <button type="submit" name="reset_manual" onclick="return confirm('Resetuj dzień?')">Resetuj wszystko</button>
            </form>
        </div>

        <form action="tracker.php" method="POST">
            <input type="text" name="opis" placeholder="Posiłek" required>
            <input type="number" name="kcal" placeholder="kcal" required>
            <button type="submit" name="dodaj">Dodaj</button>
        </form>

        <h3>Historia:</h3>
        <ul>
            <?php foreach ($data['historia'] as $wpis): ?>
                <li>
                    <strong><?php echo $wpis['godzina']; ?></strong> - 
                    <?php echo $wpis['opis']; ?>: 
                    <?php echo $wpis['kcal']; ?> kcal
                    <a href="tracker.php?delete_meal=<?php echo $wpis['id']; ?>" style="color:red; text-decoration:none; margin-left:10px;">[X]</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</body>
</html>