<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions List</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    form, table {
        width: 100%;
        margin-top: 20px;
        overflow-x: auto;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .button-container {
        margin-top: 20px;
        text-align: center;
    }

    .button-container button {
        margin: 0 5px;
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

    .button-container button:hover {
        background-color: white;
        color: black;
        border: 2px solid #4CAF50;
    }

    #summaryType, #search {
        width: calc(100% - 20px);
        padding: 8px;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    #jpgraph-container {
        margin-top: 20px;
    }

    @media screen and (max-width: 600px) {
        /* Adjust styles for smaller screens (16:9) */
        th, td {
            font-size: 14px;
            padding: 6px;
        }

        .button-container button {
            padding: 8px 16px;
            font-size: 14px;
        }

        #summaryType, #search {
            width: 100%;
            margin-bottom: 10px;
        }
    } 
</style>

    <script>
    function applyFilter() {
    var filter = document.getElementById('filter').value;

    // Clear previous content
    document.getElementById('jpgraph-container').innerHTML = "";

    if (filter === 'jpgraph') {
        // Display the jpgraph (replace with the actual URL)
        displayJpgraph();
    } else {
        // Display the filtered table
        filterTransactions(filter);
    }
}

function displayJpgraph() {
    // Use AJAX to fetch jpgraph data (replace with the actual URL)
    $.ajax({
        url: 'jpgraph_data.php',
        method: 'POST',
        success: function(response) {
            // Parse the response data and generate the jpgraph
            var jpgraphData = JSON.parse(response);
            
            // Call a function to generate the graph using jpgraph
            generateJpgraph(jpgraphData);
        },
        error: function(error) {
            alert('Error fetching jpgraph data: ' + error);
        }
    });
}

</script>
</head>
<body>

<h2>Transactions List</h2>
<!-- Dropdown for choosing summary type -->
<form id="summaryForm" action="summary.php" method="get">
    <label for="summaryType">Choose Summary Type:</label>
    <select id="summaryType" name="type">
        <option value="annual">Annual</option>
        <option value="monthly">Monthly</option>
        <option value="categorical">Categorical</option>
        <option value="methodical">Methodical</option>
    </select>
    <input type="submit" value="Show Summary">
</form>

<!-- Search Bar -->
<label for="search">Search:</label>
<input type="text" id="search" onkeyup="performSearch()" placeholder="Enter keywords...">

<!--a div to hold the jpgraph -->
<div id="jpgraph-container"></div>

<table>
    <thead>
        <tr>
            <th>Transaction ID</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Category</th>
            <th>Payment Method</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
            require("connectDB.inc.php");

            $conn = new mysqli($server, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve transactions data from the database with category and payment method names
            $sql = "SELECT transactions.*, categories.category, payment_methods.paymentMethod 
                    FROM transactions 
                    LEFT JOIN categories ON transactions.categoryID = categories.categoryID 
                    LEFT JOIN payment_methods ON transactions.idPayment = payment_methods.idPayment";
            
            $result = $conn->query($sql);

            // Displaying transactions in the table
            if ($result->num_rows > 0) {                
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["transactionID"] . "</td>";
                    echo "<td>" . $row["amount"] . "</td>";
                    echo "<td>" . $row["date"] . "</td>";
                    echo "<td>" . $row["category"] . "</td>";
                    echo "<td>" . $row["paymentMethod"] . "</td>";
                    echo "<td><a href='edit_transaction.php?id=" . $row["transactionID"] . "'>Edit</a></td>";
                    echo "<td><a href='delete_transaction.php?id=". $row["transactionID"]. "'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No transactions found</td></tr>";
            }

            $conn->close();
        ?>
    </tbody>
</table>

<div class="button-container">
    <button onclick="goToForm()">Go to Form</button>
    <button onclick="deleteAllTransactions()">Delete All Transactions</button>
</div>

<script>
    function goToForm() {
        window.location.href = "transactions_webForm.php";
    }

    function deleteAllTransactions() {
        // confirmation
        var confirmation = confirm("Are you sure you want to delete all transactions?");
        
        if (confirmation) {
            $.ajax({
                url: 'delete_all_transactions.php',
                method: 'POST',
                success: function(response) {
                    alert('All transactions deleted successfully!');
                    // Refresh the page
                    location.reload();
                },
                error: function(error) {
                    alert('Error deleting transactions: ' + error);
                }
            });
        }
    }

    function performSearch() {
        // Gets the input value from the search bar
        var input = document.getElementById('search').value.toLowerCase();

        // Gets all rows in the table body
        var rows = document.querySelectorAll('tbody tr');

        // Loops through each row and hide/show based on the search input
        rows.forEach(function(row) {
            var rowData = row.textContent.toLowerCase();

            // If the row contains the search input, shows it; otherwise, hides it
            if (rowData.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
</body>
</html>
