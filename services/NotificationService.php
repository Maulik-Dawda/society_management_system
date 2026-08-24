<?php

require_once __DIR__ . '/../core/Session.php';

class NotificationService {

    /**
     * Send OTP SMS and WhatsApp message to user's mobile number
     */
    public static function sendOtp($mobile, $otp) {
        $message = "Your Meridian Heights verification OTP is: {$otp}. Valid for 10 minutes.";

        // Format country code (default to +91 for India if 10 digits)
        $formattedMobile = self::formatMobileNumber($mobile);

        $results = [
            'sms' => self::dispatchSms($formattedMobile, $message),
            'whatsapp' => self::dispatchWhatsApp($formattedMobile, $message)
        ];

        // Save session log for UI notification preview and debugging
        Session::set('last_simulated_otp', [
            'mobile' => $mobile,
            'code' => $otp,
            'time' => date('Y-m-d H:i:s'),
            'message' => $message,
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
        $waMessage = "Hello! Welcome to Meridian Heights Society Manager. Set your password to complete registration: {$setupUrl}";

        $formattedMobile = self::formatMobileNumber($mobile);

        $smsResult = self::dispatchSms($formattedMobile, $smsMessage);
        $waResult = self::dispatchWhatsApp($formattedMobile, $waMessage);

        $dispatchData = [
            'status' => 'success',
            'url' => $setupUrl,
            'sms' => $smsMessage,
            'whatsapp' => $waMessage,
            'sms_result' => $smsResult,
            'whatsapp_result' => $waResult,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        Session::set('last_simulated_notifications', $dispatchData);

        return $dispatchData;
    }

    /**
     * Dispatch SMS using available active SMS gateways (Twilio, Fast2SMS, MSG91)
     */
    private static function dispatchSms($mobile, $message) {
        $twilioSid = getenv('TWILIO_ACCOUNT_SID');
        $fast2smsKey = getenv('FAST2SMS_API_KEY');
        $msg91Key = getenv('MSG91_AUTH_KEY');

        if (!empty($twilioSid)) {
            return self::sendTwilioSms($mobile, $message);
        } elseif (!empty($fast2smsKey)) {
            return self::sendFast2Sms($mobile, $message);
        } elseif (!empty($msg91Key)) {
            return self::sendMsg91Sms($mobile, $message);
        }

        return ['status' => 'simulated', 'message' => 'No active SMS gateway API key set in .env'];
    }

    /**
     * Dispatch WhatsApp message using active WhatsApp gateways (Twilio, Meta WhatsApp API)
     */
    private static function dispatchWhatsApp($mobile, $message) {
        $twilioSid = getenv('TWILIO_ACCOUNT_SID');
        $metaToken = getenv('META_WA_ACCESS_TOKEN');

        if (!empty($twilioSid)) {
            return self::sendTwilioWhatsApp($mobile, $message);
        } elseif (!empty($metaToken)) {
            return self::sendMetaWhatsApp($mobile, $message);
        }

        return ['status' => 'simulated', 'message' => 'No active WhatsApp API key set in .env'];
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

        // Add whatsapp: prefix
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
        // Clean mobile number (keep only 10 digits for Fast2SMS)
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
     * Send WhatsApp via Meta Cloud API
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
     * Utility to format mobile numbers (defaults to +91 if 10 digit Indian number)
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
