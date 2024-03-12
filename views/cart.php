<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
}
?>

<div class="container">
        <h1 class="mt-5 mb-4">Sepet</h1>

        <?php
        if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
            echo '<p class="alert alert-info">Sepetiniz boş.</p>';
        } else {
            ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                foreach ($_SESSION['cart'] as $productId => $quantity) {
                    $stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
                    $stmt->execute([$productId]);
                    $product = $stmt->fetch();
                    ?>
                    <div class="col">
                        <div class="card h-100">
                            <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                                <p class="card-text"><strong>Fiyat: </strong><?= htmlspecialchars($product['price']) ?></p>
                                <p class="card-text"><strong>Miktar: </strong><?= htmlspecialchars($quantity) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="text-end mt-4">
            <a href="/checkout" class="btn btn-primary me-2">Ödeme Yap</a>
                <form method="post">
                    <button type="submit" name="clear_cart" class="btn btn-danger">Sepeti Temizle</button>
                </form>
            </div>
            <?php
        }
        ?>
    </div>