<?php
require "../model/db_conn.php";
require "../model/user_model.php";
require '../vendor/autoload.php'; // Autoload de Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$conn = connect();

if ($conn && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);

    // Comprovem que existeix en la base de dades
    if (userExists($email, $conn)) {
        $token = bin2hex(random_bytes(32));

        if (storeToken($email, $token, $conn)) {
            // Enviar correu amb el token
            sendRecoveryEmail($email, $token);

            $_SESSION['success'] = 'S\'ha enviat un correu amb instruccions per restablir la contrasenya.';

            header('Location: ../vista/login.vista.php');
            exit();
        } else {
            $_SESSION['failure'] = 'Algo ha fallat';
            header('Location: ../vista/forgotpassword.view.php');
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


        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Remitente y destinatario
        $mail->setFrom('tu_correo@example.com', 'Tu Nombre');
        $mail->addAddress($email);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de contraseña';
        $mail->Body    = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='https://tu_dominio.com/reset-password.php?token=$token'>Restablecer contraseña</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
        return false;
    }
}
