<?php
$conn = mysqli_connect("localhost", "root", "", "pxkayaking");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
