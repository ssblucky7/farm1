<?php
// Define the API endpoint
$apiUrl = 'https://kalimatimarket.gov.np/api/daily-prices/en';

// Fetch data from the API
$response = file_get_contents($apiUrl);

// Check if the API request was successful
if ($response === FALSE) {
    die('Error occurred while fetching data from the API.');
}

// Decode the JSON response into a PHP array
$data = json_decode($response, true);

// Check if the data was decoded successfully
if ($data === NULL) {
    die('Error decoding JSON data.');
}

// Extract the date and prices from the data
$date = $data['date'] ?? 'N/A';
$prices = $data['prices'] ?? [];

// Determine the sorting order based on user selection
$sortOrder = $_GET['sort'] ?? 'asc'; // Default to ascending

// Function to compare prices for sorting
function comparePrices($a, $b) {
    global $sortOrder;
    if ($sortOrder === 'asc') {
        return $a['avgprice'] <=> $b['avgprice'];
    } else {
        return $b['avgprice'] <=> $a['avgprice'];
    }
}

// Sort the prices array if not empty
if (!empty($prices)) {
    usort($prices, 'comparePrices');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kalimati Vegetable and Fruits Rates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px 40px; /* Increased left and right margins */
        }
        h1 {
            text-align: center;
        }
        .controls {
            text-align: center;
            margin-bottom: 20px;
        }
        .controls a {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
        }
        .controls a.active {
            background-color: #0056b3;
        }
        .table-container {
            margin: 20px 0;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            cursor: pointer;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        @media (max-width: 600px) {
            body {
                margin: 10px;
            }
            h1 {
                font-size: 1.5em;
            }
            th, td {
                padding: 8px 10px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <h1>Kalimati Vegetable and Fruits Rates</h1>
    <p>Date: <?php echo htmlspecialchars($date); ?></p>
    <div class="controls">
        <a href="?sort=asc" class="<?php echo $sortOrder === 'asc' ? 'active' : ''; ?>">Cheapest</a>
        <a href="?sort=desc" class="<?php echo $sortOrder === 'desc' ? 'active' : ''; ?>">Most Expensive</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th onclick="sortTable(0)">Commodity</th>
                    <th onclick="sortTable(1)">Unit</th>
                    <th onclick="sortTable(2)">Minimum Price (NPR)</th>
                    <th onclick="sortTable(3)">Maximum Price (NPR)</th>
                    <th onclick="sortTable(4)">Average Price (NPR)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prices)): ?>
                    <tr>
                        <td colspan="5">No data available.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($prices as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['commodityname']); ?></td>
                            <td><?php echo htmlspecialchars($item['commodityunit']); ?></td>
                            <td><?php echo htmlspecialchars($item['minprice']); ?></td>
                            <td><?php echo htmlspecialchars($item['maxprice']); ?></td>
                            <td><?php echo htmlspecialchars($item['avgprice']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to sort the table columns
        function sortTable(columnIndex) {
            const table = document.querySelector("table tbody");
            const rows = Array.from(table.rows);
            const isAscending = table.getAttribute("data-sort-order") === "asc";
            const direction = isAscending ? 1 : -1;

            rows.sort((a, b) => {
                const aText = a.cells[columnIndex].textContent.trim();
                const bText = b.cells[columnIndex].textContent.trim();
                return aText.localeCompare(bText, undefined, {numeric: true}) * direction;
            });

            // Append sorted rows to the table body
            rows.forEach(row => table.appendChild(row));

            // Toggle the sort order
            table.setAttribute("data-sort-order", isAscending ? "desc" : "asc");
        }
    </script>
</body>
</html>
