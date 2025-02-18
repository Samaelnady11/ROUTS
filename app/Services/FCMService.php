<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FCMService
{
    private $serverKey;
    private $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        $this->serverKey = config('services.fcm.server_key');
    }

    public function send($token, $title, $body, $data = [])
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
            'data' => $data,
        ];

        $headers = [
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json',
        ];



















}    }        return $result;        curl_close($ch);

            die('Curl failed: ' . curl_error($ch));        if ($result === FALSE) {        $result = curl_exec($ch);        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);        curl_setopt($ch, CURLOPT_POST, true);        curl_setopt($ch, CURLOPT_URL, $url);        $ch = curl_init();
}
