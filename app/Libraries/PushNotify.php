<?php

namespace App\Libraries;

use Exception;

/**
 * PushNotify Library
 * 
 * Handles push notifications using Firebase Cloud Messaging (FCM).
 * This implementation is designed for the Legacy FCM API as requested by the current codebase usage.
 */
class PushNotify
{
    protected $serverKey;
    protected $apiUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        // Get FCM Server Key from .env or config
        // Make sure to add FCM_SERVER_KEY to your .env file
        $this->serverKey = env('FCM_SERVER_KEY') ?? '';
    }

    /**
     * Send notification to a single device
     * 
     * @param string $token Device token
     * @param string $title
     * @param string $body
     * @param string $screen (Optional) To handle redirection in app
     * @param array $data (Optional) Extra data payload
     */
    public function notify(string $token, string $title, string $body, ?string $screen = null, array $data = [])
    {
        if (empty($token)) {
            return false;
        }

        return $this->sendRequest($token, $title, $body, $screen, $data);
    }

    /**
     * Send notification to multiple devices (Multicast)
     * 
     * @param array $tokens Array of device tokens
     * @param string $title
     * @param string $body
     * @param string $screen
     * @param array $data
     */
    public function sendMulticast(array $tokens, string $title, string $body, ?string $screen = null, array $data = [])
    {
        if (empty($tokens)) {
            return false;
        }

        return $this->sendRequest($tokens, $title, $body, $screen, $data);
    }

    /**
     * Core request handler using cURL
     */
    protected function sendRequest($to, string $title, string $body, ?string $screen = null, array $extraData = [])
    {
        if (empty($this->serverKey)) {
            log_message('error', '[PushNotify] FCM Server Key is not configured in .env. Please add FCM_SERVER_KEY.');
            return false;
        }

        $notification = [
            'title' => $title,
            'body'  => $body,
            'sound' => 'default',
        ];

        $payload = array_merge([
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'screen'       => $screen,
            'status'       => 'done'
        ], $extraData);

        $fields = [
            'notification' => $notification,
            'data'         => $payload,
            'priority'     => 'high'
        ];

        // Handle single or multiple recipients
        if (is_array($to)) {
            $fields['registration_ids'] = $to;
        } else {
            $fields['to'] = $to;
        }

        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch);
            
            if ($result === false) {
                log_message('error', '[PushNotify] cURL Error: ' . curl_error($ch));
                curl_close($ch);
                return false;
            }

            curl_close($ch);
            return json_decode($result, true);
        } catch (Exception $e) {
            log_message('error', '[PushNotify] Send Exception: ' . $e->getMessage());
            return false;
        }
    }
}
