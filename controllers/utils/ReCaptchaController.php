<?php
class ReCaptchaController {
    public static function renderCaptcha() {
        return '<div class="g-recaptcha" data-sitekey="' . SITE_KEY . '"></div>';
    }

    public static function verifyResponse($responseToken) {
        if (empty($responseToken)) {
            return false;
        }

        try {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret' => SECRET_KEY,
                'response' => $responseToken
            ];

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false, // Deshabilita verificación SSL para desarrollo
                CURLOPT_SSL_VERIFYHOST => false  // Deshabilita verificación host para desarrollo
            ]);

            $response = curl_exec($curl);
            
            if ($response === false) {
                error_log('Error cURL: ' . curl_error($curl));
                return false;
            }
            
            curl_close($curl);
            
            $responseData = json_decode($response);
            return $responseData && $responseData->success;
            
        } catch (Exception $e) {
            error_log('Error en verificación reCAPTCHA: ' . $e->getMessage());
            return false;
        }
    }
}
?>