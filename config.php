<?php
$polaczenie=mysqli_connect("localhost","root","","sklep_internetowy");
if (!$polaczenie) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}else{
    mysqli_set_charset($polaczenie, "utf8");    
}
?>