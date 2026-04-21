<?php
function kalkulatorBMI($waga, $wzrost) {
    if ($wzrost > 0) {
        $wzrostM = $wzrost / 100;
        return round($waga / ($wzrostM ** 2), 2);
    }
    return 0;
}
?>