<?php

// Database credentials
require("connectDB.inc.php");

$conn = new mysqli($server, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$amount = $_POST['amount'];
$date = $_POST['date'];
$categoryID = $_POST['categoryID'];
$idPayment = $_POST['idPayment'];

// Inserts data into the transactions table
$sql = "INSERT INTO transactions (amount, date, categoryID, idPayment) VALUES ('$amount', '$date', '$categoryID', '$idPayment')";

if ($conn->query($sql) === TRUE) {
    // Data inserted successfully, redirect to transactions list
    header("Location: transactions_list.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
