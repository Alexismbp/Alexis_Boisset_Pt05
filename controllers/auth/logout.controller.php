<?php
// Alexis Boisset
session_destroy(); // destruir sesión
header("Location: " . BASE_URL);
exit();
