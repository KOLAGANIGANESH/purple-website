<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    
    session_destroy();
        header("Location: login.php");
    exit();  
}
?>

<?php
$conn = new mysqli('localhost', 'root', '', 'login_register');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['product_image'])) {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_FILES['product_image'];

    $imageName = time() . '_' . basename($productImage['name']);
    $targetDir = 'uploads/';
    $targetFile = $targetDir . $imageName;
    
    if (move_uploaded_file($productImage['tmp_name'], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $productName, $productPrice, $imageName);
        $stmt->execute();
        $stmt->close();
    }
}

if (isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            transition: margin-left 0.3s ease;
        }

        .topbar {
            position: fixed;
            top: 0;
            width: 100%;
            height: 60px;
            background-color: #333;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
        }

        .topbar h1 {
            font-size: 20px;
            margin-left: 10px;
        }

        .toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            width: 50px; 
            height: 40px; 
        }

        .bar {
            display: block;
            width: 100%;
            height: 4px;
            background-color: white;
            border-radius: 2px;
            transition: 0.3s; 
        }

        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #2196f3cc;
            padding-top: 80px; 
            position: fixed;
            left: -200px; 
            transition: left 0.3s ease;
        }

        .sidebar.open {
            left: 0; 
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #45a049;
        }

        .main {
            margin-left: 0;
            padding: 80px 20px 20px 20px;
            width: 100%;
            transition: margin-left 0.3s ease; 
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
        }

        .product-item {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 200px;
        }

        .product-item img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product-item h6 {
            margin: 10px 0 5px;
            font-size: 18px;
        }

        .product-item p {
            color: #555;
        }

        form label {
            margin-top: 10px;
        }

        form input,
        form button {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

.main {
    width: 80%;
    margin: 0 auto;
    padding: 30px;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;

    margin-top: 50px;

}
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

label {
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="number"],
input[type="file"] {
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="text"]:focus,
input[type="number"]:focus,
input[type="file"]:focus {
    outline: none;
    border-color: #007bff;
}

button[type="submit"] {
    padding: 10px 20px;
    font-size: 1.1rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Product List Section */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 40px;
}

.product-item {
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.product-item img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.product-item h6 {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
    margin-top: 10px;
}

.product-item p {
    font-size: 1rem;
    color: #777;
    margin-top: 5px;
}

.product-item button {
    margin-top: 10px;
    padding: 8px 15px;
    font-size: 0.9rem;
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.product-item button:hover {
    background-color: #c0392b;
}

    </style>
</head>
<body>

    <!-- Topbar -->
    <div class="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <h1>Admin Dashboard</h1>
    </div>

    <div class="sidebar" id="sidebar">
        <a href="#">Dashboard</a>
        <a href="edit.php">Manage Products</a>
        <a href="orders.php">Orders</a>
        <a href="index.php?logout=true">Logout</a>

        </div>

    <div class="main">
        <h2>Add New Product</h2>
        <form action="admin_dashboard.php" method="post" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" required>

            <label for="product_price">Product Price:</label>
            <input type="number" step="0.01" name="product_price" id="product_price" required>

            <label for="product_image">Product Image:</label>
            <input type="file" name="product_image" id="product_image" required>

            <button type="submit">Add Product</button>
        </form>

        <h2>Product List</h2>
        <div class="product-grid">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="product-item">
                    <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h6><?php echo $row['name']; ?></h6>
                    <p>$<?php echo $row['price']; ?></p>
                    <form action="admin_dashboard.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_product">Delete</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("open");

            var main = document.querySelector(".main");
            if (sidebar.classList.contains("open")) {
                main.style.marginLeft = "200px";
            } else {
                main.style.marginLeft = "0"; 
            }
        }
    </script>

</body>
</html>

<?php $conn->close(); ?>
