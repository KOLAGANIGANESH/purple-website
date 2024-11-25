<?php
// Start session to get user info (if necessary)
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register"; // Your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all orders from the orders table
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            font-size: 2rem;
            color: #333;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #667eea;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .view-button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .view-button:hover {
            background-color: #45a049;
        }

        .no-orders {
            text-align: center;
            color: #888;
            font-size: 1.2rem;
        }

        .order-details {
            padding: 15px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            width: 90%;
            max-width: 600px;
        }
    </style>
</head>
<body>

<h2>All Orders</h2>

<!-- Table to display orders -->
<div class="orders">
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Product IDs</th>
                    <th>Quantities</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['product_ids']; ?></td>
                        <td><?php echo $row['quantities']; ?></td>
                        <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo $row['status']; ?></td>

                        <td>
                            <!-- View order details link -->
                            <a href="view_order.php?order_id=<?php echo $row['id']; ?>" class="view-button">View</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-orders">No orders found.</p>
    <?php endif; ?>
</div>

</body>
</html>
