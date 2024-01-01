<?php
require_once('jpgraph/src/jpgraph.php');
require_once('jpgraph/src/jpgraph_bar.php');
require_once('jpgraph/src/jpgraph_line.php');
require("connectDB.inc.php");

// Gets the chosen summary type from the URL parameter
$summaryType = isset($_GET['type']) ? $_GET['type'] : '';

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initializes data arrays
$dataIncome = array();
$dataOutcome = array();
$labels = array();

// Generates graph based on the chosen summary type
switch ($summaryType) {
    case 'annual':
        $query = "SELECT YEAR(date) as year, 
                          SUM(CASE WHEN categories.accountingID = 2 THEN amount ELSE 0 END) as income,
                          SUM(CASE WHEN categories.accountingID = 1 THEN amount ELSE 0 END) as outcome 
                  FROM transactions 
                  LEFT JOIN categories ON transactions.categoryID = categories.categoryID
                  GROUP BY YEAR(date) 
                  ORDER BY year";

        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['year'];
            $dataIncome[] = $row['income'];
            $dataOutcome[] = abs($row['outcome']);
        }
        break;

    case 'monthly':
        $query = "SELECT DATE_FORMAT(date, '%m/%y') as month_year, 
                          SUM(CASE WHEN categories.accountingID = 2 THEN amount ELSE 0 END) as income,
                          SUM(CASE WHEN categories.accountingID = 1 THEN amount ELSE 0 END) as outcome 
                  FROM transactions 
                  LEFT JOIN categories ON transactions.categoryID = categories.categoryID
                  WHERE DATE_FORMAT(date, '%m/%y') != '00/00'
                  GROUP BY month_year 
                  ORDER BY STR_TO_DATE(month_year, '%m/%y')";

        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['month_year'];
            $dataIncome[] = $row['income'];
            $dataOutcome[] = abs($row['outcome']);
        }
        break;

    case 'categorical':
        $query = "SELECT categories.category, 
                          SUM(CASE WHEN categories.accountingID = 2 THEN transactions.amount ELSE 0 END) as income,
                          SUM(CASE WHEN categories.accountingID = 1 THEN transactions.amount ELSE 0 END) as outcome
                  FROM transactions 
                  LEFT JOIN categories ON transactions.categoryID = categories.categoryID 
                  GROUP BY categories.category 
                  ORDER BY income DESC";

        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['category'];
            $dataIncome[] = $row['income'];
            $dataOutcome[] = abs($row['outcome']);
        }
        break;

    case 'methodical':
        $query = "SELECT payment_methods.paymentMethod, 
                          SUM(CASE WHEN categories.accountingID = 2 THEN transactions.amount ELSE 0 END) as income,
                          SUM(CASE WHEN categories.accountingID = 1 THEN transactions.amount ELSE 0 END) as outcome
                  FROM transactions 
                  LEFT JOIN payment_methods ON transactions.idPayment = payment_methods.idPayment 
                  LEFT JOIN categories ON transactions.categoryID = categories.categoryID
                  GROUP BY payment_methods.paymentMethod 
                  ORDER BY income DESC";

        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $labels[] = $row['paymentMethod'];
            $dataIncome[] = $row['income'];
            $dataOutcome[] = abs($row['outcome']);
        }
        break;

    default:
        echo 'Invalid summary type';
        exit;
}

$conn->close();

// Graph setup
$graph = new Graph(1500, 1000);
$graph->SetScale("textlin");

$barPlotIncome = new BarPlot($dataIncome);
$barPlotIncome->SetFillColor('yellow');
$barPlotIncome->SetLegend('Income');

$barPlotOutcome = new BarPlot($dataOutcome);
$barPlotOutcome->SetFillColor('blue');
$barPlotOutcome->SetLegend('Outcome');

$groupBarPlot = new GroupBarPlot(array($barPlotIncome, $barPlotOutcome));
$graph->Add($groupBarPlot);

$graph->xaxis->SetTickLabels($labels);

$graph->yaxis->SetLabelFormatCallback('formatEuroLabel');
$graph->yaxis->title->Set('Amount (Euros)');
$graph->yaxis->title->SetMargin(45);

$graph->SetMargin(100, 30, 20, 50);

$graph->Stroke();

function formatEuroLabel($aVal) {
    return '' . number_format($aVal, 2);
}

$graph->legend->Pos(0.5, 0.95, 'center', 'top');

$graph->Stroke();
?>
