<?php
//delete_all_transactions.php

require("connectDB.inc.php");

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM transactions";

if ($conn->query($sql)) {
    echo "All transactions deleted successfully!";
} else {
    echo "Error deleting transactions: " . $conn->error;
}

$conn->close();

?>
