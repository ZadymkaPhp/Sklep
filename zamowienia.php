<?php
// Dołączenie pliku konfiguracyjnego
require_once('config.php');

// Zapytanie SELECT do bazy
$query = "SELECT z.id_zamowienia, CONCAT(k.imie, ' ', k.nazwisko) AS klient, k.email, z.status, z.kwota_calkowita, z.data_zamowienia 
          FROM zamowienie z 
          JOIN klient k ON z.id_klienta = k.id_klienta 
          ORDER BY z.id_zamowienia ASC;";

// Wykonanie zapytania i sprawdzenie czy się powiodło
$result = mysqli_query($polaczenie, $query);
if (!$result) {
    die("Błąd zapytania: " . mysqli_error($polaczenie));
}

// Sprawdzenie liczby wyników
$liczba_zamowien = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamówienia - Sklep Internetowy</title>
    <link rel="stylesheet" type="text/css" href="css/styl.css">
</head>

<body>
    <header>
        <h1>Lista zamówień</h1>
        <p>
            <a href="index.php"><button>Produkty</button></a>
        </p>
    </header>

    <!-- Pole wyszukiwania -->
    <input type="text" id="searchInput" placeholder="Wyszukaj zamówienie..." onkeyup="filterTable()"><br>
    
    <!-- Tabela zamówień -->
    <?php if($liczba_zamowien > 0) { ?>
        <table id="tabela">
            <thead>
                <tr>
                    <th>ID Zamówienia</th>
                    <th>Klient</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Data zamówienia</th>
                    <th>Kwota całkowita</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($row = mysqli_fetch_assoc($result)) {
                    // Formatowanie daty
                    $data_formatowana = date('d.m.Y H:i', strtotime($row['data_zamowienia']));
                    
                    // Formatowanie kwoty
                    $kwota_formatowana = number_format($row['kwota_calkowita'], 2, ',', ' ') . ' PLN';
                    
                    // Ustawienie klasy CSS bezpośrednio na podstawie statusu
                    echo "<tr>";
                    echo "<td>" . $row['id_zamowienia'] . "</td>";
                    echo "<td>" . ($row['klient']) . "</td>";
                    echo "<td>" . ($row['email']) . "</td>";
                    // Przypisanie klasy CSS na podstawie statusu
                    if ($row['status'] == "Dostarczone") {
                        echo "<td class='status-dostarczone'>" . $row['status'] . "</td>";
                    } else if ($row['status'] == "W realizacji") {
                        echo "<td class='status-w-realizacji'>" . $row['status'] . "</td>";
                    } else if ($row['status'] == "Nowe") {
                        echo "<td class='status-nowe'>" . $row['status'] . "</td>";
                    } else if ($row['status'] == "Wysłane") {
                        echo "<td class='status-wyslane'>" . $row['status'] . "</td>";
                    } else {
                        echo "<td>" . $row['status'] . "</td>";
                    }
                    echo "<td>" . $data_formatowana . "</td>";
                    echo "<td class='kwota'>" . $kwota_formatowana . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>Brak zamówień w bazie danych.</p>
    <?php } ?>

    <!-- Skrypt JavaScript do filtrowania tabeli -->
    <script src="skrypt.js"></script>

</body>
</html>
<?php
// Zamknięcie połączenia
mysqli_close($polaczenie);
?>