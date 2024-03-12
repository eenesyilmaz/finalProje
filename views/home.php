<?php
// Ürünleri veritabanından al
$stmt = $pdo->query("SELECT * FROM product");
$products = $stmt->fetchAll();

// Sepete ürün ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 0;
    }
    $_SESSION['cart'][$productId]++;
    $_SESSION['product_added'] = true; // Ürün sepete eklendiğini belirtmek için oturum değişkeni ayarla
}

// Banner HTML
echo '
<div class="container">
    <div class="banner">
        <img src="images/image3.jpg" style="width:100%;" alt="Banner Image">
    </div>
</div>

<div class="container"style="padding-top:25px;">
    <div class="row g-3">';
foreach ($products as $product) {
    echo '
    <div class="col-md-4">
        <div class="card">
            <img src="' . htmlspecialchars($product['image']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '">
            <div class="card-body">
                <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>
                <p class="card-text">' . htmlspecialchars($product['description']) . '</p>
                <p class="card-text"><strong>Fiyat: </strong>' . htmlspecialchars($product['price']) . '</p>
                <form method="post">
                    <input type="hidden" name="product_id" value="' . htmlspecialchars($product['id']) . '">
                    <button type="submit" class="btn btn-primary">Sepete Ekle</button>
                </form>
            </div>
        </div>
    </div>
    ';
}
echo '</div></div>';

if (isset($_SESSION['product_added']) && $_SESSION['product_added']) {
    // Ürün sepete eklendiğinde bir JavaScript uyarısı göster
    echo '
    <script>
    alert("Ürün sepete eklendi!");
    </script>
    ';
    // Uyarıyı bir kez gösterdikten sonra oturum değişkenini sıfırla
    $_SESSION['product_added'] = false;
}
