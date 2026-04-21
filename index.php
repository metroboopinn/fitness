<?php
include 'includes/functions.php';
include 'includes/header.php';

$bmi_result = null;

if (isset($_POST['calc_bmi'])) {
    $waga = (float)$_POST['weight'];
    $wzrost = (float)$_POST['height'];
    $bmi_result = kalkulatorBMI($waga, $wzrost);
}

if (isset($_POST['submit_workout'])) {
    $plan = htmlspecialchars($_POST['plan_name']);
    $exercise = htmlspecialchars($_POST['ex_name']);
    $details = "Sety: " . $_POST['sets'] . " | Powtorzenia: " . $_POST['reps'] . " | Czas: " . $_POST['time'] . " min";

    $path = "cwiczenia/" . $plan;

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    $filePath = $path . "/" . $exercise . ".txt";
    file_put_contents($filePath, $details);

    header("Location: index.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>FitFlow</title>
</head>
<body>
    <section id="bmi-calculator">
        <h2>Kalkulator BMI</h2>
        <form action="index.php" method="POST">
            <input type="number" step="0.1" name="weight" placeholder="Waga kg" required>
            <input type="number" name="height" placeholder="Wzrost cm" required>
            <button type="submit" name="calc_bmi">Oblicz</button>
        </form>

        <?php if ($bmi_result !== null): ?>
            <p>BMI: <strong><?php echo $bmi_result; ?></strong></p>
        <?php endif; ?>
    </section>

    <hr>

    <section id="add-workout">
        <h2>Dodaj Trening</h2>
        <form action="index.php" method="POST">
            <input type="text" name="plan_name" placeholder="Folder" required>
            <input type="text" name="ex_name" placeholder="Cwiczenie" required>
            <input type="number" name="sets" placeholder="Serie" required>
            <input type="number" name="reps" placeholder="Powtorzenia" required>
            <input type="number" name="time" placeholder="Minuty" required>
            <button type="submit" name="submit_workout">Zapisz</button>
        </form>
    </section>

    <hr>

    <section id="workout-list">
        <h2>Plany</h2>
        <?php
        $dir = 'cwiczenia/';
        if (is_dir($dir)) {
            $folders = array_diff(scandir($dir), array('.', '..'));
            foreach ($folders as $folder) {
                if (is_dir($dir . $folder)) {
                    echo "<h3>$folder</h3><ul>";
                    $files = array_diff(scandir($dir . $folder), array('.', '..'));
                    foreach ($files as $file) {
                        $info = file_get_contents($dir . $folder . "/" . $file);
                        echo "<li>" . str_replace('.txt', '', $file) . ": $info</li>";
                    }
                    echo "</ul>";
                }
            }
        }
        ?>
    </section>
</body>
</html>