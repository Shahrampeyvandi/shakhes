<?php
namespace App\Http\Schedules;

class Scheduler {


    public function sendnotification($firebasetoken, $title, $text)
    {
        // $this->sendnotification($member->firebase_token, $notification->title, $notification->text);
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $title,
            'sound' => true,
            "body" => $text,
        ];

        $extraNotificationData = ["message" => $title, "moredata" => $title];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to' => $firebasetoken, //single token
            'notification' => $notification,
            'data' => $extraNotificationData,
        ];

        $serverkey = "AAAAKN22tA0:APA91bEhhwYlPvy452mKulNuSadvK2jsfUgM41-Lg-njTxWLzb_xrcg-QhXrxXml3MHFSfCSF7dMuihvWbySp5kNxfNneUVoCnfH3hHjxJwymakBxtUxUlB2ZjnSk5V6yAF_iFWnHhMK";
        

        // $serverkey = config('FIREBASE_LEGACY_SERVER_KEY');

        $headers = [
            'Authorization: key=' . $serverkey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        //dd($result);

        return true;

    }
}