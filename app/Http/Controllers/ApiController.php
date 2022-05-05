<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller{

    protected $token;
    protected $whmip;
    protected $user;

    public function index(){

        $token = env('API_TOKEN');
        $whmip = env('WHM_IP');
        $user = env('WHM_user');

        $query = "https://".$whmip.":2087/json-api/listaccts?api.version=1";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

        $header[0] = "Authorization: whm $user:$token";
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl, CURLOPT_URL, $query);

        $result = curl_exec($curl);

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_status != 200) {
            echo "[!] Error: " . $http_status . " returned\n";
        } else {
            $json = json_decode($result);
            echo "[+] Current cPanel users on the system:\n";
            foreach ($json->{'data'}->{'acct'} as $userdetails) {
                echo "\t" . $userdetails->{'user'} . "\n";
            }
        }

        curl_close($curl);

    }
}
