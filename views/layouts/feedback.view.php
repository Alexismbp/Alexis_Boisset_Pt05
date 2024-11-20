<?php
// Alexis Boisset
echo '<div class="form-group" style="grid-column: 1 / -1;">';

if (isset($_SESSION['failure'])) {
    echo '<div class="message error">' . $_SESSION['failure'] . '</div>';
    unset($_SESSION['failure']);
} elseif (isset($_SESSION['success'])) {
    echo '<div class="message success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        echo '<div class="message error">' . $error . '</div>'; // Mostra cada error.
    }
    unset($_SESSION['errors']); // Neteja els errors de la sessió.
}

echo '</div>';
?>