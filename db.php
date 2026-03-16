<?php
$conn = mysqli_connect("database-1.c9igmcsmmcld.ap-south-1.rds.amazonaws.com", "admin", "xandy08", "kayaking");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
