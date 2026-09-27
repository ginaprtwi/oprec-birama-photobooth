<?php
session_start();

if (empty($_SESSION['email']) || empty($_SESSION['result_file'])) {
    header('Location: email.php');
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$message = '';
$messageType = 'success';

try {
    // Isi nilai SMTP di bagian ini, atau pindahkan ke file konfigurasi pribadi.
    $mail->isSMTP();
    $mail->Host = 'smtp-relay.brevo.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'baa478001@smtp-brevo.com';
    $mail->Password = 'xsmtpsib-968d1de648bd51cfec9412421cf9a53723b4696b9199b8fa2cb37216f6975b40-lLOhGUuZFryQZ6fn';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;


    $mail->setFrom('gpratiwi878@gmail.com', 'Birama Photobooth');
    $mail->addAddress($_SESSION['email']);
    $mail->addAttachment(__DIR__ . '/uploads/' . basename($_SESSION['result_file']), 'photobooth-result.png');
    $mail->isHTML(true);
    $mail->Subject = 'Your Photobooth Memories';
    $mail->Body = '<p>Terima kasih sudah mampir ke tenant BIRAMA!</p><p>Jangan Lupa Join Pers BIRAMA yaa!</p><p>Link pendaftaran: <a href="https://bit.ly/Opreccakrubirama2026-2027">https://bit.ly/Opreccakrubirama2026-2027</a></p>';
    $mail->send();
    $message = 'Foto berhasil dikirim ke ' . htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') . '.';
} catch (Exception $exception) {
    $messageType = 'error';
    $message = 'Email belum terkirim. Periksa konfigurasi SMTP: ' . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Email | Photobooth</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="status-page <?= $messageType === 'success' ? 'is-success' : 'is-error' ?>">
    <main class="page-shell narrow-shell status-shell">
        <section class="status-overlay" role="status">
            <div class="status-icon <?= $messageType === 'error' ? 'is-error' : '' ?>"><?= $messageType === 'error' ? '!' : '&#10003;' ?></div>
            <h1><?= $messageType === 'error' ? 'Email gagal terkirim' : 'Berhasil terkirim' ?></h1>
            <p class="muted"><?= $message ?></p>
            <?php if ($messageType === 'error'): ?>
                <a class="button button-primary" href="result.php">Kembali</a>
            <?php endif; ?>
        </section>
    </main>
    <?php if ($messageType === 'success'): ?>
        <script>setTimeout(() => { window.location.href = 'index.php'; }, 2800);</script>
    <?php endif; ?>
</body>
</html>
