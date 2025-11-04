<?php
// 1. Dołączenie pliku konfiguracyjnego
// Ten plik zawiera połączenie z bazą danych ($polaczenie)
require_once('config.php');

// 2. Zapytanie SELECT do bazy
// Pobieramy wszystkie produkty z tabeli 'produkty'
$query = "SELECT * FROM produkt ORDER BY id_produktu ASC";

// Wykonanie zapytania i sprawdzenie czy się powiodło
$result = mysqli_query($polaczenie, $query);
if (!$result) {
    die("Błąd zapytania: " . mysqli_error($polaczenie));
}

// 3. Sprawdzenie liczby wyników
// Zliczamy ile produktów zostało znalezionych
$liczba_produktow = mysqli_num_rows($result);

// Jeśli nie ma produktów, wyświetlimy odpowiedni komunikat później w HTML
// Zmienna $liczba_produktow będzie używana do sprawdzenia czy są produkty
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKLEP</title>
      <link rel="stylesheet" type="text/css" href="css/styl.css">
</head>
<body>
    <header>
    <h1>Lista produktów</h1>
       <p>
            <a href="zamowienia.php"><button>zamówienia</button></a>
        </p>
    </header>
    <!-- Pole wyszukiwania -->
    <input type="text" id="searchInput" placeholder="Wyszukaj produkt..." onkeyup="filterTable()">
    
    <!-- Tabela produktów -->
    <?php if($liczba_produktow > 0) { ?>
        <table id="tabela">
            <thead>
                <tr>
                    <th>ID Produktu</th>
                    <th>Nazwa Produktu</th>
                    <th>Opis Produktu</th>
                    <th>Cena</th>
                    <th>Ilość na stanie</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Pętla przez wszystkie znalezione produkty
                // mysqli_fetch_assoc pobiera jeden wiersz jako tablicę asocjacyjną
                // Klucze tablicy odpowiadają nazwom kolumn w bazie danych
                while($row = mysqli_fetch_assoc($result)) {
                // Formatowanie ceny
                $cena_formatowana = number_format($row['cena'], 2, ',', ' ') . ' PLN';
                
                // Skrócenie opisu jeśli jest dłuższy niż 50 znaków
                $opis_skrocony = substr($row['opis'], 0, 50);
                if(strlen($row['opis']) > 50) {
                    $opis_skrocony .= '...';
                }
                
                echo "<tr>";
                echo "<td>" . $row['id_produktu'] . "</td>";
                echo "<td>" . $row['nazwa'] . "</td>";
                echo "<td>" . $opis_skrocony . "</td>";
                echo "<td>" . $cena_formatowana . "</td>";
                echo "<td>" . $row['ilosc_na_stanie'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <?php } else { ?>
        <p>Brak produktów w bazie danych.</p>
    <?php } ?>

    <!-- Skrypt JavaScript do filtrowania tabeli -->
    <script src="skrypt.js"></script>

</body>
</html>
<?php
// Zamknięcie połączenia
mysqli_close($polaczenie);
?>