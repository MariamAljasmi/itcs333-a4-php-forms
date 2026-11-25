<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/validate.php';
$name = '';
$email = '';
$message = '';
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $name = (string)($_POST['name'] ?? '');
    $email = (string)($_POST['email'] ?? '');
    $message = (string)($_POST['message'] ?? '');

    $trimmedName = trim($name);
    $trimmedEmail = trim($email);
    $trimmedMessage = trim($message);

    if (!validate_required($trimmedName)) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($trimmedName, 2, 60)) {
        $errors['name'] = 'Invalid name length: must be between 2 and 60 characters.';
    }

    if (!validate_email($trimmedEmail)) {
        $errors['email'] = 'Invalid email address.';
    }

    if (!validate_required($trimmedMessage)) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($trimmedMessage, 10, 1000)) {
        $errors['message'] = 'Invalid message length: must be between 10 and 1000 characters.';
    }

    if (empty($errors)) {
        http_response_code(303);
        header('Location: success.php');
        exit;
    }

    http_response_code(200);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
</head>
<body>
  <h1>Contact Us</h1>
  <form method="post" action="index.php">

    <label>Name <input type="text" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"></label><br>
    <?php if (isset($errors['name'])): ?>
      <span><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></span><br>
    <?php endif; ?>

    <label>Email <input type="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"></label><br>
    <?php if (isset($errors['email'])): ?>
      <span><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></span><br>
    <?php endif; ?>

    <label>Message <textarea name="message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></textarea></label><br>
    <?php if (isset($errors['message'])): ?>
      <span><?= htmlspecialchars($errors['message'], ENT_QUOTES, 'UTF-8') ?></span><br>
    <?php endif; ?>

    <button type="submit">Send</button>
  </form>
</body>
</html>
