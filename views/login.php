<?php
ob_start(); // Çıktıyı tamponlamaya başla

require 'model/user.php';

// Oturum zaten başlatılmış mı kontrol et
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user'])) {
    // Kullanıcı zaten oturum açmışsa, kullanıcıyı başka bir sayfaya yönlendirin (örneğin, ana sayfa)
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if ($email && $password) {
        $user = new User($email, $password);
        
        try {
            $userId = $user->login($pdo);
            if ($userId) {
                ob_start(); // Çıktıyı tamponlamaya başla
                echo 'Giriş başarılı. Kullanıcı ID: ' . $userId;

                // Kullanıcıyı oturuma ekleyin
                $_SESSION['user'] = [
                    'id' => $userId,
                    'email' => $email
                ];

                // Kullanıcıyı ana sayfaya yönlendirin
                header('Location: index.php');
                ob_end_flush(); // Tamponu temizle ve çıktıyı gönder
                exit;
            } else {
                echo 'Geçersiz email veya şifre.';
            }
        } catch (Exception $e) {
            echo 'Giriş işleminde bir hata oluştu: ' . $e->getMessage();
        }
    } else {
        echo 'Geçersiz email veya şifre.';
    }
}

echo '
<main id="MainContent" class="content-for-layout">
    <div class="login-page mt-100">
        <div class="container">
            <form action="index.php?page=login" method="post" class="login-form common-form mx-auto">
                <div class="section-header mb-3">
                    <h2 class="section-heading text-center">Giriş Yap</h2>
                </div>
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <label class="label">E-posta</label>
                            <input type="email" name="email" required />
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Şifre</label>
                            <input type="password" name="password" required />
                        </fieldset>
                    </div>
                    <div class="col-12 mt-3">
                        <a href="#" class="text_14 d-block">Şifreni mi unuttun?</a>
                        <button type="submit" class="btn-primary d-block mt-4 btn-signin">GİRİŞ YAP</button>
                        <a href="register" class="btn-secondary mt-2 btn-signin">HESAP OLUŞTUR</a>
                    </div>
                </div>
            </form>
        </div>
    </div>            
</main>
';