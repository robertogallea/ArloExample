<?php

namespace ArloExample\Http\Controllers;

use ArloExample\Arlo;
use Illuminate\Http\Request;

class ArloController extends Controller
{
    public function index() {
        $arlo = new Arlo();
        $settings = (object)[
            'email' => env('ARLO_USER'),
            'password' => env('ARLO_PASSWORD'),
        ];

        $data = $arlo->authorize($settings);
        $user_id = $data->userId;
        $token = $data->token;

        $service_level = $arlo->getServiceLevel($token);
        $devices = $arlo->getDevices($token);
        //$success = $arlo->subscribe($token);

        $library = $arlo->getLibrary($token,'20170726','20170826');
        $offers = $arlo->getPaymentOffers($token);
        $profile = $arlo->getProfile($token);
        $session = $arlo->getSession($token);
        $friends = $arlo->getFriends($token);
        $locations = $arlo->getUserLocations($token);
        $libraryMetadata = $arlo->getLibraryMetadata($token);
        return view('index')->with(compact('token','user_id','service_level','devices','library','offers','profile','session','friends','locations','libraryMetadata'));
    }

    public function disarm() {
        $arlo = new Arlo();
        $settings = (object)[
            'email' => env('ARLO_USER'),
            'password' => env('ARLO_PASSWORD'),
        ];

        $data = $arlo->authorize($settings);
        $user_id = $data->userId;
        $token = $data->token;
        $devices = $arlo->getDevices($token);
        foreach ($devices as $device) {
            if ($device->deviceType === 'basestation') {
                return($arlo->disarm($device->deviceId,$token,$device->xCloudId));
                break;
            }
        }

    }

    public function arm() {
        $arlo = new Arlo();
        $settings = (object)[
            'email' => env('ARLO_USER'),
            'password' => env('ARLO_PASSWORD'),
        ];

        $data = $arlo->authorize($settings);
        $user_id = $data->userId;
        $token = $data->token;
        $devices = $arlo->getDevices($token);
        foreach ($devices as $device) {
            if ($device->deviceType === 'basestation') {
                return($arlo->arm($device->deviceId,$token,$device->xCloudId));
                break;
            }
        }

    }
}
