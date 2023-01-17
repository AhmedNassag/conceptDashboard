<?php
namespace App\Traits;

use GuzzleHttp\Client;
use Log;

/**
 * trait NotificationTrait
 */
trait NotificationTrait
{
    public $API_KEY = 'AAAAAvz8c9c:APA91bGGGk_blX4fTJFp1W0aPiwM6JR854gsCeIm7tyRHRwwkz3ueKw3pqszd7r5mJ3N8EUSuwyYFE7EoMAdrZtvKqY0lgHoOmhDv0hAOGvmIDTjmZnlA0dISbr5sZ9UsBJ69o8MN4TU';
    public $title = 'كونسبت';

    public function notification($tokens,$body,$type,$productData){

        $SERVER_API_KEY = $this->API_KEY;

        $data = [

            "registration_ids" => $tokens,

            "data" => [
                'type' => $type,
//                'productData' => json_encode($productData),
            ],

            "notification" => [

                "title" => $this->title,
                "body" => $body,
//                "body" => $body,


                "sound" => "default", // required for sound on ios

                // "image" =>asset('uploads/products/'.$product -> image),

                "click_action"=> "FLUTTER_NOTIFICATION_CLICK"

            ],

        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        return $response;
    }

    public function notificationAllClient($tokens,$body,$title,$type,$productData){

        $SERVER_API_KEY = $this->API_KEY;

        $data = [

            "registration_ids" => $tokens,

            "data" => [
                'type' => $type,
                'productData' => json_encode($productData),
            ],

            "notification" => [

                "title" => $title,

                "body" => $body,

                "sound" => "default", // required for sound on ios

                 "image" =>$productData['image_path'],

                "click_action"=> "FLUTTER_NOTIFICATION_CLICK"

            ],

        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        return $response;
    }
}
