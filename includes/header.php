<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <button id="theme-toggle">Zmień tryb</button>

    <nav>
        <ul>
            <li><a href="index.php" style="color: var(--accent-color)">Dashboard & Treningi</a></li>
            <li><a href="tracker.php" style="color: var(--accent-color)">Licznik Kalorii</a></li>
        </ul>
    </nav>

    <script>
        const toggleBtn = document.getElementById('theme-toggle');
        const currentTheme = localStorage.getItem('theme') || 'light';

        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }

        toggleBtn.addEventListener('click', () => {
            let theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'dark') {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>