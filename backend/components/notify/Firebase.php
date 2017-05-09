<?php
/**
 * Created by PhpStorm.
 * User: theta-php
 * Date: 2/11/16
 * Time: 6:32 PM
 */
namespace backend\components\notify;

class Firebase {

    // sending push message to single user by firebase reg id
    public function send($to, $message) {
       // print_r($message);die;
        $fields = array(
            'to' => isset($to[0])?$to[0]:$to,
            'data' => (object)[$message]
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message) {
        echo "<pre>";

        $fields = array(
            'to' => $registration_ids,
            'data' => (object)[$message],
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . FIREBASE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl Failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;
    }

    public function getPush($title,$message,$background,$image,$data) {
        $res = array();
        $res['data']['title'] = $title;
        $res['data']['is_background'] = $background;
        $res['data']['message'] = $message;
        $res['data']['image'] = $image;
        $res['data']['payload'] = (object)$data;
        $res['data']['timestamp'] = date('Y-m-d G:i:s');
        return $res;
    }
}
?>
