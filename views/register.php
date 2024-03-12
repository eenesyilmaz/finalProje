<?php
require 'model/user.php';

if (isset($_SESSION['user'])) {
    echo 'Zaten oturum açmışsınız!';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if ($email && $password) {
        $user = new User($email, $password);
        
        try {
            $userId = $user->register($pdo);

            $_SESSION['user'] = [
                'id' => $userId,
                'email' => $email
            ];

            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            echo 'Kayıt işleminde bir hata oluştu: ' . $e->getMessage();
        }
    } else {
        echo 'Geçersiz email veya şifre.';
    }
}
echo '
<main id="MainContent" class="content-for-layout">
    <div class="login-page mt-100">
        <div class="container">
            <form action="index.php?page=register" method="post" class="login-form common-form mx-auto">
                <div class="section-header mb-3">
                    <h2 class="section-heading text-center">Kayıt Ol</h2>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Email address</label>
                            <input type="email" name="email" required />
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Password</label>
                            <input type="password" name="password" required />
                        </fieldset>
                    </div>
                    <div class="col-12 mt-3">
                        <a href="login" class="text_14 d-block">Zaten bir hesabın var mı? Giriş yap!</a>
                        <button type="submit" class="btn-primary d-block mt-4 btn-signin">KAYIT OL</button>
                    </div>
                </div>
            </form>
        </div>
    </div>            
</main>
';