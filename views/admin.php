<?php

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php?page=home');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $image = $_FILES['image'];
    $imagePath = '';

    if ($image['error'] === 0) {
        $imagePath = "images/{$image['name']}";
        move_uploaded_file($image['tmp_name'], $imagePath);
    }

    $sql = "INSERT INTO product (name, description, price, image) VALUES (:name, :description, :price, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'description' => $description, 'price' => $price, 'image' => $imagePath]);

    echo 'Ürün başarıyla eklendi.';
}
?>

<div class="container mt-5">
    <h2>Ürün Ekle</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Ürün Adı:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Ürün Açıklaması:</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Ürün Fiyatı:</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Ürün Görseli:</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Ürün Ekle</button>
    </form>
</div>


<div class="container mt-5">
    <h2>Ürünler</h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Ad</th>
                <th scope="col">Açıklama</th>
                <th scope="col">Fiyat</th>
                <th scope="col">Görsel</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Veritabanından tüm ürünleri çek
            $stmt = $pdo->prepare("SELECT * FROM product");
            $stmt->execute();
            $products = $stmt->fetchAll();

            // Her bir ürünü bir tablo satırında göster
            foreach ($products as $product) {
                echo '<tr>';
                echo '<th scope="row">' . htmlspecialchars($product['id']) . '</th>';
                echo '<td>' . htmlspecialchars($product['name']) . '</td>';
                echo '<td>' . htmlspecialchars($product['description']) . '</td>';
                echo '<td>' . htmlspecialchars($product['price']) . '</td>';
                echo '<td><img src="' . htmlspecialchars($product['image']) . '" width="50" height="50"></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
