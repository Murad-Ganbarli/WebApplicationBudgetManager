<?php
require("connectDB.inc.php");

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieves form values
$transactionID = $_POST['transactionID'];
$amount = $_POST['amount'];
$date = $_POST['date'];
$categoryID = $_POST['categoryID'];
$idPayment = $_POST['idPayment'];

// Updates the transaction record
$sql = "UPDATE transactions SET amount='$amount', date='$date', categoryID='$categoryID', idPayment='$idPayment' WHERE transactionID='$transactionID'";

if ($conn->query($sql) === TRUE) {
    echo "<script>window.location.href = 'transactions_list.php';</script>";
} else {
    echo "Error updating transaction: " . $conn->error;
}


// Close the database connection
$conn->close();
?>
