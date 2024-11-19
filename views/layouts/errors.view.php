<!-- FILE: views/layouts/errors.view.php -->
<?php
if (isset($_SESSION['failure'])) {
    echo '<div class="message error">' . $_SESSION['failure'] . '</div>';
    unset($_SESSION['failure']);
} elseif (isset($_SESSION['success'])) {
    echo '<div class="message success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}

if (isset($errors) && !empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>