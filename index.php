<?php
ob_start();
session_start();
require 'db.php'; 
require 'views/header.php';

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS);
if (empty($page)) {
    $page = 'home';
}

$file = "views/{$page}.php";

if (file_exists($file)) {
    require $file;
} else {
    echo "Sayfa bulunamadı: {$page}";
}
require 'views/footer.php';

ob_end_flush();