<?php
/**
 * Created by PhpStorm.
 * User: theta-php
 * Date: 2/11/16
 * Time: 6:32 PM
 */
namespace backend\components\notify;

class Apns {

    public static $PASS_PHRASE = 'crewfacts';

    // sending push message to single user by firebase reg id
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message,$data) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
            'reservation'=>$data
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {

        $ctx = stream_context_create();

        stream_context_set_option($ctx, 'ssl', 'local_cert', __DIR__.'/certs/Apns_Development_Certificates.pem');

        stream_context_set_option($ctx, 'ssl', 'passphrase', self::$PASS_PHRASE);

        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)

            return "Failed to connect amarnew: $err $errstr" . PHP_EOL;

        $results = array();

        //echo 'Connected to APNS' . PHP_EOL;

        //Create the payload body

        $body['aps'] = array(

            'badge' => 0,

            'alert' => $fields['data'],

            'body' => $fields['reservation'],

            'sound' => 'default'

        );

        $payload = json_encode($body);

        foreach ($fields['to'] as $device) {

            if($device != 'token' && $device != 'ios'){
                // Build the binary notification
                $msg = chr(0) . pack('n', 32) . pack('H*', $device) . pack('n', strlen($payload)) . $payload;
                //Send it to the server
                $results[] = fwrite($fp, $msg, strlen($msg));
            }
        }

        //      Close the connection to the server

        fclose($fp);

        if (empty($results))
            return FALSE;
        else
            return $results;

    }
}
?>
