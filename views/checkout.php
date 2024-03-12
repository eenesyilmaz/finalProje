<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_SESSION['cart'])) {
        $customerName = $_POST['customer_name'];
        $customerEmail = $_POST['customer_email'];
        $customerAddress = $_POST['customer_address'];

        $totalAmount = array_sum(array_map(function ($productId) use ($pdo) {
            $stmt = $pdo->prepare("SELECT price FROM product WHERE id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
            return $product['price'] * $_SESSION['cart'][$productId];
        }, array_keys($_SESSION['cart'])));

        if (!isset($_SESSION['user_id'])) {
            echo "<div class='container'><p class='alert alert-danger'>Lütfen önce oturum açın.</p></div>";
            return;
        }

        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, customer_address, customer_id, total_amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$customerName, $customerEmail, $customerAddress, $_SESSION['user_id'], $totalAmount]);
        $orderId = $pdo->lastInsertId();

        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $stmt = $pdo->prepare("SELECT price FROM product WHERE id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
            $price = $product['price'];

            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$orderId, $productId, $quantity, $price]);
        }

        $_SESSION['cart'] = [];

        echo "<div class='container'><p class='alert alert-success'>Ödemeniz başarıyla alındı. Teşekkür ederiz!</p></div>";
    } else {
        echo "<div class='container'><p class='alert alert-danger'>Sepetiniz boş. Lütfen önce ürün ekleyin.</p></div>";
    }
}
echo '
<div class="container">
    <form method="post" class="mt-4">
        <div class="mb-3">
            <label for="customer_name" class="form-label">Adınız</label>
            <input type="text" id="customer_name" name="customer_name" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="customer_email" class="form-label">E-posta Adresiniz</label>
            <input type="email" id="customer_email" name="customer_email" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="customer_address" class="form-label">Adresiniz</label>
            <input type="text" id="customer_address" name="customer_address" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Ödeme Yap</button>
    </form>
</div>
';
