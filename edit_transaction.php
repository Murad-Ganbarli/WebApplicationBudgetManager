<!DOCTYPE html>
<!-- edit_transaction.php -->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            transition-duration: 0.4s;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: white;
            color: black;
            border: 2px solid #4CAF50;
        }

        a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #007BFF;
            padding: 10px 20px;
            background-color: #f2f2f2;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>

<?php
require("connectDB.inc.php");

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve transaction details based on the transaction ID
$transactionID = $_GET['id'];
$sql = "SELECT transactions.*, categories.category, payment_methods.paymentMethod 
        FROM transactions 
        LEFT JOIN categories ON transactions.categoryID = categories.categoryID 
        LEFT JOIN payment_methods ON transactions.idPayment = payment_methods.idPayment
        WHERE transactionID = $transactionID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>

    <h2>Edit Transaction</h2>

    <form action="update_transaction.php" method="post">
        <label for="amount">Amount (€):</label>
        <input type="text" id="amount" name="amount" value="<?php echo $row['amount']; ?>" required><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo $row['date']; ?>" required><br>

        <label for="categoryID">Category:</label>
        <select id="categoryID" name="categoryID" required>
            <?php
            $categories = mysqli_query($conn, "SELECT * FROM categories");
            while ($category = mysqli_fetch_assoc($categories)) {
                $selected = ($category['categoryID'] == $row['categoryID']) ? 'selected' : '';
                echo "<option value='{$category['categoryID']}' $selected>{$category['category']}</option>";
            }
            ?>
        </select><br>

        <label for="idPayment">Payment Method:</label>
        <select id="idPayment" name="idPayment" required>
            <?php
            $payments = mysqli_query($conn, "SELECT * FROM payment_methods");
            while ($payment = mysqli_fetch_assoc($payments)) {
                $selected = ($payment['idPayment'] == $row['idPayment']) ? 'selected' : '';
                echo "<option value='{$payment['idPayment']}' $selected>{$payment['paymentMethod']}</option>";
            }
            ?>
        </select><br>

        <input type="hidden" name="transactionID" value="<?php echo $row['transactionID']; ?>">

        <input type="submit" value="Save Changes">
    </form>

    <a href="transactions_list.php">Go back to Transactions List</a>

    <?php
} else {
    echo "Transaction not found";
}

$conn->close();
?>

</body>
</html>
