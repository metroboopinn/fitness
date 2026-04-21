# fitness# Projekt: Kalkulator Fitness

Aplikacja webowa ułatwiająca kontrolowanie diety oraz planowanie treningów. Projekt został wykonany w języku PHP i nie wymaga zewnętrznej bazy danych, ponieważ wszystkie informacje są zapisywane w plikach tekstowych oraz plikach JSON.

## Główne funkcje
* Kalkulator BMI oraz zapotrzebowania kalorycznego (TDEE).
* Licznik kalorii z podziałem na białko, węglowodany, cukry i tłuszcze.
* Możliwość dodawania i usuwania posiłków z dziennego zestawienia.
* Tworzenie planów treningowych i dodawanie do nich ćwiczeń.
* Zarządzanie plikami (usuwanie wybranych ćwiczeń lub całych folderów z planami).
* Wybór między trybem jasnym a ciemnym za pomocą przycisku.

## Wykorzystane technologie
* PHP (logika aplikacji i obsługa plików).
* HTML i CSS (struktura i wygląd strony).
* JavaScript (obsługa przełącznika trybu nocnego).
* JSON (przechowywanie danych o kaloriach).

## Struktura plików
* index.php - strona główna z kalkulatorami i treningami.
* tracker.php - dziennik posiłków.
* style.css - arkusz stylów z motywami.
* folder data - plik z danymi o kaloriach.
* folder cwiczenia - foldery z planami treningowymi.