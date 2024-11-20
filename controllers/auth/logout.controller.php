<?php
// Alexis Boisset
session_start(); // Iniciem per després...
session_destroy(); // ... destruir
header("Location: " . BASE_URL);
exit();
