<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an order ID is provided in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch the order details from the database
    $sql = "SELECT * FROM orders WHERE id = $order_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        echo "Order not found!";
        exit();
    }

    // Check if the status is being updated
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
        $new_status = $_POST['status'];

        // Update the order status in the database if the status is pending
        if ($order['status'] == 'pending') {
            $update_sql = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";
            if ($conn->query($update_sql) === TRUE) {
                echo "Order status updated successfully!";
                // Refresh the page to reflect the new status
                header("Location: ".$_SERVER['REQUEST_URI']);
                exit();
            } else {
                echo "Error updating status: " . $conn->error;
            }
        } else {
            echo "Status cannot be updated as the order is not pending.";
        }
    }
} else {
    echo "No order ID provided!";
    exit();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
/* Reset some default browser styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f9;
    color: #333;
    line-height: 1.6;
    padding: 20px;
}

/* Main container for the order details */
.order-details {
    max-width: 900px;
    margin: 20px auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Heading for the order details */
.order-details h3 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 20px;
    color: #333;
}

/* Style for each order detail paragraph */
.order-details p {
    font-size: 1.1rem;
    margin: 12px 0;
    color: #555;
}

/* Bold labels for the order details */
.order-details p strong {
    color: #333;
    font-weight: bold;
}

/* Back button styling */
.back-button {
    display: inline-block;
    padding: 12px 25px;
    background-color: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
    margin-top: 20px;
    font-size: 1rem;
}

.back-button:hover {
    background-color: #4e5ce6;
}

/* Status update form styling */
.status-update-form {
    margin-top: 20px;
}

.status-update-form select {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1rem;
}

.status-update-form button {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    margin-left: 10px;
}

.status-update-form button:hover {
    background-color: #218838;
}

/* Responsive design for small screens */
@media (max-width: 768px) {
    .order-details {
        padding: 20px;
        margin: 15px;
    }

    .order-details h3 {
        font-size: 1.6rem;
    }

    .order-details p {
        font-size: 1rem;
    }

    .back-button {
        padding: 10px 20px;
        font-size: 0.9rem;
    }

    .status-update-form select,
    .status-update-form button {
        width: 100%;
        margin-top: 10px;
    }
}
    </style>
</head>
<body>

<div class="order-details">
    <h3>Order Details</h3>

    <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
    <p><strong>User ID:</strong> <?php echo $order['user_id']; ?></p>
    <p><strong>Product IDs:</strong> <?php echo $order['product_ids']; ?></p>
    <p><strong>Quantities:</strong> <?php echo $order['quantities']; ?></p>
    <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>
    <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
    <p><strong>Status:</strong> <?php echo $order['status']; ?></p>

    <!-- Display the form to update the status only if the current status is 'pending' -->
    <?php if ($order['status'] == 'pending'): ?>
    <div class="status-update-form">
        <form method="POST">
            <label for="status">Update Status:</label>
            <select name="status" id="status">
                <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="completed" <?php echo ($order['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="shipped" <?php echo ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                <option value="canceled" <?php echo ($order['status'] == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
            </select>
            <button type="submit">Update Status</button>
        </form>
    </div>
    <?php endif; ?>
    
    <!-- Back button to view all orders -->
    <a href="orders.php" class="back-button">Back to Orders</a>
</div>

</body>
</html>
