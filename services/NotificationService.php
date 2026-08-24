<?php

require_once __DIR__ . '/../core/Session.php';

class NotificationService {

    /**
     * Send OTP SMS and WhatsApp message to user's mobile number
     */
    public static function sendOtp($mobile, $otp) {
        $message = "Your Meridian Heights verification OTP is: {$otp}. Valid for 10 minutes.";
        $formattedMobile = self::formatMobileNumber($mobile);

        // Generate 100% Free WhatsApp Direct Link (wa.me)
        $cleanDigits = preg_replace('/[^0-9]/', '', $formattedMobile);
        $freeWaLink = "https://api.whatsapp.com/send?phone={$cleanDigits}&text=" . urlencode($message);

        $results = [
            'sms' => self::dispatchSms($formattedMobile, $message),
            'whatsapp' => self::dispatchWhatsApp($formattedMobile, $message),
            'free_whatsapp_link' => $freeWaLink
        ];

        Session::set('last_simulated_otp', [
            'mobile' => $mobile,
            'code' => $otp,
            'time' => date('Y-m-d H:i:s'),
            'message' => $message,
            'free_whatsapp_link' => $freeWaLink,
            'dispatch_results' => $results
        ]);

        return $results;
    }

    /**
     * Send Password Creation Link via WhatsApp and Text Message (SMS)
     */
    public static function sendPasswordCreationLink($mobile, $token) {
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:8000';
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $setupUrl = "{$protocol}://{$host}/set-password?token={$token}";

        $smsMessage = "Meridian Heights CHS: Registration verified! Set your password here: {$setupUrl}";
        $waMessage = "Hello! Welcome to Meridian Heights Society Manager. Set your password here: {$setupUrl}";

        $formattedMobile = self::formatMobileNumber($mobile);
        $cleanDigits = preg_replace('/[^0-9]/', '', $formattedMobile);
        $freeWaLink = "https://api.whatsapp.com/send?phone={$cleanDigits}&text=" . urlencode($waMessage);

        $smsResult = self::dispatchSms($formattedMobile, $smsMessage);
        $waResult = self::dispatchWhatsApp($formattedMobile, $waMessage);

        $dispatchData = [
            'status' => 'success',
            'url' => $setupUrl,
            'sms' => $smsMessage,
            'whatsapp' => $waMessage,
            'free_whatsapp_link' => $freeWaLink,
            'sms_result' => $smsResult,
            'whatsapp_result' => $waResult,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        Session::set('last_simulated_notifications', $dispatchData);

        return $dispatchData;
    }

    /**
     * Dispatch SMS using available active SMS gateways (Free Android HTTP SMS, Twilio, Fast2SMS, MSG91)
     */
    private static function dispatchSms($mobile, $message) {
        $httpSmsServer = getenv('HTTP_SMS_SERVER'); // Free Android SIM SMS Gateway
        $twilioSid = getenv('TWILIO_ACCOUNT_SID');
        $fast2smsKey = getenv('FAST2SMS_API_KEY');
        $msg91Key = getenv('MSG91_AUTH_KEY');

        if (!empty($httpSmsServer)) {
            return self::sendHttpSmsAndroid($mobile, $message);
        } elseif (!empty($twilioSid)) {
            return self::sendTwilioSms($mobile, $message);
        } elseif (!empty($fast2smsKey)) {
            return self::sendFast2Sms($mobile, $message);
        } elseif (!empty($msg91Key)) {
            return self::sendMsg91Sms($mobile, $message);
        }

        return ['status' => 'free_direct', 'message' => 'Using Free Direct WhatsApp & On-Screen Notification'];
    }

    /**
     * Dispatch WhatsApp message using active WhatsApp gateways (Meta 1000 Free Tier, Twilio)
     */
    private static function dispatchWhatsApp($mobile, $message) {
        $metaToken = getenv('META_WA_ACCESS_TOKEN');
        $twilioSid = getenv('TWILIO_ACCOUNT_SID');

        if (!empty($metaToken)) {
            return self::sendMetaWhatsApp($mobile, $message);
        } elseif (!empty($twilioSid)) {
            return self::sendTwilioWhatsApp($mobile, $message);
        }

        return ['status' => 'free_direct', 'message' => 'Using Free Direct wa.me WhatsApp API Link'];
    }

    /**
     * Free Android Phone SIM SMS Gateway (HTTP SMS App)
     */
    private static function sendHttpSmsAndroid($to, $message) {
        $serverUrl = getenv('HTTP_SMS_SERVER'); // e.g. https://api.httpsms.com/v1/messages/send
        $apiKey = getenv('HTTP_SMS_API_KEY');

        $data = [
            'content' => $message,
            'recipient' => $to
        ];

        return self::makeCurlRequest($serverUrl, json_encode($data), [
            "x-api-key: {$apiKey}",
            "Content-Type: application/json"
        ], 'POST');
    }

    /**
     * Send SMS via Twilio API
     */
    private static function sendTwilioSms($to, $message) {
        $sid = getenv('TWILIO_ACCOUNT_SID');
        $token = getenv('TWILIO_AUTH_TOKEN');
        $from = getenv('TWILIO_PHONE_NUMBER');

        if (empty($sid) || empty($token) || empty($from)) {
            return ['status' => 'error', 'message' => 'Incomplete Twilio SMS credentials'];
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
        $data = [
            'From' => $from,
            'To' => $to,
            'Body' => $message
        ];

        return self::makeCurlRequest($url, $data, [
            'Authorization: Basic ' . base64_encode("{$sid}:{$token}")
        ]);
    }

    /**
     * Send WhatsApp via Twilio API
     */
    private static function sendTwilioWhatsApp($to, $message) {
        $sid = getenv('TWILIO_ACCOUNT_SID');
        $token = getenv('TWILIO_AUTH_TOKEN');
        $fromWa = getenv('TWILIO_WHATSAPP_NUMBER') ?: '+14155238886';

        if (empty($sid) || empty($token)) {
            return ['status' => 'error', 'message' => 'Incomplete Twilio WhatsApp credentials'];
        }

        $from = (strpos($fromWa, 'whatsapp:') === 0) ? $fromWa : 'whatsapp:' . $fromWa;
        $toWa = (strpos($to, 'whatsapp:') === 0) ? $to : 'whatsapp:' . $to;

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
        $data = [
            'From' => $from,
            'To' => $toWa,
            'Body' => $message
        ];

        return self::makeCurlRequest($url, $data, [
            'Authorization: Basic ' . base64_encode("{$sid}:{$token}")
        ]);
    }

    /**
     * Send SMS via Fast2SMS (Indian Gateway)
     */
    private static function sendFast2Sms($to, $message) {
        $apiKey = getenv('FAST2SMS_API_KEY');
        $cleanNum = preg_replace('/[^0-9]/', '', $to);
        if (strlen($cleanNum) > 10) {
            $cleanNum = substr($cleanNum, -10);
        }

        $url = "https://www.fast2sms.com/dev/bulkV2";
        $data = [
            'route' => 'otp',
            'variables_values' => $message,
            'numbers' => $cleanNum
        ];

        return self::makeCurlRequest($url, json_encode($data), [
            "authorization: {$apiKey}",
            "Content-Type: application/json"
        ], 'POST');
    }

    /**
     * Send SMS via MSG91
     */
    private static function sendMsg91Sms($to, $message) {
        $authKey = getenv('MSG91_AUTH_KEY');
        $cleanNum = preg_replace('/[^0-9]/', '', $to);

        $url = "https://control.msg91.com/api/v5/flow/";
        $data = [
            'mobiles' => $cleanNum,
            'message' => $message
        ];

        return self::makeCurlRequest($url, json_encode($data), [
            "authkey: {$authKey}",
            "Content-Type: application/json"
        ], 'POST');
    }

    /**
     * Send WhatsApp via Meta Cloud API (1,000 Free Messages / Month)
     */
    private static function sendMetaWhatsApp($to, $message) {
        $phoneId = getenv('META_WA_PHONE_NUMBER_ID');
        $token = getenv('META_WA_ACCESS_TOKEN');
        $cleanNum = preg_replace('/[^0-9]/', '', $to);

        $url = "https://graph.facebook.com/v18.0/{$phoneId}/messages";
        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $cleanNum,
            'type' => 'text',
            'text' => ['body' => $message]
        ];

        return self::makeCurlRequest($url, json_encode($data), [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json"
        ], 'POST');
    }

    /**
     * Format mobile number with country code (+91 default for 10 digits)
     */
    private static function formatMobileNumber($mobile) {
        $clean = preg_replace('/[^0-9]/', '', $mobile);
        if (strlen($clean) === 10) {
            return '+91' . $clean;
        }
        if (strpos($mobile, '+') !== 0) {
            return '+' . $clean;
        }
        return $mobile;
    }

    /**
     * Curl Helper
     */
    private static function makeCurlRequest($url, $postData, $headers = [], $method = 'POST') {
        if (!function_exists('curl_init')) {
            return ['status' => 'error', 'message' => 'PHP cURL extension not enabled on server'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (!empty($postData)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($postData) ? http_build_query($postData) : $postData);
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['status' => 'error', 'message' => $error];
        }

        return ['status' => 'sent', 'response' => json_decode($response, true) ?: $response];
    }
}
