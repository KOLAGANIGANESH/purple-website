<?php
// Start session to get user_id from the session
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

// Check if user is logged in (user_id should be stored in session)
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    exit(); // Exit if the user is not logged in
}

// Fetch all products from the products table
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Handle placing an order (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the order form
    $product_ids = $_POST['product_ids'];  // Array of selected product IDs
    $quantities = $_POST['quantities'];    // Array of corresponding quantities
    $total_price = 0;

    // Get the user_id from the session
    $user_id = $_SESSION['user_id'];

    // Calculate the total price of the order
    foreach ($product_ids as $index => $product_id) {
        $quantity = $quantities[$index];
        // Fetch product details to get the price
        $product_query = "SELECT price FROM products WHERE id = $product_id";
        $product_result = $conn->query($product_query);
        $product = $product_result->fetch_assoc();
        $total_price += $product['price'] * $quantity;
    }

    // Insert the order into the database
    $product_ids_str = implode(",", $product_ids);  // Convert array to comma-separated string
    $quantities_str = implode(",", $quantities);    // Convert array to comma-separated string
    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_ids, quantities, total_price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $user_id, $product_ids_str, $quantities_str, $total_price);
    $stmt->execute();

    echo "Order placed successfully!";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View and Place Orders</title>
    <style>
/* Global Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    margin-top: 40px;
    font-size: 2rem;
    color: #333;
}

/* Products Section */
.products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px;
    padding: 20px;
}

.product {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.product:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

.product img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #ddd;
}

.product h3 {
    font-size: 1.2rem;
    margin: 10px;
    color: #333;
}

.product p {
    font-size: 1rem;
    color: #555;
    margin: 10px;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 10px;
}

input[type="number"] {
    padding: 10px;
    width: 60px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 10px;
    font-size: 1rem;
}

button {
    padding: 12px 20px;
    background-color: #667eea;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #4e5ce6;
}

form button {
    width: 100%;
    max-width: 180px;
    margin-top: 10px;
}

/* No Products Found Message */
.no-products {
    text-align: center;
    color: #888;
    font-size: 1.2rem;
}

/* Form Container */
.form-container {
    margin-top: 30px;
    padding: 20px;
    border: 1px solid #ddd;
    background-color: #fff;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-container input[type="text"],
.form-container input[type="email"],
.form-container input[type="password"],
.form-container input[type="number"] {
    padding: 12px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 100%;
    box-sizing: border-box;
}

.form-container button {
    padding: 12px 20px;
    background-color: #667eea;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.form-container button:hover {
    background-color: #4e5ce6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .products {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }

    .form-container {
        padding: 15px;
    }

    .product {
        padding: 15px;
    }
}

    </style>
</head>
<body>

<h2>Products</h2>

<!-- Display Products -->
<div class="products">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>
                 <center>   <img src='uploads/" . $row['image'] . "' alt='" . $row['name'] . "' style='width: 100px; height: 100px;'> </center>
                    <h3>" . $row['name'] . "</h3>
                    <p>Price: $ " . $row['price'] . "</p>
                    <form action='' method='post'>
                        <input type='hidden' name='product_ids[]' value='" . $row['id'] . "'>
                        <label for='quantity'>Quantity:</label>
                        <input type='number' name='quantities[]' value='1' min='1'>
                        <button type='submit'>Place Order</button>
                    </form>
                  </div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>

</body>
</html>
