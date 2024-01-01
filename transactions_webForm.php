<?php
session_start();
require("connectDB.inc.php");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli($server, $username, $password, $database);
    $mysqli->set_charset("utf8");
} catch (mysqli_sql_exception $e) {
    echo "MySQLi Error Code: " . $e->getCode() . "<br />";
    echo "Exception Msg: " . $e->getMessage();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizes and validate form data
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $categoryID = filter_input(INPUT_POST, 'categoryID', FILTER_VALIDATE_INT);
    $idPayment = filter_input(INPUT_POST, 'idPayment', FILTER_VALIDATE_INT);

    if ($amount !== false && $date !== false && $categoryID !== false && $idPayment !== false) {
        // Prepares and executes the SQL statement to insert the transaction
        $query = "INSERT INTO transactions (amount, date, categoryID, idPayment) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('dsii', $amount, $date, $categoryID, $idPayment);
        $stmt->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- transaction Form-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            max-width: 100%;
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

        input,
        select {
            width: calc(100% - 16px);
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

        .nav-section {
            text-align: center;
            margin-top: 20px;
        }

        .nav-link {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }

        @media screen and (max-width: 600px) {
            form {
                padding: 10px;
            }

            input,
            select {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <form id="budgetForm" action="process_form.php" method="POST">
        <h2>Enter New Transaction</h2>

        <!-- Form fields -->
        <label for="amount">Amount (€):</label>
        <input type="number" id="amount" name="amount" required>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>

        <label for="categoryID">Category:</label>
        <select id="categoryID" name="categoryID" required>
            <!-- Category options are dynamically generated from dataJSON.js -->
        </select>

        <label for="idPayment">Payment Method:</label>
        <select id="idPayment" name="idPayment" required>
            <!-- Payment method options are dynamically generated from dataJSON.js -->
        </select>

        <input type="submit" value="Submit">
    </form>

    <!-- Navigation link to Transactions List -->
    <div class="nav-section">
        <a href="transactions_list.php" class="nav-link">Go to Transactions List</a>
    </div>

    <script src="dataJSON.js"></script>
    <script>
        // Dynamically populates category options
        var categorySelect = document.getElementById('categoryID');
        categories.forEach(function (category) {
            var option = document.createElement('option');
            option.value = category.categoryID;
            option.text = category.category;
            categorySelect.add(option);
        });

        // Dynamically populates payment method options
        var paymentSelect = document.getElementById('idPayment');
        payments.forEach(function (payment) {
            var option = document.createElement('option');
            option.value = payment.idPayment;
            option.text = payment.paymentMethod;
            paymentSelect.add(option);
        });
    </script>

</body>

</html>
