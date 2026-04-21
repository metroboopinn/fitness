<?php
$teraz = date('Y-m-d');
$data = json__decode(file_get_contents('data/kalorie.json'), true);

if ($data['ostatni_update'] !== $czas) {
    $data['dzisiejsze_kalorie'] = 0;
    $data['ostatni_update'] = $czas;
    $data['historia'] = [];
}

if (isset($_POST['dodaj'])) {
    $data['dzisiejsze_kalorie'] += $_POST'kcal'];
    $data['historia'][] = $_POST['opis'];
    file_put_contents('data/kalorie.json', json_encode($data));
}
?>