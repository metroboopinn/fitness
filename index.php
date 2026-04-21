<?php
include 'includes/functions.php';
include 'includes/header.php';

$bmi_result = null;

if (isset($_GET['delete_plan'])) {
    $plan = $_GET['delete_plan'];
    $path = "cwiczenia/" . $plan;

    if (is_dir($path)) {
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) {
            unlink($path . "/" . $file);
        }
        rmdir($path);
    }
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete_ex'])) {
    $plan = $_GET['plan'];
    $file = $_GET['file'];
    $path = "cwiczenia/" . $plan . "/" . $file;

    if (file_exists($path)) {
        unlink($path);
    }
    header("Location: index.php");
    exit;
}

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
                echo "<div style='margin-bottom: 20px;'>";
                echo "<h3>$folder ";
                echo "<a href='index.php?delete_plan=$folder' style='color:orange; font-size:12px; text-decoration:none;' onclick=\"return confirm('Usunąć cały plan?')\">[Usuń Plan]</a>";
                echo "</h3><ul>";
                
                $files = array_diff(scandir($dir . $folder), array('.', '..'));
                foreach ($files as $file) {
                    if ($file !== '.gitkeep') {
                        $info = file_get_contents($dir . $folder . "/" . $file);
                        echo "<li>";
                        echo "<strong>" . str_replace('.txt', '', $file) . "</strong> - $info ";
                        echo "<a href='index.php?delete_ex=1&plan=$folder&file=$file' style='color:red; text-decoration:none; margin-left:10px;'>[Usuń]</a>";
                        echo "</li>";
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