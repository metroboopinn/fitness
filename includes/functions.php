<?php
function kalkulatorBMI($waga, $wzrost) {
    if ($wzrost > 0) {
        $wzrostM = $wzrost / 100;
        return round($waga / ($wzrostM ** 2), 2);
    }
    return 0;
}

function obliczTDEE($plec, $waga, $wzrost, $wiek, $aktywnosc) {
    $s = ($plec == 'm') ? 5 : -161;
    $bmr = (10 * $waga) + (6.25 * $wzrost) - (5 * $wiek) + $s;
    return round($bmr * $aktywnosc);
}
?>