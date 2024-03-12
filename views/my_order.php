<?php

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT * FROM orders WHERE customer_id = ?');
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>

<div class="container">
    <div class="row">
        <?php foreach ($orders as $order) : ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sipariş ID: <?php echo $order['id']; ?></h5>
                    <p class="card-text">Toplam Tutar: <?php echo $order['total_amount']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

