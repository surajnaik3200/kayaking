<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

if (!$conn) {
    echo "DB connection failed: " . mysqli_connect_error();
    exit;
}

$result = mysqli_query($conn, "SHOW TABLES");
if (!$result) {
    echo "DB connected, but table query failed: " . mysqli_error($conn);
    exit;
}

echo "DB connection works. Tables:<br>";
while ($row = mysqli_fetch_array($result)) {
    echo htmlspecialchars($row[0]) . '<br>';
}

mysqli_close($conn);
