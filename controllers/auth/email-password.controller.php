<?php
require_once __DIR__ . '/../../models/env.php';
require_once BASE_PATH . 'models/user/user.model.php';
require_once BASE_PATH . 'vendor/autoload.php';

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
            // Configuración para PHPMailer
            // Se Xavi que parece una tonteria pero si no lo pongo no funciona en producción.
            $config = [
                'host' => MAIL_HOST ?? 'smtp.gmail.com',
                'port' => MAIL_PORT ?? 587,
                'username' => MAIL_USERNAME ?? 'a.boisset@sapalomera.cat',
                'password' => MAIL_PASSWORD ?? 'zpfh ujxj brmh mqdm',
                'from' => MAIL_FROM ?? 'a.boisset@sapalomera.cat',
                'from_name' => MAIL_FROM_NAME ?? 'Password Recovery'
            ];

            // Enviar correo con el token
            if (sendRecoveryEmail($email, $token, $config)) {
                $_SESSION['success'] = 'S\'ha enviat un correu amb instruccions per restablir la contrasenya.';
                header('Location: ' . BASE_URL . 'login');
                exit();
            } else {
                $_SESSION['failure'] = 'Error al enviar el correu';
                header('Location: ../view/forgot-password.view.php');
                exit();
            }
        }
    }
}

/**
 * Enviar correo de recuperación de contraseña.
 *
 * @param string $email El correo electrónico del usuario.
 * @param string $token El token de recuperación.
 * @param array $config Configuración para PHPMailer.
 * @return bool True si el correo se envió correctamente, False en caso contrario.
 */
function sendRecoveryEmail($email, $token, $config)
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = $config['host']; 
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['port'];
        $mail->CharSet = 'UTF-8'; // Configurar la codificación

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Remitente y destinatario
        $mail->setFrom($config['from'], $config['from_name']);
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
