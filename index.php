<?php
include 'includes/functions.php';
include 'includes/header.php';

$bmi_result = null;
$tdee_result = null;

if (isset($_POST['calc_bmi'])) {
    $bmi_result = kalkulatorBMI($_POST['weight'], $_POST['height']);
}

if (isset($_POST['calc_tdee'])) {
    $tdee_result = obliczTDEE($_POST['plec'], $_POST['weight'], $_POST['height'], $_POST['age'], $_POST['activity']);
}

if (isset($_GET['delete_plan'])) {
    $path = "cwiczenia/" . $_GET['delete_plan'];
    if (is_dir($path)) {
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) { unlink($path . "/" . $file); }
        rmdir($path);
    }
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete_ex'])) {
    $path = "cwiczenia/" . $_GET['plan'] . "/" . $_GET['file'];
    if (file_exists($path)) { unlink($path); }
    header("Location: index.php");
    exit;
}

if (isset($_POST['submit_workout'])) {
    $path = "cwiczenia/" . htmlspecialchars($_POST['plan_name']);
    if (!file_exists($path)) { mkdir($path, 0777, true); }
    $details = "Sety: {$_POST['sets']} | Powt: {$_POST['reps']} | Czas: {$_POST['time']} min";
    file_put_contents($path . "/" . htmlspecialchars($_POST['ex_name']) . ".txt", $details);
    header("Location: index.php");
    exit;
}
?>

<section id="calculators">
    <h2>Kalkulatory</h2>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <form method="POST" style="flex: 1; min-width: 250px;">
            <h3>BMI</h3>
            <input type="number" step="0.1" name="weight" placeholder="Waga kg" required>
            <input type="number" name="height" placeholder="Wzrost cm" required>
            <button type="submit" name="calc_bmi">Oblicz BMI</button>
            <?php if ($bmi_result) echo "<p>Twoje BMI: <strong>$bmi_result</strong></p>"; ?>
        </form>

        <form method="POST" style="flex: 1; min-width: 250px;">
            <h3>TDEE (Zapotrzebowanie)</h3>
            <select name="plec"><option value="m">Mężczyzna</option><option value="k">Kobieta</option></select>
            <input type="number" name="age" placeholder="Wiek" required>
            <input type="number" name="weight" placeholder="Waga kg" required>
            <input type="number" name="height" placeholder="Wzrost cm" required>
            <select name="activity">
                <option value="1.2">Brak aktywności</option>
                <option value="1.375">Lekka (1-2 treningi)</option>
                <option value="1.55">Średnia (3-4 treningi)</option>
                <option value="1.725">Duża (codziennie)</option>
            </select>
            <button type="submit" name="calc_tdee">Oblicz TDEE</button>
            <?php if ($tdee_result) echo "<p>Twoje TDEE: <strong>$tdee_result kcal</strong></p>"; ?>
        </form>
    </div>
</section>

<section id="add-workout">
    <h2>Dodaj Ćwiczenie</h2>
    <form method="POST">
        <input type="text" name="plan_name" placeholder="Nazwa Planu (np. FBW)" required>
        <input type="text" name="ex_name" id="ex_input" placeholder="Nazwa Ćwiczenia" required>
        <select onchange="document.getElementById('ex_input').value = this.value">
            <option value="">Szybki wybór...</option>
            <option value="Wyciskanie leżąc">Wyciskanie leżąc</option>
            <option value="Przysiad">Przysiad</option>
            <option value="Martwy ciąg">Martwy ciąg</option>
            <option value="Podciąganie">Podciąganie</option>
            <option value="Wyciskanie żołnierskie">Wyciskanie żołnierskie</option>
        </select>
        <input type="number" name="sets" placeholder="Serie">
        <input type="number" name="reps" placeholder="Powtórzenia">
        <input type="number" name="time" placeholder="Czas (min)">
        <button type="submit" name="submit_workout">Zapisz do planu</button>
    </form>
</section>

<section id="workout-list">
    <h2>Twoje Plany Treningowe</h2>
    <?php
    $dir = 'cwiczenia/';
    if (is_dir($dir)) {
        $folders = array_diff(scandir($dir), array('.', '..'));
        foreach ($folders as $folder) {
            if (is_dir($dir.$folder)) {
                echo "<div class='plan-card'><h3>$folder <a href='index.php?delete_plan=$folder' onclick=\"return confirm('Usunąć plan?')\">[Usuń Plan]</a></h3><ul>";
                $files = array_diff(scandir($dir.$folder), array('.', '..'));
                foreach ($files as $file) {
                    if ($file !== '.gitkeep') {
                        $info = file_get_contents($dir.$folder."/".$file);
                        echo "<li><strong>".str_replace('.txt','',$file)."</strong>: $info <a href='index.php?delete_ex=1&plan=$folder&file=$file'>[X]</a></li>";
                    }
                }
                echo "</ul></div>";
            }
        }
    }
    ?>
</section>
</body>
</html>