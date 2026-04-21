<?php
include 'includes/functions.php';
include 'includes/header.php';

$plik_json = 'data/kalorie.json';
if (!file_exists($plik_json)) {
    $start = [
        "dzisiejsze_kalorie" => 0, 
        "makro" => ["bialko"=>0, "weglowodany"=>0, "cukry"=>0, "tluszcze"=>0], 
        "ostatni_update" => "", 
        "historia" => []
    ];
    file_put_contents($plik_json, json_encode($start));
}

$data = json_decode(file_get_contents($plik_json), true);
$dzisiaj = date('Y-m-d');

if ($data['ostatni_update'] !== $dzisiaj) {
    $data['dzisiejsze_kalorie'] = 0;
    $data['makro'] = ["bialko"=>0, "weglowodany"=>0, "cukry"=>0, "tluszcze"=>0];
    $data['ostatni_update'] = $dzisiaj;
    $data['historia'] = [];
    file_put_contents($plik_json, json_encode($data));
}

if (isset($_POST['dodaj'])) {
    $kcal = (int)$_POST['kcal'];
    $b = (int)$_POST['bialko']; 
    $w = (int)$_POST['wegle']; 
    $c = (int)$_POST['cukry']; 
    $t = (int)$_POST['tluszcze'];
    
    $entry = [
        "id" => uniqid(),
        "opis" => htmlspecialchars($_POST['opis']),
        "kcal" => $kcal,
        "makro" => ["bialko"=>$b, "weglowodany"=>$w, "cukry"=>$c, "tluszcze"=>$t],
        "godzina" => date('H:i')
    ];
    
    $data['dzisiejsze_kalorie'] += $kcal;
    $data['makro']['bialko'] += $b;
    $data['makro']['weglowodany'] += $w;
    $data['makro']['cukry'] += $c;
    $data['makro']['tluszcze'] += $t;
    $data['historia'][] = $entry;
    
    file_put_contents($plik_json, json_encode($data));
    header("Location: tracker.php");
    exit;
}

if (isset($_GET['delete_meal'])) {
    foreach ($data['historia'] as $k => $v) {
        if ($v['id'] === $_GET['delete_meal']) {
            $data['dzisiejsze_kalorie'] -= $v['kcal'];
            $data['makro']['bialko'] -= $v['makro']['bialko'];
            $data['makro']['weglowodany'] -= $v['makro']['weglowodany'];
            $data['makro']['cukry'] -= $v['makro']['cukry'];
            $data['makro']['tluszcze'] -= $v['makro']['tluszcze'];
            unset($data['historia'][$k]);
            break;
        }
    }
    $data['historia'] = array_values($data['historia']);
    file_put_contents($plik_json, json_encode($data));
    header("Location: tracker.php");
    exit;
}
?>

<section id="calorie-summary">
    <h2>Licznik Kalorii i Makroskładników</h2>
    <div style="font-size: 1.2em; margin-bottom: 10px;">
        Suma: <strong style="color: var(--accent-color);"><?php echo $data['dzisiejsze_kalorie']; ?> kcal</strong>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">
        <div class="macro-box">Białko: <strong><?php echo $data['makro']['bialko']; ?>g</strong></div>
        <div class="macro-box">Węglowodany: <strong><?php echo $data['makro']['weglowodany']; ?>g</strong></div>
        <div class="macro-box">Cukry: <strong><?php echo $data['makro']['cukry']; ?>g</strong></div>
        <div class="macro-box">Tłuszcze: <strong><?php echo $data['makro']['tluszcze']; ?>g</strong></div>
    </div>
</section>

<section id="add-meal">
    <h3>Dodaj Posiłek</h3>
    <form method="POST">
        <input type="text" name="opis" placeholder="Co zjadłeś?" required>
        <input type="number" name="kcal" placeholder="Kalorie (kcal)" required>
        <input type="number" name="bialko" placeholder="Białko (g)" required>
        <input type="number" name="wegle" placeholder="Węglowodany (g)" required>
        <input type="number" name="cukry" placeholder="Cukry (g)" required>
        <input type="number" name="tluszcze" placeholder="Tłuszcze (g)" required>
        <button type="submit" name="dodaj">Dodaj do dziennika</button>
    </form>
</section>

<section id="meal-history">
    <h3>Historia posiłków (Dziś)</h3>
    <ul>
        <?php foreach ($data['historia'] as $wpis): ?>
            <li>
                <strong><?php echo $wpis['godzina']; ?></strong> - <?php echo $wpis['opis']; ?>: 
                <strong><?php echo $wpis['kcal']; ?> kcal</strong>
                <br>
                <small style="opacity: 0.8;">B: <?php echo $wpis['makro']['bialko']; ?>g | W: <?php echo $wpis['makro']['weglowodany']; ?>g | C: <?php echo $wpis['makro']['cukry']; ?>g | T: <?php echo $wpis['makro']['tluszcze']; ?>g</small>
                <a href="tracker.php?delete_meal=<?php echo $wpis['id']; ?>" style="margin-left: 15px;">[Usuń]</a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

</body>
</html>