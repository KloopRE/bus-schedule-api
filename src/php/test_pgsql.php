<?php
// Пытаемся подключиться к базе данных PostgreSQL
$conn = pg_connect("host=127.0.0.1 dbname=bus_schedule user=postgres password=05011983ztd");

if ($conn) {
    echo "Соединение с базой данных установлено!";
} else {
    echo "Ошибка при подключении к базе данных.";
}
?>
