<?php
//delete_transaction.php

require("connectDB.inc.php");

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if transaction ID is provided in the URL
if (isset($_GET['id'])) {
    $transactionID = $_GET['id'];

    // Delete the transaction from the database
    $sql = "DELETE FROM transactions WHERE transactionID = $transactionID";

    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href = 'transactions_list.php';</script>";
    } else {
        echo "Error deleting transaction: " . $conn->error;
    }
} else {
    echo "Transaction ID not provided.";
}

$conn->close();
?>
