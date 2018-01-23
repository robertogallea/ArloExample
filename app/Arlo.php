<?php
/**
 * Created by PhpStorm.
 * User: Roberto Gallea
 * Date: 04/09/2017
 * Time: 15:18
 */

namespace ArloExample;


use GuzzleHttp\Client;

class Arlo
{
    const TRANSID_PREFIX = 'web';
    private $user_id;

    private function get($url,$token) {
        $options['headers']['content-type'] = 'application/json';
        $options['headers']['Authorization'] = $token;

        $client = new Client();
        $response = $client->request(
            'GET',
            $url,
            $options
        );

        return json_decode($response->getBody())->data;
    }

    private function post($url,$token=null,$payload,$xcloudid=null) {
        $options['headers']['content-type'] = 'application/json';
        if (!is_null($xcloudid))
            $options['headers']['xcloudid'] = $xcloudid;

        if (!is_null($token))
            $options['headers']['Authorization'] = $token;
        $options['body'] = json_encode($payload);

        $client = new Client();
        $response = $client->request(
            'POST',
            $url,
            $options
        );
        $res = json_decode($response->getBody());
        if (isset($res->data))
            return $res->data;
        else
            return $res;
    }

    private function notify($payload, $deviceId,$token,$xcloudid) {

        $payload2 = [
            'transId' => $this->generateTransientId(),
            'from' => $this->user_id,
            'to' => $deviceId
        ];
        $payload = (object) array_merge((array) $payload, $payload2);
        $result = $this->post('https://arlo.netgear.com/hmsweb/users/devices/notify/'.$deviceId,$token,$payload,$xcloudid);
        return json_encode($result);
    }

    private function generateTransientId($trans_type='web') {
        // e.g. web!3975ac7b.ebb3a8!1504266382584
        //return 'web!3975ac7b.ebb3a8!1504266382584';
        $id = $trans_type . '!' . $this->random_hex(8) . '.' . $this->random_hex(6) . '!' . $this->random_num(13);
        return $id;
    }

    private function random_hex( $val_length ) {
        return $this->generateRandomString($val_length,'0123456789abcdef');
    }

    private function random_num( $val_length ) {
        return $this->generateRandomString($val_length, '0123456789');
    }


    private function generateRandomString($length, $characters ) {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function authorize($settings) {
        $res = $this->post('https://arlo.netgear.com/hmsweb/login/v2',null,$settings);
        $this->user_id = $res->userId;
        return $res;
    }

    public function getServiceLevel($token) {
        return $this->get('https://arlo.netgear.com/hmsweb/users/serviceLevel/v2',$token);
    }

    public function getDevices($token) {
        return $this->get('https://arlo.netgear.com/hmsweb/users/devices',$token);
    }

    public function getPaymentOffers($token) {
        return $this->get('https://arlo.netgear.com/hmsweb/users/payment/offers',$token);
    }

    public function getProfile($token) {
        return $this->get('https://arlo.netgear.com/hmsweb/users/profile',$token);
    }

    public function getSession($token) {
        return $this->get('https://arlo.netgear.com/hmsweb/users/session',$token);
    }

    public function getFriends($token) {
        return $this->get('https://arlo.netgear.com/hmsweb/users/friends',$token);
    }

    public function getUserLocations($token) {
        return $this->get('https://arlo.netgear.com/hmsweb/users/locations',$token);
    }

    public function getLibraryMetadata($token) {
        return $this->get('https://arlo.netgear.com/hmsweb/users/library/metadata/v2',$token);
    }

    public function getLibrary($token,$from,$to) {

        $payload = ((object)[
            'dateFrom' => $from,
            'dateTo' => $to,
        ]);

        return $this->post('https://arlo.netgear.com/hmsweb/users/library',$token,$payload);
    }

    public function setMode($token,$basestation_id,$device,$mode) {

        $payload = (object)[
            "action" =>	"set",
            "resource" =>	"modes",
            "publishResponse" =>	true,
            "properties"=>  [
                "active" =>	$mode,
            ],
        ];

        return $this->post('https://arlo.netgear.com/hmsweb/users/devices/notify/' . $basestation_id,$token,$payload);

    }

    public function disarm($deviceId,$token,$xcloudid) {
        $payload = (object)[
            "action" =>	"set",
            "resource" => "modes",
            "publishResponse" => true,
            "properties" => [
                "active" =>	"mode0",
            ]
        ];
        return $this->notify($payload,$deviceId,$token,$xcloudid);
    }

    public function arm($deviceId,$token,$xcloudid) {
        $payload = (object)[
            "action" =>	"set",
            "resource" => "modes",
            "publishResponse" => true,
            "properties" => [
                "active" =>	"mode1",
            ]
        ];
        return $this->notify($payload,$deviceId,$token,$xcloudid);
    }

    //updateFriends
    //updateDeviceName
    //updateDisplayOrder
    //deleteRecording
    //batchDeleteRecording
    //getRecording
    //getStreamUrl
    //takeSnapshot
    //startRecording
    //stopRecording
    //getCameras
    //getRules
    //getModes
    //getCalendar
    //calendar

}