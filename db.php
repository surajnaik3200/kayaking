<?php
/**
 * Database connection helper.
 *
 * This file sets $conn to a mysqli connection on success or null on failure.
 * Callers may check for $conn and show a friendly message if the database is
 * unavailable.
 */

$conn = mysqli_connect("database-1.c9igmcsmmcld.ap-south-1.rds.amazonaws.com", "admin", "kingxande08", "kayaking");
$dbError = null;
if (!$conn) {
    $dbError = mysqli_connect_error();
    $conn = null;
}
