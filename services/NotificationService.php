<?php

require_once __DIR__ . '/../core/Session.php';

class NotificationService {

    /**
     * Send OTP SMS to user mobile number
     */
    public static function sendOtp($mobile, $otp) {
        $message = "Your Meridian Heights verification OTP is: {$otp}. It will expire in 10 minutes.";
        
        // Log notification to session for instant UI notification banner preview
        Session::set('last_simulated_otp', [
            'mobile' => $mobile,
            'code' => $otp,
            'time' => date('Y-m-d H:i:s'),
            'message' => $message
        ]);

        return true;
    }

    /**
     * Send Password Creation Link via WhatsApp and Text Message (SMS)
     */
    public static function sendPasswordCreationLink($mobile, $token) {
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:8000';
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $setupUrl = "{$protocol}://{$host}/set-password?token={$token}";

        $smsMessage = "Meridian Heights: Registration verified! Click here to set your secure password: {$setupUrl}";
        $waMessage = "Hello! Welcome to Meridian Heights Society Manager. Please set your password to complete your account setup: {$setupUrl}";

        // Save simulated messages to session so user can view/click them directly in UI test notification area
        Session::set('last_simulated_notifications', [
            'mobile' => $mobile,
            'password_token' => $token,
            'password_url' => $setupUrl,
            'sms' => $smsMessage,
            'whatsapp' => $waMessage,
            'timestamp' => date('Y-m-d H:i:s')
        ]);

        return [
            'status' => 'success',
            'url' => $setupUrl,
            'sms' => $smsMessage,
            'whatsapp' => $waMessage
        ];
    }
}
