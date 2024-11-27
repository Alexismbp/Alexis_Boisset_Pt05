<?php
require_once __DIR__ . '/../../models/env.php';

class ReCaptchaController {
    private static $siteKey = SITE_KEY;
    private static $secretKey = SECRET_KEY;

    public static function verifyResponse($recaptcha_response) {
        if (empty($recaptcha_response)) {
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => self::$secretKey,
            'response' => $recaptcha_response
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);

        return $resultJson && $resultJson->success;
    }

    public static function renderCaptcha() {
        return '<div class="g-recaptcha" data-sitekey="' . self::$siteKey . '"></div>';
    }
}