<?php
//jgraph_data.php

require("connectDB.inc.php");


$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch jpgraph data from the database
$sql = "SELECT date, SUM(amount) AS totalAmount FROM transactions GROUP BY date";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $jpgraphData = array();
    while ($row = $result->fetch_assoc()) {

        // Format the data as needed for jpgraph
        $jpgraphData[] = array(
            'date' => $row['date'],
            'totalAmount' => $row['totalAmount']
        );
    }

    // Output the jpgraph data as JSON
    echo json_encode($jpgraphData);
} else {
    echo json_encode(array()); // Return an empty array if no data
}

$conn->close();
?>