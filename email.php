<?php
session_start();

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Masukkan alamat email yang valid.';
    } else {
        $_SESSION['email'] = $email;
        $_SESSION['photos'] = [];
        $_SESSION['result_file'] = null;
        header('Location: camera.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Tujuan | Photobooth</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="email-page">
    <main class="page-shell narrow-shell">
        <section class="form-panel">
            <h2>Masukin email</h2>
            <?php if ($error): ?>
                <div class="alert" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <form class="email-form" method="post" novalidate>
                <label class="visually-hidden" for="email">Email</label>
                <input id="email" name="email" type="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" placeholder="you@example.com" autocomplete="email" required autofocus>
                <button class="button button-primary start-button email-submit" type="submit">NEXT</button>
            </form>
        </section>
    </main>
</body>
</html>
