<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'login_register');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details based on the id
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the product exists
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }
}

// Handle form submission for updating the product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productImage = $_FILES['product_image'];

    // Check if a new image has been uploaded
    if (!empty($productImage['name'])) {
        // Handle image upload
        $imageName = time() . '_' . basename($productImage['name']);
        $targetDir = 'uploads/';
        $targetFile = $targetDir . $imageName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($productImage['tmp_name'], $targetFile)) {
            // Update product with the new image
            $sql = "UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $productName, $productPrice, $imageName, $product_id);
        }
    } else {
        // If no new image is uploaded, update only the name and price
        $sql = "UPDATE products SET name = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $productName, $productPrice, $product_id);
    }

    // Execute the update query
    if ($stmt->execute()) {
        // Redirect to the dashboard after updating
        header("Location: edit.php");
        exit();
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 60%;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            padding: 12px 20px;
            font-size: 1.1rem;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="main">
       <center> <h2>Edit Product</h2></center>

        <!-- Display product data in the form -->
        <?php if (isset($product)) { ?>
            <form action="edit_product.php?id=<?php echo $product['id']; ?>" method="post" enctype="multipart/form-data">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" id="product_name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

                <label for="product_price">Product Price:</label>
                <input type="number" step="0.01" name="product_price" id="product_price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

                <label for="product_image">Product Image:</label>
                <input type="file" name="product_image" id="product_image">

                <button type="submit">Update Product</button>
            </form>
        <?php } else { ?>
            <p>Product details not found. Please make sure the product exists.</p>
        <?php } ?>
    </div>
</body>
</html>
