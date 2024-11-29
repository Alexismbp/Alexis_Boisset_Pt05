<?php
require BASE_PATH . "models/user/user.model.php";
require BASE_PATH . "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = Database::getInstance();


if ($conn && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);

    // Comprovem que existeix en la base de dades
    if (userExists($email, $conn)) {
        $token = bin2hex(random_bytes(32));

        if (storeToken($email, $token, $conn)) {
            // Enviar correu amb el token
            sendRecoveryEmail($email, $token);

            $_SESSION['success'] = 'S\'ha enviat un correu amb instruccions per restablir la contrasenya.';

            header('Location: ' . BASE_URL . 'login');
            exit();
        } else {
            $_SESSION['failure'] = 'Algo ha fallat';
            header('Location: ../view/forgot-password.view.php');
            exit();
        }
    }
}

/**
 * Enviar correo de recuperación de contraseña.
 *
 * @param string $email El correo electrónico del usuario.
 * @param string $token El token de recuperación.
 * @return bool True si el correo se envió correctamente, False en caso contrario.
 */
function sendRecoveryEmail($email, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'a.boisset@sapalomera.cat';
        $mail->Password = 'zpfh ujxj brmh mqdm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = '587';
        $mail->CharSet = 'UTF-8'; // Configurar la codificación

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Remitente y destinatario
        $mail->setFrom('a.boisset@sapalomera.cat', 'Password Recovery');
        $mail->addAddress($email);

        // Cargar plantilla HTML
        $html = file_get_contents(BASE_PATH . 'templates/recovery_email_template.html');
        $html = str_replace(['{{BASE_URL}}', '{{token}}'], [BASE_URL, $token], $html);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperació de Contrasenya';
        $mail->Body = $html;
        $mail->AltBody = 'Accedeix a aquest enllaç per restablir la teva contrasenya: ' 
                        . BASE_URL . 'resetpassword?token=' . $token;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
        return false;
    }
}
