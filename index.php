<?php
require_once 'Category.php';

session_start();

if (!isset($_SESSION['categories'])) {
    $_SESSION['categories'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
    $categoryName = $_POST['category_name'];
    $products = isset($_SESSION['products']) ? $_SESSION['products'] : [];
    $newCategory = new Category($categoryName, $products);
    $_SESSION['categories'][] = $newCategory;
    $_SESSION['products'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $_SESSION['products'][] = $_POST['product_name'];
}

$selectedCategory = null;
if (isset($_GET['category'])) {
    $selectedCategory = Category::findCategoryByName($_SESSION['categories'], $_GET['category']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
</head>
<body>
    <form method="post">
        <input type="text" name="category_name" placeholder="Category Name" required>
        <button type="submit">Add Category</button>
    </form>
    <form method="post">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h2>Products</h2>
    <ul>
        <?php
        if (isset($_SESSION['products'])) {
            foreach ($_SESSION['products'] as $product) {
                echo "<li>$product</li>";
            }
        }
        ?>
    </ul>

    <h2>Categories</h2>
    <ul>
        <?php
        foreach ($_SESSION['categories'] as $category) {
            $categoryName = $category->getCategoryName();
            echo "<li><a href=\"?category=$categoryName\">$categoryName</a></li>";
        }
        ?>
    </ul>

    <?php if ($selectedCategory): ?>
        <h3>Products in <?= htmlspecialchars($selectedCategory->getCategoryName()) ?></h3>
        <ul>
            <?php
            foreach ($selectedCategory->getCategoryProducts() as $product) {
                echo "<li>" . htmlspecialchars($product) . "</li>";
            }
            ?>
        </ul>
    <?php endif; ?>
</body>
</html>
